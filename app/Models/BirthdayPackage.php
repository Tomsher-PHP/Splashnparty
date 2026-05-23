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
        'highlighted_description',
        'description',
        'sort_order',
        'status',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}