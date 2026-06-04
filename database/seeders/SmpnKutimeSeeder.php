<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SmpnKutimeSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(KelasSeeder::class);

        $admin = User::firstOrCreate(
            ['email' => 'admin@smpnkutime.sch.id'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );
        if (!$admin->admin) {
            Admin::create(['user_id' => $admin->id]);
        }

        $guru = User::firstOrCreate(
            ['email' => 'guru@smpnkutime.sch.id'],
            [
                'name' => 'Budi Santoso',
                'password' => Hash::make('password'),
                'role' => 'guru',
            ]
        );
        $kelas1 = Kelas::where('nama', 'SMP 1 A')->first();
        if (!$guru->guru) {
            Guru::create([
                'user_id' => $guru->id,
                'nip' => '198012152010011001',
                'kelas_id' => $kelas1?->id,
            ]);
        }

        $siswa = User::firstOrCreate(
            ['email' => 'siswa@smpnkutime.sch.id'],
            [
                'name' => 'Ani Wijaya',
                'password' => Hash::make('password'),
                'role' => 'siswa',
            ]
        );
        if (!$siswa->siswa) {
            Siswa::create([
                'user_id' => $siswa->id,
                'nidn' => '2024001',
                'kelas_id' => $kelas1?->id,
            ]);
        }
    }
}
