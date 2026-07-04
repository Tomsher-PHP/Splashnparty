<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BalloonDecoration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BalloonDecorationController extends Controller
{
    private function authorizeBalloonDecorationPermission(
        string $permission
    ): void {

        abort_unless(
            auth()->user()?->can($permission),
            403
        );
    }

    public function index()
    {
        
        $this->authorizeBalloonDecorationPermission(
            'view_balloon_decorations'
        );

        $query = BalloonDecoration::latest();
        

        // KEYWORD SEARCH
        if ($keyword = request('keyword')) {
            $query->where(
                'title',
                'like',
                '%' . $keyword . '%'
            );
        }

        // STATUS FILTER
        if (request()->has('status') && request('status') !== null && request('status') !== '') {
            $query->where('status', request('status'));
        }

        $decorations = $query
            ->paginate(10)
            ->withQueryString();

        return view(
            'birthday-packages.balloon-decorations.index',
            compact('decorations')
        );
    }

    public function create()
    {
        $this->authorizeBalloonDecorationPermission(
            'create_balloon_decorations'
        );

        return view(
            'birthday-packages.balloon-decorations.create'
        );
    }

    public function store(Request $request)
    {
        $this->authorizeBalloonDecorationPermission(
            'create_balloon_decorations'
        );

        $request->validate([

            'title'       => 'required|string|max:255',

            'image'       => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',

            'description' => 'nullable|string',

            'price'       => 'nullable|string|max:255',

            'sort_order'  => 'nullable|integer',

            'status'      => 'required|boolean',
        ]);

        $image = null;

        if ($request->hasFile('image')) {

            $path = $request->file('image')->store(
                'uploads/balloon-decorations',
                'public'
            );

            $image = 'storage/' . $path;
        }

        BalloonDecoration::create([

            'title'       => $request->title,

            'image'       => $image,

            'description' => $request->description,

            'price'       => $request->price,

            'sort_order'  => $request->sort_order ?? 0,

            'status'      => $request->status,
        ]);

        return redirect()
            ->route('balloon-decorations.index')
            ->with(
                'success',
                'Balloon decoration created successfully'
            );
    }

    public function edit(
        BalloonDecoration $balloon_decoration
    ) {

        $this->authorizeBalloonDecorationPermission(
            'edit_balloon_decorations'
        );

        return view(
            'birthday-packages.balloon-decorations.edit',
            compact('balloon_decoration')
        );
    }

    public function update(
        Request $request,
        BalloonDecoration $balloon_decoration
    ) {

        $this->authorizeBalloonDecorationPermission(
            'edit_balloon_decorations'
        );

        $request->validate([

            'title'       => 'required|string|max:255',

            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'description' => 'nullable|string',

            'price'       => 'nullable|string|max:255',

            'sort_order'  => 'nullable|integer',

            'status'      => 'required|boolean',
        ]);

        // IMAGE
        $image = $balloon_decoration->image;

        // REMOVE THUMBNAIL
        if ($request->remove_thumbnail == 1 && $balloon_decoration->image) {
            $oldPath = public_path($balloon_decoration->image);
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
            $image = null;
        }

        // NEW IMAGE
        if ($request->hasFile('image')) {
            if ($balloon_decoration->image && file_exists(public_path($balloon_decoration->image))) {
                unlink(public_path($balloon_decoration->image));
            }

            $path = $request->file('image')->store('uploads/balloon-decorations', 'public');
            $image = 'storage/' . $path;
        }

        $balloon_decoration->update([
            'title'       => $request->title,
            'image'       => $image,
            'description' => $request->description,
            'price'       => $request->price,
            'sort_order'  => $request->sort_order ?? 0,
            'status'      => $request->status,
        ]);

        return redirect()
            ->route('balloon-decorations.index')
            ->with(
                'success',
                'Balloon decoration updated successfully'
            );
    }

    public function destroy(BalloonDecoration $balloon_decoration) {

        $this->authorizeBalloonDecorationPermission(
            'delete_balloon_decorations'
        );

        // DELETE IMAGE
        if (
            $balloon_decoration->image &&
            file_exists(
                public_path($balloon_decoration->image)
            )
        ) {

            unlink(
                public_path($balloon_decoration->image)
            );
        }

        $balloon_decoration->delete();

        return back()->with(
            'success',
            'Deleted successfully'
        );
    }
}