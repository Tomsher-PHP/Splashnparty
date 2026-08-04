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
        // Find parent permission group 'settings'
        $settings = Permission::where('name', 'settings')->first();
        
        $parentId = $settings ? $settings->id : null;

        $permissions = [
            'view_rules',
            'create_rules',
            'edit_rules',
            'delete_rules',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                [
                    'name' => $permission,
                    'guard_name' => 'web'
                ],
                [
                    'parent_id' => $parentId
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
            // Silence if roles do not exist
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Permission::whereIn('name', [
            'view_rules',
            'create_rules',
            'edit_rules',
            'delete_rules',
        ])->delete();
    }
};
