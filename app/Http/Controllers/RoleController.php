<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoleRequest;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $roles = Role::query();

            if ($request->filled('name')) {
                $roles->where('name', 'like', '%' . $request->name . '%');
            }

            return DataTables::of($roles)
                ->addIndexColumn()
                ->editColumn('permissions', function ($row) {
                    return $row->permissions->pluck('name')->map(function ($name) {
                        return '<span class="badge bg-info me-1">' . $name . '</span>';
                    })->implode('');
                })
                ->addColumn('action', function ($row) {
                    return '
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-sm btn-outline-primary edit-btn" data-id="' . $row->id . '">Edit</button>
                            <button type="button" class="btn btn-sm btn-outline-danger delete-btn" data-id="' . $row->id . '">Delete</button>
                        </div>
                    ';
                })
                ->rawColumns(['permissions', 'action'])
                ->make(true);
        }

        return view('roles.index');
    }

    public function create(Request $request)
    {
        $permissions = Permission::all();
        if ($request->ajax()) {
            return view('roles.form', compact('permissions'))->render();
        }
        return view('roles.create', compact('permissions'));
    }

    public function store(StoreRoleRequest $request)
    {
        $role = Role::create(['name' => $request->name]);
        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        if ($request->ajax()) {
            return response()->json(['success' => 'Role created successfully.']);
        }
        return redirect()->route('roles.index')->with('success', 'Role created successfully.');
    }

    public function edit(Request $request, Role $role)
    {
        $permissions = Permission::all();
        $rolePermissions = $role->permissions->pluck('name')->toArray();
        if ($request->ajax()) {
            return view('roles.form', compact('role', 'permissions', 'rolePermissions'))->render();
        }
        return view('roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(StoreRoleRequest $request, Role $role)
    {
        $role->name = $request->name;
        $role->save();
        $role->syncPermissions($request->permissions ?? []);

        if ($request->ajax()) {
            return response()->json(['success' => 'Role updated successfully.']);
        }
        return redirect()->route('roles.index')->with('success', 'Role updated successfully.');
    }

    public function destroy(Request $request, Role $role)
    {
        $role->delete();
        if ($request->ajax()) {
            return response()->json(['success' => 'Role deleted successfully.']);
        }
        return redirect()->route('roles.index')->with('success', 'Role deleted successfully.');
    }
}
