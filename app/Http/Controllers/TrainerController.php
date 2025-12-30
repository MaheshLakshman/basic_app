<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTrainerRequest;
use App\Http\Requests\UpdateTrainerRequest;
use App\Models\User;
use App\Models\Organization;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class TrainerController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $trainers = User::whereHas('roles', function($q) {
                $q->where('name', 'trainer');
            })->with(['organization', 'trainerProfile'])->select('users.*');

            if ($request->filled('organization_id')) {
                $trainers->where('organization_id', $request->organization_id);
            }

            if ($request->filled('name')) {
                 $trainers->where('name', 'like', '%' . $request->name . '%');
            }

            return DataTables::of($trainers)
                ->addIndexColumn()
                ->editColumn('name', function ($row) {
                    return $row->name;
                })
                ->addColumn('organization_name', function ($row) {
                    return $row->organization->name ?? '-';
                })
                ->addColumn('specialization', function ($row) {
                    return $row->trainerProfile->specialization ? implode(', ', $row->trainerProfile->specialization) : '-';
                })
                ->addColumn('experience', function ($row) {
                    return $row->trainerProfile->experience_years ? $row->trainerProfile->experience_years . ' Years' : '-';
                })
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

        $organizations = Organization::all();
        return view('trainers.index', compact('organizations'));
    }

    public function create(Request $request)
    {
        $organizations = Organization::all();
        $languages = Language::where('is_active', true)->get();

        if ($request->ajax()) {
            return view('trainers.form', compact('organizations', 'languages'))->render();
        }
        return view('trainers.create', compact('organizations', 'languages'));
    }

    public function store(StoreTrainerRequest $request)
    {
        DB::transaction(function () use ($request) {
            $userData = $request->only(['organization_id', 'name', 'email']);
            $userData['password'] = Hash::make($request->password);
            
            $user = User::create($userData);
            $user->assignRole('trainer');

            $profileData = $request->only(['specialization', 'experience_years']);
            $user->trainerProfile()->create($profileData);
        });

        if ($request->ajax()) {
            return response()->json(['success' => 'Trainer created successfully.']);
        }
        return redirect()->route('trainers.index')->with('success', 'Trainer created successfully.');
    }

    public function edit(Request $request, User $trainer)
    {
        $organizations = Organization::all();
        $languages = Language::where('is_active', true)->get();
        $trainer->load('trainerProfile');

        if ($request->ajax()) {
            return view('trainers.form', compact('trainer', 'organizations', 'languages'))->render();
        }
        return view('trainers.edit', compact('trainer', 'organizations', 'languages'));
    }

    public function update(UpdateTrainerRequest $request, User $trainer)
    {
        DB::transaction(function () use ($request, $trainer) {
            $userData = $request->only(['organization_id', 'name', 'email']);
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }
            $trainer->update($userData);

            $profileData = $request->only(['specialization', 'experience_years']);
            $trainer->trainerProfile()->updateOrCreate(['user_id' => $trainer->id], $profileData);
        });

        if ($request->ajax()) {
            return response()->json(['success' => 'Trainer updated successfully.']);
        }
        return redirect()->route('trainers.index')->with('success', 'Trainer updated successfully.');
    }

    public function destroy(Request $request, User $trainer)
    {
        $trainer->delete();
        if ($request->ajax()) {
            return response()->json(['success' => 'Trainer deleted successfully.']);
        }
        return redirect()->route('trainers.index')->with('success', 'Trainer deleted successfully.');
    }
}
