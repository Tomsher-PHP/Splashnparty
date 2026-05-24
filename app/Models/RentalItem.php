<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RentalItem extends Model
{
    protected $fillable = [

        'rental_category_id',
        'image',
        'title',
        'price',
        'description',
        'sort_order',
        'status',
    ];

    public function category()
    {
        return $this->belongsTo(
            RentalCategory::class,
            'rental_category_id'
        );
    }
}