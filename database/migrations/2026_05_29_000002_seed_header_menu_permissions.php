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
        // Find or create parent permission group 'settings'
        $settings = Permission::firstOrCreate(
            ['name' => 'settings', 'guard_name' => 'web'],
            ['parent_id' => null]
        );

        $permissions = [
            'view_header_menus',
            'create_header_menus',
            'edit_header_menus',
            'delete_header_menus',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                [
                    'name' => $permission,
                    'guard_name' => 'web'
                ],
                [
                    'parent_id' => $settings->id
                ]
            );
        }

        // Auto-assign permissions to all existing Spatie roles
        try {
            $roles = Role::all();
            foreach ($roles as $role) {
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
            'view_header_menus',
            'create_header_menus',
            'edit_header_menus',
            'delete_header_menus',
        ])->delete();
    }
};
