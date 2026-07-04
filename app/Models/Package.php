<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $fillable = [

        'branch_id',
        'title',
        'food_type',

        // With Food Prices
        'child_weekday_price_with_food',
        'adult_weekday_price_with_food',
        'child_weekend_price_with_food',
        'adult_weekend_price_with_food',

        // Without Food Prices
        'child_weekday_price_without_food',
        'adult_weekday_price_without_food',
        'child_weekend_price_without_food',
        'adult_weekend_price_without_food',

        'child_count_for_free_adult',

        'start_date',
        'end_date',

        'days',

        'status',
        'sort_order',
    ];

    protected $casts = [
        'days' => 'array',
        'child_count_for_free_adult' => 'integer',
        'status' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}