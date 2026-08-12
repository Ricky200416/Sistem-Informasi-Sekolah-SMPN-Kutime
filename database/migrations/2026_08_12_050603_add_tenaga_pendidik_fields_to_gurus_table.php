<?php
// database/migrations/2026_08_12_000000_add_tenaga_pendidik_fields_to_gurus_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gurus', function (Blueprint $table) {
            $table->string('jabatan')->nullable()->after('nama');
            $table->string('mata_pelajaran')->nullable()->after('jabatan');
            $table->string('no_hp')->nullable()->after('mata_pelajaran');
            $table->boolean('tampil_website')->default(true)->after('no_hp');
            $table->integer('urutan_tampil')->default(0)->after('tampil_website');
        });
    }

    public function down(): void
    {
        Schema::table('gurus', function (Blueprint $table) {
            $table->dropColumn(['jabatan', 'mata_pelajaran', 'no_hp', 'tampil_website', 'urutan_tampil']);
        });
    }
};