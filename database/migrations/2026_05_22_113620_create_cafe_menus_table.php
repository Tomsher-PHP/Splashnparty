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
        Schema::create('cafe_menus', function (Blueprint $table) {

            $table->id();

            $table->foreignId('branch_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('cafe_menu_category_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('image')->nullable();

            $table->string('title');

            $table->longText('description')->nullable();

            $table->decimal('price', 10, 2)
                ->nullable();

            $table->enum('menu_type', [
                'adult',
                'kid'
            ])->default('adult');

            $table->enum('food_type', [
                'veg',
                'non_veg'
            ])->default('veg');

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
        Schema::dropIfExists('cafe_menus');
    }
};