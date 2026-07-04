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
        $general_access = Permission::firstOrCreate(
            ['name' => 'general_access', 'guard_name' => 'web'],
            ['parent_id' => null]
        );

        foreach ([
            'view_general_access',
            'create_general_access',
            'edit_general_access',
            'delete_general_access'
        ] as $permission) {

            Permission::firstOrCreate(
                ['name' => $permission, 'guard_name' => 'web'],
                ['parent_id' => $general_access->id]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Permission::whereIn('name', [
            'view_general_access',
            'create_general_access',
            'edit_general_access',
            'delete_general_access'
        ])->delete();

        Permission::where('name', 'general_access')->delete();
    }
};