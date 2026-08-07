<?php
// database/migrations/2026_08_07_000000_update_alumni_table_for_hard_graduate.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Lepas foreign key siswa_id & user_id (kalau ada) supaya baris
        // siswa/user boleh dihapus permanen tanpa terhalang constraint,
        // karena mulai sekarang alumni menyimpan SNAPSHOT datanya sendiri.
        Schema::table('alumni', function (Blueprint $table) {
            try { $table->dropForeign(['siswa_id']); } catch (\Throwable $e) {}
            try { $table->dropForeign(['user_id']); } catch (\Throwable $e) {}
        });

        Schema::table('alumni', function (Blueprint $table) {
            if (!Schema::hasColumn('alumni', 'password_snapshot')) {
                // Hash password siswa pada saat diluluskan, dipakai untuk
                // memulihkan akun jika admin membatalkan status alumni.
                $table->string('password_snapshot')->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('alumni', 'kelas_id_snapshot')) {
                // ID kelas (study_groups/kelas) siswa saat diluluskan,
                // dipakai untuk memulihkan penempatan kelas saat dibatalkan.
                $table->unsignedBigInteger('kelas_id_snapshot')->nullable()->after('kelas_terakhir');
            }
        });
    }

    public function down(): void
    {
        Schema::table('alumni', function (Blueprint $table) {
            $table->dropColumn(['password_snapshot', 'kelas_id_snapshot']);
        });
    }
};