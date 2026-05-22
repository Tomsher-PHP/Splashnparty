<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CafeMenuCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CafeMenuCategoryController extends Controller
{
    private function authorizeCafeMenuCategoryPermission(
        string $permission
    ): void {

        abort_unless(
            auth()->user()?->can($permission),
            403
        );
    }

    public function index()
    {
        $this->authorizeCafeMenuCategoryPermission(
            'view_cafe_menu_categories'
        );

        $query = CafeMenuCategory::latest();

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
            'cafe-menu-categories.index',
            compact('categories')
        );
    }

    public function create()
    {
        $this->authorizeCafeMenuCategoryPermission(
            'create_cafe_menu_categories'
        );

        return view('cafe-menu-categories.create');
    }

    public function store(Request $request)
    {
        $this->authorizeCafeMenuCategoryPermission(
            'create_cafe_menu_categories'
        );

        $request->validate([

            'title'      => 'required|string|max:255',

            'slug'       => 'required|string|max:255|unique:cafe_menu_categories,slug',

            'sort_order' => 'nullable|integer',

            'status'     => 'required|boolean',
        ]);

        CafeMenuCategory::create([

            'title'      => $request->title,

            'slug'       => $request->slug
                ? Str::slug($request->slug)
                : Str::slug($request->title),

            'sort_order' => $request->sort_order ?? 0,

            'status'     => $request->status,
        ]);

        return redirect()
            ->route('cafe-menu-categories.index')
            ->with(
                'success',
                'Category created successfully'
            );
    }

    public function edit(
        CafeMenuCategory $cafe_menu_category
    ) {

        $this->authorizeCafeMenuCategoryPermission(
            'edit_cafe_menu_categories'
        );

        return view(
            'cafe-menu-categories.edit',
            compact('cafe_menu_category')
        );
    }

    public function update(
        Request $request,
        CafeMenuCategory $cafe_menu_category
    ) {

        $this->authorizeCafeMenuCategoryPermission(
            'edit_cafe_menu_categories'
        );

        $request->validate([

            'title'      => 'required|string|max:255',

            'slug' => 'required|string|max:255|unique:cafe_menu_categories,slug,' . $cafe_menu_category->id,

            'sort_order' => 'nullable|integer',

            'status'     => 'required|boolean',
        ]);

        $cafe_menu_category->update([

            'title'      => $request->title,

            'slug'       => $request->slug
                ? Str::slug($request->slug)
                : Str::slug($request->title),

            'sort_order' => $request->sort_order ?? 0,

            'status'     => $request->status,
        ]);

        return redirect()
            ->route('cafe-menu-categories.index')
            ->with(
                'success',
                'Category updated successfully'
            );
    }

    public function destroy(
        CafeMenuCategory $cafe_menu_category
    ) {

        $this->authorizeCafeMenuCategoryPermission(
            'delete_cafe_menu_categories'
        );

        $cafe_menu_category->delete();

        return back()->with(
            'success',
            'Deleted successfully'
        );
    }
}