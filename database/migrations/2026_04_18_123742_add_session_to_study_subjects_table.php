<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('study_subjects', function (Blueprint $table) {
            $table->enum('session', ['Teori', 'Praktikum'])->default('Teori')->after('color');
        });
    }

    public function down(): void
    {
        Schema::table('study_subjects', function (Blueprint $table) {
            $table->dropColumn('session');
        });
    }
};
