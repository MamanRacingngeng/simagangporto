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
        Schema::create('jadwal_magang', function (Blueprint $table) {
            $table->id();
            // Sesuai ERD: periode, tgl_mulai, tgl_selesai
            $table->string('periode'); // Period of the internship schedule
            $table->date('tgl_mulai'); // Start date of the internship period
            $table->date('tgl_selesai'); // End date of the internship period
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_magang');
    }
};
