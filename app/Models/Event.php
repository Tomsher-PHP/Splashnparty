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

        'sort_order',

        'status',
    ];

    public function branchDetails()
    {
        return $this->hasMany(
            EventBranchDetail::class
        );
    }
}