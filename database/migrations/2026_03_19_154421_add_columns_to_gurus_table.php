<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Menambahkan kolom data diri lengkap ke tabel gurus.
     */
    public function up(): void
    {
        Schema::table('gurus', function (Blueprint $table) {
            $table->string('nama', 255)->nullable()->after('nip')
                  ->comment('Nama lengkap guru');
            $table->string('tempat_lahir', 100)->nullable()->after('nama')
                  ->comment('Tempat lahir');
            $table->date('tanggal_lahir')->nullable()->after('tempat_lahir')
                  ->comment('Tanggal lahir');
            $table->enum('jk', ['L', 'P'])->nullable()->after('tanggal_lahir')
                  ->comment('L = Laki-laki, P = Perempuan');
            $table->string('status_pegawai', 100)->nullable()->after('jk')
                  ->comment('PNS / Honorer / Kontrak');
            $table->string('pangkat_gol_ruang', 100)->nullable()->after('status_pegawai')
                  ->comment('Pangkat / Golongan Ruang');
            $table->string('no_sk_pertama', 150)->nullable()->after('pangkat_gol_ruang')
                  ->comment('No SK CPNS / Kontrak / Honor pertama');
            $table->string('no_sk_terakhir', 150)->nullable()->after('no_sk_pertama')
                  ->comment('No SK terakhir');
            $table->string('pendidikan_terakhir', 100)->nullable()->after('no_sk_terakhir')
                  ->comment('Pendidikan terakhir');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gurus', function (Blueprint $table) {
            $table->dropColumn([
                'nama',
                'tempat_lahir',
                'tanggal_lahir',
                'jk',
                'status_pegawai',
                'pangkat_gol_ruang',
                'no_sk_pertama',
                'no_sk_terakhir',
                'pendidikan_terakhir',
            ]);
        });
    }
};