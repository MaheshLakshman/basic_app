<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Models\Organization;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $users = User::with('organization')->select('users.*');

            if ($request->filled('name')) {
                // Search in JSON column
                $users->where('name', 'like', '%' . $request->name . '%');
            }

            if ($request->filled('email')) {
                $users->where('email', 'like', '%' . $request->email . '%');
            }

            if ($request->filled('organization_id')) {
                $users->where('organization_id', $request->organization_id);
            }

            return DataTables::of($users)
                ->addIndexColumn()
                ->editColumn('name', function ($row) {
                    return $row->name; // Automatically translated by accessor if accessing as string, but here array? No, attribute returns translation.
                })
                ->addColumn('organization_name', function ($row) {
                    return $row->organization->name ?? '-';
                })
                ->editColumn('roles', function ($row) {
                    return $row->getRoleNames()->map(function ($name) {
                        return '<span class="badge bg-primary me-1">' . $name . '</span>';
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
                ->rawColumns(['roles', 'action'])
                ->make(true);
        }

        $organizations = Organization::all();
        return view('users.index', compact('organizations'));
    }

    public function create(Request $request)
    {
        $roles = Role::all();
        $permissions = Permission::all();
        $organizations = Organization::all();
        $languages = Language::where('is_active', true)->get();

        if ($request->ajax()) {
            return view('users.form', compact('roles', 'permissions', 'organizations', 'languages'))->render();
        }
        return view('users.create', compact('roles', 'permissions', 'organizations', 'languages'));
    }

    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($request->password);
        
        $user = User::create($data);

        if ($request->filled('roles')) {
            $user->syncRoles($request->roles);
        }

        if ($request->ajax()) {
            return response()->json(['success' => 'User created successfully.']);
        }
        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function edit(Request $request, User $user)
    {
        $roles = Role::all();
        $permissions = Permission::all();
        $organizations = Organization::all();
        $languages = Language::where('is_active', true)->get();
        
        $userRoles = $user->roles->pluck('name')->toArray();
        // $userPermissions = $user->getDirectPermissions()->pluck('name')->toArray();

        if ($request->ajax()) {
            return view('users.form', compact('user', 'roles', 'permissions', 'organizations', 'languages', 'userRoles'))->render();
        }
        return view('users.edit', compact('user', 'roles', 'permissions', 'organizations', 'languages', 'userRoles'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->validated();
        
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        if ($request->has('roles')) {
             $user->syncRoles($request->roles);
        }

        if ($request->ajax()) {
            return response()->json(['success' => 'User updated successfully.']);
        }
        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(Request $request, User $user)
    {
        if ($user->id === auth()->id()) {
            return response()->json(['error' => 'You cannot delete yourself!'], 403);
        }

        $user->delete();
        if ($request->ajax()) {
            return response()->json(['success' => 'User deleted successfully.']);
        }
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
