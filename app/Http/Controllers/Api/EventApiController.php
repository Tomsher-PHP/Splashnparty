<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventBranchDetail;
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
            ->get([
                'id',
                'title',
                'slug',
                'image',
                'banner_image'
            ]);

        return response()->json([
            'success' => true,
            'data' => $events,
            'page_content' => \App\Models\Page::getPageContent('events-listing'),
        ]);
    }

    public function show(
        Request $request,
        $id
    ) {

        $request->validate([
            'branch_id' => 'required|exists:branches,id',
        ]);

        $event = Event::where(
                'status',
                1
            )
            ->findOrFail($id);

        $branchDetail = EventBranchDetail::with([
                'features',
                'galleries'
            ])
            ->where(
                'event_id',
                $event->id
            )
            ->where(
                'branch_id',
                $request->branch_id
            )
            ->first();

        return response()->json([
            'success' => true,

            'data' => [

                'id' => $event->id,

                'title' => $event->title,

                'slug' => $event->slug,

                'image' => $event->image,

                'banner_image' => $event->banner_image,

                'branch_detail' => $branchDetail,
            ],
        ]);
    }
}