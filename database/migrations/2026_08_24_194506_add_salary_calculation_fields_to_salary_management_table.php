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
        Schema::table('salary_management', function (Blueprint $table) {
            $table->unsignedInteger('assigned_students')
                ->default(0)
                ->after('salary_month');

            $table->decimal('payment_received_amount', 12, 2)
                ->default(0)
                ->after('assigned_students');

            $table->decimal('earning_percentage', 5, 2)
                ->default(0)
                ->after('payment_received_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('salary_management', function (Blueprint $table) {
            $table->dropColumn([
                'assigned_students',
                'payment_received_amount',
                'earning_percentage',
            ]);
        });
    }
};
