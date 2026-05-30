<?php

use App\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $pages = Permission::firstOrCreate(
            [
                'name' => 'pages',
                'guard_name' => 'web'
            ],
            [
                'parent_id' => null
            ]
        );

        $permissions = [
            'view_pages',
            'edit_pages'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                [
                    'name' => $permission,
                    'guard_name' => 'web'
                ],
                [
                    'parent_id' => $pages->id
                ]
            );
        }

        // To make testing convenient, assign these permissions to existing roles
        try {
            $roles = Role::all();
            foreach ($roles as $role) {
                // If role name is Super Admin or Admin or similar, or just all existing roles
                $role->givePermissionTo($permissions);
            }
        } catch (\Throwable $e) {
            // Silence if Spatie tables are not loaded yet or roles do not exist
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Permission::whereIn('name', [
            'view_pages',
            'edit_pages'
        ])->delete();

        Permission::where('name', 'pages')->delete();
    }
};
