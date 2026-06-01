<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeneralAccess extends Model
{
    protected $fillable = [

        'title',
        'weekday_price',
        'weekend_price',
        'branch_id',
        'sort_order',
        'status',
    ];

    /**
     * Relationship with Branch
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}