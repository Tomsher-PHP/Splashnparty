<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CakeEnquiry extends Model
{
    protected $fillable = [
        'cake_id',
        'name',
        'email',
        'phone',
        'preferred_date',
        'message',
        'status',
    ];

    public function cake()
    {
        return $this->belongsTo(Cake::class);
    }
}
