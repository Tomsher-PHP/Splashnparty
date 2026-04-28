<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
        'title',
        'subtitle',
        'btn_text',
        'btn_link',
        'status',
        'banner_type',
        'file',
    ];

    protected function casts(): array
    {
        return [
            'status' => 'boolean',
        ];
    }
}
