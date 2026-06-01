<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('packages', function (Blueprint $table) {

            $table->id();

            $table->foreignId('branch_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('title');

            $table->enum('food_type', ['veg', 'non_veg'])->nullable();

            /*
            |--------------------------------------------------------------------------
            | WITH FOOD PRICES
            |--------------------------------------------------------------------------
            */
            $table->decimal('child_weekday_price_with_food', 10, 2)->nullable();
            $table->decimal('adult_weekday_price_with_food', 10, 2)->nullable();
            $table->decimal('child_weekend_price_with_food', 10, 2)->nullable();
            $table->decimal('adult_weekend_price_with_food', 10, 2)->nullable();

            /*
            |--------------------------------------------------------------------------
            | WITHOUT FOOD PRICES
            |--------------------------------------------------------------------------
            */
            $table->decimal('child_weekday_price_without_food', 10, 2)->nullable();
            $table->decimal('adult_weekday_price_without_food', 10, 2)->nullable();
            $table->decimal('child_weekend_price_without_food', 10, 2)->nullable();
            $table->decimal('adult_weekend_price_without_food', 10, 2)->nullable();

            // Offer
            $table->boolean('free_adult_with_child')->default(false);

            // Date Range
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            // Selected days
            $table->json('days')->nullable();

            $table->boolean('status')->default(true);
            $table->integer('sort_order')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};