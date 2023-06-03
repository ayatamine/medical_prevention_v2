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
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->id();
            $table->string('drug_name');
            $table->string('route');
            $table->unsignedInteger('dose');
            $table->string('frequancy');
            $table->string('unit');
            $table->unsignedInteger('duration');
            $table->enum('duration_unit',['hour','day','week','month','year']);
            $table->string('shape')->nullable();
            $table->string('prn')->nullable();
            $table->mediumText('instructions')->nullable();
            $table->timestamp('valid_until')->nullable();
            $table->unsignedBigInteger('doctor_id');
            $table->foreign('doctor_id')->references('id')->on('doctors')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prescriptions');
    }
};
