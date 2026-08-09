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
        Schema::create('syllabus_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('syllabus_course_id')
                ->constrained('syllabus_courses')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->unsignedInteger('chapter_no');

            $table->string('title');

            $table->string('duration')->nullable();

            $table->longText('content')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('syllabus_details');
    }
};
