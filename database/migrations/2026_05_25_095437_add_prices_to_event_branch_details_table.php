<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('event_branch_details', function (Blueprint $table) {

            $table->string('weekday_price')
                ->nullable()
                ->after('highlighted_description');

            $table->string('weekend_price')
                ->nullable()
                ->after('weekday_price');
        });
    }

    public function down(): void
    {
        Schema::table('event_branch_details', function (Blueprint $table) {

            $table->dropColumn([
                'weekday_price',
                'weekend_price'
            ]);
        });
    }
};