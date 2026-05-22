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
        $cafeMenu = Permission::firstOrCreate(
            [
                'name'       => 'cafe_menu',
                'guard_name' => 'web'
            ],
            [
                'parent_id'  => null
            ]
        );

        foreach ([
            'view_cafe_menu_categories',
            'create_cafe_menu_categories',
            'edit_cafe_menu_categories',
            'delete_cafe_menu_categories',

            'view_cafe_menus',
            'create_cafe_menus',
            'edit_cafe_menus',
            'delete_cafe_menus',
        ] as $permission) {

            Permission::firstOrCreate(
                [
                    'name'       => $permission,
                    'guard_name' => 'web'
                ],
                [
                    'parent_id'  => $cafeMenu->id
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

            'view_cafe_menu_categories',
            'create_cafe_menu_categories',
            'edit_cafe_menu_categories',
            'delete_cafe_menu_categories',

            'view_cafe_menus',
            'create_cafe_menus',
            'edit_cafe_menus',
            'delete_cafe_menus',

        ])->delete();

        Permission::where('name', 'cafe_menu')->delete();
    }
};