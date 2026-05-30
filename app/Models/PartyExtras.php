<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PartyExtras extends Model
{
    protected $fillable = [
        'category',
        'title',
        'slug',
        'type',
        'gallery_images',
        'video_link',
        'thumbnail_image',

        'meta_title',
        'meta_description',
        'meta_keywords',

        'og_title',
        'og_description',
        'og_image',

        'twitter_title',
        'twitter_description',


        'sort_order',
        'status',
    ];

    protected $casts = [
        'gallery_images' => 'array',
        'status' => 'boolean',
    ];
}