<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('food_menus', function (Blueprint $table) {

            $table->id();

            $table->foreignId('branch_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->enum('type', [
                'adult',
                'kid'
            ]);

            $table->enum('food_type', [
                'veg',
                'non-veg'
            ]);

            $table->string('price')
                ->nullable();

            $table->text('description')
                ->nullable();

            $table->string('image')
                ->nullable();

            $table->integer('sort_order')
                ->default(0);

            $table->boolean('status')
                ->default(1);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('food_menus');
    }
};