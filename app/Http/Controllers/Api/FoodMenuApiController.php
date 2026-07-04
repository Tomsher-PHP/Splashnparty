<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Branch;
use App\Models\FoodMenu;
use App\Models\FoodMenuCategory;
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

    public function foodMenuPage(){

        $type = request('type');

        $data['locations'] = Branch::where('status', 1)
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

        return response()->json([
            'success' => true,
            'message' => 'Food menus retrieved successfully.',
            'data' => $data,
            'page_content' => ($type ?? '') == 'adult' ? Page::getPageContent('adult-platters') : Page::getPageContent('kids-meal'),
        ]);
    }

    public function foodMenuCategories(Request $request){
        $type = request('type');
        $branchId = request('branch_id');

        if (!$type) {
            return response()->json([
                'success' => false,
                'message' => 'Type parameter is required.',
                'data' => null,
            ], 200);
        }

        if (!in_array($type, ['adult', 'kid'])) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid type parameter. Allowed values are "adult" or "kid".',
                'data' => null,
            ], 200);
        }

        if (!$branchId) {
            return response()->json([
                'success' => false,
                'message' => 'Branch ID parameter is required.',
                'data' => null,
            ], 200);
        }

        $query = FoodMenuCategory::where('status', 1);

      
        if ($branchId || $type) {
            $query->whereHas('menus', function ($menuQuery) use ($branchId, $type) {
                 $menuQuery->where('status', 1)
                    ->where(function ($q) use ($branchId) {
                        $q->whereJsonContains('branch_ids', (int) $branchId)
                          ->orWhereJsonContains('branch_ids', (string) $branchId);
                    })->where('type', $type);
            });
        }

        $data['categories'] = $query->orderBy('sort_order', 'asc')
            ->get(['id', 'title', 'slug']);;

        return response()->json([
            'success' => true,
            'message' => 'Food menu categories retrieved successfully.',
            'data' => $data,
        ]);
    }

    public function foodMenuItems(Request $request){
        $branchId = request('branch_id');
        $categoryId = request('category_id');
        $type = request('type');

        if (!$type) {
            return response()->json([
                'success' => false,
                'message' => 'Type parameter is required.',
                'data' => null,
            ], 200);
        }
        if (!in_array($type, ['adult', 'kid'])) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid type parameter. Allowed values are "adult" or "kid".',
                'data' => null,
            ], 200);
        }

        if (!$branchId) {
            return response()->json([
                'success' => false,
                'message' => 'Branch ID parameter is required.',
                'data' => null,
            ], 200);
        }

        $query = FoodMenu::where('status', 1)
                        ->where('type', $type)
                        ->when($branchId, function ($query) use ($branchId) {
                            $query->where(function ($q) use ($branchId) {
                                $q->whereJsonContains('branch_ids', (int) $branchId)
                                ->orWhereJsonContains('branch_ids', (string) $branchId);
                            });
                        });

        if($categoryId){
            $query->where('food_menu_category_id', $categoryId);
        }              

        $items = $query->orderBy('sort_order', 'asc')
                        ->select('id', 'title', 'price', 'description', 'image','type','food_type','sort_order')
                        ->paginate(min(request('limit', 10), 50));

        return response()->json([
            'success' => true,
            'message' => 'Food menu items retrieved successfully.',
            'data' => $items,
        ]);
    }
    
}
