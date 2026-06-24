<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [

        'title',
        'slug',
        'image',
        'banner_image',
        'heading',
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

    public function branchDetails()
    {
        return $this->hasMany(
            EventBranchDetail::class
        );
    }

}