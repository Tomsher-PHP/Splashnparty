<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ImageGallery;
use Illuminate\Http\Request;

class ImageGalleryController extends Controller
{
    private function authorizeImageGalleryPermission(string $permission): void
    {
        abort_unless(auth()->user()?->can($permission), 403);
    }

    public function index()
    {
        $this->authorizeImageGalleryPermission('view_image_gallery');
        $query = ImageGallery::latest();

        // SEARCH CATEGORY
        if ($search = request('category')) {

            $query->where('category_name', 'like', '%' . $search . '%');
        }

        $galleries = $query
            ->paginate(10)
            ->withQueryString();

        return view('gallery.image.index', compact('galleries'));
    }

    public function create()
    {
        $this->authorizeImageGalleryPermission('create_image_gallery');
        return view('gallery.image.create');
    }

    public function store(Request $request)
    {
        $this->authorizeImageGalleryPermission('create_image_gallery');

        $request->validate([
            'category_name' => 'required|string|max:255',
            'images'        => 'required',
            'images.*'      => 'image|mimes:jpg,jpeg,png,webp|max:2048',
            'status'        => 'required|boolean',

            'meta_title'          => 'nullable|string|max:255',
            'meta_description'    => 'nullable|string',
            'meta_keywords'       => 'nullable|string',

            'og_title'            => 'nullable|string|max:255',
            'og_description'      => 'nullable|string',
            'og_image'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'twitter_title'       => 'nullable|string|max:255',
            'twitter_description' => 'nullable|string',
        ]);

        $uploadedImages = [];

        // HASH STORAGE
        $imageHashes = [];

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                // GENERATE HASH
                $newHash = md5_file(
                    $image->getRealPath()
                );

                // SKIP DUPLICATE IMAGE
                if (in_array($newHash, $imageHashes)) {
                    continue;
                }

                $path = $image->store(
                    'uploads/gallery',
                    'public'
                );

                $uploadedImages[] = 'storage/' . $path;

                // STORE HASH
                $imageHashes[] = $newHash;
            }
        }

        $ogImage = null;
        if ($request->hasFile('og_image')) {
            $path = $request->og_image->store(
                'uploads/seo',
                'public'
            );
            $ogImage = 'storage/' . $path;
        }

        ImageGallery::create([
            'category_name' => $request->category_name,
            'images'        => $uploadedImages,

            // SEO
            'meta_title'         => $request->meta_title,
            'meta_description'   => $request->meta_description,
            'meta_keywords'      => $request->meta_keywords,
            'og_title'           => $request->og_title,
            'og_description'     => $request->og_description,
            'og_image' => $ogImage,
            'twitter_title'      => $request->twitter_title,
            'twitter_description' => $request->twitter_description,
            'status'        => $request->status,

        ]);

        return redirect()
            ->route('image-gallery.index')
            ->with('success', 'Image gallery created successfully');
    }

    public function edit(ImageGallery $image_gallery)
    {
        $this->authorizeImageGalleryPermission('edit_image_gallery');
        return view('gallery.image.edit', compact('image_gallery'));
    }

    public function update(Request $request, ImageGallery $image_gallery)
    {
        $this->authorizeImageGalleryPermission('edit_image_gallery');
        $request->validate([
            'category_name' => 'required|string|max:255',
            'status'        => 'required|boolean',
            'images.*'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'meta_title'          => 'nullable|string|max:255',
            'meta_description'    => 'nullable|string',
            'meta_keywords'       => 'nullable|string',

            'og_title'            => 'nullable|string|max:255',
            'og_description'      => 'nullable|string',
            'og_image'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'twitter_title'       => 'nullable|string|max:255',
            'twitter_description' => 'nullable|string',
        ]);

        // SORTED EXISTING IMAGES
        $uploadedImages = [];
        if ($request->filled('existing_images')) {
            $uploadedImages = json_decode(
                $request->existing_images,
                true
            ) ?? [];
        }

        // EXISTING IMAGE HASHES
        $imageHashes = [];
        foreach ($uploadedImages as $existingImage) {
            $existingPath = public_path($existingImage);
            if (file_exists($existingPath)) {
                $imageHashes[] = md5_file($existingPath);
            }
        }

        // NEW IMAGE UPLOADS
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                // GENERATE HASH
                $newHash = md5_file(
                    $image->getRealPath()
                );

                // SKIP DUPLICATE IMAGE
                if (in_array($newHash, $imageHashes)) {
                    continue;
                }

                $path = $image->store(
                    'uploads/gallery',
                    'public'
                );

                $uploadedImages[] = 'storage/' . $path;

                // STORE HASH
                $imageHashes[] = $newHash;
            }
        }

        // OG IMAGE
        $ogImage = $image_gallery->og_image ?? null;

        // REMOVE EXISTING OG IMAGE
        if (
            $request->remove_og_image == 1 &&
            $image_gallery->og_image
        ) {
            if (file_exists(public_path($image_gallery->og_image))) {
                unlink(public_path($image_gallery->og_image));
            }

            $ogImage = null;
        }

        // NEW OG IMAGE UPLOAD
        if ($request->hasFile('og_image')) {
            // DELETE OLD IMAGE
            if (
                $image_gallery->og_image &&
                file_exists(public_path($image_gallery->og_image))
            ) {
                unlink(public_path($image_gallery->og_image));
            }

            $path = $request->og_image->store(
                'uploads/seo',
                'public'
            );

            $ogImage = 'storage/' . $path;
        }

        // UPDATE
        $image_gallery->update([
            'category_name' => $request->category_name,
            'images'        => array_values($uploadedImages),

            // SEO
            'meta_title'          => $request->meta_title,
            'meta_description'    => $request->meta_description,
            'meta_keywords'       => $request->meta_keywords,
            'og_title'            => $request->og_title,
            'og_description'      => $request->og_description,
            'og_image' => $ogImage,
            'twitter_title'       => $request->twitter_title,
            'twitter_description' => $request->twitter_description,

            'status'         => $request->status,

        ]);

        return redirect()
            ->route('image-gallery.index')
            ->with('success', 'Image gallery updated successfully');
    }

    public function destroy(ImageGallery $image_gallery)
    {
        $this->authorizeImageGalleryPermission('delete_image_gallery');
        // DELETE GALLERY IMAGES
        if (!empty($image_gallery->images)) {
            foreach ($image_gallery->images as $image) {
                $imagePath = public_path($image);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
        }

        // DELETE OG IMAGE
        if (
            $image_gallery->og_image &&
            file_exists(public_path($image_gallery->og_image))
        ) {
            unlink(public_path($image_gallery->og_image));
        }

        $image_gallery->delete();

        return redirect()
            ->route('image-gallery.index')
            ->with('success', 'Image gallery deleted successfully');
    }

    public function sort(Request $request)
    {
        $gallery = ImageGallery::findOrFail($request->id);
        $gallery->images = $request->images;
        $gallery->save();

        return response()->json([
            'success' => true
        ]);
    }
}
