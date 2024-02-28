<?php

use App\Models\Doctor;
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
            $table->foreignIdFor(Doctor::class)->constrained()->nullable()->cascadeOnDelete();
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
