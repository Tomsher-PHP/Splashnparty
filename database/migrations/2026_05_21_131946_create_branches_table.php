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
        Schema::create('branches', function (Blueprint $table) {

            $table->id();

            $table->string('title');

            $table->longText('description')->nullable();

            $table->string('image')->nullable();

            $table->string('location_link')->nullable();

            $table->longText('address')->nullable();

            $table->string('phone')->nullable();

            $table->string('email')->nullable();

            $table->longText('working_hours')->nullable();

            $table->integer('sort_order')->default(0);

            $table->tinyInteger('status')->default(1);


            $table->timestamps();


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};