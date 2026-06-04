<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('galeris', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->string('foto');                          // path file di storage/public
            $table->string('kategori')->default('kegiatan'); // kegiatan | akademik | prestasi | lainnya
            $table->enum('status', ['aktif', 'draf'])->default('aktif');
            $table->unsignedInteger('urutan')->default(0);   // untuk drag-sort nanti
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('galeris');
    }
};