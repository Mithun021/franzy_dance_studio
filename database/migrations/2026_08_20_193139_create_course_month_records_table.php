<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_month_records', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Student Course
            |--------------------------------------------------------------------------
            */

            $table->foreignId('student_course_id')
                ->constrained('student_course')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Fee Month
            |--------------------------------------------------------------------------
            */

            $table->date('fee_month');

            /*
            |--------------------------------------------------------------------------
            | Fee Details
            |--------------------------------------------------------------------------
            */

            $table->decimal('monthly_fee', 10, 2)
                ->default(0);

            $table->decimal('waiver_amount', 10, 2)
                ->default(0);

            $table->decimal('payable_amount', 10, 2)
                ->default(0);

            $table->decimal('paid_amount', 10, 2)
                ->default(0);

            /*
            |--------------------------------------------------------------------------
            | Due & Payment
            |--------------------------------------------------------------------------
            */

            $table->date('due_date')
                ->nullable();

            $table->date('paid_date')
                ->nullable();

            $table->decimal('payment_percentage', 5, 2)
                ->default(0);

            $table->string('payment_rule')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            $table->enum('status', [
                'unpaid',
                'partial',
                'paid',
                'waived',
            ])->default('unpaid');

            $table->text('remarks')
                ->nullable();

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            // Prevent duplicate month for same student course
            $table->unique([
                'student_course_id',
                'fee_month'
            ]);

            $table->index('student_course_id');
            $table->index('fee_month');
            $table->index('status');

            $table->index([
                'student_course_id',
                'status'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_month_records');
    }
};
