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
        Schema::create('event_payments', function (Blueprint $table) {
            $table->id();
            // Booking
            $table->foreignId('event_booking_id')
                ->constrained('event_bookings')
                ->cascadeOnDelete();

            // Payment Amount
            $table->decimal('amount', 10, 2);

            // Payment Method
            $table->string('payment_mode')->nullable();

            // Payment Status
            $table->enum('status', [
                'created',
                'pending',
                'success',
                'failed'
            ])->default('created');

            // Razorpay Order Details
            $table->string('razorpay_order_id')->nullable()->index();

            // Razorpay Payment Details
            $table->string('razorpay_payment_id')->nullable()->index();
            $table->string('razorpay_signature')->nullable();

            // Gateway Response
            $table->longText('gateway_response')->nullable();

            // Extra Details
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_payments');
    }
};
