<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    private function authorizeBranchPermission(string $permission): void
    {
        abort_unless(auth()->user()?->can($permission), 403);
    }

    public function index()
    {
        $this->authorizeBranchPermission('view_branches');

        $query = Branch::query();

        // SEARCH TITLE
        if ($search = request('title')) {

            $query->where(
                'title',
                'like',
                '%' . $search . '%'
            );
        }

        $branches = $query
            ->orderBy('sort_order')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view(
            'branches.index',
            compact('branches')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorizeBranchPermission('create_branches');

        return view('branches.create');
    }

    /** 
     * Store a newly created resource in storage.
     * @param Request $request
     */
    public function store(Request $request)
    {
        $this->authorizeBranchPermission('create_branches');
        $request->validate([

            'title'         => 'required|string|max:255',
            'description'   => 'nullable|string',
            'image'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'location_link' => 'nullable|url',
            'address'       => 'nullable|string',
            'phone' => [
                'required',
                'regex:/^(?:\+971|00971|0)?5[0-9]{8}$/'
            ],
            'email'         => 'nullable|email|max:255',
            'working_hours' => 'nullable|string',
            'sort_order'    => 'nullable|integer',
            'status'        => 'required|boolean',
        ]);

        $image = null;
        if ($request->hasFile('image')) {

            $path = $request->image->store(
                'uploads/branches',
                'public'
            );

            $image = 'storage/' . $path;
        }

        Branch::create([
            'title'         => $request->title,
            'description'   => $request->description,
            'image'         => $image,
            'location_link' => $request->location_link,
            'address'       => $request->address,
            'phone'         => $request->phone,
            'email'         => $request->email,
            'working_hours' => $request->working_hours,
            'sort_order'    => $request->sort_order ?? 0,
            'status'        => $request->status,
        ]);

        return redirect()
            ->route('branches.index')
            ->with(
                'success',
                'Branch created successfully'
            );
    }

    /**
     *  Show the form for editing a new resource.
     */
    public function edit(Branch $branch)
    {
        $this->authorizeBranchPermission('edit_branches');
        return view(
            'branches.edit',
            compact('branch')
        );
    }

    /**
     * Update the specified resource in storage.
     * 
     * @param Request $request
     * @param Branch $branch
     */
    public function update(Request $request, Branch $branch) {
        $this->authorizeBranchPermission('edit_branches');

        $request->validate([
            'title'         => 'required|string|max:255',
            'description'   => 'nullable|string',
            'image'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'address'       => 'nullable|string',
            'phone' => [
                'required',
                'regex:/^(?:\+971|00971|0)?5[0-9]{8}$/'
            ],
            'email'         => 'nullable|email|max:255',
            'working_hours' => 'nullable|string',
            'location_link' => 'nullable|url',
            'sort_order'    => 'nullable|integer',
            'status'        => 'required|boolean',
        ]);

        $image = $branch->image;
        if ($request->hasFile('image')) {

            // DELETE OLD IMAGE
            if (
                $branch->image &&
                file_exists(public_path($branch->image))
            ) {

                unlink(public_path($branch->image));
            }

            $path = $request->image->store(
                'uploads/branches',
                'public'
            );

            $image = 'storage/' . $path;
        }

        $branch->update([
            'title'         => $request->title,
            'description'   => $request->description,
            'image'         => $image,
            'address'       => $request->address,
            'phone'         => $request->phone,
            'email'         => $request->email,
            'working_hours' => $request->working_hours,
            'location_link' => $request->location_link,
            'sort_order'    => $request->sort_order ?? 0,
            'status'        => $request->status,
        ]);

        return redirect()
            ->route('branches.index')
            ->with(
                'success',
                'Branch updated successfully'
            );
    }

    /**
     * Function to delete the branch.
     * 
     * @param Branch $branch
     */
    public function destroy(Branch $branch)
    {
        $this->authorizeBranchPermission('delete_branches');

        if (
            $branch->image &&
            file_exists(public_path($branch->image))
        ) {

            unlink(public_path($branch->image));
        }

        $branch->delete();

        return back()->with(
            'success',
            'Deleted successfully'
        );
    }
}