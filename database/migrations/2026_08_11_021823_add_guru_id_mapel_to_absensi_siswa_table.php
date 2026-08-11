<?php
// database/migrations/2026_08_11_000000_add_guru_id_mapel_to_absensi_siswa_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('absensi_siswa', function (Blueprint $table) {
            if (!Schema::hasColumn('absensi_siswa', 'guru_id')) {
                $table->unsignedBigInteger('guru_id')->nullable()->after('siswa_id');
                $table->foreign('guru_id')->references('id')->on('gurus')->nullOnDelete();
            }
            if (!Schema::hasColumn('absensi_siswa', 'mata_pelajaran')) {
                $table->string('mata_pelajaran')->nullable()->after('guru_id');
            }
        });

        // Hapus unique lama (siswa_id + tanggal) jika ada — supaya 1 siswa
        // bisa punya banyak record absensi di hari yang sama (beda guru/mapel).
        Schema::table('absensi_siswa', function (Blueprint $table) {
            try {
                $table->dropUnique(['siswa_id', 'tanggal']);
            } catch (\Throwable $e) {
                // nama index beda / tidak ada unique lama — abaikan, tidak fatal
            }
        });

        // Unique baru: 1 guru hanya bisa 1 record per siswa/tanggal/mapel
        // (kalau disimpan ulang -> update). Guru lain -> record baru, tidak menimpa.
        Schema::table('absensi_siswa', function (Blueprint $table) {
            $table->unique(
                ['siswa_id', 'tanggal', 'guru_id', 'mata_pelajaran'],
                'absensi_siswa_unique_per_sesi'
            );
        });
    }

    public function down(): void
    {
        Schema::table('absensi_siswa', function (Blueprint $table) {
            $table->dropUnique('absensi_siswa_unique_per_sesi');
            $table->dropForeign(['guru_id']);
            $table->dropColumn(['guru_id', 'mata_pelajaran']);
        });
    }
};