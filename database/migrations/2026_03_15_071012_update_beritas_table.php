<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambahkan kolom media ke tabel beritas yang sudah ada.
     * Jika tabel belum ada, semua kolom sudah ada di migration create.
     */
    public function up(): void
    {
        Schema::table('beritas', function (Blueprint $table) {
            // Hapus kolom foto lama jika ada, ganti dengan file_path + tipe
            if (Schema::hasColumn('beritas', 'foto')) {
                $table->dropColumn('foto');
            }

            // Tipe media lampiran: none | photo | video | link_youtube | link_facebook
            $table->enum('media_tipe', ['none', 'photo', 'video', 'link_youtube', 'link_facebook'])
                  ->default('none')
                  ->after('isi');

            // Path file upload (foto/video)
            $table->string('media_file')->nullable()->after('media_tipe');

            // Link eksternal (YT / FB)
            $table->string('media_link')->nullable()->after('media_file');

            // Thumbnail
            $table->string('media_thumbnail')->nullable()->after('media_link');
        });
    }

    public function down(): void
    {
        Schema::table('beritas', function (Blueprint $table) {
            $table->dropColumn(['media_tipe', 'media_file', 'media_link', 'media_thumbnail']);
            $table->string('foto')->nullable();
        });
    }
};