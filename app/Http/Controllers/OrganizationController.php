<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Http\Requests\StoreOrganizationRequest;
use App\Http\Requests\UpdateOrganizationRequest;

class OrganizationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(\Illuminate\Http\Request $request)
    {
        if ($request->ajax()) {
            $organizations = Organization::query();

            // Apply Filters
            if ($request->filled('name')) {
                $searchValue = strtolower($request->name);
                $organizations->whereRaw('LOWER(name) LIKE ?', ['%' . $searchValue . '%']);
            }

            if ($request->filled('status')) {
                $organizations->where('status', $request->status);
            }

            return \Yajra\DataTables\Facades\DataTables::of($organizations)
                ->addIndexColumn()
                ->editColumn('name', function ($row) {
                    return $row->name;
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('organizations.edit', $row->id);
                    $deleteUrl = route('organizations.destroy', $row->id);
                    $csrf = csrf_field();
                    $method = method_field('DELETE');

                    return '
                        <div class="btn-group" role="group">
                            <a href="' . $editUrl . '" class="btn btn-sm btn-outline-primary">Edit</a>
                            <form action="' . $deleteUrl . '" method="POST" class="d-inline" onsubmit="return confirm(\'Are you sure?\')">
                                ' . $csrf . '
                                ' . $method . '
                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </div>
                    ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('organizations.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(\Illuminate\Http\Request $request)
    {
        $languages = \App\Models\Language::where('is_active', true)->get();
        if ($request->ajax()) {
            return view('organizations.form', compact('languages'))->render();
        }
        return view('organizations.create', compact('languages'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrganizationRequest $request)
    {
        Organization::create($request->validated());
        if ($request->ajax()) {
            return response()->json(['success' => 'Organization created successfully.']);
        }
        return redirect()->route('organizations.index')->with('success', 'Organization created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Organization $organization)
    {
        return view('organizations.show', compact('organization'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(\Illuminate\Http\Request $request, Organization $organization)
    {
        $languages = \App\Models\Language::where('is_active', true)->get();
        if ($request->ajax()) {
            return view('organizations.form', compact('organization', 'languages'))->render();
        }
        return view('organizations.edit', compact('organization', 'languages'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrganizationRequest $request, Organization $organization)
    {
        $organization->update($request->validated());
        if ($request->ajax()) {
            return response()->json(['success' => 'Organization updated successfully.']);
        }
        return redirect()->route('organizations.index')->with('success', 'Organization updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(\Illuminate\Http\Request $request, Organization $organization)
    {
        $organization->delete();
        if ($request->ajax()) {
            return response()->json(['success' => 'Organization deleted successfully.']);
        }
        return redirect()->route('organizations.index')->with('success', 'Organization deleted successfully.');
    }
}
