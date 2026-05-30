<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeaderMenu;
use Illuminate\Http\Request;

class HeaderMenuController extends Controller
{
    /**
     * Authorize action against permission.
     */
    private function authorizeHeaderMenuPermission(string $permission): void
    {
        abort_unless(auth()->user()?->can($permission), 403);
    }

    /**
     * Display a listing of the header menus.
     */
    public function index()
    {
        $this->authorizeHeaderMenuPermission('view_header_menus');

        $query = HeaderMenu::whereNull('parent_id')
            ->with(['children' => function ($cQuery) {
                $cQuery->orderBy('sort_order');
            }])
            ->orderBy('sort_order');

        if ($search = request('search')) {
            $query->where('title', 'like', '%' . $search . '%')
                ->orWhere('url', 'like', '%' . $search . '%');
        }

        $headerMenus = $query->get();

        return view('header-menus.index', compact('headerMenus'));
    }

    /**
     * Show the form for creating a new header menu.
     */
    public function create()
    {
        $this->authorizeHeaderMenuPermission('create_header_menus');

        $parentMenus = HeaderMenu::whereNull('parent_id')->orderBy('sort_order')->get();

        return view('header-menus.create', compact('parentMenus'));
    }

    /**
     * Store a newly created header menu in storage.
     */
    public function store(Request $request)
    {
        $this->authorizeHeaderMenuPermission('create_header_menus');

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'url' => ['nullable', 'string', 'max:2048'],
            'parent_id' => ['nullable', 'exists:header_menus,id'],
            'sort_order' => ['required', 'integer', 'min:0'],
            'status' => ['required', 'boolean'],
        ]);

        HeaderMenu::create($validated);

        return redirect()->route('header-menus.index')->with('success', 'Header menu item created successfully.');
    }

    /**
     * Show the form for editing the specified header menu.
     */
    public function edit(HeaderMenu $headerMenu)
    {
        $this->authorizeHeaderMenuPermission('edit_header_menus');

        $parentMenus = HeaderMenu::whereNull('parent_id')
            ->where('id', '!=', $headerMenu->id)
            ->orderBy('sort_order')
            ->get();

        return view('header-menus.edit', compact('headerMenu', 'parentMenus'));
    }

    /**
     * Update the specified header menu in storage.
     */
    public function update(Request $request, HeaderMenu $headerMenu)
    {
        $this->authorizeHeaderMenuPermission('edit_header_menus');

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'url' => ['nullable', 'string', 'max:2048'],
            'parent_id' => ['nullable', 'exists:header_menus,id'],
            'sort_order' => ['required', 'integer', 'min:0'],
            'status' => ['required', 'boolean'],
        ]);

        // Prevent circular reference (setting itself as parent)
        if ($validated['parent_id'] == $headerMenu->id) {
            $validated['parent_id'] = null;
        }

        $headerMenu->update($validated);

        return redirect()->route('header-menus.index')->with('success', 'Header menu item updated successfully.');
    }

    /**
     * Update the active status of the specified header menu.
     */
    public function updateStatus(Request $request, HeaderMenu $headerMenu)
    {
        $this->authorizeHeaderMenuPermission('edit_header_menus');

        $validated = $request->validate([
            'status' => ['required', 'boolean'],
        ]);

        $headerMenu->update([
            'status' => $validated['status'],
        ]);

        return redirect()->route('header-menus.index')->with('success', 'Header menu item status updated.');
    }

    /**
     * Reorder parent and sub menu items.
     */
    public function reorder(Request $request)
    {
        $this->authorizeHeaderMenuPermission('edit_header_menus');

        $structure = $request->input('structure', []);

        foreach ($structure as $parentIndex => $parentData) {
            $parentId = $parentData['id'];

            HeaderMenu::where('id', $parentId)->update([
                'parent_id' => null,
                'sort_order' => $parentIndex
            ]);

            if (isset($parentData['children']) && is_array($parentData['children'])) {
                foreach ($parentData['children'] as $childIndex => $childId) {
                    HeaderMenu::where('id', $childId)->update([
                        'parent_id' => $parentId,
                        'sort_order' => $childIndex
                    ]);
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Menu items reordered successfully.'
        ]);
    }

    /**
     * Remove the specified header menu from storage.
     */
    public function destroy(HeaderMenu $headerMenu)
    {
        $this->authorizeHeaderMenuPermission('delete_header_menus');

        $headerMenu->delete();

        return redirect()->route('header-menus.index')->with('success', 'Header menu item deleted successfully.');
    }
}
