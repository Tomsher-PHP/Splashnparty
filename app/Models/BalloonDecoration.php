<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BalloonDecoration extends Model
{
    protected $fillable = [
        'title',
        'image',
        'description',
        'price',
        'sort_order',
        'status',
    ];
}