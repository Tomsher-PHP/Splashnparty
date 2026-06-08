<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactEnquiry extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'about',
        'full_name',
        'email',
        'phone',
        'preferred_date',
        'subject',
        'message',
        'status',
    ];
}
