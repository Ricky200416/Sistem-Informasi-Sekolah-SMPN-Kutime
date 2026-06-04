<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('beritas', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('slug')->unique();
            $table->text('ringkasan')->nullable();
            $table->longText('isi');
            $table->string('foto')->nullable();           // path foto utama
            $table->string('kategori')->default('berita'); // berita | pengumuman
            $table->boolean('is_penting')->default(false); // untuk pengumuman penting
            $table->enum('status', ['aktif', 'draf'])->default('draf');
            $table->timestamp('tanggal_publish')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('beritas');
    }
};