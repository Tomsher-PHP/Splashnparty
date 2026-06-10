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
}