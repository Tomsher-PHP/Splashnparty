<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImageGallery extends Model
{
    protected $fillable = [
        'category_name',
        'images',
        
        'meta_title',
        'meta_description',
        'meta_keywords',

        'og_title',
        'og_description',
        'og_image',

        'twitter_title',
        'twitter_description',
        
        'status'
    ];

    protected $casts = [
        'images' => 'array'
    ];

}
