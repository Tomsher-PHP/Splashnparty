<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RentalCategory;
use App\Models\RentalItem;
use Illuminate\Http\Request;

class RentalItemController extends Controller
{
    private function authorizeRentalItemPermission(
        string $permission
    ): void {

        abort_unless(
            auth()->user()?->can($permission),
            403
        );
    }

    public function index()
    {
        $this->authorizeRentalItemPermission(
            'view_rental_items'
        );

        $query = RentalItem::with('category')
            ->latest();

        // KEYWORD SEARCH
        if ($keyword = request('keyword')) {

            $query->where(function ($q) use ($keyword) {

                $q->where(
                    'title',
                    'like',
                    '%' . $keyword . '%'
                )
                ->orWhere(
                    'price',
                    'like',
                    '%' . $keyword . '%'
                )
                ->orWhere(
                    'description',
                    'like',
                    '%' . $keyword . '%'
                )
                ->orWhereHas('category', function ($categoryQuery) use ($keyword) {

                    $categoryQuery->where(
                        'title',
                        'like',
                        '%' . $keyword . '%'
                    );

                });
            });
        }

        // CATEGORY FILTER
        if ($category = request('category')) {

            $query->where(
                'rental_category_id',
                $category
            );
        }

        $items = $query
            ->paginate(10)
            ->withQueryString();

        $categories = RentalCategory::where(
            'status',
            1
        )
        ->orderBy('sort_order')
        ->get();

        return view(
            'rental-items.index',
            compact(
                'items',
                'categories'
            )
        );
    }

    public function create()
    {
        $this->authorizeRentalItemPermission(
            'create_rental_items'
        );

        $categories = RentalCategory::where(
            'status',
            1
        )
        ->orderBy('sort_order')
        ->get();

        return view(
            'rental-items.create',
            compact('categories')
        );
    }

    public function store(Request $request)
    {
        $this->authorizeRentalItemPermission(
            'create_rental_items'
        );

        $request->validate([

            'rental_category_id' => 'required|exists:rental_categories,id',

            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'title' => 'required|string|max:255',

            'price' => 'nullable|string|max:255',

            'description' => 'nullable|string',

            'sort_order' => 'nullable|integer',

            'status' => 'required|boolean',
        ]);

        $image = null;

        if ($request->hasFile('image')) {

            $path = $request->file('image')->store(
                'uploads/rentals',
                'public'
            );

            $image = 'storage/' . $path;
        }

        RentalItem::create([

            'rental_category_id' => $request->rental_category_id,

            'image' => $image,

            'title' => $request->title,

            'price' => $request->price,

            'description' => $request->description,

            'sort_order' => $request->sort_order ?? 0,

            'status' => $request->status,
        ]);

        return redirect()
            ->route('rental-items.index')
            ->with(
                'success',
                'Rental item created successfully'
            );
    }

    public function edit(RentalItem $rental_item)
    {
        $this->authorizeRentalItemPermission(
            'edit_rental_items'
        );

        $categories = RentalCategory::where(
            'status',
            1
        )
        ->orderBy('sort_order')
        ->get();

        return view(
            'rental-items.edit',
            compact(
                'rental_item',
                'categories'
            )
        );
    }

    public function update(
        Request $request,
        RentalItem $rental_item
    ) {

        $this->authorizeRentalItemPermission(
            'edit_rental_items'
        );

        $request->validate([

            'rental_category_id' => 'required|exists:rental_categories,id',

            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'title' => 'required|string|max:255',

            'price' => 'nullable|string|max:255',

            'description' => 'nullable|string',

            'sort_order' => 'nullable|integer',

            'status' => 'required|boolean',
        ]);

        $image = $rental_item->image;

        if ($request->hasFile('image')) {

            if (
                $rental_item->image &&
                file_exists(
                    public_path($rental_item->image)
                )
            ) {

                unlink(
                    public_path($rental_item->image)
                );
            }

            $path = $request->file('image')->store(
                'uploads/rentals',
                'public'
            );

            $image = 'storage/' . $path;
        }

        $rental_item->update([

            'rental_category_id' => $request->rental_category_id,

            'image' => $image,

            'title' => $request->title,

            'price' => $request->price,

            'description' => $request->description,

            'sort_order' => $request->sort_order ?? 0,

            'status' => $request->status,
        ]);

        return redirect()
            ->route('rental-items.index')
            ->with(
                'success',
                'Rental item updated successfully'
            );
    }

    public function destroy(RentalItem $rental_item)
    {
        $this->authorizeRentalItemPermission(
            'delete_rental_items'
        );

        if (
            $rental_item->image &&
            file_exists(
                public_path($rental_item->image)
            )
        ) {

            unlink(
                public_path($rental_item->image)
            );
        }

        $rental_item->delete();

        return back()->with(
            'success',
            'Deleted successfully'
        );
    }
}