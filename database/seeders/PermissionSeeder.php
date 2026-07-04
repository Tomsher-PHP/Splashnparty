<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    
    public function run()
    {
        $roles = Permission::firstOrCreate(
            ['name' => 'roles', 'guard_name' => 'web'],
            ['parent_id' => null]
        );

        foreach (['roles.view', 'roles.create', 'roles.edit', 'roles.delete'] as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission, 'guard_name' => 'web'],
                ['parent_id' => $roles->id]
            );
        }

        $staffs = Permission::firstOrCreate(
            ['name' => 'staffs', 'guard_name' => 'web'],
            ['parent_id' => null]
        );

        foreach (['view_staff', 'create_staff', 'edit_staff'] as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission, 'guard_name' => 'web'],
                ['parent_id' => $staffs->id]
            );
        }

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

        $settings = Permission::firstOrCreate(
            ['name' => 'settings', 'guard_name' => 'web'],
            ['parent_id' => null]
        );

        foreach (['view_general_settings', 'edit_general_settings'] as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission, 'guard_name' => 'web'],
                ['parent_id' => $settings->id]
            );
        }

        $newsletters = Permission::firstOrCreate(
            ['name' => 'newsletters', 'guard_name' => 'web'],
            ['parent_id' => null]
        );

        foreach (['view_newsletter_subscriptions', 'delete_newsletter_subscriptions'] as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission, 'guard_name' => 'web'],
                ['parent_id' => $newsletters->id]
            );
        }
    }
}
