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
        $testimonials = Permission::firstOrCreate(
            [
                'name' => 'testimonials',
                'guard_name' => 'web'
            ],
            [
                'parent_id' => null
            ]
        );

        foreach ([
            'view_testimonials',
            'create_testimonials',
            'edit_testimonials',
            'delete_testimonials'
        ] as $permission) {

            Permission::firstOrCreate(
                [
                    'name' => $permission,
                    'guard_name' => 'web'
                ],
                [
                    'parent_id' => $testimonials->id
                ]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Permission::whereIn('name', [
            'view_testimonials',
            'create_testimonials',
            'edit_testimonials',
            'delete_testimonials'
        ])->delete();

        Permission::where('name', 'testimonials')->delete();
    }
};
