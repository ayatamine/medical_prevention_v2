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
        Schema::create('consultations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('doctor_id');
            $table->unsignedBigInteger('patient_id');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            // $table->text('notes')->nullable();
            $table->enum('status',['pending','in_progress','incompleted','completed','canceled','rejected']);
            $table->foreign('doctor_id')->references('id')->on('doctors');
            $table->foreign('patient_id')->references('id')->on('patients');
            $table->timestamp('finished_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultations');
    }
};
