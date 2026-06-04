<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Hapus pengumuman yang sudah melewati tanggal selesai (setiap hari jam 01:00)
        $schedule->call(function () {
            \App\Models\Pengumuman::whereNotNull('tanggal_selesai')
                ->where('tanggal_selesai', '<', now())
                ->delete();
        })->daily()->at('01:00')
          ->name('pengumuman:hapus-kadaluarsa')
          ->description('Menghapus pengumuman yang sudah kadaluarsa berdasarkan tanggal_selesai');

        // Contoh command lain yang biasa ditambahkan (opsional)
        // $schedule->command('queue:work --stop-when-empty')->everyMinute();

        // Backup database setiap minggu (contoh)
        // $schedule->command('backup:run')->weeklyOn(1, '02:00');

        // Membersihkan log lama (contoh)
        // $schedule->command('log:clear')->daily();

        // Hapus session lama (jika pakai database session)
        // $schedule->command('session:gc')->daily();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}