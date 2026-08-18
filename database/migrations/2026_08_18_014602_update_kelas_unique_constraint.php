<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * PENTING sebelum menjalankan migration ini:
     * Jalankan dulu perintah berikut untuk memastikan nama index unique
     * yang ada di database Anda sama persis dengan yang di-drop di bawah:
     *
     *   SHOW INDEX FROM kelas WHERE Key_name LIKE '%unique%';
     *   SHOW INDEX FROM study_groups WHERE Key_name LIKE '%unique%';
     *
     * Jika nama index berbeda, sesuaikan string 'kelas_nama_unique' /
     * 'study_groups_name_unique' di bawah ini.
     */
    public function up(): void
    {
        // ── Tabel kelas ──────────────────────────────────────────────
        Schema::table('kelas', function (Blueprint $table) {
            // Hapus unique constraint lama yang hanya mengunci kolom 'nama'
            $table->dropUnique('kelas_nama_unique');
        });

        Schema::table('kelas', function (Blueprint $table) {
            // Unique baru: kombinasi nama + rombel + tahun_ajaran + semester
            // Artinya "SMP 1" boleh dipakai berkali-kali SELAMA rombel/tahun/semester beda
            $table->unique(
                ['nama', 'rombel', 'tahun_ajaran', 'semester'],
                'kelas_nama_rombel_tahun_semester_unique'
            );
        });

        // ── Tabel study_groups ───────────────────────────────────────
        // Cek dulu apakah sudah ada unique constraint di kolom 'name' saja.
        // Jika ada dan namanya berbeda dari asumsi di bawah, sesuaikan.
        $studyGroupIndexes = collect(DB::select("SHOW INDEX FROM study_groups"))
            ->pluck('Key_name')
            ->unique();

        if ($studyGroupIndexes->contains('study_groups_name_unique')) {
            Schema::table('study_groups', function (Blueprint $table) {
                $table->dropUnique('study_groups_name_unique');
            });
        }

        if (!$studyGroupIndexes->contains('study_groups_name_section_academic_year_semester_unique')) {
            Schema::table('study_groups', function (Blueprint $table) {
                $table->unique(
                    ['name', 'section', 'academic_year', 'semester'],
                    'study_groups_name_section_academic_year_semester_unique'
                );
            });
        }
    }

    public function down(): void
    {
        Schema::table('kelas', function (Blueprint $table) {
            $table->dropUnique('kelas_nama_rombel_tahun_semester_unique');
            $table->unique('nama', 'kelas_nama_unique');
        });

        Schema::table('study_groups', function (Blueprint $table) {
            $table->dropUnique('study_groups_name_section_academic_year_semester_unique');
        });
    }
};