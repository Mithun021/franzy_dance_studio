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
        Schema::create('exam_fee_payments', function (Blueprint $table) {
            $table->id();
            // Student
            $table->foreignId('student_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Exam
            $table->foreignId('exam_id')
                ->constrained('exams')
                ->cascadeOnDelete();

            // Level
            $table->foreignId('level_id')
                ->constrained('levels')
                ->cascadeOnDelete();

            // Exam Fee
            $table->decimal('amount', 10, 2);

            // Payment Status
            $table->enum('status', [
                'created',
                'pending',
                'success',
                'failed'
            ])->default('created');

            // Razorpay Order Details
            $table->string('razorpay_order_id')->nullable();
            $table->string('razorpay_payment_id')->nullable();
            $table->string('razorpay_signature')->nullable();

            // Payment Method
            $table->string('payment_method')->nullable();

            // Transaction / Reference
            $table->string('transaction_id')->nullable();

            // Razorpay Response
            $table->longText('gateway_response')->nullable();

            // Failure Details
            $table->text('failure_reason')->nullable();

            // Payment Date
            $table->timestamp('payment_date')->nullable();
            $table->timestamps();
            $table->index('student_id');
            $table->index('exam_id');
            $table->index('level_id');
            $table->index('status');
            $table->index('razorpay_order_id');
            $table->index('razorpay_payment_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_fee_payments');
    }
};
