<?php
// database/migrations/2026_08_06_000001_add_foto_fields_to_absensi_gurus_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('absensi_gurus', function (Blueprint $table) {
            if (!Schema::hasColumn('absensi_gurus', 'foto_masuk')) {
                $table->string('foto_masuk')->nullable()->after('keterangan');
            }
            if (!Schema::hasColumn('absensi_gurus', 'foto_pulang')) {
                $table->string('foto_pulang')->nullable()->after('foto_masuk');
            }
            if (!Schema::hasColumn('absensi_gurus', 'jam_masuk')) {
                $table->time('jam_masuk')->nullable()->after('foto_pulang');
            }
            if (!Schema::hasColumn('absensi_gurus', 'jam_pulang')) {
                $table->time('jam_pulang')->nullable()->after('jam_masuk');
            }
            if (!Schema::hasColumn('absensi_gurus', 'tipe_absensi')) {
                $table->string('tipe_absensi')->nullable()->after('jam_pulang');
            }
        });
    }

    public function down(): void
    {
        Schema::table('absensi_gurus', function (Blueprint $table) {
            $table->dropColumn(['foto_masuk', 'foto_pulang', 'jam_masuk', 'jam_pulang', 'tipe_absensi']);
        });
    }
};