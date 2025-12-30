<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBranchRequest;
use App\Http\Requests\UpdateBranchRequest;
use App\Models\Branch;
use App\Models\Organization;
use App\Models\Language;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $branches = Branch::with(['organization', 'parent'])->select('branches.*');

            // Apply Filters
            if ($request->filled('name')) {
                $searchValue = strtolower($request->name);
                $branches->whereRaw('LOWER(name) LIKE ?', ['%' . $searchValue . '%']);
            }

            if ($request->filled('status')) {
                $branches->where('status', $request->status);
            }

            if ($request->filled('organization_id')) {
                $branches->where('organization_id', $request->organization_id);
            }

            return DataTables::of($branches)
                ->addIndexColumn()
                ->editColumn('name', function ($row) {
                    return $row->name; // Automatically handled by model accessor/trait usually, but here we return raw or translated? Organization controller returned row->name. With Spatie trait, accessing ->name returns translation for current locale.
                })
                ->addColumn('organization_name', function ($row) {
                    return $row->organization->name ?? '-';
                })
                ->addColumn('parent_name', function ($row) {
                    return $row->parent->name ?? '-';
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('branches.edit', $row->id);
                    $deleteUrl = route('branches.destroy', $row->id);
                    $csrf = csrf_field();
                    $method = method_field('DELETE');

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

        $organizations = Organization::all(); // For filter dropdown
        return view('branches.index', compact('organizations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $languages = Language::where('is_active', true)->get();
        $organizations = Organization::all();
        $branches = Branch::all(); 

        if ($request->ajax()) {
            return view('branches.form', compact('languages', 'organizations', 'branches'))->render();
        }
        return view('branches.create', compact('languages', 'organizations', 'branches'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBranchRequest $request)
    {
        Branch::create($request->validated());
        if ($request->ajax()) {
            return response()->json(['success' => 'Branch created successfully.']);
        }
        return redirect()->route('branches.index')->with('success', 'Branch created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Branch $branch)
    {
        return view('branches.show', compact('branch'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Branch $branch)
    {
        $languages = Language::where('is_active', true)->get();
        $organizations = Organization::all();
        $branches = Branch::where('id', '!=', $branch->id)->get();

        if ($request->ajax()) {
            return view('branches.form', compact('branch', 'languages', 'organizations', 'branches'))->render();
        }
        return view('branches.edit', compact('branch', 'languages', 'organizations', 'branches'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBranchRequest $request, Branch $branch)
    {
        $branch->update($request->validated());
        if ($request->ajax()) {
            return response()->json(['success' => 'Branch updated successfully.']);
        }
        return redirect()->route('branches.index')->with('success', 'Branch updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Branch $branch)
    {
        $branch->delete();
        if ($request->ajax()) {
            return response()->json(['success' => 'Branch deleted successfully.']);
        }
        return redirect()->route('branches.index')->with('success', 'Branch deleted successfully.');
    }
}
