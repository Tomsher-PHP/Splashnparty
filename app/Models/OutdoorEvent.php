<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OutdoorEvent extends Model
{
    protected $fillable = [
        'images',

        'meta_title',
        'meta_description',
        'meta_keywords',

        'og_title',
        'og_description',
        'og_image',

        'twitter_title',
        'twitter_description',
    ];

    protected $casts = [
        'images' => 'array',
    ];
}