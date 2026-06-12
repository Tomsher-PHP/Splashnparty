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
        $foodMenus = Permission::where(
            'name',
            'food_menus'
        )->first();

        if (!$foodMenus) {
            return;
        }

        foreach ([
            'view_food_menu_categories',
            'create_food_menu_categories',
            'edit_food_menu_categories',
            'delete_food_menu_categories',
        ] as $permission) {

            Permission::firstOrCreate(
                [
                    'name' => $permission,
                    'guard_name' => 'web',
                ],
                [
                    'parent_id' => $foodMenus->id,
                ]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Permission::whereIn('name', [
            'view_food_menu_categories',
            'create_food_menu_categories',
            'edit_food_menu_categories',
            'delete_food_menu_categories',
        ])->delete();
    }
};