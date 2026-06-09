<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BirthdayPackage;

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

        return response()->json([
            'success' => true,
            'message' => 'Birthday packages retrieved successfully.',
            'page_content' => \App\Models\Page::getPageContent('birthday-packages'),
            'data' => $packages
        ]);
    }
}
