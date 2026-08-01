<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabel alumni menyimpan SALINAN (snapshot) seluruh data siswa pada
 * saat mereka diluluskan. Snapshot ini sengaja dipisah dari tabel
 * siswas supaya:
 *  1. Data alumni tetap utuh walau data siswa/user aslinya suatu saat
 *     diubah, dinonaktifkan, atau dihapus.
 *  2. Sekolah punya arsip histori kelulusan permanen per tahun ajaran.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alumni', function (Blueprint $table) {
            $table->id();

            // Referensi ke data asal (nullable & nullOnDelete supaya
            // snapshot alumni tidak ikut hilang jika siswa/user dihapus)
            $table->foreignId('siswa_id')->nullable()->constrained('siswas')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();

            // Identitas
            $table->string('nama');
            $table->string('email')->nullable();
            $table->string('nidn')->nullable()->comment('NISN / NIDN');
            $table->string('nik', 20)->nullable();
            $table->enum('jk', ['L', 'P'])->nullable();
            $table->string('agama')->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tgl_lahir')->nullable();
            $table->string('no_telp', 20)->nullable();

            // Alamat
            $table->text('alamat')->nullable();
            $table->string('rt', 10)->nullable();
            $table->string('rw', 10)->nullable();
            $table->string('dusun')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('kode_pos', 10)->nullable();
            $table->string('jenis_tinggal')->nullable();
            $table->string('jalan_transportasi')->nullable();

            // Bantuan sosial
            $table->boolean('penerima_kps')->default(false);
            $table->string('no_kps', 50)->nullable();

            // Data kelulusan
            $table->string('foto')->nullable();
            $table->string('kelas_terakhir')->nullable();
            $table->year('tahun_lulus');
            $table->date('tanggal_lulus');
            $table->string('no_ijazah', 50)->nullable();
            $table->text('catatan')->nullable();

            $table->timestamps();

            $table->index(['tahun_lulus']);
            $table->index(['nama']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alumni');
    }
};