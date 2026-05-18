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
        $clientLogos = Permission::firstOrCreate(
            ['name' => 'client_logos', 'guard_name' => 'web'],
            ['parent_id' => null]
        );

        foreach (['view_client_logos', 'create_client_logos', 'edit_client_logos', 'delete_client_logos'] as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission, 'guard_name' => 'web'],
                ['parent_id' => $clientLogos->id]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Permission::whereIn('name', [
            'view_client_logos',
            'create_client_logos',
            'edit_client_logos',
            'delete_client_logos',
        ])->delete();
        Permission::where('name', 'client_logos')->delete();
    }
};
