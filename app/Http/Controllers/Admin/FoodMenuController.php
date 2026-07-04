<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\FoodMenu;
use App\Models\FoodMenuCategory;
use Illuminate\Http\Request;

class FoodMenuController extends Controller
{
    private function authorizeFoodMenuPermission(
        string $permission
    ): void {

        abort_unless(
            auth()->user()?->can($permission),
            403
        );
    }

    public function index()
    {
        $this->authorizeFoodMenuPermission(
            'view_food_menus'
        );

        $query = FoodMenu::with(['category'])->latest();

        // KEYWORD SEARCH
        if ($keyword = request('title')) {

            $query->where(
                'title',
                'like',
                '%' . $keyword . '%'
            );
        }

        // FILTER CATEGORY
        if ($category = request('category')) {
            $query->where(
                'food_menu_category_id',
                $category
            );
        }

        // FILTER LOCATION (BRANCH)
        if ($branch = request('branch')) {
            $query->where(function ($q) use ($branch) {
                $q->whereJsonContains('branch_ids', (int) $branch)
                  ->orWhereJsonContains('branch_ids', (string) $branch);
            });
        }

        // FILTER TYPE
        if ($type = request('type')) {
            $query->where(
                'type',
                $type
            );
        }

        // FILTER FOOD TYPE
        if ($foodType = request('food_type')) {
            $query->where(
                'food_type',
                $foodType
            );
        }

        $foodMenus = $query
            ->paginate(10)
            ->withQueryString();

        $categories = FoodMenuCategory::orderBy('title')->get();
        $branches = Branch::where('status', 1)->orderBy('title')->get();

        return view(
            'food-menus.index',
            compact('foodMenus', 'categories', 'branches')
        );
    }

    public function create()
    {
        $this->authorizeFoodMenuPermission(
            'create_food_menus'
        );

        $branches = Branch::where(
            'status',
            1
        )->orderBy('title')->get();

        $categories = FoodMenuCategory::where(
            'status',
            1
        )->orderBy('sort_order')->get();

        return view(
            'food-menus.create',
            compact('branches', 'categories')
        );
    }

    public function store(Request $request)
    {
        $this->authorizeFoodMenuPermission(
            'create_food_menus'
        );

        $request->validate([
            'title' => 'required|string|max:255',
            'branch_ids' => 'required|array',
            'branch_ids.*' => 'exists:branches,id',
            'type' => 'required|in:adult,kid',
            'food_type' => 'required|in:veg,non-veg',
            'price' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'sort_order' => 'nullable|integer',
            'status' => 'required|boolean',
            'food_menu_category_id' =>
                'required|exists:food_menu_categories,id',
        ]);

        $image = null;

        if ($request->hasFile('image')) {

            $path = $request->file('image')->store(
                'uploads/food-menus',
                'public'
            );

            $image = 'storage/' . $path;
        }

        $foodMenu = FoodMenu::create([
            'title' => $request->title,
            'branch_ids' => is_array($request->branch_ids) ? array_map('intval', $request->branch_ids) : [],
            'type' => $request->type,
            'food_type' => $request->food_type,
            'price' => $request->price,
            'description' => $request->description,
            'image' => $image,
            'sort_order' => $request->sort_order ?? 0,
            'status' => $request->status,
            'food_menu_category_id' =>
                $request->food_menu_category_id,
        ]);
        

        return redirect()
            ->route('food-menus.index')
            ->with(
                'success',
                'Food menu created successfully'
            );
    }

    public function edit(FoodMenu $foodMenu)
    {
        $this->authorizeFoodMenuPermission(
            'edit_food_menus'
        );

        $branches = Branch::where(
            'status',
            1
        )->orderBy('title')->get();

        $categories = FoodMenuCategory::where(
            'status',
            1
        )->orderBy('sort_order')->get();

        return view(
            'food-menus.edit',
            compact(
                'foodMenu',
                'branches',
                'categories',
            )
        );
    }

    public function update(Request $request, FoodMenu $foodMenu) {

        $this->authorizeFoodMenuPermission(
            'edit_food_menus'
        );

        $request->validate([

            'title' => 'required|string|max:255',

            'branch_ids' => 'required|array',
            'branch_ids.*' => 'exists:branches,id',

            'type' => 'required|in:adult,kid',

            'food_type' => 'required|in:veg,non-veg',

            'price' => 'nullable|string|max:255',

            'description' => 'nullable|string',

            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'sort_order' => 'nullable|integer',

            'status' => 'required|boolean',
            'food_menu_category_id' =>
                'required|exists:food_menu_categories,id',
        ]);

        $image = $foodMenu->image;

        // REMOVE IMAGE
        if ($request->remove_image == 1 && $foodMenu->image) {
            if (file_exists(public_path($foodMenu->image))) {
                unlink(public_path($foodMenu->image));
            }
            $image = null;
        }

        if ($request->hasFile('image')) {

            if (
                $image &&
                file_exists(public_path($image))
            ) {

                unlink(public_path($image));
            }

            $path = $request->file('image')->store(
                'uploads/food-menus',
                'public'
            );

            $image = 'storage/' . $path;
        }

        $foodMenu->update([
            'title' => $request->title,
            'branch_ids' => is_array($request->branch_ids) ? array_map('intval', $request->branch_ids) : [],
            'type' => $request->type,
            'food_type' => $request->food_type,
            'price' => $request->price,
            'description' => $request->description,
            'image' => $image,
            'sort_order' => $request->sort_order ?? 0,
            'status' => $request->status,
            'food_menu_category_id' =>
                $request->food_menu_category_id,
        ]);

        return redirect()
            ->route('food-menus.index')
            ->with(
                'success',
                'Food menu updated successfully'
            );
    }

    public function destroy(FoodMenu $foodMenu)
    {
        $this->authorizeFoodMenuPermission(
            'delete_food_menus'
        );

        if (
            $foodMenu->image &&
            file_exists(public_path($foodMenu->image))
        ) {

            unlink(public_path($foodMenu->image));
        }

        $foodMenu->delete();

        return back()->with(
            'success',
            'Deleted successfully'
        );
    }
}