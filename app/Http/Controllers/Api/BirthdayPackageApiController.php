<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BirthdayPackage;
use App\Models\Branch;

class BirthdayPackageApiController extends Controller
{
    public function birthdayPackages()
    {
        $limit = min(
            request('limit', 10),
            50
        );

        $query = BirthdayPackage::with('branch')
            ->where(
                'status',
                1
            );

        if ($branchId = request('branch_id')) {
            $query->where(
                'branch_id',
                $branchId
            );
        }

        if ($slug = request('slug')) {

            $query->where(
                'slug',
                $slug
            );
        }

        $packages = $query
            ->orderBy('sort_order')
            ->paginate($limit);

        $packages->getCollection()->transform(
            function ($item) {

                $item->image = $item->image
                    ? asset($item->image)
                    : null;

                $item->banner_image = $item->banner_image
                    ? asset($item->banner_image)
                    : null;

                return $item;
            }
        );

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

        $packagesArray = $packages->toArray();
        $packagesArray['locations'] = $locations;

        return response()->json([
            'success' => true,
            'message' => 'Birthday packages retrieved successfully.',
            'page_content' => \App\Models\Page::getPageContent('birthday-packages'),
            'locations' => $locations,
            'data' => $packagesArray
        ]);
    }
}
