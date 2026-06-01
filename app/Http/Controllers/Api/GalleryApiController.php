<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ImageGallery;
use App\Models\VideoGallery;
use App\Models\OutDoorEvent;

class GalleryApiController extends Controller
{
    public function imageGallery()
    {
        $limit = min(
            request('limit', 10),
            50
        );

        $query = ImageGallery::where('status', 1);
        // CATEGORY FILTER
        if ($category = request('category')) {

            $query->where(
                'category_name',
                'like',
                '%' . $category . '%'
            );
        }

        $galleries = $query
            ->latest()
            ->paginate($limit);

        $galleries->getCollection()->transform(function ($gallery) {

            $gallery->images = collect(
                $gallery->images
            )->map(function ($image) {

                return asset($image);

            });

            // OG IMAGE
            $gallery->og_image = $gallery->og_image
                ? asset($gallery->og_image)
                : null;

            return $gallery;
        });

        return response()->json([
            'success' => true,
            'page_content' => \App\Models\Page::getPageContent('image-gallery'),
            'data' => $galleries
        ]);
    }

    public function videoGallery()
    {
        $limit = min(
            request('limit', 10),
            50
        );

        $query = VideoGallery::where('status', 1);

        // CATEGORY FILTER
        if ($category = request('category')) {

            $query->where(
                'category_name',
                'like',
                '%' . $category . '%'
            );
        }

        $videos = $query
            ->latest()
            ->paginate($limit);

        return response()->json([
            'success' => true,
            'message' => 'Videos found.',
            'data' => $videos,
            'page_content' => \App\Models\Page::getPageContent('video-gallery'),
        ]);
    }

   public function outdoorEvents()
    {
        $limit = min(
            request('limit', 10),
            50
        );

        $query = OutDoorEvent::query();

        // TITLE FILTER
        if ($title = request('title')) {

            $query->where(
                'title',
                'like',
                '%' . $title . '%'
            );
        }

        $events = $query
            ->latest()
            ->paginate($limit);

        return response()->json([
            'success' => true,
            'message' => 'Events found.',
            'data' => $events,
            'page_content' => \App\Models\Page::getPageContent('outdoor-events'),
        ]);
    }
}