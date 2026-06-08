<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsUpdate extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'image',
        'publish_date',
        'status',
       
        'meta_title',
        'meta_description',
        'meta_keywords',

        'og_title',
        'og_description',
        'og_image',

        'twitter_title',
        'twitter_description',
    ];
}
