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
        Schema::create('event_bookings', function (Blueprint $table) {
            $table->id();
            // Public Booking ID
            $table->string('booking_id')->unique();

            // Event
            $table->foreignId('event_id')
                ->constrained('events')
                ->cascadeOnDelete();

            // Personal Details
            $table->string('name');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->string('father_name')->nullable();

            // Address Details
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->string('pincode', 10)->nullable();
            $table->text('address')->nullable();

            // Booking Amount
            $table->decimal('amount', 10, 2)->default(0);

            // Payment Status
            $table->enum('payment_status', [
                'pending',
                'paid',
                'failed'
            ])->default('pending');

            // Booking Status
            $table->enum('booking_status', [
                'pending',
                'confirmed',
                'cancelled'
            ])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_bookings');
    }
};
