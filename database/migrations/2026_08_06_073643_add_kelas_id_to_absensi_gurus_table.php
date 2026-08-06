<?php
// database/migrations/2026_08_06_000000_add_kelas_id_to_absensi_gurus_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('absensi_gurus', function (Blueprint $table) {
            $table->foreignId('kelas_id')
                ->nullable()
                ->after('guru_id')
                ->constrained('kelas')
                ->nullOnDelete();
        });

        // Cegah duplikasi di level database (pengaman ekstra selain guard di controller).
        // Jalankan hanya jika belum ada data duplikat guru_id+tanggal, kalau ada, bersihkan dulu.
        Schema::table('absensi_gurus', function (Blueprint $table) {
            $table->unique(['guru_id', 'tanggal'], 'absensi_gurus_guru_tanggal_unique');
        });
    }

    public function down(): void
    {
        Schema::table('absensi_gurus', function (Blueprint $table) {
            $table->dropUnique('absensi_gurus_guru_tanggal_unique');
            $table->dropConstrainedForeignId('kelas_id');
        });
    }
};