<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('permohonan_magang', function (Blueprint $table) {
            $table->text('catatan_revisi')->nullable()->after('alasan_penolakan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('permohonan_magang', function (Blueprint $table) {
            $table->dropColumn('catatan_revisi');
        });
    }
};
