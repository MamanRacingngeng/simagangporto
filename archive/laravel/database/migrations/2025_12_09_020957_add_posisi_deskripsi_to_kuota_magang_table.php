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
        Schema::table('kuota_magang', function (Blueprint $table) {
            $table->string('posisi')->nullable()->after('periode');
            $table->text('deskripsi')->nullable()->after('posisi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kuota_magang', function (Blueprint $table) {
            $table->dropColumn(['posisi', 'deskripsi']);
        });
    }
};
