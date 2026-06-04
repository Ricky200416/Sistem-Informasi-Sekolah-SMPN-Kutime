<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration
     */
    public function up(): void
    {
        Schema::create('absensi_siswas', function (Blueprint $table) {

            $table->id();

            // ── Foreign keys ──────────────────────────────────────
            $table->foreignId('siswa_id')
                  ->constrained('siswas')
                  ->cascadeOnDelete();

            $table->foreignId('jadwal_id')
                  ->nullable()
                  ->constrained('jadwals')
                  ->nullOnDelete();

            // ── Data absensi ──────────────────────────────────────
            $table->date('tanggal');

            $table->enum('hari', [
                'Senin', 'Selasa', 'Rabu',
                'Kamis', 'Jumat', 'Sabtu', 'Minggu',
            ])->nullable();

            $table->enum('status', [
                'hadir',
                'sakit',
                'izin',
                'alpha',
            ])->default('alpha');

            $table->string('keterangan', 500)->nullable();

            $table->timestamps();

            // ── Constraint & Index ────────────────────────────────
            $table->unique(['siswa_id', 'tanggal'], 'absensi_siswa_tanggal_unique');

            $table->index('tanggal');
            $table->index('status');
        });
    }

    /**
     * Rollback
     */
    public function down(): void
    {
        Schema::dropIfExists('absensi_siswas');
    }
};