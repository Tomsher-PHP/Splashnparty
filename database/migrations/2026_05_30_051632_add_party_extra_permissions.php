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
        $party_extras = Permission::firstOrCreate(
            ['name' => 'party_extras', 'guard_name' => 'web'],
            ['parent_id' => null]
        );

        foreach ([
            'view_party_extras',
            'create_party_extras',
            'edit_party_extras',
            'delete_party_extras'
        ] as $permission) {

            Permission::firstOrCreate(
                ['name' => $permission, 'guard_name' => 'web'],
                ['parent_id' => $party_extras->id]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Permission::whereIn('name', [
            'view_party_extras',
            'create_party_extras',
            'edit_party_extras',
            'delete_party_extras'
        ])->delete();

        Permission::where('name', 'party_extras')->delete();
    }
};