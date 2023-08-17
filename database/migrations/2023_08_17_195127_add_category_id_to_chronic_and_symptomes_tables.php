<?php

use App\Models\ChronicDiseaseCategory;
use App\Models\SymptomeCategory;
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
        Schema::table('symptomes', function (Blueprint $table) {
            $table->foreignIdFor(SymptomeCategory::class)->nullable()->after('name_ar');
        });
        Schema::table('chronic_diseases', function (Blueprint $table) {
            $table->foreignIdFor(ChronicDiseaseCategory::class)->nullable()->after('name_ar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('symptomes', function (Blueprint $table) {
            $table->dropConstrainedForeignId('symptome_category_id'); 
        });
        Schema::table('chronic_diseases', function (Blueprint $table) {
            $table->dropConstrainedForeignId('chronic_disease_category_id'); 
        });
    }
};
