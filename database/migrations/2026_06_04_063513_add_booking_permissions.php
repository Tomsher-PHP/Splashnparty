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
        $bookings = Permission::firstOrCreate(
            ['name' => 'bookings', 'guard_name' => 'web'],
            ['parent_id' => null]
        );

        foreach ([
            'view_bookings',
            'generate_invoice',
        ] as $permission) {

            Permission::firstOrCreate(
                ['name' => $permission, 'guard_name' => 'web'],
                ['parent_id' => $bookings->id]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Permission::whereIn('name', [
            'view_bookings',
            'generate_invoice',
        ])->delete();

        Permission::where('name', 'bookings')->delete();
    }
};