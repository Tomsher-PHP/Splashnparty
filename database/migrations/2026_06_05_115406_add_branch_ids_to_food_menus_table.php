<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('food_menus', function (Blueprint $table) {
            $table->json('branch_ids')
                ->nullable()
                ->after('id');
        });

        // Move existing branch_id values
        $foodMenus = DB::table('food_menus')->get();

        foreach ($foodMenus as $foodMenu) {

            DB::table('food_menus')
                ->where('id', $foodMenu->id)
                ->update([
                    'branch_ids' => json_encode(
                        $foodMenu->branch_id
                            ? [$foodMenu->branch_id]
                            : []
                    )
                ]);
        }

        if (Schema::hasColumn('food_menus', 'branch_id')) {
            if (DB::getDriverName() !== 'sqlite') {
                try {
                    DB::statement('ALTER TABLE food_menus DROP FOREIGN KEY food_menus_branch_id_foreign');
                } catch (\Exception $e) {
                    // FK does not exist
                }

                Schema::table('food_menus', function (Blueprint $table) {
                    $table->dropColumn('branch_id');
                });
            }
        }
    }

    public function down(): void
    {
        Schema::table('food_menus', function (Blueprint $table) {

            $table->foreignId('branch_id')
                ->nullable()
                ->after('id')
                ->constrained()
                ->cascadeOnDelete();
        });

        $foodMenus = DB::table('food_menus')->get();

        foreach ($foodMenus as $foodMenu) {

            $branchIds = json_decode(
                $foodMenu->branch_ids,
                true
            );

            DB::table('food_menus')
                ->where('id', $foodMenu->id)
                ->update([
                    'branch_id' => $branchIds[0] ?? null
                ]);
        }

        Schema::table('food_menus', function (Blueprint $table) {
            $table->dropColumn('branch_ids');
        });
    }
};