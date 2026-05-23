<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\CafeMenu;
use App\Models\CafeMenuCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CafeMenuController extends Controller
{
    private function authorizeCafeMenuPermission(
        string $permission
    ): void {

        abort_unless(
            auth()->user()?->can($permission),
            403
        );
    }

    public function index()
    {
        $this->authorizeCafeMenuPermission(
            'view_cafe_menus'
        );

        $query = CafeMenu::with([
            'branch',
            'category'
        ])->latest();
       
        // KEYWORD SEARCH
        if ($keyword = request('keyword')) {

            $query->where(function ($q) use ($keyword) {
                $q->where(
                    'title',
                    'like',
                    '%' . $keyword . '%'
                )
                ->orWhere(
                    'description',
                    'like',
                    '%' . $keyword . '%'
                )
                ->orWhereHas('branch', function ($branchQuery) use ($keyword) {

                    $branchQuery->where(
                        'title',
                        'like',
                        '%' . $keyword . '%'
                    );

                })
                ->orWhereHas('category', function ($categoryQuery) use ($keyword) {

                    $categoryQuery->where(
                        'title',
                        'like',
                        '%' . $keyword . '%'
                    );

                });
            });
        }

        // FILTER CATEGORY
        if ($category = request('category')) {

            $query->where(
                'cafe_menu_category_id',
                $category
            );
        }

        // FILTER BRANCH
        if ($branch = request('branch')) {

            $query->where(
                'branch_id',
                $branch
            );
        }

        $menus = $query
            ->paginate(10)
            ->withQueryString();

        // FILTER DROPDOWNS
        $categories = CafeMenuCategory::orderBy('title')
            ->get();

        $branches = Branch::orderBy('title')
            ->get();

        return view(
            'cafe-menus.index',
            compact(
                'menus',
                'categories',
                'branches'
            )
        );
    }

    public function create()
    {
        $this->authorizeCafeMenuPermission(
            'create_cafe_menus'
        );

        $branches = Branch::where(
            'status',
            1
        )->orderBy('title')->get();

        $categories = CafeMenuCategory::where(
            'status',
            1
        )->orderBy('sort_order')->get();

        return view(
            'cafe-menus.create',
            compact(
                'branches',
                'categories'
            )
        );
    }

    public function store(Request $request)
    {
        $this->authorizeCafeMenuPermission(
            'create_cafe_menus'
        );

        $request->validate([

            'branch_id' => 'required|exists:branches,id',

            'cafe_menu_category_id' =>
                'required|exists:cafe_menu_categories,id',

            'image' =>
                'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'title' =>
                'required|string|max:255',

            'description' =>
                'nullable|string',

            'price' =>
                'nullable|numeric|min:0',

            'menu_type' =>
                'required|in:adult,kid',

            'food_type' =>
                'required|in:veg,non_veg',

            'sort_order' =>
                'nullable|integer',

            'status' =>
                'required|boolean',
        ]);

        $image = null;

        if ($request->hasFile('image')) {

            $path = $request->file('image')->store(
                'uploads/cafe-menus',
                'public'
            );

            $image = 'storage/' . $path;
        }

        CafeMenu::create([

            'branch_id' =>
                $request->branch_id,

            'cafe_menu_category_id' =>
                $request->cafe_menu_category_id,

            'image' =>
                $image,

            'title' =>
                $request->title,

            'description' =>
                $request->description,

            'price' =>
                $request->price,

            'menu_type' =>
                $request->menu_type,

            'food_type' =>
                $request->food_type,

            'sort_order' =>
                $request->sort_order ?? 0,

            'status' =>
                $request->status,
        ]);

        return redirect()
            ->route('cafe-menus.index')
            ->with(
                'success',
                'Cafe menu created successfully'
            );
    }

    public function edit(CafeMenu $cafe_menu)
    {
        $this->authorizeCafeMenuPermission(
            'edit_cafe_menus'
        );

        $branches = Branch::where(
            'status',
            1
        )->orderBy('title')->get();

        $categories = CafeMenuCategory::where(
            'status',
            1
        )->orderBy('sort_order')->get();

        return view(
            'cafe-menus.edit',
            compact(
                'cafe_menu',
                'branches',
                'categories'
            )
        );
    }

    public function update(
        Request $request,
        CafeMenu $cafe_menu
    ) {

        $this->authorizeCafeMenuPermission(
            'edit_cafe_menus'
        );

        $request->validate([

            'branch_id' => 'required|exists:branches,id',

            'cafe_menu_category_id' =>
                'required|exists:cafe_menu_categories,id',

            'image' =>
                'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'title' =>
                'required|string|max:255',

            'description' =>
                'nullable|string',

            'price' =>
                'nullable|numeric|min:0',

            'menu_type' =>
                'required|in:adult,kid',

            'food_type' =>
                'required|in:veg,non_veg',

            'sort_order' =>
                'nullable|integer',

            'status' =>
                'required|boolean',
        ]);

        $image = $cafe_menu->image;

        // REMOVE IMAGE
        if (
            $request->remove_image == 1 &&
            $cafe_menu->image
        ) {

            $oldPath = str_replace(
                'storage/',
                '',
                $cafe_menu->image
            );

            Storage::disk('public')->delete(
                $oldPath
            );

            $image = null;
        }

        // NEW IMAGE
        if ($request->hasFile('image')) {

            if ($cafe_menu->image) {

                $oldPath = str_replace(
                    'storage/',
                    '',
                    $cafe_menu->image
                );

                Storage::disk('public')->delete(
                    $oldPath
                );
            }

            $path = $request->file('image')->store(
                'uploads/cafe-menus',
                'public'
            );

            $image = 'storage/' . $path;
        }

        $cafe_menu->update([

            'branch_id' =>
                $request->branch_id,

            'cafe_menu_category_id' =>
                $request->cafe_menu_category_id,

            'image' =>
                $image,

            'title' =>
                $request->title,

            'description' =>
                $request->description,

            'price' =>
                $request->price,

            'menu_type' =>
                $request->menu_type,

            'food_type' =>
                $request->food_type,

            'sort_order' =>
                $request->sort_order ?? 0,

            'status' =>
                $request->status,
        ]);

        return redirect()
            ->route('cafe-menus.index')
            ->with(
                'success',
                'Cafe menu updated successfully'
            );
    }

    public function destroy(CafeMenu $cafe_menu)
    {
        $this->authorizeCafeMenuPermission(
            'delete_cafe_menus'
        );

        if ($cafe_menu->image) {

            $oldPath = str_replace(
                'storage/',
                '',
                $cafe_menu->image
            );

            Storage::disk('public')->delete(
                $oldPath
            );
        }

        $cafe_menu->delete();

        return back()->with(
            'success',
            'Deleted successfully'
        );
    }
}