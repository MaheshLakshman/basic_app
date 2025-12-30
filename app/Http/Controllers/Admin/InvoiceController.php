<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\User;
use App\Services\InvoiceGeneratorService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf; // Optional if we had PDF package, but skipping for simple view

class InvoiceController extends Controller
{
    protected $generatorService;

    public function __construct(InvoiceGeneratorService $generatorService)
    {
        $this->generatorService = $generatorService;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Invoice::with('user')->select('invoices.*');
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('user_name', function ($row) {
                    return $row->user->name;
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('admin.invoices.show', $row->id) . '" class="btn btn-info btn-sm me-1">View</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.invoices.index');
    }

    public function create()
    {
        $members = User::all(); // Should filter by member role
        return view('admin.invoices.create', compact('members'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after_or_equal:period_start',
        ]);

        $member = User::findOrFail($request->user_id);
        
        try {
            $invoice = $this->generatorService->generateForMember(
                $member, 
                $request->period_start, 
                $request->period_end
            );

            return redirect()->route('admin.invoices.show', $invoice->id)
                ->with('success', 'Invoice generated successfully.');

        } catch (\Exception $e) {
            return back()->with('error', 'Error generating invoice: ' . $e->getMessage());
        }
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['items', 'adjustments', 'user', 'organization', 'branch']);
        return view('admin.invoices.show', compact('invoice'));
    }
}
