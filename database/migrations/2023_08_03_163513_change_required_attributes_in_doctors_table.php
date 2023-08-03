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
        Schema::table('doctors', function (Blueprint $table) {
            $table->string('full_name')->nullable()->change();
            $table->unsignedInteger('id_number')->nullable()->change();
            $table->string('email')->nullable()->change();
            $table->string('job_title')->nullable()->change();
            $table->string('classification_number')->nullable()->change();
            $table->string('insurance_number')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            //
        });
    }
};
