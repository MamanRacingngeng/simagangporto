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
        Schema::create('permohonan_magang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            // Sesuai ERD: dokumen_id (FK) - one-to-one dengan Dokumen
            $table->foreignId('dokumen_id')->nullable()->constrained('dokumen')->onDelete('set null');
            // Sesuai ERD: tanggal_pengajuan (date)
            $table->date('tanggal_pengajuan');
            // Sesuai ERD: status adalah "Diajukan", "Diverifikasi", "Diterima", atau "Ditolak"
            $table->enum('status', ['Diajukan', 'Diverifikasi', 'Diterima', 'Ditolak'])->default('Diajukan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permohonan_magang');
    }
};
