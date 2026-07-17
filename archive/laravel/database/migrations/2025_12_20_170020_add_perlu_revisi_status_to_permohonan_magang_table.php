<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Mengubah enum status untuk menambahkan 'Perlu Revisi'
        DB::statement("ALTER TABLE permohonan_magang MODIFY COLUMN status ENUM('Diajukan', 'Diverifikasi', 'Perlu Revisi', 'Diterima', 'Ditolak') DEFAULT 'Diajukan'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan ke enum asli
        DB::statement("ALTER TABLE permohonan_magang MODIFY COLUMN status ENUM('Diajukan', 'Diverifikasi', 'Diterima', 'Ditolak') DEFAULT 'Diajukan'");
    }
};
