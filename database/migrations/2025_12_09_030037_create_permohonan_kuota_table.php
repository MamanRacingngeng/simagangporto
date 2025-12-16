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
        Schema::create('permohonan_kuota', function (Blueprint $table) {
            $table->id();
            $table->foreignId('permohonan_magang_id')->constrained('permohonan_magang')->onDelete('cascade');
            $table->foreignId('kuota_magang_id')->constrained('kuota_magang')->onDelete('cascade');
            $table->timestamps();
            
            // Unique constraint untuk mencegah duplikasi relasi
            $table->unique(['permohonan_magang_id', 'kuota_magang_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permohonan_kuota');
    }
};
