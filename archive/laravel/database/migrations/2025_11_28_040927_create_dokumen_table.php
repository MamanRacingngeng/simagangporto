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
        Schema::create('dokumen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            // Sesuai ERD: cv, surat_pengantar, proposal (bukan jenis_dokumen generic)
            $table->string('cv')->nullable(); // Path atau reference ke file CV
            $table->string('surat_pengantar')->nullable(); // Path atau reference ke file Surat Pengantar
            $table->string('proposal')->nullable(); // Path atau reference ke file Proposal
            // Sesuai ERD: tanggal_upload (date)
            $table->date('tanggal_upload');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumen');
    }
};
