<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RentalCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RentalCategoryController extends Controller
{
    private function authorizeRentalCategoryPermission(
        string $permission
    ): void {

        abort_unless(
            auth()->user()?->can($permission),
            403
        );
    }

    public function index()
    {
        $this->authorizeRentalCategoryPermission(
            'view_rental_categories'
        );

        $query = RentalCategory::latest();

        if ($search = request('title')) {

            $query->where(
                'title',
                'like',
                '%' . $search . '%'
            );
        }

        $categories = $query
            ->paginate(10)
            ->withQueryString();

        return view(
            'rental-categories.index',
            compact('categories')
        );
    }

    public function create()
    {
        $this->authorizeRentalCategoryPermission(
            'create_rental_categories'
        );

        return view('rental-categories.create');
    }

    public function store(Request $request)
    {
        $this->authorizeRentalCategoryPermission(
            'create_rental_categories'
        );

        $request->validate([

            'title'      => 'required|string|max:255',

            'slug'       => 'required|string|max:255|unique:rental_categories,slug',

            'sort_order' => 'nullable|integer',

            'status'     => 'required|boolean',
        ]);

        RentalCategory::create([

            'title'      => $request->title,

            'slug'       => Str::slug($request->slug),

            'sort_order' => $request->sort_order ?? 0,

            'status'     => $request->status,
        ]);

        return redirect()
            ->route('rental-categories.index')
            ->with(
                'success',
                'Rental category created successfully'
            );
    }

    public function edit(
        RentalCategory $rental_category
    ) {

        $this->authorizeRentalCategoryPermission(
            'edit_rental_categories'
        );

        return view(
            'rental-categories.edit',
            compact('rental_category')
        );
    }

    public function update(
        Request $request,
        RentalCategory $rental_category
    ) {

        $this->authorizeRentalCategoryPermission(
            'edit_rental_categories'
        );

        $request->validate([

            'title'      => 'required|string|max:255',

            'slug'       => 'required|string|max:255|unique:rental_categories,slug,' . $rental_category->id,

            'sort_order' => 'nullable|integer',

            'status'     => 'required|boolean',
        ]);

        $rental_category->update([

            'title'      => $request->title,

            'slug'       => Str::slug($request->slug),

            'sort_order' => $request->sort_order ?? 0,

            'status'     => $request->status,
        ]);

        return redirect()
            ->route('rental-categories.index')
            ->with(
                'success',
                'Rental category updated successfully'
            );
    }

    public function destroy(
        RentalCategory $rental_category
    ) {

        $this->authorizeRentalCategoryPermission(
            'delete_rental_categories'
        );

        $rental_category->delete();

        return back()->with(
            'success',
            'Deleted successfully'
        );
    }
}