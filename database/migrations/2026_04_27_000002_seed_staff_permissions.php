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
        $staffs = Permission::firstOrCreate(
            ['name' => 'staffs', 'guard_name' => 'web'],
            ['parent_id' => null]
        );

        foreach (['view_staff', 'create_staff', 'edit_staff'] as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission, 'guard_name' => 'web'],
                ['parent_id' => $staffs->id]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Permission::whereIn('name', ['view_staff', 'create_staff', 'edit_staff'])->delete();
        Permission::where('name', 'staffs')->delete();
    }
};
