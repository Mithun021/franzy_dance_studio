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
        Schema::table('users', function (Blueprint $table) {
            // Personal Information
            $table->date('date_of_birth')->nullable()->after('user_type');

            $table->enum('gender', [
                'Male',
                'Female',
                'Other'
            ])->nullable()->after('date_of_birth');

            $table->string('religion')->nullable()->after('gender');

            $table->string('mother_tongue')->nullable()->after('religion');

            $table->string('occupation')->nullable()->after('mother_tongue');

            $table->string('qualification')->nullable()->after('occupation');

            $table->string('whatsapp_no')->nullable()->after('qualification');

            // Guardian Details
            $table->string('guardian_name')->nullable()->after('whatsapp_no');

            $table->string('guardian_contact')->nullable()->after('guardian_name');

            $table->string('guardian_occupation')->nullable()->after('guardian_contact');

            // Local Guardian
            $table->string('local_guardian_name')->nullable()->after('guardian_occupation');

            $table->string('local_guardian_relation')->nullable()->after('local_guardian_name');

            // Signature
            $table->string('signature')->nullable()->after('profile_image');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->dropColumn([
                'date_of_birth',
                'gender',
                'religion',
                'mother_tongue',
                'occupation',
                'qualification',
                'whatsapp_no',

                'guardian_name',
                'guardian_contact',
                'guardian_occupation',

                'local_guardian_name',
                'local_guardian_relation',

                'signature',

            ]);

        });
    }
};
