<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventBranchGallery extends Model
{
    protected $fillable = [

        'event_branch_detail_id',

        'title',
        'description',
        'image',

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