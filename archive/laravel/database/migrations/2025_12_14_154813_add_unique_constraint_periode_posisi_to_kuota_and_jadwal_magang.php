<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Menambahkan unique constraint untuk kombinasi periode + posisi
     * Memungkinkan periode yang sama dengan divisi berbeda
     */
    public function up(): void
    {
        // Update data yang posisinya null dengan nilai default
        \DB::table('kuota_magang')->whereNull('posisi')->update(['posisi' => 'Umum']);
        \DB::table('jadwal_magang')->whereNull('posisi')->update(['posisi' => 'Umum']);

        // Tambahkan unique constraint untuk kuota_magang (periode + posisi)
        Schema::table('kuota_magang', function (Blueprint $table) {
            // Pastikan posisi tidak null sebelum menambahkan unique constraint
            $table->string('posisi')->nullable(false)->default('Umum')->change();
            
            // Tambahkan unique constraint untuk kombinasi periode + posisi
            $table->unique(['periode', 'posisi'], 'kuota_magang_periode_posisi_unique');
        });

        // Tambahkan unique constraint untuk jadwal_magang (periode + posisi)
        Schema::table('jadwal_magang', function (Blueprint $table) {
            // Pastikan posisi tidak null sebelum menambahkan unique constraint
            $table->string('posisi')->nullable(false)->default('Umum')->change();
            
            // Tambahkan unique constraint untuk kombinasi periode + posisi
            $table->unique(['periode', 'posisi'], 'jadwal_magang_periode_posisi_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kuota_magang', function (Blueprint $table) {
            $table->dropUnique('kuota_magang_periode_posisi_unique');
            $table->string('posisi')->nullable()->change();
        });

        Schema::table('jadwal_magang', function (Blueprint $table) {
            $table->dropUnique('jadwal_magang_periode_posisi_unique');
            $table->string('posisi')->nullable()->change();
        });
    }
};
