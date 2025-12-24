<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
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
            $users = User::query();

            if ($request->filled('name')) {
                $users->where('name', 'like', '%' . $request->name . '%');
            }

            if ($request->filled('email')) {
                $users->where('email', 'like', '%' . $request->email . '%');
            }

            return DataTables::of($users)
                ->addIndexColumn()
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

        return view('users.index');
    }

    public function create(Request $request)
    {
        $roles = Role::all();
        $permissions = Permission::all();
        if ($request->ajax()) {
            return view('users.form', compact('roles', 'permissions'))->render();
        }
        return view('users.create', compact('roles', 'permissions'));
    }

    public function store(StoreUserRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->syncRoles($request->roles ?? []);
        $user->syncPermissions($request->permissions ?? []);

        if ($request->ajax()) {
            return response()->json(['success' => 'User created successfully.']);
        }
        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function edit(Request $request, User $user)
    {
        $roles = Role::all();
        $permissions = Permission::all();
        $userRoles = $user->roles->pluck('name')->toArray();
        $userPermissions = $user->getDirectPermissions()->pluck('name')->toArray();

        if ($request->ajax()) {
            return view('users.form', compact('user', 'roles', 'userRoles', 'permissions', 'userPermissions'))->render();
        }
        return view('users.edit', compact('user', 'roles', 'userRoles', 'permissions', 'userPermissions'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);
        $user->syncRoles($request->roles ?? []);
        $user->syncPermissions($request->permissions ?? []);

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
