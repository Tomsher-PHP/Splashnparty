<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('site_settings')
            ->whereIn('key', [
                'facebook_url',
                'instagram_url',
                'twitter_url',
                'linkedin_url',
                'youtube_url'
            ])
            ->delete();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Re-inserting with null values if needed on rollback
        foreach (['facebook_url' => 100, 'instagram_url' => 110, 'twitter_url' => 120, 'linkedin_url' => 130, 'youtube_url' => 140] as $key => $order) {
            DB::table('site_settings')->updateOrInsert(
                ['key' => $key],
                [
                    'group' => 'social',
                    'value' => null,
                    'type' => 'text',
                    'sort_order' => $order,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
};
