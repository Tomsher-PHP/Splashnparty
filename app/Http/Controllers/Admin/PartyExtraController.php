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

        $query = PartyExtras::latest();

        if ($keyword = request('title')) {

            $query->where(
                'title',
                'like',
                '%' . $keyword . '%'
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

            'meta_title'          => 'nullable|string|max:255',
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
        $partyExtra = PartyExtras::findOrFail($id);

        return view('party-extras.edit', compact('partyExtra'));
    }

    /**
     * Update record
     */
    public function update(Request $request, $id)
    {
        $partyExtra = PartyExtras::findOrFail($id);

        $request->validate([
            'category'      => 'required|string|max:255',
            'title'         => 'required|string|max:255',
            'type' => 'required',
            'video_link'    => 'nullable|url',
            'images.*'      => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'thumbnail'     => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'status'        => 'required|boolean',
            'sort_order'    => 'nullable|integer',

            'meta_title'          => 'nullable|string|max:255',
            'meta_description'    => 'nullable|string',
            'meta_keywords'       => 'nullable|string',

            'og_title'            => 'nullable|string|max:255',
            'og_description'      => 'nullable|string',
            'og_image'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'twitter_title'       => 'nullable|string|max:255',
            'twitter_description' => 'nullable|string',
        ]);

        // Basic fields
        $partyExtra->category    = $request->category;
        $partyExtra->title       = $request->title;
        $partyExtra->type        = $request->type;
        $partyExtra->status      = $request->status;
        $partyExtra->sort_order  = $request->sort_order;
        $partyExtra->slug        = Str::slug($request->title);

        // Video link (only if videolink type)
        $partyExtra->video_link = $request->type === 'videolink'
            ? $request->video_link
            : null;

        // Thumbnail update
        if ($request->hasFile('thumbnail')) {
            if ($partyExtra->thumbnail) {
                Storage::delete($partyExtra->thumbnail);
            }

            $partyExtra->thumbnail = $request->file('thumbnail')
                ->store('party_extras/thumbnails');
        }

        // Image gallery update
        if ($request->type === 'image_gallery' && $request->hasFile('images')) {
            $images = $partyExtra->images ?? [];

            foreach ($request->file('images') as $image) {
                $images[] = $image->store('party_extras/gallery');
            }

            $partyExtra->images = $images;
        }

        // OG IMAGE
        $ogImage = $partyExtra->og_image ?? null;

        // REMOVE EXISTING OG IMAGE
        if (
            $request->remove_og_image == 1 &&
            $partyExtra->og_image
        ) {
            if (file_exists(public_path($partyExtra->og_image))) {
                unlink(public_path($partyExtra->og_image));
            }

            $ogImage = null;
        }

        // NEW OG IMAGE UPLOAD
        if ($request->hasFile('og_image')) {
            // DELETE OLD IMAGE
            if (
                $partyExtra->og_image &&
                file_exists(public_path($partyExtra->og_image))
            ) {
                unlink(public_path($partyExtra->og_image));
            }

            $path = $request->og_image->store(
                'uploads/party-extras/seo',
                'public'
            );

            $ogImage = 'storage/' . $path;
        }

        
        // SEO
        $partyExtra->meta_title        = $request->meta_title;
        $partyExtra->meta_description  = $request->meta_description;
        $partyExtra->meta_keywords     = $request->meta_keywords;
        $partyExtra->og_title          = $request->og_title;
        $partyExtra->og_description    = $request->og_description;
        $partyExtra->og_image = $ogImage;
        $partyExtra->twitter_title     = $request->twitter_title;
        $partyExtra->twitter_description = $request->twitter_description;

        $partyExtra->save();

        return redirect()
            ->route('party-extras.index')
            ->with('success', 'Party Extra updated successfully.');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
