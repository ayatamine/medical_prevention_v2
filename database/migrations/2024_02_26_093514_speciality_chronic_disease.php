<?php

use App\Models\ChronicDiseases;
use App\Models\Speciality;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('speciality_chronic_disease', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Speciality::class);
            $table->foreignIdFor(ChronicDiseases::class);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('speciality_chronic_disease');
    }
};
