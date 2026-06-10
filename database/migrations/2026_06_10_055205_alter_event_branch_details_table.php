<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up()
    {
        Schema::table('event_branch_details', function (Blueprint $table) {


            $table->string('image')->nullable();

            $table->string('middle_banner')->nullable();

            // REMOVE OLD FIELDS
            $table->dropColumn([
                'weekday_price',
                'weekend_price',
                'highlighted_description'
            ]);
        });
    }

    public function down()
    {
        Schema::table('event_branch_details', function (Blueprint $table) {

            $table->decimal('weekday_price',10,2)->nullable();

            $table->decimal('weekend_price',10,2)->nullable();

            $table->text('highlighted_description')->nullable();

            $table->dropColumn([
                'image',
                'middle_banner'
            ]);
        });
    }
};