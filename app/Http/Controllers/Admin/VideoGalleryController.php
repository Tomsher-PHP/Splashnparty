<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VideoGallery;
use Illuminate\Http\Request;

class VideoGalleryController extends Controller
{
    private function authorizeVideoGalleryPermission(string $permission): void
    {
        abort_unless(auth()->user()?->can($permission), 403);
    }

    public function index()
    {
        $this->authorizeVideoGalleryPermission('view_video_gallery');
        $query = VideoGallery::latest();

        // SEARCH CATEGORY
        if ($search = request('category')) {

            $query->where(
                'category_name',
                'like',
                '%' . $search . '%'
            );
        }

        $galleries = $query
            ->paginate(10)
            ->withQueryString();

        return view(
            'gallery.video.index',
            compact('galleries')
        );
    }

    public function create()
    {
        $this->authorizeVideoGalleryPermission('create_video_gallery');
        return view('gallery.video.create');
    }

    public function store(Request $request)
    {
        $this->authorizeVideoGalleryPermission('create_video_gallery');
        $request->validate([

            'category_name'      => 'required|string|max:255',

            'youtube_link'       => 'required|array|min:1',

            'youtube_link.*'     => 'required|url',

            'status'             => 'required|boolean',

            // SEO
            'meta_title'         => 'nullable|string|max:255',
            'meta_description'   => 'nullable|string',
            'meta_keywords'      => 'nullable|string',

            'og_title'           => 'nullable|string|max:255',
            'og_description'     => 'nullable|string',

            'og_image'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'twitter_title'      => 'nullable|string|max:255',
            'twitter_description'=> 'nullable|string',
        ]);

        // REMOVE EMPTY LINKS
        $youtubeLinks = array_values(
            array_filter($request->youtube_link)
        );

        // REMOVE DUPLICATE LINKS
        $youtubeLinks = array_unique($youtubeLinks);

        // OG IMAGE
        $ogImage = null;
        if ($request->hasFile('og_image')) {
            $path = $request->file('og_image')->store(
                'uploads/seo',
                'public'
            );
            $ogImage = 'storage/' . $path;
        }

        VideoGallery::create([

            'category_name'       => $request->category_name,

            'youtube_link'        => array_values($youtubeLinks),

            // SEO
            'meta_title'          => $request->meta_title,
            'meta_description'    => $request->meta_description,
            'meta_keywords'       => $request->meta_keywords,

            'og_title'            => $request->og_title,
            'og_description'      => $request->og_description,
            'og_image'            => $ogImage,

            'twitter_title'       => $request->twitter_title,
            'twitter_description' => $request->twitter_description,

            'status'              => $request->status,
        ]);

        return redirect()
            ->route('video-gallery.index')
            ->with(
                'success',
                'Video gallery created successfully'
            );
    }

    public function edit(VideoGallery $video_gallery)
    {
        $this->authorizeVideoGalleryPermission('edit_video_gallery');
        return view(
            'gallery.video.edit',
            compact('video_gallery')
        );
    }

    public function update(
        Request $request,
        VideoGallery $video_gallery
    ) {

        $this->authorizeVideoGalleryPermission('edit_video_gallery');

        $request->validate([

            'category_name'      => 'required|string|max:255',

            'youtube_link'       => 'required|array|min:1',

            'youtube_link.*'     => 'required|url',

            'status'             => 'required|boolean',

            // SEO
            'meta_title'         => 'nullable|string|max:255',
            'meta_description'   => 'nullable|string',
            'meta_keywords'      => 'nullable|string',

            'og_title'           => 'nullable|string|max:255',
            'og_description'     => 'nullable|string',

            'og_image'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'twitter_title'      => 'nullable|string|max:255',
            'twitter_description'=> 'nullable|string',
        ]);

        // REMOVE EMPTY LINKS
        $youtubeLinks = array_values(
            array_filter($request->youtube_link)
        );

        // REMOVE DUPLICATE LINKS
        $youtubeLinks = array_unique($youtubeLinks);

        // EXISTING OG IMAGE
        $ogImage = $video_gallery->og_image ?? null;

        // REMOVE OG IMAGE
        if (
            $request->remove_og_image == 1 &&
            $video_gallery->og_image
        ) {

            if (
                file_exists(
                    public_path($video_gallery->og_image)
                )
            ) {

                unlink(
                    public_path($video_gallery->og_image)
                );
            }

            $ogImage = null;
        }

        // NEW OG IMAGE
        if ($request->hasFile('og_image')) {

            // DELETE OLD IMAGE
            if (
                $video_gallery->og_image &&
                file_exists(
                    public_path($video_gallery->og_image)
                )
            ) {

                unlink(
                    public_path($video_gallery->og_image)
                );
            }

            $fileName = time() . '_og.' .
                $request->og_image->extension();

            $path = $request->file('og_image')->store(
                'uploads/seo',
                'public'
            );

            $ogImage = 'storage/' . $path;
        }

        $video_gallery->update([

            'category_name'       => $request->category_name,

            'youtube_link'        => array_values($youtubeLinks),

            // SEO
            'meta_title'          => $request->meta_title,
            'meta_description'    => $request->meta_description,
            'meta_keywords'       => $request->meta_keywords,

            'og_title'            => $request->og_title,
            'og_description'      => $request->og_description,
            'og_image'            => $ogImage,

            'twitter_title'       => $request->twitter_title,
            'twitter_description' => $request->twitter_description,

            'status'              => $request->status,
        ]);

        return redirect()
            ->route('video-gallery.index')
            ->with(
                'success',
                'Video gallery updated successfully'
            );
    }

    public function destroy(VideoGallery $video_gallery)
    {
        $this->authorizeVideoGalleryPermission('delete_video_gallery');

        // DELETE OG IMAGE
        if (
            $video_gallery->og_image &&
            file_exists(
                public_path($video_gallery->og_image)
            )
        ) {

            unlink(
                public_path($video_gallery->og_image)
            );
        }

        $video_gallery->delete();

        return back()->with(
            'success',
            'Deleted successfully'
        );
    }

    public function sort(Request $request)
    {
        $gallery = VideoGallery::findOrFail(
            $request->id
        );

        $gallery->youtube_link = array_values(
            $request->videos
        );

        $gallery->save();

        return response()->json([
            'success' => true
        ]);
    }
}