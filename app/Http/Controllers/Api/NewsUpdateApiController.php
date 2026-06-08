<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NewsUpdate;

class NewsUpdateApiController extends Controller
{
    public function index() {
        $news = NewsUpdate::where('status',1)
            ->orderBy('publish_date','desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $news,
            'page_content' => \App\Models\Page::getPageContent('news-updates')
        ]);
    }

    public function show($id)
    {
        $news = NewsUpdate::where('id',$id)
            ->where('status',1)
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => $news,
            'page_content' => \App\Models\Page::getPageContent('news-updates')
        ]);
    }
}
