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
        Schema::create('rental_items', function (Blueprint $table) {

            $table->id();

            $table->foreignId('rental_category_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('image')
                ->nullable();

            $table->string('title');

            $table->text('price')
                ->nullable();

            $table->longText('description')
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
        Schema::dropIfExists('rental_items');
    }
};