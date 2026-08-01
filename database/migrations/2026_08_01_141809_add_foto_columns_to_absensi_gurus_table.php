<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('absensi_gurus', function (Blueprint $table) {
            $table->string('foto_masuk')->nullable()->after('keterangan');
            $table->string('foto_pulang')->nullable()->after('foto_masuk');
            $table->time('jam_masuk')->nullable()->after('foto_pulang');
            $table->time('jam_pulang')->nullable()->after('jam_masuk');
            $table->enum('tipe_absensi', ['mengajar', 'kantor'])->nullable()->after('jam_pulang');
        });
    }

    public function down(): void
    {
        Schema::table('absensi_gurus', function (Blueprint $table) {
            $table->dropColumn(['foto_masuk', 'foto_pulang', 'jam_masuk', 'jam_pulang', 'tipe_absensi']);
        });
    }
};