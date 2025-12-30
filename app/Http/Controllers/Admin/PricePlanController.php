<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PricePlan;
use App\Models\Organization;
use App\Models\Branch;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PricePlanController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = PricePlan::with(['organization', 'branch'])->select('price_plans.*');
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('name', function ($row) {
                    return $row->name; // Translatable
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('admin.price_plans.edit', $row->id) . '" class="edit btn btn-primary btn-sm">Edit</a>';
                    // Add delete button
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.price_plans.index');
    }

    public function create()
    {
        $organizations = Organization::all();
        $branches = Branch::all();
        return view('admin.price_plans.create', compact('organizations', 'branches'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'organization_id' => 'required|exists:organizations,id',
            'price' => 'required|numeric',
            'billing_type' => 'required',
            'duration_days' => 'required|integer',
        ]);

        PricePlan::create($request->all());

        return redirect()->route('admin.price_plans.index')->with('success', 'Price Plan created successfully.');
    }

    public function edit(PricePlan $pricePlan)
    {
        $organizations = Organization::all();
        $branches = Branch::all();
        return view('admin.price_plans.edit', compact('pricePlan', 'organizations', 'branches'));
    }

    public function update(Request $request, PricePlan $pricePlan)
    {
        $request->validate([
            'name' => 'required',
            'organization_id' => 'required|exists:organizations,id',
            'price' => 'required|numeric',
            'billing_type' => 'required',
            'duration_days' => 'required|integer',
        ]);

        $pricePlan->update($request->all());

        return redirect()->route('admin.price_plans.index')->with('success', 'Price Plan updated successfully.');
    }

    public function destroy(PricePlan $pricePlan)
    {
        $pricePlan->delete();
        return response()->json(['success' => 'Price Plan deleted successfully.']);
    }
}
