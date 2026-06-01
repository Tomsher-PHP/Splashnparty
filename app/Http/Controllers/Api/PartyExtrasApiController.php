<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PartyExtras;

class PartyExtrasApiController extends Controller
{

    public function partyExtras()
    {
        $limit = min(
            request('limit', 10),
            50
        );

        $query = PartyExtras::query();


        // Category Filter
        if ($category = request('category')) {

            $query->where(
                'category',
                'like',
                '%' . $category . '%'
            );
        }

        // Title Filter
        if ($title = request('title')) {

            $query->where(
                'title',
                'like',
                '%' . $title . '%'
            );
        }

        // Type Filter
        if ($type = request('type')) {

            $query->where(
                'type',
                $type
            );
        }

        $extras = $query
            ->orderBy('sort_order')
            ->latest()
            ->paginate($limit);

        // IMAGE URL FIX
        $extras->getCollection()->transform(function ($extra) {
            $extra->image = $extra->image
                ? asset($extra->image)
                : null;
            return $extra;
        });

        return response()->json([
            'success' => true,
            'message' => 'Party Extras data fetched successfully',
            'data' => $extras,
            'page_content' => \App\Models\Page::getPageContent('party-extras'),

        ]);
    }
}
