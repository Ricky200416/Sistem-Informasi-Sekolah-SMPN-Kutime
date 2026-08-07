<?php
// database/migrations/2026_08_07_000001_change_penerima_kps_type_on_alumni_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Jika sebelumnya ada baris lama dengan nilai 0/1 (integer),
        // amankan dulu supaya tidak hilang sebelum kolom diubah tipenya.
        if (Schema::hasColumn('alumni', 'penerima_kps')) {
            DB::table('alumni')->where('penerima_kps', 1)->update(['penerima_kps' => null]);
            DB::table('alumni')->where('penerima_kps', 0)->update(['penerima_kps' => null]);
        }

        Schema::table('alumni', function (Blueprint $table) {
            $table->string('penerima_kps', 10)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('alumni', function (Blueprint $table) {
            $table->boolean('penerima_kps')->nullable()->change();
        });
    }
};