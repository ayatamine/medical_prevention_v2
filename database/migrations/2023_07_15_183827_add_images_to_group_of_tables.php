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
        Schema::table('symptomes', function (Blueprint $table) {
            $table->string('icon')->nullable();
        });
        Schema::table('chronic_diseases', function (Blueprint $table) {
            $table->string('icon')->nullable();
        });
        Schema::table('specialities', function (Blueprint $table) {
            $table->string('icon')->nullable();
        });
        Schema::table('sub_specialities', function (Blueprint $table) {
            $table->string('icon')->nullable();
        });
        Schema::table('allergies', function (Blueprint $table) {
            $table->string('icon')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('symptomes', function (Blueprint $table) {
            $table->dropColumn('icon');
        });
        Schema::table('chronic_diseases', function (Blueprint $table) {
            $table->dropColumn('icon');
        });
        Schema::table('specialities', function (Blueprint $table) {
            $table->dropColumn('icon');
        });
        Schema::table('sub_specialities', function (Blueprint $table) {
            $table->dropColumn('icon');
        });
        Schema::table('allergies', function (Blueprint $table) {
            $table->dropColumn('icon');
        });
    }
};
