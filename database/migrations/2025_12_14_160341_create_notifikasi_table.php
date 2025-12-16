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
        Schema::create('notifikasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Pendaftar yang menerima notifikasi
            $table->foreignId('permohonan_magang_id')->nullable()->constrained('permohonan_magang')->onDelete('cascade'); // Permohonan terkait
            $table->foreignId('admin_id')->nullable()->constrained('users')->onDelete('set null'); // Admin yang mengirim (menggunakan users dengan role admin)
            $table->string('judul'); // Judul notifikasi
            $table->text('pesan'); // Pesan notifikasi
            $table->enum('tipe', ['info', 'warning', 'error', 'success'])->default('info'); // Tipe notifikasi
            $table->boolean('dibaca')->default(false); // Status sudah dibaca atau belum
            $table->timestamp('dibaca_at')->nullable(); // Waktu dibaca
            $table->timestamps();
            
            // Index untuk query yang lebih cepat
            $table->index('user_id');
            $table->index('dibaca');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifikasi');
    }
};
