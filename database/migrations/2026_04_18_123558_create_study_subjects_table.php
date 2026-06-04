<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('study_subjects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();          // e.g. "MTK", "FIS", "BIO"
            $table->integer('credit_hours')->default(2);
            $table->enum('type', ['core', 'elective'])->default('core');
            $table->string('color')->nullable();       // warna di tampilan kalender
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('study_subjects');
    }
};
