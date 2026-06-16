<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PartyExtras;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PartyExtraController extends Controller
{

    private function authorizePartyExtraPermission(
        string $permission
    ): void {

        abort_unless(
            auth()->user()?->can($permission),
            403
        );
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorizePartyExtraPermission(
            'view_party_extras'
        );

        $query = PartyExtras::query()->latest();

        // Title Filter
        if ($title = request('title')) {

            $query->where(
                'title',
                'like',
                '%' . $title . '%'
            );
        }

        // Category Filter
        if ($category = request('category')) {
            $query->where(
                'category',
                'like',
                '%' . $category . '%'
            );
        }

        // Type Filter
        if ($type = request('type')) {
            $query->where(
                'type',
                $type
            );
        }

        $partyExtras = $query
            ->paginate(10)
            ->withQueryString();

        return view(
            'party-extras.index',
            compact('partyExtras')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorizePartyExtraPermission(
            'create_party_extras'
        );

        return view('party-extras.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorizePartyExtraPermission(
            'create_party_extras'
        );

        $request->validate([

            'category' => 'required',
            'title' => 'required',
            'slug' => 'required|unique:party_extras',
            'type' => 'required',
            'thumbnail_image' => 'nullable|image',
            'gallery_images.*' => 'nullable|image',

            'meta_title'          => 'nullable|string',
            'meta_description'    => 'nullable|string',
            'meta_keywords'       => 'nullable|string',

            'og_title'            => 'nullable|string|max:255',
            'og_description'      => 'nullable|string',
            'og_image'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'twitter_title'       => 'nullable|string|max:255',
            'twitter_description' => 'nullable|string',
        ]);

        $galleryImages = [];

        if ($request->hasFile('gallery_images')) {
            foreach (
                $request->file('gallery_images')
                as $image
            ) {
                $path = $image->store(
                    'uploads/party-extras/gallery',
                    'public'
                );
                $galleryImages[] =
                    'storage/' . $path;
            }
        }

        $thumbnail = null;
        if ($request->hasFile('thumbnail_image')) {
            $path = $request
                ->file('thumbnail_image')
                ->store(
                    'uploads/party-extras/thumb',
                    'public'
                );

            $thumbnail = 'storage/' . $path;
        }

        $ogImage = null;

        if ($request->hasFile('og_image')) {

            $path = $request
                ->file('og_image')
                ->store(
                    'uploads/party-extras/seo',
                    'public'
                );

            $ogImage = 'storage/' . $path;
        }

        PartyExtras::create([

            'category' => $request->category,
            'title' => $request->title,
            'slug' => $request->slug,
            'type' => $request->type,
            'gallery_images' => $galleryImages,
            'video_link' => $request->video_link,
            'thumbnail_image' => $thumbnail,

            // SEO
            'meta_title'         => $request->meta_title,
            'meta_description'   => $request->meta_description,
            'meta_keywords'      => $request->meta_keywords,
            'og_title'           => $request->og_title,
            'og_description'     => $request->og_description,
            'og_image' => $ogImage,
            'twitter_title'      => $request->twitter_title,
            'twitter_description' => $request->twitter_description,

            'sort_order' => $request->sort_order,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('party-extras.index')
            ->with(
                'success',
                'Created successfully'
            );
    }

    
    /**
     * Show edit page
     */
    public function edit($id)
    {
        $this->authorizePartyExtraPermission(
            'edit_party_extras'
        );

        $partyExtra = PartyExtras::findOrFail($id);

        return view(
            'party-extras.edit',
            compact('partyExtra')
        );
    }

    /**
     * Update record
     */
    public function update(Request $request, $id)
    {
        $this->authorizePartyExtraPermission(
            'edit_party_extras'
        );

        $partyExtra = PartyExtras::findOrFail($id);

        $request->validate([

            'category' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'slug' => 'required|unique:party_extras,slug,' . $id,
            'type' => 'required|in:image_gallery,video_link',

            'video_link' => 'required_if:type,video_link|nullable|url',
            'thumbnail_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'gallery_images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'existing_gallery_images' => 'nullable|string',
            'remove_thumbnail' => 'nullable|boolean',
            'remove_og_image' => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
            'status' => 'required|in:0,1',

            // SEO
            'meta_title' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',

            'og_title' => 'nullable|string|max:255',
            'og_description' => 'nullable|string',
            'og_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'twitter_title' => 'nullable|string|max:255',
            'twitter_description' => 'nullable|string',
        ]);

        // Basic Details
        $partyExtra->category = $request->category;
        $partyExtra->title = $request->title;
        $partyExtra->slug = $request->slug;
        $partyExtra->type = $request->type;
        $partyExtra->sort_order = $request->sort_order;
        $partyExtra->status = $request->status;

        // Video Link
        $partyExtra->video_link = $request->type === 'video_link'
            ? $request->video_link
            : null;

        /* Thumbnail Image */
        $thumbnailImage = $partyExtra->thumbnail_image;

        // Remove Thumbnail
        if (
            $request->remove_thumbnail == 1 &&
            $partyExtra->thumbnail_image
        ) {

            if (
                file_exists(
                    public_path($partyExtra->thumbnail_image)
                )
            ) {
                unlink(
                    public_path($partyExtra->thumbnail_image)
                );
            }

            $thumbnailImage = null;
        }

        // Upload New Thumbnail
        if ($request->hasFile('thumbnail_image')) {

            if (
                $partyExtra->thumbnail_image &&
                file_exists(
                    public_path($partyExtra->thumbnail_image)
                )
            ) {
                unlink(
                    public_path($partyExtra->thumbnail_image)
                );
            }

            $path = $request
                ->file('thumbnail_image')
                ->store(
                    'uploads/party-extras/thumb',
                    'public'
                );

            $thumbnailImage = 'storage/' . $path;
        }

        $partyExtra->thumbnail_image = $thumbnailImage;

        /* Gallery Images */
        if ($request->type === 'image_gallery') {
            $oldGalleryImages = $partyExtra->gallery_images ?? [];
            $existingGalleryImages = [];

            if ($request->filled('existing_gallery_images')) {
                $existingGalleryImages = json_decode($request->input('existing_gallery_images'), true);
                if (!is_array($existingGalleryImages)) {
                    $existingGalleryImages = [];
                }
            }

            // Identify deleted images to remove them from disk
            $deletedImages = array_diff($oldGalleryImages, $existingGalleryImages);
            foreach ($deletedImages as $deletedImage) {
                if ($deletedImage && file_exists(public_path($deletedImage))) {
                    unlink(public_path($deletedImage));
                }
            }

            $galleryImages = $existingGalleryImages;

            if ($request->hasFile('gallery_images')) {
                foreach ($request->file('gallery_images') as $image) {
                    $path = $image->store('uploads/party-extras/gallery', 'public');
                    $galleryImages[] = 'storage/' . $path;
                }
            }

            $partyExtra->gallery_images = $galleryImages;
        } else {
            // Delete all gallery images if type is changed to video_link
            if (!empty($partyExtra->gallery_images)) {
                foreach ($partyExtra->gallery_images as $image) {
                    if ($image && file_exists(public_path($image))) {
                        unlink(public_path($image));
                    }
                }
            }
            $partyExtra->gallery_images = [];
        }

        /* OG Image */
        $ogImage = $partyExtra->og_image;

        // Remove Existing OG Image
        if (
            $request->remove_og_image == 1 &&
            $partyExtra->og_image
        ) {

            if (
                file_exists(
                    public_path($partyExtra->og_image)
                )
            ) {
                unlink(
                    public_path($partyExtra->og_image)
                );
            }

            $ogImage = null;
        }

        // Upload New OG Image
        if ($request->hasFile('og_image')) {

            if (
                $partyExtra->og_image &&
                file_exists(
                    public_path($partyExtra->og_image)
                )
            ) {
                unlink(
                    public_path($partyExtra->og_image)
                );
            }

            $path = $request
                ->file('og_image')
                ->store(
                    'uploads/party-extras/seo',
                    'public'
                );

            $ogImage = 'storage/' . $path;
        }

        $partyExtra->og_image = $ogImage;

        /* SEO */
        $partyExtra->meta_title = $request->meta_title;
        $partyExtra->meta_description = $request->meta_description;
        $partyExtra->meta_keywords = $request->meta_keywords;

        $partyExtra->og_title = $request->og_title;
        $partyExtra->og_description = $request->og_description;

        $partyExtra->twitter_title = $request->twitter_title;
        $partyExtra->twitter_description = $request->twitter_description;

        $partyExtra->save();

        return redirect()
            ->route('party-extras.index')
            ->with(
                'success',
                'Party Extra updated successfully.'
            );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PartyExtras $partyExtra)
    {
        $this->authorizePartyExtraPermission(
            'delete_party_extras'
        );

        // DELETE THUMBNAIL
        if (
            $partyExtra->thumbnail_image &&
            file_exists(
                public_path(
                    $partyExtra->thumbnail_image
                )
            )
        ) {

            unlink(
                public_path(
                    $partyExtra->thumbnail_image
                )
            );
        }

        // DELETE GALLERY IMAGES
        if (!empty($partyExtra->gallery_images)) {

            foreach (
                $partyExtra->gallery_images
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

        // DELETE OG IMAGE
        if (
            $partyExtra->og_image &&
            file_exists(
                public_path(
                    $partyExtra->og_image
                )
            )
        ) {

            unlink(
                public_path(
                    $partyExtra->og_image
                )
            );
        }

        $partyExtra->delete();

        return back()->with(
            'success',
            'Party Extra deleted successfully.'
        );
    }
}
