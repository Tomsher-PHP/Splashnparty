<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Event;
use App\Models\EventBranchDetail;
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

        foreach ($request->branch_details as $detail) {

            EventBranchDetail::create([

                'event_id' => $event->id,

                'branch_id' => $detail['branch_id'],

                'weekday_price' => $detail['weekday_price'] ?? null,

                'weekend_price' => $detail['weekend_price'] ?? null,

                'description' => $detail['description'],

                'highlighted_description' => $detail['highlighted_description'],

                'sort_order' => $detail['sort_order'] ?? 0,

                'status' => $detail['status'],
            ]);
        }

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

        $event->load('branchDetails');

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

        // DELETE OLD DETAILS
        $event->branchDetails()->delete();

        foreach ($request->branch_details as $detail) {

            EventBranchDetail::create([

                'event_id' => $event->id,

                'branch_id' => $detail['branch_id'],

                'weekday_price' => $detail['weekday_price'] ?? null,

                'weekend_price' => $detail['weekend_price'] ?? null,

                'description' => $detail['description'],

                'highlighted_description' => $detail['highlighted_description'],

                'sort_order' => $detail['sort_order'] ?? 0,

                'status' => $detail['status'],
            ]);
        }

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

        $event->delete();

        return back()->with(
            'success',
            'Deleted successfully'
        );
    }
}