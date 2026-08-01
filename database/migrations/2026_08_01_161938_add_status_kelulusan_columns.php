<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Menambahkan:
 * - siswas.status        -> 'aktif' | 'lulus'   (default 'aktif')
 * - siswas.tanggal_lulus -> tanggal saat siswa diluluskan
 * - users.is_active      -> untuk menonaktifkan login akun alumni
 *
 * Semua penambahan kolom dibungkus pengecekan hasColumn() supaya
 * migration ini aman dijalankan meskipun kolom sudah pernah ada.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('siswas', function (Blueprint $table) {
            if (!Schema::hasColumn('siswas', 'status')) {
                $table->enum('status', ['aktif', 'lulus'])->default('aktif')->after('id');
            }
            if (!Schema::hasColumn('siswas', 'tanggal_lulus')) {
                $table->date('tanggal_lulus')->nullable()->after('status');
            }
        });

        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('email');
            }
        });
    }

    public function down(): void
    {
        Schema::table('siswas', function (Blueprint $table) {
            if (Schema::hasColumn('siswas', 'tanggal_lulus')) {
                $table->dropColumn('tanggal_lulus');
            }
            if (Schema::hasColumn('siswas', 'status')) {
                $table->dropColumn('status');
            }
        });

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'is_active')) {
                $table->dropColumn('is_active');
            }
        });
    }
};