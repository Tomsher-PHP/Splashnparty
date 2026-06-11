<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\FoodMenu;
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

        $query = FoodMenu::latest();

        // KEYWORD SEARCH
        if ($keyword = request('title')) {

            $query->where(
                'title',
                'like',
                '%' . $keyword . '%'
            );
        }

        $foodMenus = $query
            ->paginate(10)
            ->withQueryString();

        return view(
            'food-menus.index',
            compact('foodMenus')
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

        return view(
            'food-menus.create',
            compact('branches')
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

        return view(
            'food-menus.edit',
            compact(
                'foodMenu',
                'branches'
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
        ]);

        $image = $foodMenu->image;

        if ($request->hasFile('image')) {

            if (
                $foodMenu->image &&
                file_exists(public_path($foodMenu->image))
            ) {

                unlink(public_path($foodMenu->image));
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