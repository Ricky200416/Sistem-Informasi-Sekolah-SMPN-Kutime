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
        Schema::create('jadwals', function (Blueprint $table) {
            $table->id(); // primary key, tipe unsignedBigInteger

            // Contoh kolom jadwal
            $table->string('mata_pelajaran', 100); // nama mata pelajaran
            $table->string('kelas', 50);           // kelas / rombongan
            $table->time('jam_mulai');             // jam mulai
            $table->time('jam_selesai');           // jam selesai
            $table->enum('hari', [                 // hari dalam minggu
                'Senin', 'Selasa', 'Rabu',
                'Kamis', 'Jumat', 'Sabtu', 'Minggu',
            ]);

            $table->timestamps();
        });
    }

    /**
     * Rollback
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwals');
    }
};