<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Mengaktifkan user yang sudah terdaftar sebelumnya dan sudah verified
     */
    public function up(): void
    {
        // Aktifkan user yang sudah memiliki email_verified_at (sudah verified sebelumnya)
        DB::table('users')
            ->whereNotNull('email_verified_at')
            ->where('is_active', false)
            ->orWhereNull('is_active')
            ->update(['is_active' => true]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tidak perlu rollback karena ini adalah data migration
    }
};
