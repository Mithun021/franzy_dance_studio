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

            $table->enum('booking_type', [
                'day',
                'hour'
            ])->default('day');

            // Booking Date
            $table->date('booking_from_date');

            $table->time('booking_from_time');

            $table->date('booking_to_date')->nullable();

            $table->time('booking_to_time');

            $table->decimal('booking_duration', 10, 2)
                ->default(0);

            $table->decimal('rate', 10, 2);

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

            $table->text('admin_remarks')->nullable();
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
