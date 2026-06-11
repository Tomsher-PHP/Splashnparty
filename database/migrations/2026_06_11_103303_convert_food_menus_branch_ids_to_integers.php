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
        $foodMenus = Illuminate\Support\Facades\DB::table('food_menus')->get();

        foreach ($foodMenus as $foodMenu) {
            $branchIds = json_decode($foodMenu->branch_ids, true);
            if (is_array($branchIds)) {
                $integerBranchIds = array_map('intval', $branchIds);
                Illuminate\Support\Facades\DB::table('food_menus')
                    ->where('id', $foodMenu->id)
                    ->update([
                        'branch_ids' => json_encode($integerBranchIds)
                    ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $foodMenus = Illuminate\Support\Facades\DB::table('food_menus')->get();

        foreach ($foodMenus as $foodMenu) {
            $branchIds = json_decode($foodMenu->branch_ids, true);
            if (is_array($branchIds)) {
                $stringBranchIds = array_map('strval', $branchIds);
                Illuminate\Support\Facades\DB::table('food_menus')
                    ->where('id', $foodMenu->id)
                    ->update([
                        'branch_ids' => json_encode($stringBranchIds)
                    ]);
            }
        }
    }
};
