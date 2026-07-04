<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BirthdayPackage extends Model
{
    protected $fillable = [

        'branch_id',
        'title',
        'slug',
        'image',
        'banner_image',
        'price',
        'minimum_kids',
        'duration',
        'weekday_rate',
        'weekend_rate',
        'highlighted_description',
        'description',
        'sort_order',
        'status',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'og_title',
        'og_description',
        'og_image',
        'twitter_title',
        'twitter_description',
        'schema',
        'faq_selection',
        'faq_title',
        'faq_description',
    ];

    protected $casts = [
        'faq_selection' => 'array',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}