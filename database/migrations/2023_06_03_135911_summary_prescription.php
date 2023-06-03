<?php

use App\Models\Prescription;
use App\Models\Summary;
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
        Schema::create('summary_prescription', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Summary::class);
            $table->foreignIdFor(Prescription::class);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('summary_prescription');
    }
};
