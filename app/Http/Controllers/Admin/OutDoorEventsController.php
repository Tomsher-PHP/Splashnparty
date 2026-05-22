<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OutdoorEvent;
use Illuminate\Http\Request;

class OutDoorEventsController extends Controller
{
    private function authorizeOutDoorEventsPermission(string $permission): void
    {
        abort_unless(auth()->user()?->can($permission), 403);
    }

    public function index()
    {
        $this->authorizeOutDoorEventsPermission('view_outdoor_events');
        $events = OutdoorEvent::latest()
            ->paginate(10);

        return view(
            'gallery.outdoor-events.index',
            compact('events')
        );
    }

    public function create()
    {
        $this->authorizeOutDoorEventsPermission('create_outdoor_events');
        return view('gallery.outdoor-events.create');
    }

    public function store(Request $request)
    {
        $this->authorizeOutDoorEventsPermission('create_outdoor_events');
        $request->validate([

            'images'             => 'required|array',

            'images.*'           => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',

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

        $images = [];

        // HASH STORAGE
        $imageHashes = [];

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
                'uploads/outdoor-events',
                'public'
            );

            $images[] = 'storage/' . $path;

            // STORE HASH
            $imageHashes[] = $newHash;
        }

        // OG IMAGE
        $ogImage = null;

        if ($request->hasFile('og_image')) {

            $fileName = time() . '_og.' .
                $request->og_image->extension();

            $request->og_image->move(
                public_path('uploads/seo'),
                $fileName
            );

            $ogImage = 'uploads/seo/' . $fileName;
        }

        OutdoorEvent::create([

            'images'              => $images,

            // SEO
            'meta_title'          => $request->meta_title,
            'meta_description'    => $request->meta_description,
            'meta_keywords'       => $request->meta_keywords,

            'og_title'            => $request->og_title,
            'og_description'      => $request->og_description,
            'og_image'            => $ogImage,

            'twitter_title'       => $request->twitter_title,
            'twitter_description' => $request->twitter_description,
        ]);

        return redirect()
            ->route('outdoor-events.index')
            ->with(
                'success',
                'Outdoor event created successfully'
            );
    }

    public function edit(OutdoorEvent $outdoor_event)
    {
        $this->authorizeOutDoorEventsPermission('edit_outdoor_events');
        return view(
            'gallery.outdoor-events.edit',
            compact('outdoor_event')
        );
    }

    public function update(
        Request $request,
        OutdoorEvent $outdoor_event
    ) {

        $this->authorizeOutDoorEventsPermission('edit_outdoor_events');

        $request->validate([

            'images.*'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

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

        // EXISTING SORTED IMAGES
        $uploadedImages = [];

        if ($request->filled('existing_images')) {

            $uploadedImages = json_decode(
                $request->existing_images,
                true
            ) ?? [];
        }

        // EXISTING IMAGE HASHES
        $existingHashes = [];

        foreach ($uploadedImages as $existingImage) {

            $fullPath = public_path($existingImage);

            if (file_exists($fullPath)) {

                $existingHashes[] = md5_file($fullPath);
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
                if (in_array($newHash, $existingHashes)) {
                    continue;
                }

                $path = $image->store(
                    'uploads/outdoor-events',
                    'public'
                );

                $uploadedImages[] = 'storage/' . $path;

                // STORE HASH
                $existingHashes[] = $newHash;
            }
        }

        // EXISTING OG IMAGE
        $ogImage = $outdoor_event->og_image ?? null;

        // REMOVE OG IMAGE
        if (
            $request->remove_og_image == 1 &&
            $outdoor_event->og_image
        ) {

            if (
                file_exists(
                    public_path($outdoor_event->og_image)
                )
            ) {

                unlink(
                    public_path($outdoor_event->og_image)
                );
            }

            $ogImage = null;
        }

        // NEW OG IMAGE
        if ($request->hasFile('og_image')) {

            // DELETE OLD IMAGE
            if (
                $outdoor_event->og_image &&
                file_exists(
                    public_path($outdoor_event->og_image)
                )
            ) {

                unlink(
                    public_path($outdoor_event->og_image)
                );
            }

            $fileName = time() . '_og.' .
                $request->og_image->extension();

            $request->og_image->move(
                public_path('uploads/seo'),
                $fileName
            );

            $ogImage = 'uploads/seo/' . $fileName;
        }

        $outdoor_event->update([

            'images'              => array_values($uploadedImages),

            // SEO
            'meta_title'          => $request->meta_title,
            'meta_description'    => $request->meta_description,
            'meta_keywords'       => $request->meta_keywords,

            'og_title'            => $request->og_title,
            'og_description'      => $request->og_description,
            'og_image'            => $ogImage,

            'twitter_title'       => $request->twitter_title,
            'twitter_description' => $request->twitter_description,
        ]);

        return redirect()
            ->route('outdoor-events.index')
            ->with(
                'success',
                'Outdoor event updated successfully'
            );
    }

    public function destroy(OutdoorEvent $outdoor_event)
    {
        $this->authorizeOutDoorEventsPermission('delete_outdoor_events');
        // DELETE EVENT IMAGES
        if (!empty($outdoor_event->images)) {

            foreach ($outdoor_event->images as $image) {

                $imagePath = public_path($image);

                if (file_exists($imagePath)) {

                    unlink($imagePath);
                }
            }
        }

        // DELETE OG IMAGE
        if (
            $outdoor_event->og_image &&
            file_exists(
                public_path($outdoor_event->og_image)
            )
        ) {

            unlink(
                public_path($outdoor_event->og_image)
            );
        }

        $outdoor_event->delete();

        return back()->with(
            'success',
            'Deleted successfully'
        );
    }

    public function sort(Request $request)
    {
        $event = OutdoorEvent::findOrFail(
            $request->id
        );

        $event->images = array_values(
            $request->images
        );

        $event->save();

        return response()->json([
            'success' => true
        ]);
    }
}