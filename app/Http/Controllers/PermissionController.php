<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePermissionRequest;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $permissions = Permission::query();

            // Apply Filters
            if ($request->filled('name')) {
                $permissions->where('name', 'like', '%' . $request->name . '%');
            }

            return DataTables::of($permissions)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-sm btn-outline-primary edit-btn" data-id="' . $row->id . '">Edit</button>
                            <button type="button" class="btn btn-sm btn-outline-danger delete-btn" data-id="' . $row->id . '">Delete</button>
                        </div>
                    ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('permissions.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        if ($request->ajax()) {
            return view('permissions.form')->render();
        }
        return view('permissions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePermissionRequest $request)
    {
        Permission::create(['name' => $request->name, 'guard_name' => 'web']);
        
        if ($request->ajax()) {
            return response()->json(['success' => 'Permission created successfully.']);
        }
        return redirect()->route('permissions.index')->with('success', 'Permission created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Permission $permission)
    {
        if ($request->ajax()) {
            return view('permissions.form', compact('permission'))->render();
        }
        return view('permissions.edit', compact('permission'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StorePermissionRequest $request, Permission $permission)
    {
        $permission->update(['name' => $request->name]);

        if ($request->ajax()) {
            return response()->json(['success' => 'Permission updated successfully.']);
        }
        return redirect()->route('permissions.index')->with('success', 'Permission updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Permission $permission)
    {
        $permission->delete();

        if ($request->ajax()) {
            return response()->json(['success' => 'Permission deleted successfully.']);
        }
        return redirect()->route('permissions.index')->with('success', 'Permission deleted successfully.');
    }
}
