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
        Schema::create('galeri_magang', function (Blueprint $table) {
            $table->id();
            $table->string('judul'); // Judul/keterangan foto
            $table->text('deskripsi')->nullable(); // Deskripsi kegiatan (opsional)
            $table->string('foto'); // Path ke file foto
            $table->boolean('aktif')->default(true); // Status aktif/tidak aktif untuk ditampilkan
            $table->integer('urutan')->default(0); // Urutan tampilan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('galeri_magang');
    }
};
