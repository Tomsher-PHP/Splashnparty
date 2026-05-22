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
        Schema::create('cakes', function (Blueprint $table) {

            $table->id();

            $table->string('title');

            $table->string('product_code')->unique();

            $table->string('thumbnail_image')->nullable();

            $table->json('gallery_images')->nullable();

            $table->longText('description')->nullable();

            $table->decimal('price', 10, 2)
                ->nullable();

            $table->integer('sort_order')
                ->default(0);

            $table->boolean('status')
                ->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cakes');
    }
};