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
        Schema::create('event_branch_features', function (Blueprint $table) {

            $table->id();

            $table->foreignId('event_branch_detail_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('icon')->nullable();

            $table->string('title')->nullable();

            $table->string('subtitle')->nullable();

            $table->text('content')->nullable();

            $table->integer('sort_order')->default(0);

            $table->boolean('status')->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_branch_features');
    }
};
