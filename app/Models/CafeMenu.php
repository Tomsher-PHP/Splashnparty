<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CafeMenu extends Model
{
    protected $fillable = [

        'branch_id',
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

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function category()
    {
        return $this->belongsTo(
            CafeMenuCategory::class,
            'cafe_menu_category_id'
        );
    }
}