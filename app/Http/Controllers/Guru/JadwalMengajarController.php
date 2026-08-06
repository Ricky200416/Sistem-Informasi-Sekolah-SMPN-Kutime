<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\AbsensiGuru;
use App\Models\Timetable;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;

class AbsensiFotoController extends Controller
{
    /**
     * Peta nomor hari Carbon (0=Minggu..6=Sabtu) ke label hari yang
     * dipakai pada kolom `day_of_week` tabel timetables, konsisten
     * dengan $hariList di halaman Jadwal Mengajar.
     */
    private array $mapHari = [
        0 => 'Minggu',
        1 => 'Senin',
        2 => 'Selasa',
        3 => 'Rabu',
        4 => 'Kamis',
        5 => 'Jumat',
        6 => 'Sabtu',
    ];

    public function index()
    {
        $guru   = Auth::user()->guru;
        $guruId = $guru->id;          // dipakai untuk tabel absensi_gurus (relasi ke gurus)
        $userId = Auth::id();         // dipakai untuk tabel timetables (kolom teacher_id = users.id)

        $today       = Carbon::today();
        $hariIniNama = $this->mapHari[$today->dayOfWeek];

        // Absensi hari ini (kalau sudah ada), lengkap dengan data jadwal
        // (mapel & kelas) yang dipakai saat absen masuk.
        $absensiHariIni = AbsensiGuru::with(['timetable.studySubject', 'timetable.studyGroup'])
            ->where('guru_id', $guruId)
            ->whereDate('tanggal', $today)
            ->first();

        // Riwayat 14 absensi foto terakhir.
        $riwayat = AbsensiGuru::with(['timetable.studySubject', 'timetable.studyGroup'])
            ->where('guru_id', $guruId)
            ->whereNotNull('foto_masuk')
            ->orderByDesc('tanggal')
            ->limit(14)
            ->get();

        // Jadwal mengajar milik guru ini yang jatuh pada HARI INI saja,
        // diurutkan dari jam paling pagi. Inilah yang menjadi pilihan
        // pada opsi "Saya Akan Mengajar".
        // PENTING: kolom pemilik jadwal adalah `teacher_id`, diisi dengan
        // Auth::id() (id dari tabel users), BUKAN id dari tabel gurus.
        $jadwalHariIni = Timetable::with(['studySubject', 'studyGroup'])
            ->where('teacher_id', $userId)
            ->where('is_active', true)
            ->where('day_of_week', $hariIniNama)
            ->orderBy('start_time')
            ->get();

        return view('guru.absensi-foto.index', compact(
            'absensiHariIni',
            'riwayat',
            'jadwalHariIni',
            'hariIniNama'
        ));
    }

    /**
     * Absen masuk untuk sesi MENGAJAR — wajib memilih salah satu jadwal
     * miliknya yang berlaku hari ini. Hanya boleh sekali per hari.
     */
    public function storeMasuk(Request $request)
    {
        $guruId = Auth::user()->guru->id;
        $userId = Auth::id();

        $today       = Carbon::today();
        $hariIniNama = $this->mapHari[$today->dayOfWeek];

        if ($this->sudahAbsenHariIni($guruId, $today->toDateString())) {
            return back()->with('error', 'Anda sudah melakukan absensi hari ini. Absensi hanya dapat dilakukan satu kali per hari.');
        }

        $request->validate([
            'foto'         => 'required|image|mimes:jpg,jpeg,png|max:3072',
            'timetable_id' => 'required|integer|exists:timetables,id',
        ], [
            'foto.required'         => 'Foto wajib diambil sebelum mulai mengajar.',
            'timetable_id.required' => 'Pilih jadwal kelas yang akan Anda ajar.',
            'timetable_id.exists'   => 'Jadwal yang dipilih tidak ditemukan.',
        ]);

        // Pastikan jadwal yang dipilih benar-benar milik guru ini (via
        // teacher_id = users.id) DAN memang berjadwal untuk hari ini juga —
        // mencegah guru memilih jadwal orang lain atau jadwal hari lain
        // lewat manipulasi form/DevTools.
        $jadwal = Timetable::where('id', $request->timetable_id)
            ->where('teacher_id', $userId)
            ->where('is_active', true)
            ->where('day_of_week', $hariIniNama)
            ->first();

        if (!$jadwal) {
            return back()->with('error', 'Jadwal yang dipilih tidak valid atau bukan jadwal Anda hari ini.');
        }

        $path = $request->file('foto')->store('absensi-guru/masuk', 'public');

        try {
            AbsensiGuru::create([
                'guru_id'      => $guruId,
                'timetable_id' => $jadwal->id,
                'tanggal'      => $today->toDateString(),
                'status'       => AbsensiGuru::STATUS_HADIR,
                'foto_masuk'   => $path,
                'jam_masuk'    => now()->format('H:i:s'),
                'tipe_absensi' => AbsensiGuru::TIPE_MENGAJAR,
            ]);
        } catch (QueryException $e) {
            // Race condition: dua request masuk hampir bersamaan,
            // unique index (guru_id, tanggal) menahan yang kedua.
            return back()->with('error', 'Anda sudah melakukan absensi hari ini. Absensi hanya dapat dilakukan satu kali per hari.');
        }

        $namaKelas = $jadwal->studyGroup->name ?? '-';
        $namaMapel = $jadwal->studySubject->name ?? '-';

        return back()->with('success', "Foto sebelum mengajar {$namaMapel} di kelas {$namaKelas} berhasil diunggah. Selamat mengajar!");
    }

    /**
     * Foto setelah selesai mengajar. Hanya sekali, hanya jika sudah ada
     * foto masuk dengan tipe 'mengajar'.
     */
    public function storePulang(Request $request)
    {
        $guruId = Auth::user()->guru->id;
        $today  = Carbon::today()->toDateString();

        $absensi = AbsensiGuru::where('guru_id', $guruId)
            ->whereDate('tanggal', $today)
            ->first();

        if (!$absensi || $absensi->tipe_absensi !== AbsensiGuru::TIPE_MENGAJAR || !$absensi->foto_masuk) {
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
     * Absensi KANTOR (tidak mengajar) — tidak terikat jadwal apapun.
     * Bisa dipakai guru piket, rapat, atau saat tidak ada jadwal
     * mengajar hari itu. Hanya satu foto, hanya sekali per hari.
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
                'timetable_id' => null,
                'tanggal'      => $today,
                'status'       => AbsensiGuru::STATUS_HADIR,
                'foto_masuk'   => $path,
                'jam_masuk'    => now()->format('H:i:s'),
                'tipe_absensi' => AbsensiGuru::TIPE_KANTOR,
            ]);
        } catch (QueryException $e) {
            return back()->with('error', 'Anda sudah melakukan absensi hari ini. Absensi hanya dapat dilakukan satu kali per hari.');
        }

        return back()->with('success', 'Foto kehadiran di kantor berhasil diunggah.');
    }

    private function sudahAbsenHariIni(int $guruId, string $today): bool
    {
        return AbsensiGuru::sudahAbsen($guruId, $today);
    }
}