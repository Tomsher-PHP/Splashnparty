<?php

namespace App\Models;

use App\Models\Branch;
use Illuminate\Database\Eloquent\Model;

class FoodMenu extends Model
{
    protected $fillable = [
        'title',
        'branch_ids',
        'type',
        'food_type',
        'price',
        'description',
        'image',
        'sort_order',
        'status',
        'food_menu_category_id',
    ];

    protected $casts = [
        'branch_ids' => 'array',
    ];

    public function getBranchesAttribute()
    {
        return Branch::whereIn(
            'id',
            $this->branch_ids ?? []
        )->get();
    }

    public function category()
    {
        return $this->belongsTo(
            FoodMenuCategory::class,
            'food_menu_category_id'
        );
    }
}