<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\ClientLogo;
use App\Models\Testimonial;
use App\Models\Branch;
use App\Models\Faq;
use Illuminate\Http\Request;

class PageApiController extends Controller
{
    public function HomePageContent()
    {
        $page = Page::getPageContent('home');

        if (!$page) {
            return response()->json([
                'success' => false,
                'message' => 'Page not found.'
            ], 200);
        }

        $data['clients'] = ClientLogo::where('status', 1)
            ->orderBy('sort_order', 'asc')
            ->get(['title', 'logo', 'link'])
            ->map(function ($client) {
                return [
                    'title' => $client->title,
                    'logo' => $client->logo ? asset('storage/'.$client->logo) : null,
                    'link' => $client->link
                ];
            });
        $data['testimonials'] = Testimonial::where('status', 1)
                                        ->orderBy('sort_order', 'asc')
                                        ->get(['name','title','star_rating', 'description']);
        return response()->json([
            'success' => true,
            'message' => 'Page found.',
            'data' => $data,
            'page_content' => $page
        ]);
    }

    public function aboutUs()
    {
        $data = [];
        $page = Page::getPageContent('about-us');

        if (!$page) {
            return response()->json([
                'success' => false,
                'message' => 'Page not found.'
            ], 200);
        }

        $data['locations'] = Branch::where('status', 1)
            ->orderBy('sort_order', 'asc')
            ->get(['title', 'description', 'image','location_link', 'address', 'phone', 'email','working_hours'])
            ->map(function ($client) {
                return [
                    'title' => $client->title,
                    'description' => $client->description,
                    'image' => $client->image ? asset($client->image) : null,
                    'location_link' => $client->location_link,
                    'address' => $client->address,
                    'phone' => $client->phone,
                    'email' => $client->email,
                    'working_hours' => $client->working_hours
                ];
            });

        return response()->json([
            'success' => true,
            'message' => 'Page found.',
            'data' => $data,
            'page_content' => $page
        ]);
    }

    public function privacyPolicy()
    {
        $data = [];
        $page = Page::getPageContent('privacy-policy');

        if (!$page) {
            return response()->json([
                'success' => false,
                'message' => 'Page not found.'
            ], 200);
        }

        return response()->json([
            'success' => true,
            'message' => 'Page found.',
            'data' => $data,
            'page_content' => $page
        ]);
    }

    public function termsAndConditions()
    {
        $data = [];
        $page = Page::getPageContent('terms-and-conditions');

        if (!$page) {
            return response()->json([
                'success' => false,
                'message' => 'Page not found.'
            ], 200);
        }

        return response()->json([
            'success' => true,
            'message' => 'Page found.',
            'data' => $data,
            'page_content' => $page
        ]);
    }

    public function refundPolicy()
    {
        $data = [];
        $page = Page::getPageContent('cancellation-and-refund-policy');

        if (!$page) {
            return response()->json([
                'success' => false,
                'message' => 'Page not found.'
            ], 200);
        }

        return response()->json([
            'success' => true,
            'message' => 'Page found.',
            'data' => $data,
            'page_content' => $page
        ]);
    }

    public function faqs()
    {
        $data = [];
        $page = Page::getPageContent('faqs');

        if (!$page) {
            return response()->json([
                'success' => false,
                'message' => 'Page not found.'
            ], 200);
        }

        $data = Faq::where('status', 1)
            ->orderBy('sort_order', 'asc')
            ->get()
            ->map(function ($faq) {
                return [
                    'category' => $faq->category,
                    'details' => collect($faq->details)
                        ->filter(fn($item) => ($item['status'] ?? 1) == 1)
                        ->sortBy(fn($item) => (int) ($item['sort_order'] ?? 0))
                        ->map(fn($item) => [
                            'question' => $item['question'] ?? '',
                            'answer' => $item['answer'] ?? ''
                        ])
                        ->values()
                        ->all()
                ];
            });

        return response()->json([
            'success' => true,
            'message' => 'Page found.',
            'data' => $data,
            'page_content' => $page
        ]);
    }

    public function contactUs()
    {
        $data = [];
        $page = Page::getPageContent('contact-us');

        if (!$page) {
            return response()->json([
                'success' => false,
                'message' => 'Page not found.'
            ], 200);
        }

         $data['locations'] = Branch::where('status', 1)
                                    ->orderBy('sort_order', 'asc')
                                    ->get(['title', 'description', 'image','location_link', 'address', 'phone', 'email','working_hours'])
                                    ->map(function ($client) {
                                        return [
                                            'title' => $client->title,
                                            'description' => $client->description,
                                            'image' => $client->image ? asset($client->image) : null,
                                            'location_link' => $client->location_link,
                                            'address' => $client->address,
                                            'phone' => $client->phone,
                                            'email' => $client->email,
                                            'working_hours' => $client->working_hours
                                        ];
                                    });


        return response()->json([
            'success' => true,
            'message' => 'Page found.',
            'data' => $data,
            'page_content' => $page
        ]);
    }
}
