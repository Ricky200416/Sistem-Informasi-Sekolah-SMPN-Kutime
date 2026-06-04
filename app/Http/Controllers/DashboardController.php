<?php

namespace App\Http\Controllers;

use App\Models\Pengumuman;


class DashboardController extends Controller
{
    /**
     * Dashboard Admin
     * Menampilkan pengumuman untuk semua audience.
     */
    public function adminDashboard()
    {
        $widgetPengumuman = Pengumuman::where('is_active', 1)
            ->where('show_di_dashboard', 1)
            ->whereIn('target_audience', ['guru', 'siswa', 'semua'])
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('widgetPengumuman'));
    }

    /**
     * Dashboard Guru
     * Menampilkan pengumuman untuk guru dan semua.
     */
    public function guruDashboard()
    {
        $widgetPengumuman = Pengumuman::where('is_active', 1)
            ->where('show_di_dashboard', 1)
            ->whereIn('target_audience', ['guru', 'semua'])
            ->latest()
            ->limit(4)
            ->get();

        return view('guru.dashboard', compact('widgetPengumuman'));
    }

    /**
     * Dashboard Siswa
     * Menampilkan pengumuman untuk siswa dan semua.
     */
    public function siswaDashboard()
    {
        $widgetPengumuman = Pengumuman::where('is_active', 1)
            ->where('show_di_dashboard', 1)
            ->whereIn('target_audience', ['siswa', 'semua'])
            ->latest()
            ->limit(4)
            ->get();

        return view('siswa.dashboard', compact('widgetPengumuman'));
    }
}