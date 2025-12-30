<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PriceAdjustment;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PriceAdjustmentController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = PriceAdjustment::with('member')->select('price_adjustments.*');
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('member_name', function ($row) {
                    return $row->member->name;
                })
                ->addColumn('action', function ($row) {
                    return ''; // Maybe generic edit/delete
                })
                ->make(true);
        }
        return view('admin.price_adjustments.index');
    }

    public function create()
    {
        $members = User::all();
        return view('admin.price_adjustments.create', compact('members'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'member_id' => 'required|exists:users,id',
            'type' => 'required',
            'amount' => 'required|numeric',
        ]);

        PriceAdjustment::create($request->all());

        return redirect()->route('admin.price_adjustments.index')->with('success', 'Adjustment created.');
    }
}
