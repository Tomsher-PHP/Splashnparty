<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        // 1. Create the many-to-many pivot table
        Schema::create('attraction_branch', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('attraction_id')
                  ->constrained('attractions')
                  ->cascadeOnDelete();

            $table->foreignId('branch_id')
                  ->constrained('branches')
                  ->cascadeOnDelete();

            $table->timestamps();
        });

        // 2. Migrate existing data from attractions.branch_id to the pivot table
        if (Schema::hasColumn('attractions', 'branch_id')) {
            $attractions = DB::table('attractions')->whereNotNull('branch_id')->get();
            foreach ($attractions as $attraction) {
                DB::table('attraction_branch')->insert([
                    'attraction_id' => $attraction->id,
                    'branch_id' => $attraction->branch_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // 3. Drop the old direct foreign key and column
            Schema::table('attractions', function (Blueprint $table) {
                $table->dropForeign(['branch_id']);
                $table->dropColumn('branch_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        // 1. Re-add the direct branch_id column to attractions
        Schema::table('attractions', function (Blueprint $table) {
            $table->foreignId('branch_id')
                  ->nullable()
                  ->constrained('branches')
                  ->cascadeOnDelete();
        });

        // 2. Restore relationships from pivot table back to the direct column
        if (Schema::hasTable('attraction_branch')) {
            $pivots = DB::table('attraction_branch')->get();
            foreach ($pivots as $pivot) {
                DB::table('attractions')
                    ->where('id', $pivot->attraction_id)
                    ->update(['branch_id' => $pivot->branch_id]);
            }

            // 3. Drop the pivot table
            Schema::dropIfExists('attraction_branch');
        }
    }
};
