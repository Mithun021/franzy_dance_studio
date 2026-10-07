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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            // Required fields
            $table->string('event_name');
            $table->text('description');
            $table->date('event_date');

            // Optional fields
            $table->string('slug')->nullable()->unique();
            $table->time('event_time')->nullable();
            $table->string('venue_details')->nullable();

            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->string('pincode', 10)->nullable();
            $table->text('address')->nullable();

            // Event document
            $table->string('document')->nullable();

            // Event payment
            $table->tinyInteger('is_free')
                ->default(1)
                ->comment('0 = Paid, 1 = Free');

            $table->decimal('event_amount', 10, 2)
                ->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
