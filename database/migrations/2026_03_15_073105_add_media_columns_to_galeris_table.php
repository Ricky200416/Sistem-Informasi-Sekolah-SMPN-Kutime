<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('galeris', function (Blueprint $table) {

            // Tambah kolom 'tipe' jika belum ada
            if (!Schema::hasColumn('galeris', 'tipe')) {
                $table->enum('tipe', ['photo', 'video', 'link_youtube', 'link_facebook'])
                      ->default('photo')
                      ->after('deskripsi');
            }

            // Tambah kolom 'file_path' jika belum ada
            // (menggantikan kolom 'foto' yang mungkin masih pakai nama lama)
            if (!Schema::hasColumn('galeris', 'file_path')) {
                $table->string('file_path')->nullable()->after('tipe');
            }

            // Tambah kolom 'link_url' jika belum ada
            if (!Schema::hasColumn('galeris', 'link_url')) {
                $table->string('link_url')->nullable()->after('file_path');
            }

            // Tambah kolom 'thumbnail' jika belum ada
            if (!Schema::hasColumn('galeris', 'thumbnail')) {
                $table->string('thumbnail')->nullable()->after('link_url');
            }

            // Hapus kolom 'foto' lama jika masih ada (dari migration sebelumnya)
            if (Schema::hasColumn('galeris', 'foto')) {
                $table->dropColumn('foto');
            }
        });
    }

    public function down(): void
    {
        Schema::table('galeris', function (Blueprint $table) {
            $columnsToDrop = [];

            if (Schema::hasColumn('galeris', 'tipe'))      $columnsToDrop[] = 'tipe';
            if (Schema::hasColumn('galeris', 'file_path')) $columnsToDrop[] = 'file_path';
            if (Schema::hasColumn('galeris', 'link_url'))  $columnsToDrop[] = 'link_url';
            if (Schema::hasColumn('galeris', 'thumbnail')) $columnsToDrop[] = 'thumbnail';

            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }

            // Kembalikan kolom foto lama
            if (!Schema::hasColumn('galeris', 'foto')) {
                $table->string('foto')->nullable();
            }
        });
    }
};