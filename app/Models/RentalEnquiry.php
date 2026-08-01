<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RentalEnquiry extends Model
{
    protected $fillable = [
        'rental_id',
        'name',
        'email',
        'phone',
        'message',
        'status',
    ];

    public function rentalItem()
    {
        return $this->belongsTo(RentalItem::class, 'rental_id');
    }
}
