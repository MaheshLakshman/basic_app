<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAttendanceRequest;
use App\Http\Requests\UpdateAttendanceRequest;
use App\Models\Attendance;
use App\Models\User;
use App\Models\Organization;
use App\Models\Branch;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $attendances = Attendance::with(['organization', 'branch', 'user'])->select('attendances.*');

            if ($request->filled('date')) {
                $attendances->where('attendance_day', $request->date);
            }
            
            if ($request->filled('user_id')) {
                $attendances->where('user_id', $request->user_id);
            }

            if ($request->filled('status')) {
                $attendances->where('status', $request->status);
            }

            return DataTables::of($attendances)
                ->addIndexColumn()
                ->editColumn('attendance_day', function ($row) {
                    return $row->attendance_day->format('Y-m-d');
                })
                ->addColumn('user_name', function ($row) {
                    return $row->user->name ?? '-';
                })
                ->addColumn('organization_name', function ($row) {
                    return $row->organization->name ?? '-'; // HasTranslations trait handles string conversion
                })
                ->addColumn('branch_name', function ($row) {
                    return $row->branch->name ?? '-';
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('attendances.edit', $row->id);
                    $deleteUrl = route('attendances.destroy', $row->id);

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

        $users = User::all();
        $organizations = Organization::all();
        return view('attendances.index', compact('users', 'organizations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $users = User::all();
        $organizations = Organization::all();
        $branches = Branch::all();

        if ($request->ajax()) {
            return view('attendances.form', compact('users', 'organizations', 'branches'))->render();
        }
        return view('attendances.create', compact('users', 'organizations', 'branches'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAttendanceRequest $request)
    {
        Attendance::create($request->validated());
        if ($request->ajax()) {
            return response()->json(['success' => 'Attendance recorded successfully.']);
        }
        return redirect()->route('attendances.index')->with('success', 'Attendance recorded successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Attendance $attendance)
    {
        return view('attendances.show', compact('attendance'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Attendance $attendance)
    {
        $users = User::all();
        $organizations = Organization::all();
        $branches = Branch::all();

        if ($request->ajax()) {
            return view('attendances.form', compact('attendance', 'users', 'organizations', 'branches'))->render();
        }
        return view('attendances.edit', compact('attendance', 'users', 'organizations', 'branches'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAttendanceRequest $request, Attendance $attendance)
    {
        $attendance->update($request->validated());
        if ($request->ajax()) {
            return response()->json(['success' => 'Attendance updated successfully.']);
        }
        return redirect()->route('attendances.index')->with('success', 'Attendance updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Attendance $attendance)
    {
        $attendance->delete();
        if ($request->ajax()) {
            return response()->json(['success' => 'Attendance deleted successfully.']);
        }
        return redirect()->route('attendances.index')->with('success', 'Attendance deleted successfully.');
    }
}
