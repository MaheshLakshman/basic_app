<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubscriptionRequest;
use App\Http\Requests\UpdateSubscriptionRequest;
use App\Models\MemberPlan;
use App\Models\User;
use App\Models\Plan;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class SubscriptionController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $subscriptions = MemberPlan::with(['user', 'plan.service'])->select('member_plans.*');

            if ($request->filled('user_id')) {
                $subscriptions->where('user_id', $request->user_id);
            }

            if ($request->filled('status')) {
                $subscriptions->where('status', $request->status);
            }

            return DataTables::of($subscriptions)
                ->addIndexColumn()
                ->addColumn('member_name', function ($row) {
                    return $row->user->name ?? '-';
                })
                ->addColumn('plan_name', function ($row) {
                    return ($row->plan->service->name ?? '') . ' - ' . ($row->plan->name ?? '');
                })
                ->editColumn('start_date', function ($row) {
                    return $row->start_date ? $row->start_date->format('Y-m-d') : '-';
                })
                ->editColumn('end_date', function ($row) {
                    return $row->end_date ? $row->end_date->format('Y-m-d') : '-';
                })
                ->editColumn('status', function ($row) {
                    $badges = [
                        'active' => 'success',
                        'frozen' => 'warning',
                        'expired' => 'secondary'
                    ];
                    $badge = $badges[$row->status] ?? 'secondary';
                    return '<span class="badge bg-' . $badge . '">' . ucfirst($row->status) . '</span>';
                })
                ->addColumn('action', function ($row) {
                    return '
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-sm btn-outline-primary edit-btn" data-id="' . $row->id . '">Edit</button>
                            <button type="button" class="btn btn-sm btn-outline-danger delete-btn" data-id="' . $row->id . '">Delete</button>
                        </div>
                    ';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        $users = User::all(); // Should ideally filter by member role
        return view('subscriptions.index', compact('users'));
    }

    public function create(Request $request)
    {
        $users = User::all();
        $plans = Plan::with('service')->get();

        if ($request->ajax()) {
            return view('subscriptions.form', compact('users', 'plans'))->render();
        }
        return view('subscriptions.create', compact('users', 'plans'));
    }

    public function store(StoreSubscriptionRequest $request)
    {
        $data = $request->validated();
        
        // Auto-calculate end date if not provided
        if (empty($data['end_date'])) {
            $plan = Plan::find($data['plan_id']);
            if ($plan) {
                $startDate = Carbon::parse($data['start_date']);
                switch ($plan->duration_type) {
                    case 'day':
                        $data['end_date'] = $startDate->addDays($plan->duration_value);
                        break;
                    case 'month':
                        $data['end_date'] = $startDate->addMonths($plan->duration_value);
                        break;
                    case 'year':
                        $data['end_date'] = $startDate->addYears($plan->duration_value);
                        break;
                }
            }
        }

        MemberPlan::create($data);

        if ($request->ajax()) {
            return response()->json(['success' => 'Subscription created successfully.']);
        }
        return redirect()->route('subscriptions.index')->with('success', 'Subscription created successfully.');
    }

    public function edit(Request $request, MemberPlan $subscription)
    {
        $users = User::all();
        $plans = Plan::with('service')->get();

        if ($request->ajax()) {
            return view('subscriptions.form', compact('subscription', 'users', 'plans'))->render();
        }
        return view('subscriptions.edit', compact('subscription', 'users', 'plans'));
    }

    public function update(UpdateSubscriptionRequest $request, MemberPlan $subscription)
    {
        $subscription->update($request->validated());
        if ($request->ajax()) {
            return response()->json(['success' => 'Subscription updated successfully.']);
        }
        return redirect()->route('subscriptions.index')->with('success', 'Subscription updated successfully.');
    }

    public function destroy(Request $request, MemberPlan $subscription)
    {
        $subscription->delete();
        if ($request->ajax()) {
            return response()->json(['success' => 'Subscription deleted successfully.']);
        }
        return redirect()->route('subscriptions.index')->with('success', 'Subscription deleted successfully.');
    }
}
