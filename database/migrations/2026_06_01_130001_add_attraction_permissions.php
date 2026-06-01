<?php

use App\Models\Permission;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        $parent = Permission::firstOrCreate(
            ['name' => 'attractions', 'guard_name' => 'web'],
            ['parent_id' => null]
        );

        foreach ([
            'view_attractions',
            'create_attractions',
            'edit_attractions',
            'delete_attractions'
        ] as $permission) {

            Permission::firstOrCreate(
                ['name' => $permission, 'guard_name' => 'web'],
                ['parent_id' => $parent->id]
            );
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Permission::whereIn('name', [
            'view_attractions',
            'create_attractions',
            'edit_attractions',
            'delete_attractions'
        ])->delete();

        Permission::where('name', 'attractions')->delete();
    }
};
