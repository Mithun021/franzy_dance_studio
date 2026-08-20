<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_payment_records', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Relationships
            |--------------------------------------------------------------------------
            */

            $table->foreignId('student_course_id')
                ->constrained('student_course')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Payment Details
            |--------------------------------------------------------------------------
            */

            $table->date('payment_date');

            $table->enum('payment_mode', [
                'Cash',
                'UPI',
                'Card',
                'Bank Transfer',
                'Cheque',
            ]);

            $table->decimal('amount', 10, 2);

            $table->decimal('platform_fee_percentage', 5, 2)->default(0);
            $table->decimal('platform_fee_amount', 12, 2)->default(0);

            /*
            |--------------------------------------------------------------------------
            | Transaction Details
            |--------------------------------------------------------------------------
            */

            $table->string('transaction_id')
                ->nullable();

            $table->string('payment_proof')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Payment Status
            |--------------------------------------------------------------------------
            */

            $table->enum('status', [
                'success',
                'pending',
                'failed',
                'cancelled',
                'refunded',
            ])->default('success');

            $table->text('remarks')
                ->nullable();

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            $table->index('student_course_id');
            $table->index('user_id');
            $table->index('payment_date');
            $table->index('payment_mode');
            $table->index('status');

            $table->index([
                'student_course_id',
                'payment_date'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_payment_records');
    }
};
