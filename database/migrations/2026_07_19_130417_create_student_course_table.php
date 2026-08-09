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
        Schema::create('student_course', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Student & Admission Details
            |--------------------------------------------------------------------------
            */

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('admission_no')->unique();

            $table->date('admission_date');

            /*
            |--------------------------------------------------------------------------
            | Course Details
            |--------------------------------------------------------------------------
            */

            $table->foreignId('course_id')
                ->constrained('courses')
                ->cascadeOnDelete();

            $table->integer('course_duration')->nullable();

            $table->string('duration_type',20)->nullable();

            $table->foreignId('level_id')
                ->nullable()
                ->constrained('levels')
                ->nullOnDelete();

            $table->foreignId('category_id')
                ->nullable()
                ->constrained('categories')
                ->nullOnDelete();

            $table->foreignId('batch_id')
                ->nullable()
                ->constrained('batches')
                ->nullOnDelete();

            $table->foreignId('instructor_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Fee Snapshot
            |--------------------------------------------------------------------------
            */

            $table->decimal('registration_fee', 10, 2)->default(0);
            $table->decimal('admission_fee', 10, 2)->default(0);
            $table->decimal('course_fee', 10, 2)->default(0);

            /*
            |--------------------------------------------------------------------------
            | Course Status
            |--------------------------------------------------------------------------
            */

            $table->boolean('is_enroll')
            ->default(0)
            ->comment('1 = Active Enrollment, 0 = Not Enrolled');

            $table->enum('status', [
                'ongoing',
                'completed',
                'discontinued',
            ])->default('ongoing');

            $table->date('completion_date')->nullable();

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            $table->index('user_id');
            $table->index('course_id');
            $table->index('level_id');
            $table->index('category_id');
            $table->index('batch_id');
            $table->index('instructor_id');
            $table->index('admission_date');
            $table->index('completion_date');
            $table->index('status');

            // Frequently used filters
            $table->index(['course_id', 'status']);
            $table->index(['batch_id', 'status']);
            $table->index(['instructor_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_course');
    }
};
