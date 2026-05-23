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
        $events = Permission::firstOrCreate(
            ['name' => 'events', 'guard_name' => 'web'],
            ['parent_id' => null]
        );

        foreach ([
            'view_events',
            'create_events',
            'edit_events',
            'delete_events'
        ] as $permission) {

            Permission::firstOrCreate(
                ['name' => $permission, 'guard_name' => 'web'],
                ['parent_id' => $events->id]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Permission::whereIn('name', [
            'view_events',
            'create_events',
            'edit_events',
            'delete_events'
        ])->delete();

        Permission::where('name', 'events')->delete();
    }
};