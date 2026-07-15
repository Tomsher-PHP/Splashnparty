<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\ClientLogo;
use App\Models\Testimonial;
use App\Models\Branch;
use App\Models\Faq;
use App\Models\HeaderMenu;
use App\Models\SiteSetting;
use App\Models\Banner;
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

        // Fetch banner details for slider_banners
        if (isset($page['slider_banners']) && is_array($page['slider_banners']) && !empty($page['slider_banners'])) {
            $bannerIds = $page['slider_banners'];
            $banners = Banner::whereIn('id', $bannerIds)
                ->where('status', true)
                ->get()
                ->keyBy('id');

            $sliderBannersDetails = [];
            foreach ($bannerIds as $id) {
                if (isset($banners[$id])) {
                    $banner = $banners[$id];
                    $sliderBannersDetails[] = [
                        // 'id' => $banner->id,
                        'title' => $banner->title,
                        'subtitle' => $banner->subtitle,
                        'btn_text' => $banner->btn_text,
                        'btn_link' => $banner->btn_link,
                        'banner_type' => $banner->banner_type,
                        'file' => $banner->file ? asset('storage/' . $banner->file) : null,
                    ];
                }
            }
            $page['slider_banners'] = $sliderBannersDetails;
        } else {
            $page['slider_banners'] = [];
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

         $data['locations'] = Branch::where('status', 1)
            ->orderBy('sort_order', 'asc')
            ->get(['id','title', 'description', 'image','location_link', 'address', 'phone', 'email','working_hours'])
            ->map(function ($client) {
                return [
                    'id' => $client->id,
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
            ->get(['id','title', 'description', 'image','location_link', 'address', 'phone', 'email','working_hours'])
            ->map(function ($client) {
                return [
                    'id' => $client->id,
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

        return response()->json([
            'success' => true,
            'message' => 'Page found.',
            'data' => $page['selected_faqs'] ?? [],
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
                                    ->get(['id','title', 'description', 'image','location_link', 'address', 'phone', 'email','working_hours'])
                                    ->map(function ($client) {
                                        return [
                                            'id' => $client->id,
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

    public function waterpark()
    {
        $data = [];
        $page = Page::getPageContent('waterpark');

        if (!$page) {
            return response()->json([
                'success' => false,
                'message' => 'Page not found.'
            ], 200);
        }

        $data['branches'] = Branch::where('status', 1)
            ->orderBy('sort_order', 'asc')
            ->get()
            ->map(function ($branch) {
                // Get all active attractions and adventures associated with this branch, ordered by sort_order
                $associated = $branch->attractions()
                    ->where('status', 1)
                    ->orderBy('sort_order', 'asc')
                    ->get()
                    ->map(function ($item) {
                        return [
                            'id' => $item->id,
                            'title' => $item->title,
                            'description' => $item->description,
                            'image' => $item->image ? asset($item->image) : null,
                            'type' => $item->type
                        ];
                    });

                // Differentiate into attractions and adventures
                $attractions = $associated->where('type', 'attraction')->values()->all();
                $adventures = $associated->where('type', 'adventure')->values()->all();

                // Get all active packages associated with this branch
                $packages = $branch->generalAccess()
                    ->where('status', 1)
                    ->orderBy('sort_order', 'asc')
                    ->get()
                    ->map(function ($package) {
                        return [
                            'id' => $package->id,
                            'title' => $package->title,
                            'weekday_price' => $package->weekday_price,
                            'weekend_price' => $package->weekend_price
                        ];
                    })->values()->all();

                return [
                    'id' => $branch->id,
                    'title' => $branch->title,
                    'description' => $branch->description,
                    'image' => $branch->image ? asset($branch->image) : null,
                    'location_link' => $branch->location_link,
                    'address' => $branch->address,
                    'phone' => $branch->phone,
                    'email' => $branch->email,
                    'working_hours' => $branch->working_hours,
                    'attractions' => $attractions,
                    'adventures' => $adventures,
                    'packages' => $packages
                ];
            });

        return response()->json([
            'success' => true,
            'message' => 'Page found.',
            'data' => $data,
            'page_content' => $page
        ]);
    }

    public function footerSettings()
    {
        $page = Page::getPageContent('footer');

        if (!$page) {
            return response()->json([
                'success' => false,
                'message' => 'Footer settings not found.'
            ], 200);
        }

        return response()->json([
            'success' => true,
            'message' => 'Footer settings retrieved successfully.',
            'page_content' => $page
        ]);
    }

    public function settings()
    {
        // 1. Fetch Header Menus
        $headerMenus = HeaderMenu::whereNull('parent_id')
            ->where('status', true)
            ->with(['children' => function($query) {
                $query->where('status', true)->orderBy('sort_order');
            }])
            ->orderBy('sort_order')
            ->get()
            ->map(function ($menu) {
                return [
                    'id' => $menu->id,
                    'title' => $menu->title,
                    'url' => $menu->url,
                    'icon' => $menu->icon ? asset('storage/' . $menu->icon) : null,
                    'children' => $menu->children->map(function ($child) {
                        return [
                            'id' => $child->id,
                            'title' => $child->title,
                            'url' => $child->url,
                            'icon' => $child->icon ? asset('storage/' . $child->icon) : null,
                        ];
                    })
                ];
            });

        // 2. Fetch Footer Settings
        $footerSettings = Page::getPageContent('footer') ?: [];

        // 3. Fetch General Settings
        $siteSettings = SiteSetting::pluck('value', 'key')->all();

        // Format file inputs to absolute URLs
        if (!empty($siteSettings['logo'])) {
            $siteSettings['logo'] = asset('storage/' . $siteSettings['logo']);
        }
        if (!empty($siteSettings['favicon'])) {
            $siteSettings['favicon'] = asset('storage/' . $siteSettings['favicon']);
        }
        if (!empty($siteSettings['popup_image'])) {
            $siteSettings['popup_image'] = asset('storage/' . $siteSettings['popup_image']);
        }

        $data['popup_settings'] = [
            'popup_status' => ($siteSettings['popup_status']) ? true : false,
            'popup_image' => !empty($siteSettings['popup_image']) ? asset($siteSettings['popup_image']) : null,
            'popup_button_text' => $siteSettings['popup_button_text'] ?? '',
            'popup_button_link' => $siteSettings['popup_button_link'] ?? '',
        ];

        unset($siteSettings['enquiry_email']);

        $locations = Branch::where('status', 1)
            ->orderBy('sort_order', 'asc')
            ->get(['id','title', 'description', 'image','location_link', 'address', 'phone', 'email','working_hours'])
            ->map(function ($client) {
                return [
                    'id' => $client->id,
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
            'message' => 'Settings retrieved successfully.',
            'data' => [
                'header_menus' => $headerMenus,
                'footer_settings' => $footerSettings,
                'general_settings' => $siteSettings,
                'locations' => $locations,
                'popup_settings' => $data['popup_settings'] ?? []
            ]
        ]);
    }
}


