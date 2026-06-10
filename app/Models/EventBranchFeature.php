<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventBranchFeature extends Model
{
    protected $fillable = [

        'event_branch_detail_id',

        'icon',
        'title',
        'subtitle',
        'content',

        'sort_order',
        'status'
    ];

    public function branchDetail()
    {
        return $this->belongsTo(
            EventBranchDetail::class
        );
    }
}