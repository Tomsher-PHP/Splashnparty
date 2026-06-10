<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NewsUpdate;
use Illuminate\Http\Request;

class NewsUpdateApiController extends Controller
{
    public function index() {
        $news = NewsUpdate::where('status',1)
            ->orderBy('publish_date','desc')
            ->paginate(min(request('limit', 10), 50));

        return response()->json([
            'success' => true,
            'data' => $news,
            'page_content' => \App\Models\Page::getPageContent('news-updates')
        ]);
    }

    public function show(Request $request) {
        $slug = $request->query('slug');
        if (!$slug) {
            return response()->json([
                'success' => false,
                'message' => 'Slug parameter is required.'
            ], 200);
        }

        $news = NewsUpdate::where('slug', $slug)
            ->where('status', 1)
            ->first();

        if (!$news) {
            return response()->json([
                'success' => false,
                'message' => 'News update not found.'
            ], 200);
        }

        return response()->json([
            'success' => true,
            'data' => $news,
            'page_content' => \App\Models\Page::getPageContent('news-updates')
        ]);
    }
    
}
