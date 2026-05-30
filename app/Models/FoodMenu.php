<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FoodMenu extends Model
{
    protected $fillable = [
        'title',
        'branch_id',
        'type',
        'food_type',
        'price',
        'description',
        'image',
        'sort_order',
        'status',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}