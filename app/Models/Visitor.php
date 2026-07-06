<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    protected $fillable = [
        'uuid',
        'ip_address',
        'user_agent',
        'last_visited_at',
    ];
}
