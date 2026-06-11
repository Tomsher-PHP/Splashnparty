<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ImageGallery;
use App\Models\VideoGallery;
use App\Models\OutdoorEvent;
use App\Models\IndoorEvent;
use Illuminate\Http\Request;


class GalleryApiController extends Controller
{

    public function galleryCategories(Request $request){
        $type = $request->type ?? 'image';

        if($type == 'image'){
            $category = ImageGallery::where('status', 1)->get(['id', 'category_name', 'slug', 'images']);
            $category->transform(function ($item) {
                $item->images = collect($item->images ?? [])->map(fn($img) => asset($img))->toArray();
                return $item;
            });
        }else if($type == 'video'){
            $category = VideoGallery::where('status', 1)->get(['id', 'category_name', 'slug']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Categories found.',
            'data' => $category,
            'page_content' => \App\Models\Page::getPageContent($type.'-gallery'),
        ]);
    }

    public function galleryItems(Request $request){
        $type = $request->type ?? 'image';
        $category = $request->category ?? null;

        if($type == 'image'){
            $query = ImageGallery::where('status', 1);
            if($category){
                $query->where('slug', $category);
            }
            $items = $query->latest()->get();
            $items->transform(function ($item) {
                $item->images = collect($item->images ?? [])->map(fn($img) => asset($img))->toArray();
                $item->og_image = $item->og_image ? asset($item->og_image) : null;
                return $item;
            });
        }else if($type == 'video'){
            $query = VideoGallery::where('status', 1);
            if($category){
                $query->where('slug', $category);
            }
            $items = $query->latest()->get();
            $items->transform(function ($item) {
                $item->og_image = $item->og_image ? asset($item->og_image) : null;
                return $item;
            });
        }

        return response()->json([
            'success' => true,
            'message' => 'Items found.',
            'data' => $items,
        ]);
    }
    public function imageGallery()
    {
        $limit = min(
            request('limit', 10),
            50
        );

        $query = ImageGallery::where('status', 1);
      
        // SLUG FILTER
        if ($slug = request('category')) {
            $query->where('slug', $slug);
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

        $query = OutdoorEvent::query();

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