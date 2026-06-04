<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_contents', function (Blueprint $table) {
            $table->id();
            $table->string('section');          // hero, tentang, visi, misi, fasilitas, sambutan, info_penting
            $table->string('key')->unique();    // hero_title, hero_description, tentang_paragraf1, dll
            $table->text('value')->nullable();  // isi teks
            $table->text('value_2')->nullable(); // untuk paragraf kedua atau tambahan
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_contents');
    }
};