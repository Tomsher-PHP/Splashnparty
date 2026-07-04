<?php

use App\Models\Permission;
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
        $news = Permission::firstOrCreate(
            ['name' => 'news_updates', 'guard_name' => 'web'],
            ['parent_id' => null]
        );

        foreach ([
            'view_news_updates',
            'create_news_updates',
            'edit_news_updates',
            'delete_news_updates'
        ] as $permission) {

            Permission::firstOrCreate(
                ['name' => $permission, 'guard_name' => 'web'],
                ['parent_id' => $news->id]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Permission::whereIn('name', [
            'view_news_updates',
            'create_news_updates',
            'edit_news_updates',
            'delete_news_updates'
        ])->delete();

        Permission::where('name', 'news_updates')->delete();
    }
};
