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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('full_name')->nullable();
            $table->string('birth_date')->nullable();
            $table->string('thumbnail')->default('patient.png');
            $table->string('phone_number');
            $table->string('otp_verification_code')->nullable();
            $table->timestamp('otp_expire_at')->nullable();
            $table->double('height')->nullable();
            $table->double('weight')->nullable();
            $table->string('gender')->default('male');
            $table->string('address',192)->nullable();
            $table->integer('age')->nullable();
            $table->boolean('notification_status')->default(true);
            $table->foreignId('allergy_id')
            ->nullable()->references('id')->on('allergies')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('chronic_diseases_id')
            ->nullable()->references('id')->on('chronic_diseases')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('family_history_id')
            ->nullable()->references('id')->on('family_histories')->onUpdate('cascade')->onDelete('cascade');
            $table->boolean('has_physical_activity')->default(false);
            $table->boolean('has_cancer_screening')->default(false);
            $table->boolean('has_depression_screening')->default(false);
            $table->mediumText('other_problems')->nullable();
            $table->boolean('account_status')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
