<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('study_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');                    // e.g. "1A", "2B"
            $table->integer('grade');                  // 1, 2, 3
            $table->string('section');                 // A, B, C, D
            $table->integer('capacity')->default(30);
            $table->foreignId('homeroom_teacher_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->string('room')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('study_groups');
    }
};
