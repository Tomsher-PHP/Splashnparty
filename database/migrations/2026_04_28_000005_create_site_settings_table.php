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
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('group')->default('general');
            $table->string('key')->unique();
            $table->longText('value')->nullable();
            $table->string('type')->default('text');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        if (! Schema::hasTable('general_settings')) {
            return;
        }

        $generalSetting = DB::table('general_settings')->first();

        if (! $generalSetting) {
            return;
        }

        foreach ((array) $generalSetting as $key => $value) {
            if (in_array($key, ['id', 'created_at', 'updated_at'], true) || $value === null || $value === '') {
                continue;
            }

            DB::table('site_settings')->updateOrInsert(
                ['key' => $key],
                [
                    'group' => $this->groupForKey($key),
                    'value' => $value,
                    'type' => $this->typeForKey($key),
                    'sort_order' => $this->sortOrderForKey($key),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }

    private function groupForKey(string $key): string
    {
        return match ($key) {
            'site_name', 'logo', 'favicon', 'footer_text' => 'identity',
            'email', 'phone', 'whatsapp', 'address', 'working_hours' => 'contact',
            'facebook_url', 'instagram_url', 'twitter_url', 'linkedin_url', 'youtube_url' => 'social',
            'map_embed_url', 'meta_title', 'meta_description' => 'seo',
            default => 'general',
        };
    }

    private function typeForKey(string $key): string
    {
        return match ($key) {
            'logo', 'favicon' => 'file',
            'address', 'map_embed_url', 'meta_description' => 'textarea',
            default => 'text',
        };
    }

    private function sortOrderForKey(string $key): int
    {
        $orders = [
            'site_name' => 10,
            'footer_text' => 20,
            'logo' => 30,
            'favicon' => 40,
            'email' => 50,
            'phone' => 60,
            'whatsapp' => 70,
            'working_hours' => 80,
            'address' => 90,
            'facebook_url' => 100,
            'instagram_url' => 110,
            'twitter_url' => 120,
            'linkedin_url' => 130,
            'youtube_url' => 140,
            'meta_title' => 150,
            'meta_description' => 160,
            'map_embed_url' => 170,
        ];

        return $orders[$key] ?? 999;
    }
};
