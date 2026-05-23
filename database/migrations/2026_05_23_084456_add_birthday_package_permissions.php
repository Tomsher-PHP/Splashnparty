<?php

use App\Models\Permission;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $birthdayPackages = Permission::firstOrCreate(
            [
                'name' => 'birthday_packages',
                'guard_name' => 'web'
            ],
            [
                'parent_id' => null
            ]
        );

        foreach ([
            'view_balloon_decorations',
            'create_balloon_decorations',
            'edit_balloon_decorations',
            'delete_balloon_decorations',

            'view_birthday_packages',
            'create_birthday_packages',
            'edit_birthday_packages',
            'delete_birthday_packages',
        ] as $permission) {

            Permission::firstOrCreate(
                [
                    'name' => $permission,
                    'guard_name' => 'web'
                ],
                [
                    'parent_id' => $birthdayPackages->id
                ]
            );
        }
    }

    public function down(): void
    {
        Permission::whereIn('name', [

            'view_balloon_decorations',
            'create_balloon_decorations',
            'edit_balloon_decorations',
            'delete_balloon_decorations',

            'view_birthday_packages',
            'create_birthday_packages',
            'edit_birthday_packages',
            'delete_birthday_packages',

        ])->delete();

        Permission::where(
            'name',
            'birthday_packages'
        )->delete();
    }
};