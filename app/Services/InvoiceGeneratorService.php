<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\InvoiceAdjustment;
use App\Models\MemberPriceAssignment;
use App\Models\PriceAdjustment;
use App\Models\User;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class InvoiceGeneratorService
{
    /**
     * Generate an invoice for a specific member for a given period.
     */
    public function generateForMember(User $member, $startDate, $endDate)
    {
        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);

        // check existing invoice for period? (Optional logic - Skipping for now to allow regeneration or overlapping)

        DB::beginTransaction();
        try {
            $invoice = Invoice::create([
                'organization_id' => $member->organization_id,
                'branch_id' => $member->branch_id, // Assuming user has branch_id or we get it from logic
                'user_id' => $member->id,
                'invoice_no' => 'INV-' . strtoupper(Str::random(10)), // Simple generation, upgrade to sequence later
                'period_start' => $startDate,
                'period_end' => $endDate,
                'status' => 'draft',
            ]);

            $subtotal = 0;

            // 1. Process Active Price Plan
            // Assuming one active assignment generally. If multiple, we might loop.
            $assignments = MemberPriceAssignment::where('member_id', $member->id)
                ->where('is_active', true)
                ->where(function($q) use ($startDate, $endDate) {
                    $q->whereBetween('start_date', [$startDate, $endDate])
                      ->orWhereNull('end_date')
                      ->orWhere('end_date', '>=', $startDate);
                })
                ->with('pricePlan')
                ->get();

            foreach ($assignments as $assignment) {
                // Determine billing logic based on plan type
                $plan = $assignment->pricePlan;
                $price = $assignment->custom_price ?? $plan->price;
                
                $itemAmount = 0;
                $description = "Plan: " . ($plan->getTranslation('name', 'en') ?? $plan->name);

                if ($plan->billing_type === 'monthly') {
                    // Fixed Price
                    $itemAmount = $price;
                    $description .= " (Monthly Fee)";
                } elseif ($plan->billing_type === 'usage' || $plan->billing_type === 'hourly') {
                    // Calculate Usage
                    // This is 'optimized' part: fetch attendance
                    $minutes = Attendance::where('user_id', $member->id)
                        ->whereBetween('attendance_day', [$startDate, $endDate])
                        ->where('status', 'present')
                        ->sum('duration_minutes');
                    
                    // Simple logic: if usage, price is likely per visit or per hour?
                    // User request said: "based on our current attendance".
                    // Assuming price is PER SESSION or PER CHECKIN if 'usage'?
                    // Or PER HOUR if 'hourly'?
                    
                    if ($plan->billing_type === 'hourly') {
                        $hours = ceil($minutes / 60);
                        $itemAmount = $hours * $price;
                        $description .= " ({$hours} Hours @ {$price}/hr)";
                    } else {
                        // Usage / Pay-As-You-Go per Session
                        $sessions = Attendance::where('user_id', $member->id)
                        ->whereBetween('attendance_day', [$startDate, $endDate])
                        ->where('status', 'present')
                        ->count();
                        
                        $itemAmount = $sessions * $price;
                        $description .= " ({$sessions} Sessions @ {$price}/session)";
                    }
                } elseif ($plan->billing_type === 'package') {
                    // Typically prepaid, but if generating invoice, maybe renewal? 
                    // Treat as fixed for invoice generation.
                    $itemAmount = $price;
                     $description .= " (Package Renewal)";
                }

                if ($itemAmount > 0) {
                    InvoiceItem::create([
                        'invoice_id' => $invoice->id,
                        'service_id' => null, // Could link to service if plan has one
                        'description' => json_encode(['en' => $description]), // storing as json for translation support consistency
                        'quantity' => 1,
                        'unit_price' => $itemAmount,
                        'amount' => $itemAmount,
                    ]);
                    $subtotal += $itemAmount;
                }
            }

            // 2. Process Pending Adjustments
            // Fetch adjustments that haven't been applied (applied_month could be used to track)
            // For simplicity, we fetch all adjustments for the member that match the period or are open.
            // Let's assume adjustments table has 'applied_month'. We'll pick adjustments matching the invoice month.
            $adjustments = PriceAdjustment::where('member_id', $member->id)
                ->whereBetween('applied_month', [$startDate, $endDate])
                ->get();

            $adjustmentTotal = 0;
            $discountTotal = 0;

            foreach ($adjustments as $adj) {
                // If Type is Discount, it reduces Subtotal.
                // If Type is Penalty, it adds to Total (Adjustment).
                // If Waiver, strictly speaking, it reduces.
                
                $invAdjType = 'debit'; // Default adds to cost
                if (in_array($adj->type, ['discount', 'waiver'])) {
                    $invAdjType = 'credit';
                    $discountTotal += $adj->amount;
                } else {
                    $adjustmentTotal += $adj->amount;
                }

                InvoiceAdjustment::create([
                    'invoice_id' => $invoice->id,
                    'type' => $invAdjType,
                    'amount' => $adj->amount,
                    'reason' => $adj->reason . ' (' . ucfirst($adj->type) . ')',
                ]);
            }

            // Calculate Totals
            // Grand Total = Subtotal - Discounts + Adjustments (Penalties) + Tax
            // Tax logic: currently 0 as per requirement simple request, but field exists.
            
            $taxTotal = 0; 
            $grandTotal = $subtotal - $discountTotal + $adjustmentTotal + $taxTotal;

            $invoice->update([
                'subtotal' => $subtotal,
                'discount_total' => $discountTotal,
                'adjustment_total' => $adjustmentTotal,
                'tax_total' => $taxTotal,
                'grand_total' => max(0, $grandTotal), // Ensure no negative invoice
            ]);
            
            DB::commit();
            return $invoice;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
