<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\FoodMenu;
use App\Models\Page;
use DB;

class FoodMenuApiController extends Controller
{
    public function foodMenus()
    {
        $limit = min(request('limit', 10), 50);

        $query = FoodMenu::where('status', 1);
        // BRANCH FILTER
        if ($branchId = request('branch_id')) {
            $query->where(function ($q) use ($branchId) {
                $q->whereJsonContains('branch_ids', (int) $branchId)
                    ->orWhereJsonContains('branch_ids', (string) $branchId);
            });
        }

        // TYPE FILTER
        if ($type = request('type')) {
            $query->where('type', $type);
        }

        $menus = $query->orderBy('sort_order')->paginate($limit);

        $menus->transform(function ($item) {

            $item->image = $item->image
                ? asset($item->image)
                : null;

            return $item;
        });

        $locations = Branch::where('status', 1)
            ->orderBy('sort_order', 'asc')
            ->get(['id', 'title', 'description', 'image', 'location_link', 'address', 'phone', 'email', 'working_hours'])
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
                    'working_hours' => $client->working_hours,
                ];
            });

        $foodArray = $menus->toArray();
        $foodArray['locations'] = $locations;

        return response()->json([
            'success' => true,
            'message' => 'Food menus retrieved successfully.',
            'data' => $foodArray,
            'page_content' => ($type ?? '') == 'adult' ? Page::getPageContent('adult-platters') : Page::getPageContent('kids-meal'),
        ]);
    }
}
