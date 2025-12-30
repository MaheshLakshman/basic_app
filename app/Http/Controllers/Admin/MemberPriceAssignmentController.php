<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MemberPriceAssignment;
use App\Models\User;
use App\Models\Plan;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class MemberPriceAssignmentController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = MemberPriceAssignment::with(['member', 'plan.service'])->select('member_price_assignments.*');
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('member_name', function ($row) {
                    return $row->member->name;
                })
                ->editColumn('plan_name', function ($row) {
                    $serviceName = $row->plan->service->getTranslation('name', app()->getLocale()) ?? $row->plan->service->name;
                    $planName = $row->plan->getTranslation('name', app()->getLocale()) ?? $row->plan->name;
                    return $serviceName . ' - ' . $planName;
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('admin.member_price_assignments.edit', $row->id) . '" class="edit btn btn-primary btn-sm">Edit</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.member_price_assignments.index');
    }

    public function create()
    {
        $members = User::all(); // Should filter by role if possible
        $plans = Plan::with('service')->get();
        return view('admin.member_price_assignments.create', compact('members', 'plans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'member_id' => 'required|exists:users,id',
            'plan_id' => 'required|exists:plans,id',
            'start_date' => 'required|date',
        ]);

        MemberPriceAssignment::create($request->all());

        return redirect()->route('admin.member_price_assignments.index')->with('success', 'Assignment created successfully.');
    }

    public function edit(MemberPriceAssignment $memberPriceAssignment)
    {
        $members = User::all();
        $plans = Plan::with('service')->get();
        return view('admin.member_price_assignments.edit', compact('memberPriceAssignment', 'members', 'plans'));
    }

    public function update(Request $request, MemberPriceAssignment $memberPriceAssignment)
    {
        $request->validate([
            'member_id' => 'required|exists:users,id',
            'plan_id' => 'required|exists:plans,id',
            'start_date' => 'required|date',
        ]);

        $memberPriceAssignment->update($request->all());

        return redirect()->route('admin.member_price_assignments.index')->with('success', 'Assignment updated successfully.');
    }
}
