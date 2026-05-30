<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BalloonDecoration;

class BalloonDecorationApiController extends Controller
{

    public function balloonDecorations()
    {
        $limit = min(
            request('limit', 10),
            50
        );

        $query = BalloonDecoration::where(
            'status',
            1
        );

        // TITLE FILTER
        if ($title = request('title')) {
            $query->where(
                'title',
                'like',
                '%' . $title . '%'
            );
        }

        $decorations = $query
            ->orderBy('sort_order')
            ->paginate($limit);

        $decorations->getCollection()->transform(
            function ($item) {

                $item->image = $item->image
                    ? asset($item->image)
                    : null;

                return $item;
            }
        );

        return response()->json([
            'success' => true,
            'data' => $decorations
        ]);
    }
}
