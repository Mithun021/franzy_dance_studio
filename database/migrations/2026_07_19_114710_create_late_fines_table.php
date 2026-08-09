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
        Schema::create('late_fines', function (Blueprint $table) {
            $table->id();
            // Fee payment due date (1-31)
            $table->unsignedTinyInteger('due_date')->default(5);

            // Late fee if paid after due date in the same month
            $table->decimal('same_month_late_fee', 10, 2)->default(0);

            // Late fee if previous month's fee is paid in the next month
            $table->decimal('next_month_late_fee', 10, 2)->default(0);

            // Percentage of fee charged if student is absent for the entire month
            $table->decimal('absent_charge_percentage', 5, 2)->default(50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('late_fines');
    }
};
