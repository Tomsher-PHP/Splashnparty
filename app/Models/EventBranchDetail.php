<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventBranchDetail extends Model
{
    protected $fillable = [

        'event_id',
        'branch_id',

        'title',
        'description',
        'image',
        'middle_banner',
        'features_title',
        'features_description',
        'middle_banner_link',
        'gallery_title',
        'gallery_description',

        'sort_order',
        'status',
    ];

    public function branch()
    {
        return $this->belongsTo(
            Branch::class
        );
    }

    public function event()
    {
        return $this->belongsTo(
            Event::class
        );
    }

    public function features()
    {
        return $this->hasMany(
            EventBranchFeature::class
        );
    }

    public function galleries()
    {
        return $this->hasMany(
            EventBranchGallery::class
        );
    }
}