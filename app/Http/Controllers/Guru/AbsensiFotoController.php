<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\AbsensiGuru;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class AbsensiFotoController extends Controller
{
    public function index()
    {
        $guru   = Auth::user()->guru;
        $guruId = $guru->id;
        $today  = Carbon::today()->toDateString();

        $absensiHariIni = AbsensiGuru::with('kelas')
            ->where('guru_id', $guruId)
            ->whereDate('tanggal', $today)
            ->first();

        $riwayat = AbsensiGuru::with('kelas')
            ->where('guru_id', $guruId)
            ->whereNotNull('foto_masuk')
            ->orderByDesc('tanggal')
            ->limit(14)
            ->get();

        // Kelas yang diampu guru ini, untuk dropdown "Saya Akan Mengajar"
        $kelasList = method_exists($guru, 'kelas')
            ? $guru->kelas()->orderBy('nama')->get()
            : collect();

        return view('guru.absensi-foto.index', compact('absensiHariIni', 'riwayat', 'kelasList'));
    }

    /**
     * Foto sebelum mulai mengajar.
     * Guru hanya boleh mengisi SATU absensi (apapun jenisnya) per hari.
     */
    public function storeMasuk(Request $request)
    {
        $guruId = Auth::user()->guru->id;
        $today  = Carbon::today()->toDateString();

        if ($this->sudahAbsenHariIni($guruId, $today)) {
            return back()->with('error', 'Anda sudah melakukan absensi hari ini. Absensi hanya dapat dilakukan satu kali per hari.');
        }

        $request->validate([
            'foto'     => 'required|image|mimes:jpg,jpeg,png|max:3072',
            'kelas_id' => 'nullable|exists:kelas,id',
        ], [
            'foto.required' => 'Foto wajib diambil sebelum mulai mengajar.',
        ]);

        $path = $request->file('foto')->store('absensi-guru/masuk', 'public');

        try {
            AbsensiGuru::create([
                'guru_id'      => $guruId,
                'kelas_id'     => $request->kelas_id,
                'tanggal'      => $today,
                'status'       => 'P',
                'foto_masuk'   => $path,
                'jam_masuk'    => now()->format('H:i:s'),
                'tipe_absensi' => 'mengajar',
            ]);
        } catch (QueryException $e) {
            // Race condition: dua request masuk bersamaan, DB unique constraint menahan yang kedua.
            return back()->with('error', 'Anda sudah melakukan absensi hari ini. Absensi hanya dapat dilakukan satu kali per hari.');
        }

        return back()->with('success', 'Foto sebelum mengajar berhasil diunggah. Selamat mengajar!');
    }

    /**
     * Foto setelah selesai mengajar. Hanya sekali, hanya jika sudah ada foto masuk.
     */
    public function storePulang(Request $request)
    {
        $guruId = Auth::user()->guru->id;
        $today  = Carbon::today()->toDateString();

        $absensi = AbsensiGuru::where('guru_id', $guruId)
            ->whereDate('tanggal', $today)
            ->first();

        if (!$absensi || $absensi->tipe_absensi !== 'mengajar' || !$absensi->foto_masuk) {
            return back()->with('error', 'Anda harus mengunggah foto sebelum mengajar terlebih dahulu.');
        }

        if ($absensi->foto_pulang) {
            return back()->with('error', 'Anda sudah mengisi foto pulang. Absensi pulang hanya dapat dilakukan satu kali.');
        }

        $request->validate([
            'foto' => 'required|image|mimes:jpg,jpeg,png|max:3072',
        ], [
            'foto.required' => 'Foto wajib diambil setelah selesai mengajar.',
        ]);

        $path = $request->file('foto')->store('absensi-guru/pulang', 'public');

        $absensi->update([
            'foto_pulang' => $path,
            'jam_pulang'  => now()->format('H:i:s'),
        ]);

        return back()->with('success', 'Foto setelah mengajar berhasil diunggah. Terima kasih!');
    }

    /**
     * Absensi kantor (tidak mengajar). Hanya satu foto, hanya sekali per hari.
     */
    public function storeKantor(Request $request)
    {
        $guruId = Auth::user()->guru->id;
        $today  = Carbon::today()->toDateString();

        if ($this->sudahAbsenHariIni($guruId, $today)) {
            return back()->with('error', 'Anda sudah melakukan absensi hari ini. Absensi hanya dapat dilakukan satu kali per hari.');
        }

        $request->validate([
            'foto' => 'required|image|mimes:jpg,jpeg,png|max:3072',
        ], [
            'foto.required' => 'Foto wajib diambil sebagai bukti kehadiran di kantor.',
        ]);

        $path = $request->file('foto')->store('absensi-guru/kantor', 'public');

        try {
            AbsensiGuru::create([
                'guru_id'      => $guruId,
                'tanggal'      => $today,
                'status'       => 'P',
                'foto_masuk'   => $path,
                'jam_masuk'    => now()->format('H:i:s'),
                'tipe_absensi' => 'kantor',
            ]);
        } catch (QueryException $e) {
            return back()->with('error', 'Anda sudah melakukan absensi hari ini. Absensi hanya dapat dilakukan satu kali per hari.');
        }

        return back()->with('success', 'Foto kehadiran di kantor berhasil diunggah.');
    }

    private function sudahAbsenHariIni(int $guruId, string $today): bool
    {
        return AbsensiGuru::where('guru_id', $guruId)
            ->whereDate('tanggal', $today)
            ->exists();
    }
}