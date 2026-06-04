<?php

namespace Database\Seeders;

use App\Models\Kelas;
use Illuminate\Database\Seeder;

class KelasSeeder extends Seeder
{
    public function run(): void
    {
        $kelas = [
            ['nama' => 'SMP 1 A', 'tingkat' => '1', 'rombel' => 'A'],
            ['nama' => 'SMP 1 B', 'tingkat' => '1', 'rombel' => 'B'],
            ['nama' => 'SMP 2 A', 'tingkat' => '2', 'rombel' => 'A'],
            ['nama' => 'SMP 2 B', 'tingkat' => '2', 'rombel' => 'B'],
            ['nama' => 'SMP 3 A', 'tingkat' => '3', 'rombel' => 'A'],
            ['nama' => 'SMP 3 B', 'tingkat' => '3', 'rombel' => 'B'],
        ];

        foreach ($kelas as $k) {
            Kelas::firstOrCreate(['nama' => $k['nama']], $k);
        }
    }
}
