<?php

namespace App\Http\Controllers;

use App\Models\Language;
use App\Http\Requests\StoreLanguageRequest;
use App\Http\Requests\UpdateLanguageRequest;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class LanguageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $languages = Language::query();

            // Apply Filters
            if ($request->filled('name')) {
                $languages->where('name', 'like', '%' . $request->name . '%');
            }

            if ($request->filled('code')) {
                $languages->where('code', 'like', '%' . $request->code . '%');
            }

            return DataTables::of($languages)
                ->addIndexColumn()
                ->editColumn('is_active', function ($row) {
                    $badgeClass = $row->is_active ? 'bg-success' : 'bg-secondary';
                    return '<span class="badge ' . $badgeClass . '">' . ($row->is_active ? 'Active' : 'Inactive') . '</span>';
                })
                ->addColumn('action', function ($row) {
                    return '
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-sm btn-outline-primary edit-btn" data-id="' . $row->id . '">Edit</button>
                            <button type="button" class="btn btn-sm btn-outline-danger delete-btn" data-id="' . $row->id . '">Delete</button>
                        </div>
                    ';
                })
                ->rawColumns(['is_active', 'action'])
                ->make(true);
        }

        return view('languages.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        if ($request->ajax()) {
            return view('languages.form')->render();
        }
        return view('languages.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLanguageRequest $request)
    {
        Language::create($request->validated());
        if ($request->ajax()) {
            return response()->json(['success' => 'Language created successfully.']);
        }
        return redirect()->route('languages.index')->with('success', 'Language created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Language $language)
    {
        if ($request->ajax()) {
            return view('languages.form', compact('language'))->render();
        }
        return view('languages.edit', compact('language'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLanguageRequest $request, Language $language)
    {
        $language->update($request->validated());
        if ($request->ajax()) {
            return response()->json(['success' => 'Language updated successfully.']);
        }
        return redirect()->route('languages.index')->with('success', 'Language updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Language $language)
    {
        $language->delete();
        if ($request->ajax()) {
            return response()->json(['success' => 'Language deleted successfully.']);
        }
        return redirect()->route('languages.index')->with('success', 'Language deleted successfully.');
    }
}
