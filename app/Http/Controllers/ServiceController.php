<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use App\Models\Service;
use App\Models\Organization;
use App\Models\Language;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $services = Service::with('organization')->select('services.*');

            if ($request->filled('organization_id')) {
                $services->where('organization_id', $request->organization_id);
            }

            return DataTables::of($services)
                ->addIndexColumn()
                ->editColumn('name', function ($row) {
                    return $row->name;
                })
                ->addColumn('organization_name', function ($row) {
                    return $row->organization->name ?? '-';
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

        $organizations = Organization::all();
        return view('services.index', compact('organizations'));
    }

    public function create(Request $request)
    {
        $organizations = Organization::all();
        $languages = Language::where('is_active', true)->get();

        if ($request->ajax()) {
            return view('services.form', compact('organizations', 'languages'))->render();
        }
        return view('services.create', compact('organizations', 'languages'));
    }

    public function store(StoreServiceRequest $request)
    {
        Service::create($request->validated());
        if ($request->ajax()) {
            return response()->json(['success' => 'Service created successfully.']);
        }
        return redirect()->route('services.index')->with('success', 'Service created successfully.');
    }

    public function edit(Request $request, Service $service)
    {
        $organizations = Organization::all();
        $languages = Language::where('is_active', true)->get();

        if ($request->ajax()) {
            return view('services.form', compact('service', 'organizations', 'languages'))->render();
        }
        return view('services.edit', compact('service', 'organizations', 'languages'));
    }

    public function update(UpdateServiceRequest $request, Service $service)
    {
        $service->update($request->validated());
        if ($request->ajax()) {
            return response()->json(['success' => 'Service updated successfully.']);
        }
        return redirect()->route('services.index')->with('success', 'Service updated successfully.');
    }

    public function destroy(Request $request, Service $service)
    {
        $service->delete();
        if ($request->ajax()) {
            return response()->json(['success' => 'Service deleted successfully.']);
        }
        return redirect()->route('services.index')->with('success', 'Service deleted successfully.');
    }
}
