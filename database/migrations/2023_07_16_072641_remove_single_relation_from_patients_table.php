<?php

use App\Models\FamilyHistory;
use App\Models\ChronicDiseases;
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
        Schema::table('patients', function (Blueprint $table) {
            // $table->dropConstrainedForeignId('allergy_id'); 
            // $table->dropColumn('allergy_id');
            $table->dropConstrainedForeignId('chronic_diseases_id'); 
            // $table->dropColumn('chronic_diseases_id');
            $table->dropConstrainedForeignId('family_history_id'); 
            // $table->dropColumn('family_history_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->foreignIdFor(Allergy::class);
            $table->foreignIdFor(ChronicDiseases::class);
            $table->foreignIdFor(FamilyHistory::class);
        });
    }
};
