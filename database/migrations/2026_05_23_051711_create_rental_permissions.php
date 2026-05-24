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
        $rental = Permission::firstOrCreate(
            [
                'name'       => 'rentals',
                'guard_name' => 'web'
            ],
            [
                'parent_id'  => null
            ]
        );

        foreach ([
            'view_rental_categories',
            'create_rental_categories',
            'edit_rental_categories',
            'delete_rental_categories',

            'view_rental_items',
            'create_rental_items',
            'edit_rental_items',
            'delete_rental_items',
        ] as $permission) {

            Permission::firstOrCreate(
                [
                    'name'       => $permission,
                    'guard_name' => 'web'
                ],
                [
                    'parent_id'  => $rental->id
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

            'view_rental_categories',
            'create_rental_categories',
            'edit_rental_categories',
            'delete_rental_categories',

            'view_rental_items',
            'create_rental_items',
            'edit_rental_items',
            'delete_rental_items',

        ])->delete();

        Permission::where('name', 'rentals')->delete();
    }
};