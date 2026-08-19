<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CafeMenuCategory;
use App\Models\Branch;
use App\Models\Page;
use App\Models\CafeMenu;

class CafeMenuApiController extends Controller
{

    public function cafeMenuPage(){
        $data['locations'] = Branch::where('status', 1)
            ->orderBy('sort_order', 'asc')
            ->get(['id', 'title', 'description', 'image', 'location_link', 'embedded_link', 'address', 'phone', 'email', 'working_hours'])
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
                    'working_hours' => $client->working_hours,
                ];
            });

        return response()->json([
            'success' => true,
            'message' => 'Food menus retrieved successfully.',
            'data' => $data,
            'page_content' => Page::getPageContent('cafe-menu'),
        ]);
    }

    public function cafeMenuCategories(Request $request){
        $query = CafeMenuCategory::where('status', 1);

        $branchId = request('branch_id');

        // BRANCH FILTER
        if ($branchId) {
            $query->whereHas('menus', function ($menuQuery) use ($branchId) {
                $menuQuery->where('status', 1)
                    ->where(function ($q) use ($branchId) {
                        $q->whereJsonContains('branch_ids', (int) $branchId)
                          ->orWhereJsonContains('branch_ids', (string) $branchId);
                    });
            });
        }

        $data['categories'] = $query->orderBy('sort_order', 'asc')
            ->get(['id', 'title', 'slug']);;

        return response()->json([
            'success' => true,
            'message' => 'Cafe menu categories retrieved successfully.',
            'data' => $data,
        ]);
    }

    public function cafeMenuItems(Request $request){
        $branchId = request('branch_id');
        $categoryId = request('category_id');

        $items = CafeMenu::where('status', 1)
            ->when($branchId, function ($query) use ($branchId) {
                $query->where(function ($q) use ($branchId) {
                    $q->whereJsonContains('branch_ids', (int) $branchId)
                      ->orWhereJsonContains('branch_ids', (string) $branchId);
                });
            })
            ->where('cafe_menu_category_id', $categoryId)
            ->orderBy('sort_order', 'asc')
            ->select('id', 'title', 'price', 'description', 'image','menu_type','food_type','sort_order')
            ->paginate(min(request('limit', 10), 50));

        return response()->json([
            'success' => true,
            'message' => 'Cafe menu items retrieved successfully.',
            'data' => $items,
        ]);
    }

}
