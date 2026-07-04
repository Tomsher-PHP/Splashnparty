<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CafeMenu extends Model
{
    protected $fillable = [
        'branch_ids',
        'cafe_menu_category_id',
        'image',
        'title',
        'description',
        'price',
        'menu_type',
        'food_type',
        'sort_order',
        'status',
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
            CafeMenuCategory::class,
            'cafe_menu_category_id'
        );
    }
}