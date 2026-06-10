<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = [

        'title',
        'description',
        'image',
        'location_link',
        'address',
        'phone',
        'email',
        'working_hours',
        'sort_order',
        'status',

    ];

    public function foodMenus()
    {
        return $this->belongsToMany(
            FoodMenu::class,
            'branch_food_menu'
        );
    }
    
    /**
     * Get the attractions/adventures that belong to this branch.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function attractions()
    {
        return $this->belongsToMany(Attraction::class, 'attraction_branch');
    }

    public function packages()
    {
        return $this->hasMany(Package::class);
    }

    public function generalAccess()
    {
        return $this->hasMany(GeneralAccess::class);
    }
}