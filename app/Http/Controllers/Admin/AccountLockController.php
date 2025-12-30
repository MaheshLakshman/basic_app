<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AccountLock;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AccountLockController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = AccountLock::with('user')->select('account_locks.*');
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('user_name', function ($row) {
                    return $row->user->name;
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('admin.account_locks.edit', $row->id) . '" class="edit btn btn-primary btn-sm">Edit</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.account_locks.index');
    }

    public function create()
    {
        $users = User::all();
        return view('admin.account_locks.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'reason' => 'required',
        ]);

        AccountLock::create($request->all());

        return redirect()->route('admin.account_locks.index')->with('success', 'Account locked successfully.');
    }

    public function edit(AccountLock $accountLock)
    {
        $users = User::all();
        return view('admin.account_locks.edit', compact('accountLock', 'users'));
    }

    public function update(Request $request, AccountLock $accountLock)
    {
        $request->validate([
            'reason' => 'required',
        ]);

        $accountLock->update($request->all());

        return redirect()->route('admin.account_locks.index')->with('success', 'Account lock updated.');
    }
}
