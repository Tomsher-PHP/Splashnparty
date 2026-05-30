<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Branch;

class BranchApiController extends Controller
{
    
    public function branches()
{
    $limit = min(
        request('limit', 10),
        50
    );

    $query = Branch::query();

    // TITLE SEARCH
    if ($title = request('title')) {

        $query->where(
            'title',
            'like',
            '%' . $title . '%'
        );
    }

    // STATUS FILTER
    if (request()->has('status')) {

        $query->where(
            'status',
            request('status')
        );
    }

    $branches = $query
        ->orderBy('sort_order')
        ->latest()
        ->paginate($limit);

    // IMAGE URL FIX
    $branches->getCollection()->transform(function ($branch) {

        $branch->image = $branch->image
            ? asset($branch->image)
            : null;

        return $branch;
    });

    return response()->json([

        'success' => true,

        'data' => $branches

    ]);
}
}