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
        Schema::create('studio_bookings', function (Blueprint $table) {
            $table->id();
            // Booking ID
            $table->string('booking_id')->unique();

            // Existing Student/User (Optional)
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Customer Details
            $table->string('customer_name');

            $table->string('email')->nullable();

            $table->string('phone',20);

            $table->string('city')->nullable();

            $table->string('state')->nullable();

            $table->string('pincode',20)->nullable();

            $table->text('address')->nullable();

            // Selected Studio
            $table->foreignId('studio_id')
                ->constrained('studios')
                ->cascadeOnDelete();

            // Booking Date
            $table->date('booking_from_date');

            $table->date('booking_to_date')->nullable();

            // Price Snapshot
            $table->decimal('studio_amount',10,2);

            // Booking Status
            $table->enum('enquiry_status',[
                'Inquiry',
                'Pending',
                'Confirmed',
                'Completed',
                'Cancelled'
            ])->default('Inquiry');

            // Remarks
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('studio_bookings');
    }
};
