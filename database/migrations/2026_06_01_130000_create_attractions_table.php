<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('attractions', function (Blueprint $table) {
            $table->id();
            
            // Foreign key to branches table
            $table->foreignId('branch_id')
                  ->constrained('branches')
                  ->cascadeOnDelete();

            $table->string('title');
            $table->longText('description')->nullable();
            $table->string('image')->nullable();
            $table->string('type'); // attraction or adventure
            $table->integer('sort_order')->default(0);
            $table->tinyInteger('status')->default(1); // 1 active, 0 inactive
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('attractions');
    }
};
