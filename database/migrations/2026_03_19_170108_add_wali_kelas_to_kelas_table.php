<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kelas', function (Blueprint $table) {
            $table->string('tahun_ajaran', 20)->nullable()->after('rombel'); // mis. 2024/2025
            $table->foreignId('guru_id')
                  ->nullable()
                  ->after('tahun_ajaran')
                  ->constrained('gurus')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('kelas', function (Blueprint $table) {
            $table->dropForeign(['guru_id']);
            $table->dropColumn(['tahun_ajaran', 'guru_id']);
        });
    }
};