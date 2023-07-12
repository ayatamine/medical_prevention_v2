<?php

use App\Models\Consultation;
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
        Schema::table('ratings', function (Blueprint $table) {
            // $table->foreignIdFor(Consultation::class)->cascadeOnDelete()->cascadeOnUpdate();
            $table->dropForeign('ratings_doctor_id_foreign');
            $table->dropForeign('ratings_patient_id_foreign');
            $table->dropColumn('doctor_id');
            $table->dropColumn('patient_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ratings', function (Blueprint $table) {
            $table->dropColumn('consultation_id');
        });
    }
};
