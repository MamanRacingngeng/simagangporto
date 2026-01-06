<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Menambahkan status 'Revisi' ke ENUM status di tabel permohonan_magang
     */
    public function up(): void
    {
        // Mengubah enum status untuk menambahkan 'Revisi'
        // Cek dulu apakah 'Perlu Revisi' sudah ada, jika ya tambahkan 'Revisi' juga
        DB::statement("ALTER TABLE permohonan_magang MODIFY COLUMN status ENUM('Diajukan', 'Diverifikasi', 'Revisi', 'Perlu Revisi', 'Diterima', 'Ditolak') DEFAULT 'Diajukan'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan ke enum tanpa 'Revisi' (tetap ada 'Perlu Revisi' jika sudah ada)
        DB::statement("ALTER TABLE permohonan_magang MODIFY COLUMN status ENUM('Diajukan', 'Diverifikasi', 'Perlu Revisi', 'Diterima', 'Ditolak') DEFAULT 'Diajukan'");
    }
};
