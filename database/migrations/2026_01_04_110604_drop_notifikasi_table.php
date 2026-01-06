<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    /**
     * Run the migrations.
     * 
     * PERINGATAN: Migration ini akan menghapus tabel notifikasi beserta semua datanya.
     * Pastikan Anda sudah backup data jika diperlukan.
     */
    public function up(): void
    {
        Schema::dropIfExists('notifikasi');
    }

    /**
     * Reverse the migrations.
     * 
     * Jika perlu rollback, migration ini akan membuat ulang tabel notifikasi.
     */
    public function down(): void
    {
        Schema::create('notifikasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('permohonan_magang_id')->nullable()->constrained('permohonan_magang')->onDelete('cascade');
            $table->foreignId('admin_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('judul');
            $table->text('pesan');
            $table->enum('tipe', ['info', 'warning', 'error', 'success', 'revisi'])->default('info');
            $table->boolean('dibaca')->default(false);
            $table->timestamp('dibaca_at')->nullable();
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('dibaca');
            $table->index('created_at');
        });
    }
};
