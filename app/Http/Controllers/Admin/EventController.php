<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Event;
use App\Models\EventBranchDetail;
use App\Models\EventBranchFeature;
use App\Models\EventBranchGallery;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EventController extends Controller
{
    private function authorizeEventPermission(
        string $permission
    ): void {

        abort_unless(
            auth()->user()?->can($permission),
            403
        );
    }

    public function index()
    {
        $this->authorizeEventPermission(
            'view_events'
        );

        $query = Event::latest();

        // KEYWORD SEARCH
        if ($keyword = request('title')) {

            $query->where(
                'title',
                'like',
                '%' . $keyword . '%'
            );
        }

        $events = $query
            ->paginate(10)
            ->withQueryString();

        return view(
            'events.index',
            compact('events')
        );
    }

    public function create()
    {
        $this->authorizeEventPermission(
            'create_events'
        );

        $branches = Branch::where(
            'status',
            1
        )->orderBy('title')->get();

        return view(
            'events.create',
            compact('branches')
        );
    }

    public function store(Request $request)
    {
        $this->authorizeEventPermission(
            'create_events'
        );

        $request->validate([

            'title' => 'required|string|max:255',

            'slug' => 'required|string|max:255|unique:events,slug',

            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'banner_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'sort_order' => 'nullable|integer',

            'status' => 'required|boolean',

            'branch_details' => 'required|array|min:1',

            'branch_details.*.branch_id' => 'required|exists:branches,id',

            'branch_details.*.description' => 'nullable|string',

            'branch_details.*.highlighted_description' => 'nullable|string',

            'branch_details.*.sort_order' => 'nullable|integer',

            'branch_details.*.status' => 'required|boolean',

            'branch_details.*.weekday_price' => 'nullable|string|max:255',
            'branch_details.*.weekend_price' => 'nullable|string|max:255',
        ]);

        $image = null;

        if ($request->hasFile('image')) {

            $path = $request->file('image')->store(
                'uploads/events',
                'public'
            );

            $image = 'storage/' . $path;
        }

        $bannerImage = null;

        if ($request->hasFile('banner_image')) {

            $path = $request->file('banner_image')->store(
                'uploads/events/banner',
                'public'
            );

            $bannerImage = 'storage/' . $path;
        }

        $event = Event::create([

            'title' => $request->title,

            'slug' => Str::slug($request->slug),

            'image' => $image,

            'banner_image' => $bannerImage,

            'sort_order' => $request->sort_order ?? 0,

            'status' => $request->status,
        ]);
        
        $this->saveBranchDetails(
            $event,
            $request->branch_details
        );

        return redirect()
            ->route('events.index')
            ->with(
                'success',
                'Event created successfully'
            );
    }

    public function edit(Event $event)
    {
        $this->authorizeEventPermission(
            'edit_events'
        );

        $event->load([
            'branchDetails.features',
            'branchDetails.galleries'
        ]);

        $branches = Branch::where(
            'status',
            1
        )->orderBy('title')->get();

        return view(
            'events.edit',
            compact(
                'event',
                'branches'
            )
        );
    }

    public function update(
        Request $request,
        Event $event
    ) {

        $this->authorizeEventPermission(
            'edit_events'
        );

        $request->validate([

            'title' => 'required|string|max:255',

            'slug' => 'required|string|max:255|unique:events,slug,' . $event->id,

            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'banner_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'sort_order' => 'nullable|integer',

            'status' => 'required|boolean',

            'branch_details' => 'required|array|min:1',

            'branch_details.*.branch_id' => 'required|exists:branches,id',

            'branch_details.*.description' => 'nullable|string',

            'branch_details.*.highlighted_description' => 'nullable|string',

            'branch_details.*.sort_order' => 'nullable|integer',

            'branch_details.*.status' => 'required|boolean',

            'branch_details.*.weekday_price' => 'nullable|string|max:255',

            'branch_details.*.weekend_price' => 'nullable|string|max:255',
        ]);

        $image = $event->image;

        if ($request->hasFile('image')) {

            if (
                $event->image &&
                file_exists(public_path($event->image))
            ) {

                unlink(public_path($event->image));
            }

            $path = $request->file('image')->store(
                'uploads/events',
                'public'
            );

            $image = 'storage/' . $path;
        }

        $bannerImage = $event->banner_image;

        if ($request->hasFile('banner_image')) {

            if (
                $event->banner_image &&
                file_exists(public_path($event->banner_image))
            ) {

                unlink(public_path($event->banner_image));
            }

            $path = $request->file('banner_image')->store(
                'uploads/events/banner',
                'public'
            );

            $bannerImage = 'storage/' . $path;
        }

        $event->update([

            'title' => $request->title,

            'slug' => Str::slug($request->slug),

            'image' => $image,

            'banner_image' => $bannerImage,

            'sort_order' => $request->sort_order ?? 0,

            'status' => $request->status,
        ]);

        

        foreach ($event->branchDetails as $detail)
        {
            $detail->features()->delete();

            $detail->galleries()->delete();
        }

        $event->branchDetails()->delete();

        $this->saveBranchDetails(
            $event,
            $request->branch_details
        );

        return redirect()
            ->route('events.index')
            ->with(
                'success',
                'Event updated successfully'
            );
    }

    public function destroy(Event $event)
    {
        $this->authorizeEventPermission(
            'delete_events'
        );

        if (
            $event->image &&
            file_exists(public_path($event->image))
        ) {

            unlink(public_path($event->image));
        }

        if (
            $event->banner_image &&
            file_exists(public_path($event->banner_image))
        ) {

            unlink(public_path($event->banner_image));
        }

        foreach ($event->branchDetails as $detail)
        {
            $detail->features()->delete();

            $detail->galleries()->delete();
        }

        $event->delete();

        return back()->with(
            'success',
            'Deleted successfully'
        );
    }


    private function saveBranchDetails(Event $event, array $branchDetails)
    {
        foreach ($branchDetails as $detail)
        {
            $detailImage = null;

            if (
                isset($detail['image']) &&
                $detail['image'] instanceof \Illuminate\Http\UploadedFile
            ) {
                $path = $detail['image']->store(
                    'uploads/events/branch-details',
                    'public'
                );

                $detailImage = 'storage/' . $path;
            }

            $middleBanner = null;

            if (
                isset($detail['middle_banner']) &&
                $detail['middle_banner'] instanceof \Illuminate\Http\UploadedFile
            ) {
                $path = $detail['middle_banner']->store(
                    'uploads/events/middle-banner',
                    'public'
                );

                $middleBanner = 'storage/' . $path;
            }

            $branchDetail = EventBranchDetail::create([

                'event_id' => $event->id,

                'branch_id' => $detail['branch_id'],

                'title' => $detail['title'] ?? null,

                'description' => $detail['description'] ?? null,

                'image' => $detailImage,

                'middle_banner' => $middleBanner,

                'sort_order' => $detail['sort_order'] ?? 0,

                'status' => $detail['status'] ?? 1,
            ]);

            // Features
            foreach ($detail['features'] ?? [] as $feature)
            {
                $icon = null;

                if (
                    isset($feature['icon']) &&
                    $feature['icon'] instanceof \Illuminate\Http\UploadedFile
                ) {
                    $path = $feature['icon']->store(
                        'uploads/events/features',
                        'public'
                    );

                    $icon = 'storage/' . $path;
                }

                EventBranchFeature::create([

                    'event_branch_detail_id' => $branchDetail->id,

                    'icon' => $icon,

                    'title' => $feature['title'] ?? null,

                    'subtitle' => $feature['subtitle'] ?? null,

                    'content' => $feature['content'] ?? null,

                    'sort_order' => $feature['sort_order'] ?? 0,

                    'status' => $feature['status'] ?? 1,
                ]);
            }

            // Gallery
            foreach ($detail['gallery'] ?? [] as $gallery)
            {
                $galleryImage = null;

                if (
                    isset($gallery['image']) &&
                    $gallery['image'] instanceof \Illuminate\Http\UploadedFile
                ) {
                    $path = $gallery['image']->store(
                        'uploads/events/gallery',
                        'public'
                    );

                    $galleryImage = 'storage/' . $path;
                }

                EventBranchGallery::create([

                    'event_branch_detail_id' => $branchDetail->id,

                    'title' => $gallery['title'] ?? null,

                    'description' => $gallery['description'] ?? null,

                    'image' => $galleryImage,

                    'sort_order' => $gallery['sort_order'] ?? 0,

                    'status' => $gallery['status'] ?? 1,
                ]);
            }
        }
    }
}