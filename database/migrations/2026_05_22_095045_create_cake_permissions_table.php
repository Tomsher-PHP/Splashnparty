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
        $cakes = Permission::firstOrCreate(
            [
                'name' => 'cakes',
                'guard_name' => 'web'
            ],
            [
                'parent_id' => null
            ]
        );

        foreach ([
            'view_cakes',
            'create_cakes',
            'edit_cakes',
            'delete_cakes'
        ] as $permission) {

            Permission::firstOrCreate(
                [
                    'name' => $permission,
                    'guard_name' => 'web'
                ],
                [
                    'parent_id' => $cakes->id
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
            'view_cakes',
            'create_cakes',
            'edit_cakes',
            'delete_cakes'
        ])->delete();

        Permission::where('name', 'cakes')->delete();
    }
};