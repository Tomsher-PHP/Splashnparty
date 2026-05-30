<?php

use App\Models\Permission;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $food_menus = Permission::firstOrCreate(
            ['name' => 'food_menus', 'guard_name' => 'web'],
            ['parent_id' => null]
        );

        foreach ([
            'view_food_menus',
            'create_food_menus',
            'edit_food_menus',
            'delete_food_menus'
        ] as $permission) {

            Permission::firstOrCreate(
                ['name' => $permission, 'guard_name' => 'web'],
                ['parent_id' => $food_menus->id]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Permission::whereIn('name', [
            'view_food_menus',
            'create_food_menus',
            'edit_food_menus',
            'delete_food_menus'
        ])->delete();

        Permission::where('name', 'food_menus')->delete();
    }
};