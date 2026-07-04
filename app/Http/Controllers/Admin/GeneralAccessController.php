<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\GeneralAccess;
use Illuminate\Http\Request;

class GeneralAccessController extends Controller
{
    private function authorizeGeneralAccessPermission(
        string $permission
    ): void {
        abort_unless(
            auth()->user()?->can($permission),
            403
        );
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorizeGeneralAccessPermission(
            'view_general_access'
        );

        $query = GeneralAccess::with('branch')->latest();

        // Title filter
        if ($title = request('title')) {
            $query->where(
                'title',
                'like',
                '%' . $title . '%'
            );
        }

        // Branch filter (FIXED)
        if ($branch = request('branch')) {
            $query->where(
                'branch_id',
                $branch
            );
        }

        $generalAccesses = $query
            ->paginate(10)
            ->withQueryString();

        $branches = Branch::where(
            'status',
            1
        )->orderBy('title')->get();

        return view(
            'general-access.index',
            compact('generalAccesses', 'branches')
        );
    }

    /**
     * Show create form.
     */
    public function create()
    {
        $this->authorizeGeneralAccessPermission(
            'create_general_access'
        );
        
        $branches = Branch::where(
            'status',
            1
        )->orderBy('title')->get();

        return view('general-access.create', compact('branches'));
    }

    /**
     * Store new record.
     */
    public function store(Request $request)
    {
        $this->authorizeGeneralAccessPermission(
            'create_general_access'
        );

        $request->validate([

            'title' => 'required|max:255',

            'weekday_price' => 'nullable|numeric',

            'weekend_price' => 'nullable|numeric',

            'branch_id' => 'required|exists:branches,id',

            'sort_order' => 'nullable|integer',

            'status' => 'required|in:0,1',
        ]);

        GeneralAccess::create([

            'title' => $request->title,

            'weekday_price' => $request->weekday_price,

            'weekend_price' => $request->weekend_price,

            'branch_id' => $request->branch_id,

            'sort_order' => $request->sort_order,

            'status' => $request->status,
        ]);

        return redirect()
            ->route('general-access.index')
            ->with('success', 'Created successfully');
    }

    /**
     * Edit form.
     */
    public function edit(GeneralAccess $generalAccess)
    {
        $this->authorizeGeneralAccessPermission(
            'edit_general_access'
        );

        $branches = Branch::where(
            'status',
            1
        )->orderBy('title')->get();

        return view(
            'general-access.edit',
            compact('generalAccess', 'branches')
        );
    }

    /**
     * Update record.
     */
    public function update(
        Request $request,
        GeneralAccess $generalAccess
    ) {
        $this->authorizeGeneralAccessPermission(
            'edit_general_access'
        );

        $request->validate([

            'title' => 'required|max:255',

            'weekday_price' => 'nullable|numeric',

            'weekend_price' => 'nullable|numeric',

            'branch_id' => 'required|exists:branches,id',

            'sort_order' => 'nullable|integer',

            'status' => 'required|in:0,1',
        ]);

        $generalAccess->update([

            'title' => $request->title,

            'weekday_price' => $request->weekday_price,

            'weekend_price' => $request->weekend_price,

            'branch_id' => $request->branch_id,

            'sort_order' => $request->sort_order,

            'status' => $request->status,
        ]);

        return redirect()
            ->route('general-access.index')
            ->with('success', 'Updated successfully');
    }

    /**
     * Delete record.
     */
    public function destroy(
        GeneralAccess $generalAccess
    ) {
        $this->authorizeGeneralAccessPermission(
            'delete_general_access'
        );

        $generalAccess->delete();

        return back()->with(
            'success',
            'Deleted successfully'
        );
    }
}