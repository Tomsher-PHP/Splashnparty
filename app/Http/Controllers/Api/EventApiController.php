<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventBranchDetail;
use App\Models\Page;
use Illuminate\Http\Request;

class EventApiController extends Controller
{
    public function index()
    {
        $events = Event::where(
            'status',
            1
        )
            ->orderBy(
                'sort_order'
            )
            ->select(
                'id',
                'title',
                'slug',
                'image',
                'banner_image',
                'heading',
                'description',
            )
            ->paginate(min(request('limit', 10), 50));

        return response()->json([
            'success' => true,
            'data' => $events,
            'page_content' => Page::getPageContent('events-listing'),
        ]);
    }

    public function show(Request $request)
    {
        $request->validate([
            'slug' => 'required|string|exists:events,slug',
            'branch_id' => 'nullable|integer|exists:branches,id',
        ]);

        $event = Event::where('status', 1)
            ->where('slug', $request->slug)
            ->firstOrFail();

        $query = EventBranchDetail::with([
            'branch:id,title,description,image,location_link,address,phone,email,working_hours',
            'features' => function ($q) {
                $q->where('status', 1)->orderBy('sort_order');
            },
            'galleries' => function ($q) {
                $q->where('status', 1)->orderBy('sort_order');
            },
        ])
            ->where('event_id', $event->id)
            ->where('status', 1);

        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        $branchDetails = $query->orderBy('sort_order')->get();

        // Format image URLs to absolute URLs
        $event->image = $event->image ? asset($event->image) : null;
        $event->banner_image = $event->banner_image ? asset($event->banner_image) : null;
        $event->og_image = $event->og_image ? asset($event->og_image) : null;

        $branchDetails->transform(function ($detail) {
            $detail->image = $detail->image ? asset($detail->image) : null;
            $detail->middle_banner = $detail->middle_banner ? asset($detail->middle_banner) : null;

            if ($detail->branch) {
                $detail->branch->image = $detail->branch->image ? asset($detail->branch->image) : null;
            }
            
            $detail->galleries->transform(function ($gallery) {
                $gallery->image = $gallery->image ? asset($gallery->image) : null;
                return $gallery;
            });
            
            $detail->features->transform(function ($feature) {
                $feature->icon = $feature->icon ? asset($feature->icon) : null;
                return $feature;
            });

            return $detail;
        });

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $event->id,
                'title' => $event->title,
                'slug' => $event->slug,
                'image' => $event->image,
                'banner_image' => $event->banner_image,
                'heading' => $event->heading,
                'description' => $event->description,
                'meta_title' => $event->meta_title,
                'meta_description' => $event->meta_description,
                'meta_keywords' => $event->meta_keywords,
                'og_title' => $event->og_title,
                'og_description' => $event->og_description,
                'og_image' => $event->og_image,
                'twitter_title' => $event->twitter_title,
                'twitter_description' => $event->twitter_description,
                'branch_details' => $branchDetails,
            ],
        ]);
    }
}
