<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();

            $table->string('booking_reference')->unique();

            $table->foreignId('package_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('branch_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            
            $table->string('food_type');

            $table->date('booking_date');
            $table->integer('child_count')->default(0);
            $table->integer('adult_count')->default(0);

            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('vat', 10, 2)->default(0);  //vat(%)
            $table->decimal('total_amount', 10, 2)->default(0);

            // Contact Details
            $table->string('contact_name');
            $table->string('email');
            $table->string('phone');
            $table->string('emirate')->nullable();
            $table->string('address')->nullable();


            $table->text('remarks')->nullable();

           $table->string('status')->default('confirmed');

            $table->enum('payment_status', [
                'unpaid',
                'paid',
            ])->default('unpaid');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};