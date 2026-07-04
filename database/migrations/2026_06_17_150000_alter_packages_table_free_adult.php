<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            if (Schema::hasColumn('packages', 'free_adult_with_child')) {
                $table->dropColumn('free_adult_with_child');
            }
            $table->integer('child_count_for_free_adult')->nullable()->default(0)->after('adult_weekend_price_without_food');
        });
    }

    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->boolean('free_adult_with_child')->default(false)->after('adult_weekend_price_without_food');
            if (Schema::hasColumn('packages', 'child_count_for_free_adult')) {
                $table->dropColumn('child_count_for_free_adult');
            }
        });
    }
};
