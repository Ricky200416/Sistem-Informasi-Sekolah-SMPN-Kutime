<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /*
         * Tabel yang benar adalah absensi_siswas
         */
        Schema::table('absensi_siswas', function (Blueprint $table) {

            /*
             * Tambahkan guru_id jika belum ada.
             */
            if (!Schema::hasColumn('absensi_siswas', 'guru_id')) {
                $table->unsignedBigInteger('guru_id')
                    ->nullable()
                    ->after('siswa_id');

                $table->foreign('guru_id')
                    ->references('id')
                    ->on('gurus')
                    ->nullOnDelete();
            }

            /*
             * Tambahkan mata_pelajaran jika belum ada.
             */
            if (!Schema::hasColumn('absensi_siswas', 'mata_pelajaran')) {
                $table->string('mata_pelajaran')
                    ->nullable()
                    ->after('guru_id');
            }
        });

        /*
         * PENTING:
         *
         * Foreign key siswa_id membutuhkan index pada siswa_id.
         *
         * Saat ini index siswa_id hanya menjadi bagian dari:
         *
         * absensi_siswa_tanggal_unique
         *
         * Jadi sebelum unique lama dihapus,
         * kita harus membuat index siswa_id terlebih dahulu.
         */
        $indexes = Schema::getIndexes('absensi_siswas');

        $siswaIdIndexExists = collect($indexes)->contains(function ($index) {
            return in_array(
                'siswa_id',
                $index['columns'],
                true
            );
        });

        if (!$siswaIdIndexExists) {
            Schema::table('absensi_siswas', function (Blueprint $table) {
                $table->index('siswa_id', 'absensi_siswas_siswa_id_index');
            });
        }

        /*
         * Hapus unique lama:
         *
         * siswa_id + tanggal
         *
         * karena satu siswa sekarang dapat mempunyai
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
         * siswa_id
         * tanggal
         * guru_id
         * mata_pelajaran
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
         * Hapus unique baru.
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
         * Pastikan unique lama dikembalikan.
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

        /*
         * Hapus foreign key guru_id.
         */
        if (Schema::hasColumn('absensi_siswas', 'guru_id')) {
            $indexes = Schema::getForeignKeys('absensi_siswas');

            $guruForeignExists = collect($indexes)->contains(function ($foreign) {
                return $foreign['name'] === 'absensi_siswas_guru_id_foreign';
            });

            if ($guruForeignExists) {
                Schema::table('absensi_siswas', function (Blueprint $table) {
                    $table->dropForeign('absensi_siswas_guru_id_foreign');
                });
            }
        }

        /*
         * Hapus kolom baru.
         */
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
         * Hapus index tambahan siswa_id.
         *
         * Setelah unique lama dikembalikan, index tambahan
         * tidak diperlukan lagi karena unique tersebut sudah
         * menyediakan index untuk siswa_id.
         */
        $indexes = Schema::getIndexes('absensi_siswas');

        $siswaIdIndexExists = collect($indexes)->contains(function ($index) {
            return $index['name'] === 'absensi_siswas_siswa_id_index';
        });

        if ($siswaIdIndexExists) {
            Schema::table('absensi_siswas', function (Blueprint $table) {
                $table->dropIndex('absensi_siswas_siswa_id_index');
            });
        }
    }
};