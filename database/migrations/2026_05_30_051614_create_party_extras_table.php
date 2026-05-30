<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('party_extras', function (Blueprint $table) {

            $table->id();

            $table->string('category');

            $table->string('title');

            $table->string('slug')->unique();

            $table->enum(
                'type',
                [
                    'image_gallery',
                    'video_link'
                ]
            );

            $table->json('gallery_images')
                ->nullable();

            $table->text('video_link')
                ->nullable();

            $table->string('thumbnail_image')
                ->nullable();

            // SEO
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();

            $table->string('og_title')->nullable();
            $table->text('og_description')->nullable();
            $table->string('og_image')->nullable();

            $table->string('twitter_title')->nullable();
            $table->text('twitter_description')->nullable();

            $table->integer('sort_order')
                ->default(0);

            $table->boolean('status')
                ->default(1);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('party_extras');
    }
};