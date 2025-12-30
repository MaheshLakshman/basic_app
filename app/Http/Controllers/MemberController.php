<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMemberRequest;
use App\Http\Requests\UpdateMemberRequest;
use App\Models\User;
use App\Models\MemberProfile;
use App\Models\Organization;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // Filter users who have 'member' role or just strictly by existence of memberProfile?
            // "Members" module usually implies 'member' role. 
            // We can also check if they have a memberProfile.
            $members = User::whereHas('roles', function($q) {
                $q->where('name', 'member');
            })->with(['organization', 'memberProfile'])->select('users.*');

            if ($request->filled('organization_id')) {
                $members->where('organization_id', $request->organization_id);
            }

            if ($request->filled('name')) {
                 $members->where('name', 'like', '%' . $request->name . '%');
            }

            return DataTables::of($members)
                ->addIndexColumn()
                ->editColumn('name', function ($row) {
                    return $row->name;
                })
                ->addColumn('organization_name', function ($row) {
                    return $row->organization->name ?? '-';
                })
                ->addColumn('member_code', function ($row) {
                    return $row->memberProfile->member_code ?? '-';
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
        return view('members.index', compact('organizations'));
    }

    public function create(Request $request)
    {
        $organizations = Organization::all();
        $languages = Language::where('is_active', true)->get();

        if ($request->ajax()) {
            return view('members.form', compact('organizations', 'languages'))->render();
        }
        return view('members.create', compact('organizations', 'languages'));
    }

    public function store(StoreMemberRequest $request)
    {
        DB::transaction(function () use ($request) {
            $userData = $request->only(['organization_id', 'name', 'email']);
            $userData['password'] = Hash::make($request->password);
            
            $user = User::create($userData);
            $user->assignRole('member');

            $profileData = $request->only(['member_code', 'join_date', 'dob', 'gender', 'emergency_contact']);
            $user->memberProfile()->create($profileData);
        });

        if ($request->ajax()) {
            return response()->json(['success' => 'Member created successfully.']);
        }
        return redirect()->route('members.index')->with('success', 'Member created successfully.');
    }

    public function edit(Request $request, User $member)
    {
        // $member is injected User model
        $organizations = Organization::all();
        $languages = Language::where('is_active', true)->get();
        // Load profile
        $member->load('memberProfile');

        if ($request->ajax()) {
            return view('members.form', compact('member', 'organizations', 'languages'))->render();
        }
        return view('members.edit', compact('member', 'organizations', 'languages'));
    }

    public function update(UpdateMemberRequest $request, User $member)
    {
        DB::transaction(function () use ($request, $member) {
            $userData = $request->only(['organization_id', 'name', 'email']);
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }
            $member->update($userData);

            $profileData = $request->only(['member_code', 'join_date', 'dob', 'gender', 'emergency_contact']);
            
            // Update or Create profile if not exists
            $member->memberProfile()->updateOrCreate(['user_id' => $member->id], $profileData);
        });

        if ($request->ajax()) {
            return response()->json(['success' => 'Member updated successfully.']);
        }
        return redirect()->route('members.index')->with('success', 'Member updated successfully.');
    }

    public function destroy(Request $request, User $member)
    {
        $member->delete(); // Cascades profile deletion usually, defined in migration
        if ($request->ajax()) {
            return response()->json(['success' => 'Member deleted successfully.']);
        }
        return redirect()->route('members.index')->with('success', 'Member deleted successfully.');
    }
}
