<?php
// database/migrations/2026_08_07_000000_add_timetable_id_to_absensi_gurus_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('absensi_gurus', function (Blueprint $table) {
            if (!Schema::hasColumn('absensi_gurus', 'timetable_id')) {
                $table->foreignId('timetable_id')
                    ->nullable()
                    ->after('guru_id')
                    ->constrained('timetables')
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('absensi_gurus', function (Blueprint $table) {
            $table->dropConstrainedForeignId('timetable_id');
        });
    }
};