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
        Schema::create('medicines', function (Blueprint $table) {
            $table->id();
            $table->string('registeration_number')->nullable();
            $table->year('registeration_year')->nullable();
            $table->string('target')->nullable();
            $table->string('type')->nullable();
            $table->string('branch')->nullable();
            $table->string('scientific_name')->nullable();
            $table->string('commercial_name')->nullable();
            $table->string('dose')->nullable();
            $table->string('dose_unit')->nullable();
            $table->string('pharmaceutical_form')->nullable();
            $table->string('route')->nullable();
            $table->string('code1')->nullable();
            $table->string('code2')->nullable();
            $table->double('size')->nullable();
            $table->string('size_unit')->nullable();
            $table->string('package_type')->nullable();
            $table->double('package_size')->nullable();
            $table->string('prescription_method')->nullable();
            $table->string('control')->nullable();
            $table->string('marketing_company_name')->nullable();
            $table->string('representative')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicines');
    }
};
