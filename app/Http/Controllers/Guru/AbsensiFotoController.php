<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\AbsensiGuru;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AbsensiFotoController extends Controller
{
    public function index()
    {
        $guruId = Auth::user()->guru->id;
        $today  = Carbon::today()->toDateString();

        $absensiHariIni = AbsensiGuru::where('guru_id', $guruId)
            ->whereDate('tanggal', $today)
            ->first();

        $riwayat = AbsensiGuru::where('guru_id', $guruId)
            ->whereNotNull('foto_masuk')
            ->orderByDesc('tanggal')
            ->limit(14)
            ->get();

        return view('guru.absensi-foto.index', compact('absensiHariIni', 'riwayat'));
    }

    public function storeMasuk(Request $request)
    {
        $request->validate([
            'foto' => 'required|image|mimes:jpg,jpeg,png|max:3072',
        ], [
            'foto.required' => 'Foto wajib diambil sebelum mulai mengajar.',
        ]);

        $guruId = Auth::user()->guru->id;
        $today  = Carbon::today()->toDateString();

        $path = $request->file('foto')->store('absensi-guru/masuk', 'public');

        AbsensiGuru::updateOrCreate(
            ['guru_id' => $guruId, 'tanggal' => $today],
            [
                'status'       => 'P',
                'foto_masuk'   => $path,
                'jam_masuk'    => now()->format('H:i:s'),
                'tipe_absensi' => 'mengajar',
            ]
        );

        return back()->with('success', 'Foto sebelum mengajar berhasil diunggah. Selamat mengajar!');
    }

    public function storePulang(Request $request)
    {
        $request->validate([
            'foto' => 'required|image|mimes:jpg,jpeg,png|max:3072',
        ], [
            'foto.required' => 'Foto wajib diambil setelah selesai mengajar.',
        ]);

        $guruId = Auth::user()->guru->id;
        $today  = Carbon::today()->toDateString();

        $absensi = AbsensiGuru::where('guru_id', $guruId)
            ->whereDate('tanggal', $today)
            ->first();

        if (!$absensi || !$absensi->foto_masuk) {
            return back()->with('error', 'Anda harus mengunggah foto sebelum mengajar terlebih dahulu.');
        }

        $path = $request->file('foto')->store('absensi-guru/pulang', 'public');

        $absensi->update([
            'foto_pulang' => $path,
            'jam_pulang'  => now()->format('H:i:s'),
        ]);

        return back()->with('success', 'Foto setelah mengajar berhasil diunggah. Terima kasih!');
    }

    public function storeKantor(Request $request)
    {
        $request->validate([
            'foto' => 'required|image|mimes:jpg,jpeg,png|max:3072',
        ], [
            'foto.required' => 'Foto wajib diambil sebagai bukti kehadiran di kantor.',
        ]);

        $guruId = Auth::user()->guru->id;
        $today  = Carbon::today()->toDateString();

        $path = $request->file('foto')->store('absensi-guru/kantor', 'public');

        AbsensiGuru::updateOrCreate(
            ['guru_id' => $guruId, 'tanggal' => $today],
            [
                'status'       => 'P',
                'foto_masuk'   => $path,
                'jam_masuk'    => now()->format('H:i:s'),
                'tipe_absensi' => 'kantor',
            ]
        );

        return back()->with('success', 'Foto kehadiran di kantor berhasil diunggah.');
    }
}