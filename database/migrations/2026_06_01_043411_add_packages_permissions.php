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
        $packages = Permission::firstOrCreate(
            ['name' => 'packages', 'guard_name' => 'web'],
            ['parent_id' => null]
        );

        foreach ([
            'view_packages',
            'create_packages',
            'edit_packages',
            'delete_packages'
        ] as $permission) {

            Permission::firstOrCreate(
                ['name' => $permission, 'guard_name' => 'web'],
                ['parent_id' => $packages->id]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Permission::whereIn('name', [
            'view_packages',
            'create_packages',
            'edit_packages',
            'delete_packages'
        ])->delete();

        Permission::where('name', 'packages')->delete();
    }
};