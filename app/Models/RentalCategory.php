<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RentalCategory extends Model
{
    protected $fillable = [

        'title',
        'slug',
        'sort_order',
        'status',
    ];

    public function rentalItems()
    {
        return $this->hasMany(RentalItem::class);
    }
}