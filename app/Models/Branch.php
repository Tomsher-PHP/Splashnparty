<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = [

        'title',
        'description',
        'image',
        'location_link',
        'address',
        'phone',
        'email',
        'working_hours',
        'sort_order',
        'status',

    ];
}