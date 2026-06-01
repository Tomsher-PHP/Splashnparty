<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('general_accesses', function (Blueprint $table) {

            $table->id();

            $table->string('title');

            $table->decimal('weekday_price', 10, 2)->nullable();

            $table->decimal('weekend_price', 10, 2)->nullable();

            $table->foreignId('branch_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->integer('sort_order')->default(0);

            $table->boolean('status')->default(1);

            $table->timestamps();

            $table->index('branch_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(
            'general_accesses'
        );
    }
};
