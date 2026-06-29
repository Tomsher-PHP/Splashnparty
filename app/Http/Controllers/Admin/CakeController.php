<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cake;
use Illuminate\Http\Request;

class CakeController extends Controller
{
    private function authorizeCakePermission(string $permission): void
    {
        abort_unless(auth()->user()?->can($permission), 403);
    }

    public function index()
    {
        $this->authorizeCakePermission('view_cakes');

        $query = Cake::latest();

        // SEARCH
        if ($search = request('title')) {
            $query->where(
                'title',
                'like',
                '%' . $search . '%'
            );
        }

        if (request()->has('status') && request('status') !== null && request('status') !== '') {
            $query->where('status', request('status'));
        }

        $cakes = $query
            ->orderBy('sort_order')
            ->paginate(10)
            ->withQueryString();

        return view(
            'cakes.index',
            compact('cakes')
        );
    }

    public function create()
    {
        $this->authorizeCakePermission(
            'create_cakes'
        );

        return view('cakes.create');
    }

    public function store(Request $request)
    {
        $this->authorizeCakePermission(
            'create_cakes'
        );
        $request->validate([

            'title' => 'required|string|max:255',
            'product_code' => 'required|string|max:255|unique:cakes,product_code',
            'thumbnail_image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'gallery_images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'sort_order' => 'nullable|integer',
            'status' => 'required|boolean',
        ]);

        // THUMBNAIL IMAGE
        $thumbnailImage = null;
        if ($request->hasFile('thumbnail_image')) {
            $path = $request->file('thumbnail_image')
                ->store('uploads/cakes', 'public');

            $thumbnailImage = 'storage/' . $path;
        }

        // GALLERY IMAGES
        $galleryImages = [];

        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $image) {
                $path = $image->store('uploads/cakes', 'public');
                $galleryImages[] = 'storage/' . $path;
            }
        }

        Cake::create([
            'title'            => $request->title,
            'product_code'     => $request->product_code,
            'thumbnail_image'  => $thumbnailImage,
            'gallery_images'   => $galleryImages,
            'description'      => $request->description,
            'price'            => $request->price,
            'sort_order'       => $request->sort_order ?? 0,
            'status'           => $request->status,
        ]);

        return redirect()
            ->route('cakes.index')
            ->with(
                'success',
                'Cake created successfully'
            );
    }

    public function edit(Cake $cake)
    {
        $this->authorizeCakePermission('edit_cakes');
        return view('cakes.edit', compact('cake'));
    }

    public function update(Request $request, Cake $cake)
    {
        $this->authorizeCakePermission('edit_cakes');

        $request->validate([
            'title' => 'required|string|max:255',
            'product_code' => 'required|string|max:255|unique:cakes,product_code,' . $cake->id,
            'thumbnail_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'gallery_images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'sort_order' => 'nullable|integer',
            'status' => 'required|boolean',
        ]);

        // THUMBNAIL IMAGE
        $thumbnailImage = $cake->thumbnail_image;

        // REMOVE THUMBNAIL
        if (
            $request->remove_thumbnail == 1 &&
            $cake->thumbnail_image
        ) {

            $oldPath = public_path($cake->thumbnail_image);

            if (file_exists($oldPath)) {
                unlink($oldPath);
            }

            $thumbnailImage = null;
        }

        // NEW THUMBNAIL
        if ($request->hasFile('thumbnail_image')) {
            if ($cake->thumbnail_image && file_exists(public_path($cake->thumbnail_image))) {
                unlink(public_path($cake->thumbnail_image));
            }

            $path = $request->file('thumbnail_image')->store('uploads/cakes', 'public');

            $thumbnailImage = 'storage/' . $path;
        }

        // EXISTING GALLERY IMAGES
        $galleryImages = [];

        if ($request->filled('existing_gallery_images')) {
            $galleryImages = json_decode(
                $request->existing_gallery_images,
                true
            ) ?? [];
        }

        // NEW GALLERY IMAGES
        if (
            $request->hasFile(
                'gallery_images'
            )
        ) {

            foreach (
                $request->file(
                    'gallery_images'
                ) as $image
            ) {

                $path = $image->store(
                    'uploads/cakes',
                    'public'
                );

                $galleryImages[] =
                    'storage/' . $path;
            }
        }

        $cake->update([

            'title'            => $request->title,

            'product_code'     => $request->product_code,

            'thumbnail_image'  => $thumbnailImage,

            'gallery_images'   => array_values(
                $galleryImages
            ),

            'description'      => $request->description,

            'price'            => $request->price,

            'sort_order'       => $request->sort_order ?? 0,

            'status'           => $request->status,
        ]);

        return redirect()
            ->route('cakes.index')
            ->with(
                'success',
                'Cake updated successfully'
            );
    }

    public function destroy(Cake $cake)
    {
        $this->authorizeCakePermission(
            'delete_cakes'
        );

        // DELETE THUMBNAIL
        if (
            $cake->thumbnail_image &&
            file_exists(
                public_path(
                    $cake->thumbnail_image
                )
            )
        ) {

            unlink(
                public_path(
                    $cake->thumbnail_image
                )
            );
        }

        // DELETE GALLERY
        if (!empty($cake->gallery_images)) {

            foreach (
                $cake->gallery_images
                as $image
            ) {

                if (
                    file_exists(
                        public_path($image)
                    )
                ) {

                    unlink(
                        public_path($image)
                    );
                }
            }
        }

        $cake->delete();

        return back()->with(
            'success',
            'Deleted successfully'
        );
    }
}
