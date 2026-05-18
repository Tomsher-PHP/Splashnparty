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
        $faqs = Permission::firstOrCreate(
            ['name' => 'faqs', 'guard_name' => 'web'],
            ['parent_id' => null]
        );

        foreach ([
            'view_faqs',
            'create_faqs',
            'edit_faqs',
            'delete_faqs'
        ] as $permission) {

            Permission::firstOrCreate(
                ['name' => $permission, 'guard_name' => 'web'],
                ['parent_id' => $faqs->id]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Permission::whereIn('name', [
            'view_faqs',
            'create_faqs',
            'edit_faqs',
            'delete_faqs'
        ])->delete();

        Permission::where('name', 'faqs')->delete();
    }
};