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
        Schema::table('video_galleries', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('category_name');
        });

        // Generate slugs for existing video galleries
        $galleries = Illuminate\Support\Facades\DB::table('video_galleries')->get();
        foreach ($galleries as $gallery) {
            $slug = Illuminate\Support\Str::slug($gallery->category_name);
            Illuminate\Support\Facades\DB::table('video_galleries')
                ->where('id', $gallery->id)
                ->update(['slug' => $slug]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('video_galleries', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
