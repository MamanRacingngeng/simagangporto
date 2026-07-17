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
        // Buat tabel notifikasi jika belum ada
        if (!Schema::hasTable('notifikasi')) {
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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifikasi');
    }
};
