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
            $table->boolean('is_phone_verified')->after('otp_expire_at')->default(false);
        });
        Schema::table('patients', function (Blueprint $table) {
            $table->boolean('is_phone_verified')->after('otp_expire_at')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
           $table->dropColumn('is_phone_verified');
        });
        Schema::table('patients', function (Blueprint $table) {
           $table->dropColumn('is_phone_verified');
        });
    }
};
