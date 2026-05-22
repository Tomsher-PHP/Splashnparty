<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cake extends Model
{
    protected $fillable = [

        'title',
        'product_code',
        'thumbnail_image',
        'gallery_images',
        'description',
        'price',
        'sort_order',
        'status',
    ];

    protected $casts = [

        'gallery_images' => 'array',
        'status'         => 'boolean',
    ];
}