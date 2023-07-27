<?php

use App\Models\Summary;
use App\Models\Symptome;
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
        Schema::create('summary_symptome', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Summary::class);
            $table->foreignIdFor(Symptome::class);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('summary_symptome');
    }
};
