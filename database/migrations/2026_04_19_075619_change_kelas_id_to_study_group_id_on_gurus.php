<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('gurus', function (Blueprint $table) {
            $table->dropForeign(['kelas_id']);

            $table->renameColumn('kelas_id', 'study_group_id');

            $table->foreign('study_group_id')
                ->references('id')
                ->on('study_groups')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('study_group_id_on_gurus', function (Blueprint $table) {
            //
        });
    }
};
