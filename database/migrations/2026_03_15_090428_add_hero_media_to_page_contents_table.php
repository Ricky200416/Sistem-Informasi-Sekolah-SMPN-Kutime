<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('page_contents', function (Blueprint $table) {

            // Tipe media hero: none | image | video | youtube | slideshow
            if (!Schema::hasColumn('page_contents', 'hero_media_tipe')) {
                $table->enum('hero_media_tipe', ['none', 'image', 'video', 'youtube', 'slideshow'])
                      ->default('none')
                      ->after('value_2');
            }

            // Path file upload (gambar/video) – bisa multiple untuk slideshow, disimpan JSON
            if (!Schema::hasColumn('page_contents', 'hero_media_files')) {
                $table->text('hero_media_files')->nullable()->after('hero_media_tipe');
                // JSON array: ["path/to/img1.jpg", "path/to/img2.jpg"]
            }

            // Link YouTube untuk embed
            if (!Schema::hasColumn('page_contents', 'hero_media_link')) {
                $table->string('hero_media_link')->nullable()->after('hero_media_files');
            }

            // Interval slideshow dalam milidetik (default 4000 = 4 detik)
            if (!Schema::hasColumn('page_contents', 'hero_slide_interval')) {
                $table->unsignedInteger('hero_slide_interval')->default(4000)->after('hero_media_link');
            }
        });
    }

    public function down(): void
    {
        Schema::table('page_contents', function (Blueprint $table) {
            $cols = ['hero_media_tipe', 'hero_media_files', 'hero_media_link', 'hero_slide_interval'];
            foreach ($cols as $col) {
                if (Schema::hasColumn('page_contents', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};