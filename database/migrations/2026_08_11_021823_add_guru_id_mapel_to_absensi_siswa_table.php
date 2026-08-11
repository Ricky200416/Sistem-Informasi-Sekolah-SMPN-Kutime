<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /*
         * Tabel yang sebenarnya adalah:
         * absensi_siswas
         */

        Schema::table('absensi_siswas', function (Blueprint $table) {

            if (!Schema::hasColumn('absensi_siswas', 'guru_id')) {
                $table->unsignedBigInteger('guru_id')
                    ->nullable()
                    ->after('siswa_id');

                $table->foreign('guru_id')
                    ->references('id')
                    ->on('gurus')
                    ->nullOnDelete();
            }

            if (!Schema::hasColumn('absensi_siswas', 'mata_pelajaran')) {
                $table->string('mata_pelajaran')
                    ->nullable()
                    ->after('guru_id');
            }
        });

        /*
         * Hapus unique lama:
         *
         * siswa_id + tanggal
         *
         * karena sekarang satu siswa dapat memiliki
         * beberapa absensi pada tanggal yang sama
         * untuk guru/mapel yang berbeda.
         */
        $indexes = Schema::getIndexes('absensi_siswas');

        $oldUniqueExists = collect($indexes)->contains(function ($index) {
            return $index['name'] === 'absensi_siswa_tanggal_unique';
        });

        if ($oldUniqueExists) {
            Schema::table('absensi_siswas', function (Blueprint $table) {
                $table->dropUnique('absensi_siswa_tanggal_unique');
            });
        }

        /*
         * Unique baru:
         *
         * 1 siswa
         * + 1 tanggal
         * + 1 guru
         * + 1 mata pelajaran
         *
         * = 1 record absensi.
         */
        $indexes = Schema::getIndexes('absensi_siswas');

        $newUniqueExists = collect($indexes)->contains(function ($index) {
            return $index['name'] === 'absensi_siswas_unique_per_sesi';
        });

        if (!$newUniqueExists) {
            Schema::table('absensi_siswas', function (Blueprint $table) {
                $table->unique(
                    [
                        'siswa_id',
                        'tanggal',
                        'guru_id',
                        'mata_pelajaran'
                    ],
                    'absensi_siswas_unique_per_sesi'
                );
            });
        }
    }

    public function down(): void
    {
        /*
         * Hapus unique baru jika ada.
         */
        $indexes = Schema::getIndexes('absensi_siswas');

        $newUniqueExists = collect($indexes)->contains(function ($index) {
            return $index['name'] === 'absensi_siswas_unique_per_sesi';
        });

        if ($newUniqueExists) {
            Schema::table('absensi_siswas', function (Blueprint $table) {
                $table->dropUnique('absensi_siswas_unique_per_sesi');
            });
        }

        /*
         * Hapus foreign key dan kolom baru.
         */
        if (Schema::hasColumn('absensi_siswas', 'guru_id')) {
            Schema::table('absensi_siswas', function (Blueprint $table) {
                $table->dropForeign(['guru_id']);
            });
        }

        $columnsToDrop = [];

        if (Schema::hasColumn('absensi_siswas', 'guru_id')) {
            $columnsToDrop[] = 'guru_id';
        }

        if (Schema::hasColumn('absensi_siswas', 'mata_pelajaran')) {
            $columnsToDrop[] = 'mata_pelajaran';
        }

        if (!empty($columnsToDrop)) {
            Schema::table('absensi_siswas', function (Blueprint $table) use ($columnsToDrop) {
                $table->dropColumn($columnsToDrop);
            });
        }

        /*
         * Kembalikan unique lama.
         */
        $indexes = Schema::getIndexes('absensi_siswas');

        $oldUniqueExists = collect($indexes)->contains(function ($index) {
            return $index['name'] === 'absensi_siswa_tanggal_unique';
        });

        if (!$oldUniqueExists) {
            Schema::table('absensi_siswas', function (Blueprint $table) {
                $table->unique(
                    ['siswa_id', 'tanggal'],
                    'absensi_siswa_tanggal_unique'
                );
            });
        }
    }
};