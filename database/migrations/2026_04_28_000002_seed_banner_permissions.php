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
        $banners = Permission::firstOrCreate(
            ['name' => 'banners', 'guard_name' => 'web'],
            ['parent_id' => null]
        );

        foreach (['view_banners', 'create_banners', 'edit_banners', 'delete_banners'] as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission, 'guard_name' => 'web'],
                ['parent_id' => $banners->id]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Permission::whereIn('name', ['view_banners', 'create_banners', 'edit_banners', 'delete_banners'])->delete();
        Permission::where('name', 'banners')->delete();
    }
};
