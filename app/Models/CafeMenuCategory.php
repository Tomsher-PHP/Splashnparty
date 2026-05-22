<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CafeMenuCategory extends Model
{
    protected $fillable = [

        'title',
        'slug',
        'sort_order',
        'status',
    ];

    public function menus()
    {
        return $this->hasMany(CafeMenu::class);
    }
}