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
        Schema::create('doctor_availabilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained('doctors')->onDelete('cascade');
            $table->tinyInteger('day_of_week')->unsigned(); // 1 (Sunday) - 7 (Saturday)
            $table->time('start_time');
            $table->time('end_time');
            $table->boolean('is_pm')->default(false); // Default to morning
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctor_availabilities');
    }
};
