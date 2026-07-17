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
        Schema::create('kuota_magang', function (Blueprint $table) {
            $table->id();
            // Sesuai ERD: periode, kuota_max, kuota_terpakai
            $table->string('periode'); // Contoh: "Semester Genap 2024"
            $table->integer('kuota_max'); // Maximum number of internship slots
            $table->integer('kuota_terpakai')->default(0); // Number of slots currently filled
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kuota_magang');
    }
};
