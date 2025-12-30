<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePlanRequest;
use App\Http\Requests\UpdatePlanRequest;
use App\Models\Plan;
use App\Models\Service;
use App\Models\Language;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PlanController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $plans = Plan::with('service.organization')->select('plans.*'); // Optimized loading

            if ($request->filled('service_id')) {
                $plans->where('service_id', $request->service_id);
            }

            return DataTables::of($plans)
                ->addIndexColumn()
                ->editColumn('name', function ($row) {
                    return $row->name;
                })
                ->addColumn('service_name', function ($row) {
                    return $row->service->name ?? '-';
                })
                ->addColumn('price', function ($row) {
                    return number_format($row->price, 2);
                })
                ->addColumn('duration', function ($row) {
                    return $row->duration_value . ' ' . ucfirst($row->duration_type) . '(s)';
                })
                ->addColumn('action', function ($row) {
                    return '
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-sm btn-outline-primary edit-btn" data-id="' . $row->id . '">Edit</button>
                            <button type="button" class="btn btn-sm btn-outline-danger delete-btn" data-id="' . $row->id . '">Delete</button>
                        </div>
                    ';
                })
                ->make(true);
        }

        $services = Service::all();
        return view('plans.index', compact('services'));
    }

    public function create(Request $request)
    {
        $services = Service::all();
        $languages = Language::where('is_active', true)->get();

        if ($request->ajax()) {
            return view('plans.form', compact('services', 'languages'))->render();
        }
        return view('plans.create', compact('services', 'languages'));
    }

    public function store(StorePlanRequest $request)
    {
        Plan::create($request->validated());
        if ($request->ajax()) {
            return response()->json(['success' => 'Plan created successfully.']);
        }
        return redirect()->route('plans.index')->with('success', 'Plan created successfully.');
    }

    public function edit(Request $request, Plan $plan)
    {
        $services = Service::all();
        $languages = Language::where('is_active', true)->get();

        if ($request->ajax()) {
            return view('plans.form', compact('plan', 'services', 'languages'))->render();
        }
        return view('plans.edit', compact('plan', 'services', 'languages'));
    }

    public function update(UpdatePlanRequest $request, Plan $plan)
    {
        $plan->update($request->validated());
        if ($request->ajax()) {
            return response()->json(['success' => 'Plan updated successfully.']);
        }
        return redirect()->route('plans.index')->with('success', 'Plan updated successfully.');
    }

    public function destroy(Request $request, Plan $plan)
    {
        $plan->delete();
        if ($request->ajax()) {
            return response()->json(['success' => 'Plan deleted successfully.']);
        }
        return redirect()->route('plans.index')->with('success', 'Plan deleted successfully.');
    }
}
