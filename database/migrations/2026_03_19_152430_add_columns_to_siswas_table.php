<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Menambahkan kolom data diri lengkap ke tabel siswas.
     */
    public function up(): void
    {
        Schema::table('siswas', function (Blueprint $table) {
            $table->string('nik', 20)->nullable()->after('nidn');               // Nomor Induk Kependudukan
            $table->string('nama', 255)->nullable()->after('nik');              // Nama lengkap siswa
            $table->enum('jk', ['L', 'P'])->nullable()->after('nama');         // Jenis kelamin
            $table->string('tempat_lahir', 100)->nullable()->after('jk');      // Tempat lahir
            $table->date('tgl_lahir')->nullable()->after('tempat_lahir');      // Tanggal lahir
            $table->string('agama', 20)->nullable()->after('tgl_lahir');       // Agama
            $table->text('alamat')->nullable()->after('agama');                // Alamat lengkap
            $table->string('rt', 10)->nullable()->after('alamat');             // RT
            $table->string('rw', 10)->nullable()->after('rt');                 // RW
            $table->string('dusun', 100)->nullable()->after('rw');             // Dusun
            $table->string('kecamatan', 100)->nullable()->after('dusun');      // Kecamatan
            $table->string('kode_pos', 10)->nullable()->after('kecamatan');    // Kode Pos
            $table->string('jenis_tinggal', 50)->nullable()->after('kode_pos');         // Jenis Tinggal
            $table->string('jalan_transportasi', 100)->nullable()->after('jenis_tinggal'); // Alat Transportasi
            $table->string('no_telp', 20)->nullable()->after('jalan_transportasi');     // Nomor telepon
            $table->string('shkun', 50)->nullable()->after('no_telp');                  // SKHUN
            $table->enum('penerima_kps', ['Ya', 'Tidak'])->default('Tidak')->after('shkun'); // Penerima KPS
            $table->string('no_kps', 50)->nullable()->after('penerima_kps');  // Nomor KPS
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('siswas', function (Blueprint $table) {
            $table->dropColumn([
                'nik',
                'nama',
                'jk',
                'tempat_lahir',
                'tgl_lahir',
                'agama',
                'alamat',
                'rt',
                'rw',
                'dusun',
                'kecamatan',
                'kode_pos',
                'jenis_tinggal',
                'jalan_transportasi',
                'no_telp',
                'shkun',
                'penerima_kps',
                'no_kps',
            ]);
        });
    }
};