<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->string('heading')->nullable()->after('banner_image');
            $table->text('description')->nullable()->after('heading');
        });

        Schema::table('event_branch_details', function (Blueprint $table) {
            $table->string('features_title')->nullable()->after('middle_banner');
            $table->text('features_description')->nullable()->after('features_title');
            $table->string('middle_banner_link')->nullable()->after('features_description');
            $table->string('gallery_title')->nullable()->after('middle_banner_link');
            $table->text('gallery_description')->nullable()->after('gallery_title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['heading', 'description']);
        });

        Schema::table('event_branch_details', function (Blueprint $table) {
            $table->dropColumn([
                'features_title',
                'features_description',
                'middle_banner_link',
                'gallery_title',
                'gallery_description'
            ]);
        });
    }
};
