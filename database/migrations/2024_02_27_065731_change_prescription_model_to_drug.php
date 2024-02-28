<?php

use App\Models\Consultation;
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
        Schema::rename('prescriptions','drugs');
        Schema::table('drugs', function (Blueprint $table) {
            $table->dropColumn('prescription_title');
            $table->dropColumn('valid_until');
            $table->foreignIdFor(Consultation::class)->nullable()->cascadeOnDelete();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::table('drugs', function (Blueprint $table) {
            $table->string('prescription_title')->nullable();
            $table->timestamp('valid_until')->nullable();
            $table->dropForeignIdFor(Consultation::class);
        });
        Schema::rename('drugs','prescriptions');
    }
};
