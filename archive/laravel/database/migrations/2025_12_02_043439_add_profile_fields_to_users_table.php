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
        Schema::table('users', function (Blueprint $table) {
            $table->string('nama_panggilan')->nullable()->after('nama');
            $table->string('ttl')->nullable()->after('nama_panggilan'); // Tempat, Tanggal Lahir
            $table->string('domisili')->nullable()->after('ttl');
            $table->string('nim')->nullable()->after('domisili'); // NIM/NIS
            $table->integer('semester')->nullable()->after('nim');
            $table->decimal('ipk', 3, 2)->nullable()->after('semester'); // IPK dengan 2 desimal
            $table->string('program')->nullable()->after('ipk'); // Program studi (S1 Informatika, dll)
            $table->string('universitas')->nullable()->after('program');
            $table->text('software_tools')->nullable()->after('universitas');
            $table->string('portofolio')->nullable()->after('software_tools'); // URL portofolio
            $table->string('kompetensi_utama')->nullable()->after('portofolio');
            $table->string('foto_profil')->nullable()->after('kompetensi_utama'); // Path foto profil
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'nama_panggilan',
                'ttl',
                'domisili',
                'nim',
                'semester',
                'ipk',
                'program',
                'universitas',
                'software_tools',
                'portofolio',
                'kompetensi_utama',
                'foto_profil',
            ]);
        });
    }
};
