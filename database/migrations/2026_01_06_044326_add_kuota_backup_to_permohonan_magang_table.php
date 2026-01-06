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
        Schema::table('permohonan_magang', function (Blueprint $table) {
            // Backup informasi kuota/jadwal untuk riwayat meskipun kuota sudah dihapus
            if (!Schema::hasColumn('permohonan_magang', 'periode_backup')) {
                $table->string('periode_backup')->nullable();
            }
            if (!Schema::hasColumn('permohonan_magang', 'posisi_backup')) {
                $table->string('posisi_backup')->nullable();
            }
            if (!Schema::hasColumn('permohonan_magang', 'tgl_mulai_backup')) {
                $table->date('tgl_mulai_backup')->nullable();
            }
            if (!Schema::hasColumn('permohonan_magang', 'tgl_selesai_backup')) {
                $table->date('tgl_selesai_backup')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permohonan_magang', function (Blueprint $table) {
            $table->dropColumn(['periode_backup', 'posisi_backup', 'tgl_mulai_backup', 'tgl_selesai_backup']);
        });
    }
};
