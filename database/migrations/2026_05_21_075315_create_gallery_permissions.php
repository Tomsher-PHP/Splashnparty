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
        /*
        |--------------------------------------------------------------------------
        | IMAGE GALLERY
        |--------------------------------------------------------------------------
        */

        $imageGallery = Permission::firstOrCreate(
            ['name' => 'image_gallery', 'guard_name' => 'web'],
            ['parent_id' => null]
        );

        foreach ([
            'view_image_gallery',
            'create_image_gallery',
            'edit_image_gallery',
            'delete_image_gallery'
        ] as $permission) {

            Permission::firstOrCreate(
                ['name' => $permission, 'guard_name' => 'web'],
                ['parent_id' => $imageGallery->id]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | VIDEO GALLERY
        |--------------------------------------------------------------------------
        */

        $videoGallery = Permission::firstOrCreate(
            ['name' => 'video_gallery', 'guard_name' => 'web'],
            ['parent_id' => null]
        );

        foreach ([
            'view_video_gallery',
            'create_video_gallery',
            'edit_video_gallery',
            'delete_video_gallery'
        ] as $permission) {

            Permission::firstOrCreate(
                ['name' => $permission, 'guard_name' => 'web'],
                ['parent_id' => $videoGallery->id]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | OUTDOOR EVENTS
        |--------------------------------------------------------------------------
        */

        $outdoorEvents = Permission::firstOrCreate(
            ['name' => 'outdoor_events', 'guard_name' => 'web'],
            ['parent_id' => null]
        );

        foreach ([
            'view_outdoor_events',
            'create_outdoor_events',
            'edit_outdoor_events',
            'delete_outdoor_events'
        ] as $permission) {

            Permission::firstOrCreate(
                ['name' => $permission, 'guard_name' => 'web'],
                ['parent_id' => $outdoorEvents->id]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        /*
        |--------------------------------------------------------------------------
        | IMAGE GALLERY
        |--------------------------------------------------------------------------
        */

        Permission::whereIn('name', [
            'view_image_gallery',
            'create_image_gallery',
            'edit_image_gallery',
            'delete_image_gallery'
        ])->delete();

        Permission::where('name', 'image_gallery')->delete();

        /*
        |--------------------------------------------------------------------------
        | VIDEO GALLERY
        |--------------------------------------------------------------------------
        */

        Permission::whereIn('name', [
            'view_video_gallery',
            'create_video_gallery',
            'edit_video_gallery',
            'delete_video_gallery'
        ])->delete();

        Permission::where('name', 'video_gallery')->delete();

        /*
        |--------------------------------------------------------------------------
        | OUTDOOR EVENTS
        |--------------------------------------------------------------------------
        */

        Permission::whereIn('name', [
            'view_outdoor_events',
            'create_outdoor_events',
            'edit_outdoor_events',
            'delete_outdoor_events'
        ])->delete();

        Permission::where('name', 'outdoor_events')->delete();
    }
};