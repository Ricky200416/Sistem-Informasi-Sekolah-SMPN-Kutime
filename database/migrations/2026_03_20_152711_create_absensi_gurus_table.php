<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration – buat tabel absensi_gurus.
     */
    public function up(): void
    {
        Schema::create('absensi_gurus', function (Blueprint $table) {
            $table->id();

            $table->foreignId('guru_id')
                  ->constrained('gurus')
                  ->onDelete('cascade')
                  ->comment('Relasi ke tabel gurus');

            $table->date('tanggal')->comment('Tanggal absensi');

            $table->enum('status', ['P', 'A', 'S', 'I', 'L', 'W'])
                  ->comment('P=Hadir, A=Alpha, S=Sakit, I=Izin, L=Telat, W=Libur');

            $table->text('keterangan')->nullable()->comment('Keterangan tambahan (opsional)');

            $table->timestamps();

            // Satu guru hanya boleh punya satu record per tanggal
            $table->unique(['guru_id', 'tanggal'], 'unique_absensi_guru_tanggal');

            // Index untuk query filter bulan/tahun
            $table->index('tanggal');
            $table->index('guru_id');
        });
    }

    /**
     * Balik migration – hapus tabel absensi_gurus.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensi_gurus');
    }
};