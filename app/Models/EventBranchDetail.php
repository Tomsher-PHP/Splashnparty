<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventBranchDetail extends Model
{
    protected $fillable = [

        'event_id',

        'branch_id',

        'description',

        'highlighted_description',

        'sort_order',

        'status',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}