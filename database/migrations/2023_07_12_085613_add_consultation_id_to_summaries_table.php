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
        Schema::table('summaries', function (Blueprint $table) {
            $table->foreignIdFor(Consultation::class)->cascadeOnDelete()->cascadeOnUpdate();
            $table->mediumText('prescriptions')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('summaries', function (Blueprint $table) {
            $table->dropColumn('consultation_id');
            $table->dropColumn('prescriptions');
        });
    }
};
