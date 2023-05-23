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
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->unsignedInteger('id_number');
            $table->string('birth_date')->nullable();
            $table->string('phone_number');
            $table->string('otp_verification_code')->nullable();
            $table->timestamp('otp_expire_at')->nullable();
            $table->string('email')->unique();
            $table->string('job_title');
            $table->string('thumbnail')->default('doctor.png');
            $table->string('classification_number');
            $table->string('insurance_number');
            $table->string('medical_licence_file')->nullable();
            $table->string('cv_file')->nullable()->comment('the doctor cv file'); // the doctor cv
            $table->string('certification_file')->nullable();
            $table->boolean('notification_status')->default(true);
            $table->boolean('online_status')->default(true);
            $table->enum('gender',['male','female'])->default('male');
            $table->enum('account_status',['pending','accepted','blocked'])->default('pending');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};
