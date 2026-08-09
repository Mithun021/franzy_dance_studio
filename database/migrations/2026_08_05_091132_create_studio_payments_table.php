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
        Schema::create('studio_payments', function (Blueprint $table) {
            $table->id();
            // Payment ID
            $table->string('payment_id')->unique();

            // Booking
            $table->foreignId('booking_id')
                ->constrained('studio_bookings')
                ->cascadeOnDelete();

            // Payment Amount
            $table->decimal('amount',10,2);

            // Payment Type
            $table->enum('payment_type',[
                'Advance',
                'Partial',
                'Full',
                'Refund'
            ]);

            // Payment Method
            $table->enum('payment_method',[
                'Cash',
                'UPI',
                'Card',
                'Bank Transfer',
                'Online',
                'QR'
            ]);

            // Transaction Number
            $table->string('transaction_id')->nullable();

            $table->string('payment_proof')->nullable();

            // Payment Status
            $table->enum('payment_status',[
                'Pending',
                'Success',
                'Failed',
                'Refunded'
            ])->default('Success');

            // Payment Date
            $table->dateTime('payment_date');

            // Remarks
            $table->text('remarks')->nullable();

            // Admin/User who received payment
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('studio_payments');
    }
};
