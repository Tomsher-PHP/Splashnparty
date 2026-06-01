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
            ['name' => 'contact_enquiries', 'guard_name' => 'web'],
            ['parent_id' => null]
        );

        foreach ([
            'view_contact_enquiries',
            'delete_contact_enquiries'
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
            'view_contact_enquiries',
            'delete_contact_enquiries'
        ])->delete();

        Permission::where('name', 'contact_enquiries')->delete();
    }
};
