<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attraction;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AttractionController extends Controller
{
    /**
     * Check user permission authorization.
     *
     * @param string $permission
     */
    private function authorizeAttractionPermission(string $permission): void
    {
        abort_unless(auth()->user()?->can($permission), 403);
    }

    /**
     * Display a listing of the attractions.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $this->authorizeAttractionPermission('view_attractions');

        $query = Attraction::with('branches');

        // Search Filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('title', 'like', "%{$search}%");
        }

        // Branch Filter
        if ($request->filled('branch_id')) {
            $query->whereHas('branches', function ($q) use ($request) {
                $q->where('branches.id', $request->input('branch_id'));
            });
        }

        // Type Filter
        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        // Status Filter
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Paginate by sort_order, then latest
        $attractions = $query->orderBy('sort_order')
                             ->latest()
                             ->paginate(10)
                             ->withQueryString();

        $branches = Branch::where('status', 1)->orderBy('sort_order')->get();

        return view('attractions.index', compact('attractions', 'branches'));
    }

    /**
     * Show the form for creating a new attraction.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $this->authorizeAttractionPermission('create_attractions');

        $branches = Branch::where('status', 1)->orderBy('sort_order')->get();

        return view('attractions.create', compact('branches'));
    }

    /**
     * Store a newly created attraction in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $this->authorizeAttractionPermission('create_attractions');

        $request->validate([
            'branch_ids' => ['required', 'array'],
            'branch_ids.*' => ['exists:branches,id'],
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'in:attraction,adventure'],
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'description' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer'],
            'status' => ['required', 'boolean'],
        ]);

        // Handle Image Upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('uploads/attractions', 'public');
            $imagePath = 'storage/' . $path;
        }

        $attraction = Attraction::create([
            'title' => $request->title,
            'type' => $request->type,
            'image' => $imagePath,
            'description' => $request->description,
            'sort_order' => $request->sort_order ?? 0,
            'status' => $request->status,
        ]);

        $attraction->branches()->sync($request->branch_ids);

        return redirect()->route('attractions.index')->with('success', 'Created successfully.');
    }

    /**
     * Show the form for editing the specified attraction.
     *
     * @param  \App\Models\Attraction  $attraction
     * @return \Illuminate\View\View
     */
    public function edit(Attraction $attraction)
    {
        $this->authorizeAttractionPermission('edit_attractions');

        $branches = Branch::where('status', 1)->orderBy('sort_order')->get();

        return view('attractions.edit', compact('attraction', 'branches'));
    }

    /**
     * Update the specified attraction in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Attraction  $attraction
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Attraction $attraction)
    {
        $this->authorizeAttractionPermission('edit_attractions');

        $request->validate([
            'branch_ids' => ['required', 'array'],
            'branch_ids.*' => ['exists:branches,id'],
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'in:attraction,adventure'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'description' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer'],
            'status' => ['required', 'boolean'],
        ]);

        $imagePath = $attraction->image;

        // Handle Image Replacement
        if ($request->hasFile('image')) {
            // Delete old file
            if ($attraction->image) {
                $oldPath = str_replace('storage/', '', $attraction->image);
                Storage::disk('public')->delete($oldPath);
            }

            $path = $request->file('image')->store('uploads/attractions', 'public');
            $imagePath = 'storage/' . $path;
        }

        $attraction->update([
            'title' => $request->title,
            'type' => $request->type,
            'image' => $imagePath,
            'description' => $request->description,
            'sort_order' => $request->sort_order ?? 0,
            'status' => $request->status,
        ]);

        $attraction->branches()->sync($request->branch_ids);

        return redirect()->route('attractions.index')->with('success', 'Updated successfully.');
    }

    /**
     * Remove the specified attraction from storage.
     *
     * @param  \App\Models\Attraction  $attraction
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Attraction $attraction)
    {
        $this->authorizeAttractionPermission('delete_attractions');

        // Delete associated image file
        if ($attraction->image) {
            $oldPath = str_replace('storage/', '', $attraction->image);
            Storage::disk('public')->delete($oldPath);
        }

        $attraction->delete();

        return redirect()->route('attractions.index')->with('success', 'Deleted successfully.');
    }

    /**
     * Patch the status of the specified attraction.
     *
     * @param  \App\Models\Attraction  $attraction
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus(Attraction $attraction, Request $request)
    {
        $this->authorizeAttractionPermission('edit_attractions');

        $request->validate([
            'status' => ['required', 'boolean'],
        ]);

        $attraction->update([
            'status' => $request->status,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully.'
        ]);
    }
}
