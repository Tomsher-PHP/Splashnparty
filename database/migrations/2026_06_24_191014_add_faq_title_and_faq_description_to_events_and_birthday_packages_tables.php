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
        Schema::table('events', function (Blueprint $table) {
            $table->string('faq_title')->nullable()->after('faq_selection');
            $table->text('faq_description')->nullable()->after('faq_title');
        });

        Schema::table('birthday_packages', function (Blueprint $table) {
            $table->string('faq_title')->nullable()->after('faq_selection');
            $table->text('faq_description')->nullable()->after('faq_title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['faq_title', 'faq_description']);
        });

        Schema::table('birthday_packages', function (Blueprint $table) {
            $table->dropColumn(['faq_title', 'faq_description']);
        });
    }
};
