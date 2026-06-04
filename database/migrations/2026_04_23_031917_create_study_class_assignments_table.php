<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('study_class_assignments', function (Blueprint $table) {
            $table->id();

            // Relasi ke users (guru)
            $table->foreignId('teacher_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            // Relasi ke study_groups (kelas/mapel)
            $table->foreignId('study_group_id')
                  ->constrained('study_groups')
                  ->cascadeOnDelete();

            $table->timestamps();

            // Optional: biar tidak ada duplikasi relasi
            $table->unique(['teacher_id', 'study_group_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('study_class_assignments');
    }
};