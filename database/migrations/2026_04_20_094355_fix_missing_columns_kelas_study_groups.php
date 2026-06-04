<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── STEP 1: Pastikan kolom semester ada dulu (study_groups)
        Schema::table('study_groups', function (Blueprint $table) {
            if (!Schema::hasColumn('study_groups', 'semester')) {
                $table->tinyInteger('semester')->nullable()->after('grade');
            }
        });

        // ── STEP 2: Tambahkan kolom lain (study_groups)
        Schema::table('study_groups', function (Blueprint $table) {

            if (!Schema::hasColumn('study_groups', 'academic_year')) {
                $table->string('academic_year', 9)->nullable()->after('semester');
            }

            if (!Schema::hasColumn('study_groups', 'room')) {
                $table->string('room', 50)->nullable()->after('section');
            }

            if (!Schema::hasColumn('study_groups', 'capacity')) {
                $table->integer('capacity')->default(30)->after('room');
            }

            if (!Schema::hasColumn('study_groups', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('capacity');
            }
        });

        // ── STEP 3: Tabel kelas
        Schema::table('kelas', function (Blueprint $table) {

            if (!Schema::hasColumn('kelas', 'semester')) {
                $table->tinyInteger('semester')->nullable()->after('tahun_ajaran');
            }

            if (!Schema::hasColumn('kelas', 'ruang')) {
                $table->string('ruang', 50)->nullable()->after('semester');
            }

            if (!Schema::hasColumn('kelas', 'kapasitas')) {
                $table->integer('kapasitas')->default(30)->after('ruang');
            }
        });
    }

    public function down(): void
    {
        // ── study_groups rollback
        Schema::table('study_groups', function (Blueprint $table) {
            $cols = ['academic_year', 'semester', 'room', 'capacity', 'is_active'];

            foreach ($cols as $col) {
                if (Schema::hasColumn('study_groups', $col)) {
                    $table->dropColumn($col);
                }
            }
        });

        // ── kelas rollback
        Schema::table('kelas', function (Blueprint $table) {
            $cols = ['semester', 'ruang', 'kapasitas'];

            foreach ($cols as $col) {
                if (Schema::hasColumn('kelas', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};