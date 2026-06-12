<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FoodMenuCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FoodMenuCategoryController extends Controller
{
    private function authorizeFoodMenuCategoryPermission(
        string $permission
    ): void {

        abort_unless(
            auth()->user()?->can($permission),
            403
        );
    }

    public function index()
    {
        $this->authorizeFoodMenuCategoryPermission(
            'view_food_menu_categories'
        );

        $query = FoodMenuCategory::latest();

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
            'food-menu-categories.index',
            compact('categories')
        );
    }

    public function create()
    {
        $this->authorizeFoodMenuCategoryPermission(
            'create_food_menu_categories'
        );

        return view('food-menu-categories.create');
    }

    public function store(Request $request)
    {
        $this->authorizeFoodMenuCategoryPermission(
            'create_food_menu_categories'
        );

        $request->validate([

            'title'      => 'required|string|max:255',

            'slug'       => 'required|string|max:255|unique:food_menu_categories,slug',

            'sort_order' => 'nullable|integer',

            'status'     => 'required|boolean',
        ]);

        FoodMenuCategory::create([

            'title'      => $request->title,

            'slug'       => $request->slug
                ? Str::slug($request->slug)
                : Str::slug($request->title),

            'sort_order' => $request->sort_order ?? 0,

            'status'     => $request->status,
        ]);

        return redirect()
            ->route('food-menu-categories.index')
            ->with(
                'success',
                'Category created successfully'
            );
    }

    public function edit(
        FoodMenuCategory $food_menu_category
    ) {

        $this->authorizeFoodMenuCategoryPermission(
            'edit_food_menu_categories'
        );

        return view(
            'food-menu-categories.edit',
            compact('food_menu_category')
        );
    }

    public function update(
        Request $request,
        FoodMenuCategory $food_menu_category
    ) {

        $this->authorizeFoodMenuCategoryPermission(
            'edit_food_menu_categories'
        );

        $request->validate([

            'title'      => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:food_menu_categories,slug,' . $food_menu_category->id,
            'sort_order' => 'nullable|integer',
            'status'     => 'required|boolean',
        ]);

        $food_menu_category->update([
            'title'      => $request->title,
            'slug'       => $request->slug
                ? Str::slug($request->slug)
                : Str::slug($request->title),

            'sort_order' => $request->sort_order ?? 0,
            'status'     => $request->status,
        ]);

        return redirect()
            ->route('food-menu-categories.index')
            ->with(
                'success',
                'Category updated successfully'
            );
    }

    public function destroy(
        FoodMenuCategory $food_menu_category
    ) {

        $this->authorizeFoodMenuCategoryPermission(
            'delete_food_menu_categories'
        );

        $food_menu_category->delete();

        return back()->with(
            'success',
            'Deleted successfully'
        );
    }
}
