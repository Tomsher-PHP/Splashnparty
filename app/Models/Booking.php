<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'booking_reference',

        'package_id',
        'branch_id',

        'food_type',

        'booking_date',

        'child_count',
        'adult_count',

        'adult_price',
        'child_price',

        'free_adults',
        'chargeable_adults',

        'subtotal',
        'vat',
        'total_amount',

        'contact_name',
        'email',
        'phone',
        'emirate',
        'address',

        'remarks',

        'status',
        'payment_status',
    ];

    protected $casts = [
        'booking_date' => 'date',
    ];

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

}