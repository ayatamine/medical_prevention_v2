<?php

use App\Models\LabTest;
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
        Schema::create('summary_lab_test', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Summary::class);
            $table->foreignIdFor(LabTest::class);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('summary_lab_test');
    }
};
