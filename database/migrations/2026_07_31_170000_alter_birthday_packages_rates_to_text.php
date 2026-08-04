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
        Schema::table('birthday_packages', function (Blueprint $table) {
            $table->text('weekday_rate')->nullable()->change();
            $table->text('weekend_rate')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('birthday_packages', function (Blueprint $table) {
            $table->decimal('weekday_rate', 10, 2)->nullable()->change();
            $table->decimal('weekend_rate', 10, 2)->nullable()->change();
        });
    }
};
