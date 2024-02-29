<?php

use App\Models\Consultation;
use App\Models\Drug;
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
        Schema::create('consultation_drug', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Consultation::class);
            $table->foreignIdFor(Drug::class);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultation_drug');
    }
};
