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
        Schema::table('cafe_menus', function (Blueprint $table) {
            $table->json('branch_ids')
                ->nullable()
                ->after('id');
        });

        // Copy existing branch_id values
        $cafeMenus = Illuminate\Support\Facades\DB::table('cafe_menus')->get();
        foreach ($cafeMenus as $menu) {
            Illuminate\Support\Facades\DB::table('cafe_menus')
                ->where('id', $menu->id)
                ->update([
                    'branch_ids' => json_encode(
                        $menu->branch_id
                            ? [(int) $menu->branch_id]
                            : []
                    )
                ]);
        }

        Schema::table('cafe_menus', function (Blueprint $table) {
            // Drop foreign key and column
            try {
                $table->dropForeign(['branch_id']);
            } catch (\Exception $e) {
                // Constraint might have a different name or not exist
            }
            $table->dropColumn('branch_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cafe_menus', function (Blueprint $table) {
            $table->foreignId('branch_id')
                ->nullable()
                ->after('id')
                ->constrained()
                ->cascadeOnDelete();
        });

        $cafeMenus = Illuminate\Support\Facades\DB::table('cafe_menus')->get();
        foreach ($cafeMenus as $menu) {
            $branchIds = json_decode($menu->branch_ids, true);
            Illuminate\Support\Facades\DB::table('cafe_menus')
                ->where('id', $menu->id)
                ->update([
                    'branch_id' => is_array($branchIds) && count($branchIds) > 0 ? $branchIds[0] : null
                ]);
        }

        Schema::table('cafe_menus', function (Blueprint $table) {
            $table->dropColumn('branch_ids');
        });
    }
};
