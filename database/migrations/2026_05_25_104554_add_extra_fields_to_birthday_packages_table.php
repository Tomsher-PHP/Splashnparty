<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('birthday_packages', function (Blueprint $table) {

            $table->string('minimum_kids')
                ->nullable()
                ->after('price');

            $table->string('duration')
                ->nullable()
                ->after('minimum_kids');

            $table->decimal('weekday_rate', 10, 2)
                ->nullable()
                ->after('duration');

            $table->decimal('weekend_rate', 10, 2)
                ->nullable()
                ->after('weekday_rate');
        });
    }

    public function down(): void
    {
        Schema::table('birthday_packages', function (Blueprint $table) {

            $table->dropColumn([
                'minimum_kids',
                'duration',
                'weekday_rate',
                'weekend_rate'
            ]);
        });
    }
};