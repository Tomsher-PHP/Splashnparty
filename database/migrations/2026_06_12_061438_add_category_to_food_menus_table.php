<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('food_menus', function (Blueprint $table) {

             $table->foreignId('food_menu_category_id')
                ->constrained()
                ->cascadeOnDelete();

        });
    }

    public function down(): void
    {
        Schema::table('food_menus', function (Blueprint $table) {

            $table->dropColumn('food_menu_category_id');

        });
    }
};