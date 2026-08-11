<?php
// database/migrations/2026_08_11_000001_add_lokasi_columns_to_school_settings_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('school_settings')) {
            return;
        }

        Schema::table('school_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('school_settings', 'latitude')) {
                $table->decimal('latitude', 10, 7)->nullable();
            }
            if (!Schema::hasColumn('school_settings', 'longitude')) {
                $table->decimal('longitude', 10, 7)->nullable();
            }
            if (!Schema::hasColumn('school_settings', 'radius_meter')) {
                $table->unsignedInteger('radius_meter')->default(100);
            }
            if (!Schema::hasColumn('school_settings', 'toleransi_telat_menit')) {
                $table->unsignedInteger('toleransi_telat_menit')->default(15);
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('school_settings')) {
            return;
        }
        Schema::table('school_settings', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude', 'radius_meter', 'toleransi_telat_menit']);
        });
    }
};