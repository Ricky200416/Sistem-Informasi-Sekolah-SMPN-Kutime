<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->nullable(); // null jika anonymous
            $table->string('foto_path')->nullable(); // null jika tidak upload foto
            $table->text('komentar');
            $table->boolean('is_active')->default(true); // kontrol tampil oleh admin
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};