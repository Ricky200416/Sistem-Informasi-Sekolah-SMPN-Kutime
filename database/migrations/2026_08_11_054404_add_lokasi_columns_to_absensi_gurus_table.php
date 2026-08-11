<?php
// database/migrations/2026_08_11_000000_add_lokasi_columns_to_absensi_gurus_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('absensi_gurus', function (Blueprint $table) {
            if (!Schema::hasColumn('absensi_gurus', 'latitude_masuk')) {
                $table->decimal('latitude_masuk', 10, 7)->nullable()->after('foto_masuk');
            }
            if (!Schema::hasColumn('absensi_gurus', 'longitude_masuk')) {
                $table->decimal('longitude_masuk', 10, 7)->nullable()->after('latitude_masuk');
            }
            if (!Schema::hasColumn('absensi_gurus', 'jarak_masuk')) {
                $table->unsignedInteger('jarak_masuk')->nullable()->after('longitude_masuk')
                      ->comment('Jarak dari titik sekolah saat absen masuk, dalam meter');
            }
            if (!Schema::hasColumn('absensi_gurus', 'lokasi_valid_masuk')) {
                $table->boolean('lokasi_valid_masuk')->nullable()->after('jarak_masuk');
            }

            if (!Schema::hasColumn('absensi_gurus', 'latitude_pulang')) {
                $table->decimal('latitude_pulang', 10, 7)->nullable()->after('foto_pulang');
            }
            if (!Schema::hasColumn('absensi_gurus', 'longitude_pulang')) {
                $table->decimal('longitude_pulang', 10, 7)->nullable()->after('latitude_pulang');
            }
            if (!Schema::hasColumn('absensi_gurus', 'jarak_pulang')) {
                $table->unsignedInteger('jarak_pulang')->nullable()->after('longitude_pulang')
                      ->comment('Jarak dari titik sekolah saat absen pulang, dalam meter');
            }
            if (!Schema::hasColumn('absensi_gurus', 'lokasi_valid_pulang')) {
                $table->boolean('lokasi_valid_pulang')->nullable()->after('jarak_pulang');
            }

            if (!Schema::hasColumn('absensi_gurus', 'keterlambatan_menit')) {
                $table->unsignedInteger('keterlambatan_menit')->nullable()->after('status')
                      ->comment('Jumlah menit keterlambatan jika status = L');
            }
        });
    }

    public function down(): void
    {
        Schema::table('absensi_gurus', function (Blueprint $table) {
            $table->dropColumn([
                'latitude_masuk', 'longitude_masuk', 'jarak_masuk', 'lokasi_valid_masuk',
                'latitude_pulang', 'longitude_pulang', 'jarak_pulang', 'lokasi_valid_pulang',
                'keterlambatan_menit',
            ]);
        });
    }
};