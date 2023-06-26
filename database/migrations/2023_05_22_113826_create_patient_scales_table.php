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
        Schema::create('patient_scales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')
            ->nullable()->references('id')->on('patients')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('scale_question_id')
            ->nullable()->references('id')->on('scale_questions')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('answer')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_scales');
    }
};
