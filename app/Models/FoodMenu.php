<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FoodMenu extends Model
{
   protected $fillable = [
        'branch_ids',
        'type',
        'food_type',
        'price',
        'description',
        'image',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'branch_ids' => 'array',
    ];

    public function branch()
    {
        return \App\Models\Branch::whereIn(
            'id',
            $this->branch_ids ?? []
        )->get();
    }

}