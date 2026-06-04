<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('role', 20)->index();          // guru | siswa | admin
            $table->string('action', 100);                // login | logout | view_nilai | submit_absensi …
            $table->string('module', 80)->nullable();     // Dashboard | Absensi | Nilai …
            $table->text('description')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 255)->nullable();
            $table->json('extra')->nullable();            // data tambahan bebas
            $table->timestamp('created_at')->useCurrent()->index();
            // Tidak perlu updated_at — log tidak pernah diedit
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};