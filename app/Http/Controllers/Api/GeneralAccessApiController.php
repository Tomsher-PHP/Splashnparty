<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GeneralAccess;
use Illuminate\Http\Request;
use App\Models\Branch;
use App\Models\BirthdayPackage;

class GeneralAccessApiController extends Controller
{
    public function generalAccess(Request $request)
    {

        $data['locations'] = Branch::where('status', 1)
                                ->orderBy('sort_order', 'asc')
                                ->get(['id','title', 'description', 'image','location_link', 'embedded_link', 'address', 'phone', 'email','working_hours'])
                                ->map(function ($client) {
                                    return [
                                        'id' => $client->id,
                                        'title' => $client->title,
                                        'description' => $client->description,
                                        'image' => $client->image ? asset($client->image) : null,
                                        'location_link' => $client->location_link,
                                        'embedded_link' => $client->embedded_link,
                                        'address' => $client->address,
                                        'phone' => $client->phone,
                                        'email' => $client->email,
                                        'working_hours' => $client->working_hours
                                    ];
                                });

        $query = GeneralAccess::where('status', 1)
            ->orderBy('sort_order', 'asc')
            ->latest();

        // Branch filter
        if ($request->branch_id) {
            $query->where(
                'branch_id',
                $request->branch_id
            );
        }

        $data['general_access'] = $query->get(['id', 'branch_id', 'title', 'weekday_price', 'weekend_price']);

        $queryPackage = BirthdayPackage::where('status',1);

        if ($branchId = request('branch_id')) {

            $queryPackage->where(
                'branch_id',
                $branchId
            );
        }

        $data['birthday_packages'] = $queryPackage->get(['id', 'branch_id', 'title', 'weekday_rate','weekend_rate']);


        return response()->json([

            'success' => true,
            'message' => 'General Access data fetched successfully',
            'page_content' => \App\Models\Page::getPageContent('general-access'),
            'data' => $data
        ]);
    }
}