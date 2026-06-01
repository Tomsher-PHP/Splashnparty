<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GeneralAccess;
use Illuminate\Http\Request;

class GeneralAccessApiController extends Controller
{
    public function generalAccess(Request $request)
    {
        $query = GeneralAccess::with('branch')
            ->where('status', 1)
            ->orderBy('sort_order', 'asc')
            ->latest();

        // Title filter
        if ($request->title) {
            $query->where(
                'title',
                'like',
                '%' . $request->title . '%'
            );
        }

        // Branch filter
        if ($request->branch_id) {
            $query->where(
                'branch_id',
                $request->branch_id
            );
        }

        $data = $query->paginate(
            $request->per_page ?? 10
        );

        return response()->json([

            'success' => true,
            'message' => 'General Access data fetched successfully',
            'page_content' => \App\Models\Page::getPageContent('general-access'),
            'data' => $data->items(),
            'pagination' => [
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total(),
            ]

        ]);
    }
}