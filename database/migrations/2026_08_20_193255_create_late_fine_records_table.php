<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('late_fine_records', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Relationships
            |--------------------------------------------------------------------------
            */

            $table->foreignId('student_course_id')
                ->constrained('student_course')
                ->cascadeOnDelete();

            $table->foreignId('course_month_record_id')
                ->constrained('course_month_records')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Fine Details
            |--------------------------------------------------------------------------
            */

            $table->date('fine_date');

            $table->date('due_date')
                ->nullable();

            $table->decimal('fine_amount', 10, 2)
                ->default(0);

            $table->decimal('paid_amount', 10, 2)
                ->default(0);

            $table->decimal('waived_amount', 10, 2)
                ->default(0);

            /*
            |--------------------------------------------------------------------------
            | Fine Status
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

            $table->index('student_course_id');
            $table->index('course_month_record_id');
            $table->index('fine_date');
            $table->index('status');

            $table->index([
                'student_course_id',
                'status'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('late_fine_records');
    }
};
