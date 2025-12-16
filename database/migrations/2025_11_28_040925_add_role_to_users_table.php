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
            // Sesuai ERD: role adalah 'user' atau 'admin'
            $table->enum('role', ['user', 'admin'])->default('user')->after('password');
            $table->string('no_telepon')->nullable()->after('email');
            // Sesuai ERD: instansi (bukan universitas/jurusan terpisah)
            $table->string('instansi')->nullable()->after('no_telepon');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'no_telepon', 'instansi']);
        });
    }
};
