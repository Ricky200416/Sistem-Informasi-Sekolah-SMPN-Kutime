<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration ini HANYA jika tabel page_contents sudah ada
     * (update dari versi sebelumnya yang belum punya kolom baru).
     */
    public function up(): void
    {
        Schema::table('page_contents', function (Blueprint $table) {
            if (!Schema::hasColumn('page_contents', 'hero_media_tipe')) {
                $table->string('hero_media_tipe')->default('none')->after('value_2');
            }
            if (!Schema::hasColumn('page_contents', 'hero_media_files')) {
                $table->text('hero_media_files')->nullable()->after('hero_media_tipe');
            }
            if (!Schema::hasColumn('page_contents', 'hero_media_link')) {
                $table->string('hero_media_link')->nullable()->after('hero_media_files');
            }
            if (!Schema::hasColumn('page_contents', 'hero_slide_interval')) {
                $table->integer('hero_slide_interval')->default(4000)->after('hero_media_link');
            }
            if (!Schema::hasColumn('page_contents', 'sambutan_foto')) {
                $table->string('sambutan_foto')->nullable()->after('hero_slide_interval');
            }
            if (!Schema::hasColumn('page_contents', 'kontak_telepon')) {
                $table->string('kontak_telepon')->nullable();
            }
            if (!Schema::hasColumn('page_contents', 'kontak_email')) {
                $table->string('kontak_email')->nullable();
            }
            if (!Schema::hasColumn('page_contents', 'kontak_alamat')) {
                $table->string('kontak_alamat')->nullable();
            }
            if (!Schema::hasColumn('page_contents', 'kontak_maps_embed')) {
                $table->string('kontak_maps_embed')->nullable();
            }
            if (!Schema::hasColumn('page_contents', 'sosmed_instagram')) {
                $table->string('sosmed_instagram')->nullable();
            }
            if (!Schema::hasColumn('page_contents', 'sosmed_facebook')) {
                $table->string('sosmed_facebook')->nullable();
            }
            if (!Schema::hasColumn('page_contents', 'sosmed_youtube')) {
                $table->string('sosmed_youtube')->nullable();
            }
            if (!Schema::hasColumn('page_contents', 'sosmed_twitter')) {
                $table->string('sosmed_twitter')->nullable();
            }
            if (!Schema::hasColumn('page_contents', 'stat_siswa')) {
                $table->string('stat_siswa')->nullable();
            }
            if (!Schema::hasColumn('page_contents', 'stat_guru')) {
                $table->string('stat_guru')->nullable();
            }
            if (!Schema::hasColumn('page_contents', 'stat_prestasi')) {
                $table->string('stat_prestasi')->nullable();
            }
            if (!Schema::hasColumn('page_contents', 'stat_ekskul')) {
                $table->string('stat_ekskul')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('page_contents', function (Blueprint $table) {
            $cols = [
                'sambutan_foto','kontak_telepon','kontak_email','kontak_alamat',
                'kontak_maps_embed','sosmed_instagram','sosmed_facebook',
                'sosmed_youtube','sosmed_twitter','stat_siswa','stat_guru',
                'stat_prestasi','stat_ekskul',
            ];
            foreach ($cols as $col) {
                if (Schema::hasColumn('page_contents', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};