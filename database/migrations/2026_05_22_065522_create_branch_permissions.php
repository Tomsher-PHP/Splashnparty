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
        $branches = Permission::firstOrCreate(
            [
                'name' => 'branches',
                'guard_name' => 'web'
            ],
            [
                'parent_id' => null
            ]
        );

        foreach ([
            'view_branches',
            'create_branches',
            'edit_branches',
            'delete_branches'
        ] as $permission) {

            Permission::firstOrCreate(
                [
                    'name' => $permission,
                    'guard_name' => 'web'
                ],
                [
                    'parent_id' => $branches->id
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
            'view_branches',
            'create_branches',
            'edit_branches',
            'delete_branches'
        ])->delete();

        Permission::where('name', 'branches')->delete();
    }
};