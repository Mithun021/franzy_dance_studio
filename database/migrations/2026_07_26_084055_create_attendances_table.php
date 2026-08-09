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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            // Student
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            // Course
            $table->foreignId('course_id')
                  ->constrained('courses')
                  ->cascadeOnDelete();

            // Batch
            $table->foreignId('batch_id')
                  ->constrained('batches')
                  ->cascadeOnDelete();

            // Attendance Date
            $table->date('attendance_date');

            // Attendance Status
            $table->enum('status', [
                'Present',
                'Absent',
                'Leave',
                'Half Day'
            ])->default('Present');

            // Optional Remarks
            $table->text('remarks')->nullable();
            $table->timestamps();
             $table->unique([
                'user_id',
                'course_id',
                'batch_id',
                'attendance_date'
            ], 'attendance_unique');

            $table->index('attendance_date');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
