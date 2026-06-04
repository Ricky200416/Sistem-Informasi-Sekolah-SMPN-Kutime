<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tabel master kelas: SMP 1 A, SMP 1 B, SMP 2 A, SMP 2 B, SMP 3 A, SMP 3 B.
     */
    public function up(): void
    {
        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 50)->unique(); // SMP 1 A, SMP 1 B, dll
            $table->string('tingkat', 10)->nullable(); // 1, 2, 3
            $table->string('rombel', 5)->nullable(); // A, B
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelas');
    }
};
