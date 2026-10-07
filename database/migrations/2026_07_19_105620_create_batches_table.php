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
        Schema::create('batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')
                ->constrained('courses')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('level_id')
                ->constrained('levels')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->string('batch_name',100);

            /*
            |--------------------------------------------------------------------------
            | Monday
            |--------------------------------------------------------------------------
            */
            $table->time('monday_start_time')->nullable();
            $table->time('monday_end_time')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Tuesday
            |--------------------------------------------------------------------------
            */
            $table->time('tuesday_start_time')->nullable();
            $table->time('tuesday_end_time')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Wednesday
            |--------------------------------------------------------------------------
            */
            $table->time('wednesday_start_time')->nullable();
            $table->time('wednesday_end_time')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Thursday
            |--------------------------------------------------------------------------
            */
            $table->time('thursday_start_time')->nullable();
            $table->time('thursday_end_time')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Friday
            |--------------------------------------------------------------------------
            */
            $table->time('friday_start_time')->nullable();
            $table->time('friday_end_time')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Saturday
            |--------------------------------------------------------------------------
            */
            $table->time('saturday_start_time')->nullable();
            $table->time('saturday_end_time')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Sunday
            |--------------------------------------------------------------------------
            */
            $table->time('sunday_start_time')->nullable();
            $table->time('sunday_end_time')->nullable();

            // Maximum Student Capacity
            $table->unsignedSmallInteger('capacity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batches');
    }
};
