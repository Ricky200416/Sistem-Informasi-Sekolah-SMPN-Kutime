@extends('layouts.app')
@section('title', 'Absensi Saya')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div id="absensiApp" class="space-y-4"
     data-sudah-absen-masuk="{{ $absensiHariIni ? 'true' : 'false' }}"
     data-sudah-absen-pulang="{{ ($absensiHariIni && $absensiHariIni->foto_pulang) ? 'true' : 'false' }}"
     data-tipe-absen="{{ $absensiHariIni->tipe_absensi ?? '' }}"
     data-sekolah-lat="{{ $lokasiSekolah['latitude'] }}"
     data-sekolah-lng="{{ $lokasiSekolah['longitude'] }}"
     data-sekolah-radius="{{ $lokasiSekolah['radius'] }}"
     data-url-masuk="{{ route('guru.absensi-foto.masuk') }}"
     data-url-pulang="{{ route('guru.absensi-foto.pulang') }}">

    {{-- ══════════════ HALAMAN 0: ABSENSI SAYA (default) ══════════════ --}}
    <div id="stepHome" class="wizard-step">
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm p-4">
            <div class="flex items-center justify-between mb-1">
                <h2 class="text-sm font-bold text-slate-800 dark:text-slate-100">Absensi Saya</h2>
                <span class="text-[10px] text-slate-400" id="jamSekarang"></span>
            </div>
            <p class="text-xs text-slate-400 dark:text-slate-500 mb-3">
                {{ $hariIniNama }}, {{ now()->translatedFormat('d F Y') }}
            </p>

            @if(session('success'))
            <div class="flex items-center gap-2 px-3.5 py-2.5 mb-3 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-100 dark:border-emerald-800">
                <i class="bi bi-check-circle-fill text-emerald-600"></i>
                <p class="text-[11px] text-emerald-700 dark:text-emerald-300 font-medium">{{ session('success') }}</p>
            </div>
            @endif

            {{-- STATUS CARD --}}
            @if(!$absensiHariIni)
                <div class="rounded-xl border border-slate-200 dark:border-slate-700 p-4 text-center mb-4">
                    <span class="inline-block w-3 h-3 rounded-full bg-slate-300 mb-2"></span>
                    <p class="text-xs font-bold text-slate-500 mb-3">BELUM ABSEN</p>
                    <button type="button" id="btnMulaiAbsenMasuk"
                            class="px-5 py-2.5 bg-indigo-600 text-white text-xs font-bold rounded-xl hover:bg-indigo-700 transition">
                        <i class="bi bi-geo-alt-fill me-1"></i> ABSEN MASUK
                    </button>
                </div>
            @elseif($absensiHariIni->tipe_absensi === 'mengajar' && !$absensiHariIni->foto_pulang)
                <div class="rounded-xl border border-emerald-200 dark:border-emerald-800 bg-emerald-50 dark:bg-emerald-900/10 p-4 mb-4">
                    <p class="text-xs font-bold text-emerald-700 dark:text-emerald-300 mb-2">
                        <i class="bi bi-check-circle-fill"></i> SUDAH ABSEN MASUK
                    </p>
                    <p class="text-[11px] text-slate-600 dark:text-slate-300">Jam Masuk : <strong>{{ substr($absensiHariIni->jam_masuk,0,5) }} WIB</strong></p>
                    <p class="text-[11px] text-slate-600 dark:text-slate-300">Status : <strong>{{ $absensiHariIni->status === 'L' ? 'TERLAMBAT' : 'HADIR' }}</strong></p>
                    <p class="text-[11px] text-slate-600 dark:text-slate-300 mb-3">Lokasi : <strong class="text-emerald-600">Valid ✓ ({{ $absensiHariIni->jarak_masuk }} m)</strong></p>
                    <button type="button" id="btnMulaiAbsenPulang"
                            class="w-full px-5 py-2.5 bg-amber-500 text-white text-xs font-bold rounded-xl hover:bg-amber-600 transition">
                        <i class="bi bi-geo-alt-fill me-1"></i> ABSEN PULANG
                    </button>
                </div>
            @else
                <div class="rounded-xl border border-emerald-200 dark:border-emerald-800 bg-emerald-50 dark:bg-emerald-900/10 p-4 mb-4">
                    <p class="text-xs font-bold text-emerald-700 dark:text-emerald-300 mb-2">
                        <i class="bi bi-check-circle-fill"></i> ABSENSI SELESAI
                    </p>
                    <p class="text-[11px] text-slate-600 dark:text-slate-300">Jam Masuk  : <strong>{{ substr($absensiHariIni->jam_masuk,0,5) }} WIB</strong></p>
                    @if($absensiHariIni->jam_pulang)
                    <p class="text-[11px] text-slate-600 dark:text-slate-300">Jam Pulang : <strong>{{ substr($absensiHariIni->jam_pulang,0,5) }} WIB</strong></p>
                    @endif
                    <p class="text-[11px] text-slate-600 dark:text-slate-300">Status     : <strong>{{ $absensiHariIni->status === 'L' ? 'TERLAMBAT' : 'HADIR' }}</strong></p>
                </div>
            @endif

            {{-- JADWAL HARI INI --}}
            @if($jadwalHariIni->count())
            <div>
                <p class="text-[11px] font-bold text-slate-600 dark:text-slate-300 mb-2">Jadwal Mengajar Hari Ini</p>
                <div class="divide-y divide-slate-100 dark:divide-slate-700/40 border border-slate-100 dark:border-slate-700 rounded-xl overflow-hidden">
                    @foreach($jadwalHariIni as $jadwal)
                    <div class="flex items-center gap-2.5 px-3 py-2">
                        <div class="w-1 h-8 rounded-full shrink-0" style="background: {{ $jadwal->studySubject->color ?? '#6366f1' }}"></div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold text-slate-700 dark:text-slate-200 truncate">{{ $jadwal->studySubject->name ?? '—' }}</p>
                            <p class="text-[10px] text-slate-400">
                                {{ substr($jadwal->start_time,0,5) }}–{{ substr($jadwal->end_time,0,5) }} · {{ $jadwal->studyGroup->name ?? '—' }}
                            </p>
                        </div>
                        @if($absensiHariIni && $absensiHariIni->timetable_id === $jadwal->id)
                            <span class="text-[9.5px] font-semibold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700">Sudah Absen</span>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <button type="button" id="btnLihatRiwayat" class="mt-3 w-full text-[11px] text-indigo-600 dark:text-indigo-400 font-semibold py-1.5">
                Riwayat Absensi <i class="bi bi-chevron-down"></i>
            </button>
        </div>

        {{-- RIWAYAT --}}
        <div id="blokRiwayat" class="hidden mt-4 bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
            <div class="px-3.5 py-3 border-b border-slate-100 dark:border-slate-700">
                <h3 class="text-xs font-bold text-slate-700 dark:text-slate-200">Riwayat Absensi</h3>
            </div>
            <div class="divide-y divide-slate-50 dark:divide-slate-700/30">
                @forelse($riwayat as $r)
                    <div class="flex items-center gap-3 px-3.5 py-2.5">
                        <img src="{{ Storage::url($r->foto_masuk) }}" class="w-10 h-10 rounded-lg object-cover border border-slate-200 dark:border-slate-700">
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold text-slate-700 dark:text-slate-200">{{ \Carbon\Carbon::parse($r->tanggal)->translatedFormat('d M Y') }}</p>
                            <p class="text-[10px] text-slate-400">
                                {{ $r->tipe_absensi === 'kantor' ? 'Kantor' : 'Mengajar' }}
                                · Masuk {{ substr($r->jam_masuk,0,5) }}
                                @if($r->jam_pulang) · Pulang {{ substr($r->jam_pulang,0,5) }} @endif
                                @if($r->jarak_masuk !== null) · {{ $r->jarak_masuk }}m @endif
                            </p>
                        </div>
                        <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full {{ $r->status === 'L' ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700' }}">
                            {{ $r->status === 'L' ? 'Terlambat' : 'Hadir' }}
                        </span>
                    </div>
                @empty
                    <div class="text-center py-8"><p class="text-slate-400 text-xs">Belum ada riwayat absensi.</p></div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ══════════════ HALAMAN 0.5: PILIH MODE (mengajar/kantor) ══════════════ --}}
    <div id="stepPilihMode" class="wizard-step hidden">
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm p-4">
            <h3 class="text-xs font-bold text-slate-700 dark:text-slate-200 mb-3">Pilih Jenis Kehadiran</h3>

            @if($jadwalHariIni->count())
            <div class="mb-3">
                <p class="text-[11px] font-semibold text-slate-600 dark:text-slate-300 mb-1.5">
                    <i class="bi bi-easel2-fill text-indigo-500"></i> Saya Akan Mengajar
                </p>
                <div class="space-y-1.5 max-h-40 overflow-y-auto pr-1">
                    @foreach($jadwalHariIni as $jadwal)
                    <label class="flex items-center gap-2 px-2.5 py-2 rounded-lg border border-slate-200 dark:border-slate-700 cursor-pointer has-[:checked]:bg-indigo-50 has-[:checked]:border-indigo-400">
                        <input type="radio" name="pilihJadwal" value="{{ $jadwal->id }}" class="radioJadwal w-3.5 h-3.5 text-indigo-600">
                        <div class="flex-1 min-w-0">
                            <p class="text-[11px] font-semibold text-slate-700 dark:text-slate-200 truncate">{{ $jadwal->studySubject->name ?? '—' }} <span class="text-slate-400 font-normal">· {{ $jadwal->studyGroup->name ?? '—' }}</span></p>
                            <p class="text-[9.5px] text-slate-400">{{ substr($jadwal->start_time,0,5) }}–{{ substr($jadwal->end_time,0,5) }}</p>
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>
            @endif

            <button type="button" id="btnLanjutMengajar" class="w-full mb-2 py-2.5 bg-indigo-600 text-white text-xs font-bold rounded-xl hover:bg-indigo-700 transition disabled:opacity-40" disabled>
                Lanjut sebagai Mengajar
            </button>

            <div class="text-center text-[10px] text-slate-400 my-2">— atau —</div>

            <button type="button" id="btnLanjutKantor" class="w-full py-2.5 bg-slate-700 text-white text-xs font-bold rounded-xl hover:bg-slate-800 transition">
                <i class="bi bi-building-fill me-1"></i> Saya di Kantor (Tidak Mengajar)
            </button>

            <button type="button" class="btnBatalWizard w-full mt-3 py-2 text-[11px] text-slate-400 font-semibold">Batal</button>
        </div>
    </div>

    {{-- ══════════════ HALAMAN 1: CEK LOKASI ══════════════ --}}
    <div id="stepLokasi" class="wizard-step hidden">
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm p-6 text-center">
            <div id="lokasiLoading">
                <div class="text-4xl mb-3">📍</div>
                <p class="text-xs font-semibold text-slate-600 dark:text-slate-300">Mencari lokasi Anda...</p>
                <p class="text-[10px] text-slate-400 mt-1">Mohon tunggu sebentar</p>
            </div>

            <div id="lokasiValid" class="hidden">
                <div class="text-3xl text-emerald-500 mb-2"><i class="bi bi-check-circle-fill"></i></div>
                <p class="text-xs font-bold text-emerald-600 mb-3">LOKASI TERDETEKSI</p>
                <div class="text-left text-[11px] text-slate-600 dark:text-slate-300 space-y-1 mb-3 bg-slate-50 dark:bg-slate-900/30 rounded-lg p-3">
                    <p>Jarak dari sekolah : <strong id="txtJarakValid">-</strong> meter</p>
                    <p>Batas maksimal : <strong id="txtRadiusValid">-</strong> meter</p>
                </div>
                <p class="text-[11px] text-emerald-600 font-semibold mb-3"><i class="bi bi-check2"></i> Anda berada di area sekolah</p>
                <button type="button" id="btnLanjutFoto" class="w-full py-2.5 bg-indigo-600 text-white text-xs font-bold rounded-xl hover:bg-indigo-700">LANJUTKAN</button>
            </div>

            <div id="lokasiInvalid" class="hidden">
                <div class="text-3xl text-rose-500 mb-2"><i class="bi bi-x-circle-fill"></i></div>
                <p class="text-xs font-bold text-rose-600 mb-3">LOKASI TIDAK VALID</p>
                <div class="text-left text-[11px] text-slate-600 dark:text-slate-300 space-y-1 mb-3 bg-slate-50 dark:bg-slate-900/30 rounded-lg p-3">
                    <p>Jarak dari sekolah : <strong id="txtJarakInvalid">-</strong> meter</p>
                    <p>Batas maksimal : <strong id="txtRadiusInvalid">-</strong> meter</p>
                </div>
                <p class="text-[11px] text-rose-600 mb-3">Anda berada di luar area sekolah.</p>
                <button type="button" class="btnUlangiLokasi w-full py-2.5 bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs font-bold rounded-xl">COBA LAGI</button>
                <button type="button" class="btnBatalWizard w-full mt-2 py-2 text-[11px] text-slate-400 font-semibold">Kembali</button>
            </div>

            <div id="lokasiError" class="hidden">
                <div class="text-3xl text-amber-500 mb-2"><i class="bi bi-geo-alt-fill"></i></div>
                <p class="text-xs font-bold text-amber-600 mb-2">LOKASI TIDAK TERSEDIA</p>
                <p class="text-[11px] text-slate-500 mb-3">Sistem tidak dapat mendapatkan lokasi Anda. Silakan:</p>
                <ul class="text-left text-[11px] text-slate-500 mb-3 space-y-1">
                    <li>1. Aktifkan GPS / lokasi</li>
                    <li>2. Izinkan browser mengakses lokasi</li>
                    <li>3. Pastikan koneksi internet aktif</li>
                </ul>
                <button type="button" class="btnUlangiLokasi w-full py-2.5 bg-indigo-600 text-white text-xs font-bold rounded-xl">COBA LAGI</button>
                <button type="button" class="btnBatalWizard w-full mt-2 py-2 text-[11px] text-slate-400 font-semibold">Batal</button>
            </div>
        </div>
    </div>

    {{-- ══════════════ HALAMAN 2: FOTO ABSENSI ══════════════ --}}
    <div id="stepFoto" class="wizard-step hidden">
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm p-4 text-center">
            <h3 class="text-xs font-bold text-slate-700 dark:text-slate-200 mb-3">Foto Absensi</h3>

            <div id="cameraWrap" class="relative w-full max-w-xs mx-auto rounded-xl overflow-hidden bg-black mb-3" style="aspect-ratio: 3/4;">
                <video id="videoCamera" autoplay playsinline muted class="w-full h-full object-cover"></video>
                <div id="cameraError" class="hidden absolute inset-0 flex items-center justify-center bg-slate-800 text-white text-[11px] p-4 text-center"></div>
            </div>
            <canvas id="canvasFoto" class="hidden"></canvas>

            <p class="text-[10px] text-slate-400 mb-3">Pastikan wajah terlihat jelas</p>

            <button type="button" id="btnAmbilFoto" class="w-full py-2.5 bg-indigo-600 text-white text-xs font-bold rounded-xl hover:bg-indigo-700">
                <i class="bi bi-camera-fill me-1"></i> AMBIL FOTO
            </button>

            <div class="mt-3">
                <label class="block text-[10px] text-slate-400 mb-1">Kamera tidak berfungsi? Unggah foto manual:</label>
                <input type="file" id="inputFotoManual" accept="image/*" capture="user" class="w-full text-[10px] rounded-lg border-slate-200 dark:border-slate-700">
            </div>

            <button type="button" class="btnBatalWizard w-full mt-3 py-2 text-[11px] text-slate-400 font-semibold">Batal</button>
        </div>
    </div>

    {{-- ══════════════ HALAMAN 3: KONFIRMASI FOTO ══════════════ --}}
    <div id="stepKonfirmasiFoto" class="wizard-step hidden">
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm p-4 text-center">
            <h3 class="text-xs font-bold text-slate-700 dark:text-slate-200 mb-3">Konfirmasi Foto</h3>
            <img id="previewFoto" src="" class="w-full max-w-xs mx-auto rounded-xl border border-slate-200 dark:border-slate-700 mb-3" style="aspect-ratio: 3/4; object-fit: cover;">
            <p class="text-[11px] text-slate-500 mb-3">Apakah foto sudah jelas?</p>
            <div class="flex gap-2">
                <button type="button" id="btnUlangiFoto" class="flex-1 py-2.5 bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs font-bold rounded-xl">ULANGI FOTO</button>
                <button type="button" id="btnGunakanFoto" class="flex-1 py-2.5 bg-indigo-600 text-white text-xs font-bold rounded-xl">GUNAKAN FOTO</button>
            </div>
        </div>
    </div>

    {{-- ══════════════ HALAMAN 4: KONFIRMASI ABSENSI ══════════════ --}}
    <div id="stepKonfirmasiAbsensi" class="wizard-step hidden">
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm p-4">
            <h3 class="text-xs font-bold text-slate-700 dark:text-slate-200 mb-3">Konfirmasi Absensi</h3>
            <div class="text-[11px] text-slate-600 dark:text-slate-300 space-y-1.5 mb-4 bg-slate-50 dark:bg-slate-900/30 rounded-lg p-3">
                <p>Nama : <strong>{{ Auth::user()->name }}</strong></p>
                <p>Tanggal : <strong>{{ now()->translatedFormat('d F Y') }}</strong></p>
                <p>Jam : <strong id="ringkasJam">-</strong> WIB</p>
                <p>Jenis : <strong id="ringkasJenis">-</strong></p>
                <p>Lokasi : <strong class="text-emerald-600">✓ Dalam area sekolah (<span id="ringkasJarak">-</span> m)</strong></p>
                <p>Foto : <strong class="text-emerald-600">✓ Tersedia</strong></p>
            </div>
            <div id="konfirmasiError" class="hidden text-[11px] text-rose-600 bg-rose-50 dark:bg-rose-900/20 border border-rose-100 dark:border-rose-800 rounded-lg p-2.5 mb-3"></div>
            <div class="flex gap-2">
                <button type="button" class="btnBatalWizard flex-1 py-2.5 bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs font-bold rounded-xl">BATAL</button>
                <button type="button" id="btnKirimAbsensi" class="flex-1 py-2.5 bg-indigo-600 text-white text-xs font-bold rounded-xl">
                    <span id="btnKirimText">KIRIM ABSENSI</span>
                </button>
            </div>
        </div>
    </div>

    {{-- ══════════════ HALAMAN 5: BERHASIL ══════════════ --}}
    <div id="stepBerhasil" class="wizard-step hidden">
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm p-6 text-center">
            <div class="text-4xl text-emerald-500 mb-2"><i class="bi bi-check-circle-fill"></i></div>
            <p class="text-sm font-bold text-emerald-600 mb-1">ABSENSI BERHASIL</p>
            <p class="text-[11px] text-slate-500 mb-4" id="txtBerhasilPesan">Absensi Anda telah tersimpan.</p>
            <button type="button" id="btnSelesai" class="w-full py-2.5 bg-indigo-600 text-white text-xs font-bold rounded-xl">SELESAI</button>
        </div>
    </div>

</div>

<script>
(function () {
    const app = document.getElementById('absensiApp');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    const steps = {
        home: document.getElementById('stepHome'),
        pilihMode: document.getElementById('stepPilihMode'),
        lokasi: document.getElementById('stepLokasi'),
        foto: document.getElementById('stepFoto'),
        konfirmasiFoto: document.getElementById('stepKonfirmasiFoto'),
        konfirmasiAbsensi: document.getElementById('stepKonfirmasiAbsensi'),
        berhasil: document.getElementById('stepBerhasil'),
    };

    const sekolahLat    = parseFloat(app.dataset.sekolahLat) || null;
    const sekolahLng     = parseFloat(app.dataset.sekolahLng) || null;
    const sekolahRadius  = parseInt(app.dataset.sekolahRadius) || 100;
    const urlMasuk       = app.dataset.urlMasuk;
    const urlPulang      = app.dataset.urlPulang;

    // State absensi yang sedang berjalan
    let state = {
        aksi: null,        // 'masuk' | 'pulang'
        tipe: null,        // 'mengajar' | 'kantor'
        timetableId: null,
        latitude: null,
        longitude: null,
        jarak: null,
        fotoBlob: null,
        stream: null,
    };

    function showStep(name) {
        Object.values(steps).forEach(el => el.classList.add('hidden'));
        steps[name].classList.remove('hidden');
    }

    function resetState() {
        state = { aksi: null, tipe: null, timetableId: null, latitude: null, longitude: null, jarak: null, fotoBlob: null, stream: null };
        stopCamera();
    }

    // ── Jam berjalan ──
    function updateJam() {
        const el = document.getElementById('jamSekarang');
        if (el) el.textContent = 'Jam sekarang: ' + new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }) + ' WIB';
    }
    updateJam();
    setInterval(updateJam, 30000);

    // ── Riwayat toggle ──
    const btnRiwayat = document.getElementById('btnLihatRiwayat');
    if (btnRiwayat) {
        btnRiwayat.addEventListener('click', () => {
            document.getElementById('blokRiwayat').classList.toggle('hidden');
        });
    }

    // ── Mulai Absen Masuk ──
    const btnMulaiMasuk = document.getElementById('btnMulaiAbsenMasuk');
    if (btnMulaiMasuk) {
        btnMulaiMasuk.addEventListener('click', () => {
            resetState();
            state.aksi = 'masuk';
            const punyaJadwal = document.querySelectorAll('.radioJadwal').length > 0;
            if (punyaJadwal) {
                showStep('pilihMode');
            } else {
                state.tipe = 'kantor';
                mulaiCekLokasi();
            }
        });
    }

    // ── Mulai Absen Pulang ──
    const btnMulaiPulang = document.getElementById('btnMulaiAbsenPulang');
    if (btnMulaiPulang) {
        btnMulaiPulang.addEventListener('click', () => {
            resetState();
            state.aksi = 'pulang';
            mulaiCekLokasi();
        });
    }

    // ── Pilih mode: mengajar / kantor ──
    document.querySelectorAll('.radioJadwal').forEach(r => {
        r.addEventListener('change', () => {
            document.getElementById('btnLanjutMengajar').disabled = false;
        });
    });

    const btnLanjutMengajar = document.getElementById('btnLanjutMengajar');
    if (btnLanjutMengajar) {
        btnLanjutMengajar.addEventListener('click', () => {
            const checked = document.querySelector('.radioJadwal:checked');
            if (!checked) return;
            state.tipe = 'mengajar';
            state.timetableId = checked.value;
            mulaiCekLokasi();
        });
    }

    const btnLanjutKantor = document.getElementById('btnLanjutKantor');
    if (btnLanjutKantor) {
        btnLanjutKantor.addEventListener('click', () => {
            state.tipe = 'kantor';
            state.timetableId = null;
            mulaiCekLokasi();
        });
    }

    // ── Batal wizard (kembali ke home) ──
    document.querySelectorAll('.btnBatalWizard').forEach(btn => {
        btn.addEventListener('click', () => {
            resetState();
            showStep('home');
        });
    });

    // ── STEP: Cek Lokasi ──
    function mulaiCekLokasi() {
        showStep('lokasi');
        document.getElementById('lokasiLoading').classList.remove('hidden');
        document.getElementById('lokasiValid').classList.add('hidden');
        document.getElementById('lokasiInvalid').classList.add('hidden');
        document.getElementById('lokasiError').classList.add('hidden');

        if (!('geolocation' in navigator)) {
            document.getElementById('lokasiLoading').classList.add('hidden');
            document.getElementById('lokasiError').classList.remove('hidden');
            return;
        }

        navigator.geolocation.getCurrentPosition(
            (pos) => {
                const lat = pos.coords.latitude;
                const lng = pos.coords.longitude;
                state.latitude = lat;
                state.longitude = lng;

                let jarak = 0;
                let valid = true;
                if (sekolahLat !== null && sekolahLng !== null) {
                    jarak = hitungJarak(sekolahLat, sekolahLng, lat, lng);
                    valid = jarak <= sekolahRadius;
                }
                state.jarak = Math.round(jarak);

                document.getElementById('lokasiLoading').classList.add('hidden');
                if (valid) {
                    document.getElementById('txtJarakValid').textContent = state.jarak;
                    document.getElementById('txtRadiusValid').textContent = sekolahRadius;
                    document.getElementById('lokasiValid').classList.remove('hidden');
                } else {
                    document.getElementById('txtJarakInvalid').textContent = state.jarak;
                    document.getElementById('txtRadiusInvalid').textContent = sekolahRadius;
                    document.getElementById('lokasiInvalid').classList.remove('hidden');
                }
            },
            () => {
                document.getElementById('lokasiLoading').classList.add('hidden');
                document.getElementById('lokasiError').classList.remove('hidden');
            },
            { enableHighAccuracy: true, timeout: 15000, maximumAge: 0 }
        );
    }

    document.querySelectorAll('.btnUlangiLokasi').forEach(btn => {
        btn.addEventListener('click', mulaiCekLokasi);
    });

    document.getElementById('btnLanjutFoto').addEventListener('click', () => {
        showStep('foto');
        startCamera();
    });

    // Rumus Haversine (versi JS, sama dengan di server untuk estimasi cepat)
    function hitungJarak(lat1, lng1, lat2, lng2) {
        const R = 6371000;
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLng = (lng2 - lng1) * Math.PI / 180;
        const a = Math.sin(dLat / 2) ** 2 +
                  Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                  Math.sin(dLng / 2) ** 2;
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        return R * c;
    }

    // ── STEP: Kamera ──
    const video = document.getElementById('videoCamera');
    const canvas = document.getElementById('canvasFoto');

    function startCamera() {
        document.getElementById('cameraError').classList.add('hidden');
        video.classList.remove('hidden');
        navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' }, audio: false })
            .then(stream => {
                state.stream = stream;
                video.srcObject = stream;
            })
            .catch(() => {
                video.classList.add('hidden');
                const err = document.getElementById('cameraError');
                err.textContent = 'Kamera tidak dapat diakses. Gunakan opsi unggah foto manual di bawah.';
                err.classList.remove('hidden');
            });
    }

    function stopCamera() {
        if (state.stream) {
            state.stream.getTracks().forEach(t => t.stop());
            state.stream = null;
        }
    }

    document.getElementById('btnAmbilFoto').addEventListener('click', () => {
        if (!state.stream) return;
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        canvas.getContext('2d').drawImage(video, 0, 0);
        canvas.toBlob(blob => {
            state.fotoBlob = blob;
            document.getElementById('previewFoto').src = URL.createObjectURL(blob);
            stopCamera();
            showStep('konfirmasiFoto');
        }, 'image/jpeg', 0.9);
    });

    document.getElementById('inputFotoManual').addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (!file) return;
        state.fotoBlob = file;
        document.getElementById('previewFoto').src = URL.createObjectURL(file);
        stopCamera();
        showStep('konfirmasiFoto');
    });

    // ── STEP: Konfirmasi Foto ──
    document.getElementById('btnUlangiFoto').addEventListener('click', () => {
        state.fotoBlob = null;
        showStep('foto');
        startCamera();
    });

    document.getElementById('btnGunakanFoto').addEventListener('click', () => {
        document.getElementById('ringkasJam').textContent = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
        document.getElementById('ringkasJenis').textContent = state.aksi === 'pulang'
            ? 'Absen Pulang'
            : (state.tipe === 'mengajar' ? 'Absen Masuk — Mengajar' : 'Absen Masuk — Kantor');
        document.getElementById('ringkasJarak').textContent = state.jarak ?? 0;
        document.getElementById('konfirmasiError').classList.add('hidden');
        showStep('konfirmasiAbsensi');
    });

    // ── STEP: Kirim Absensi ──
    document.getElementById('btnKirimAbsensi').addEventListener('click', kirimAbsensi);

    function kirimAbsensi() {
        const btn = document.getElementById('btnKirimAbsensi');
        const errBox = document.getElementById('konfirmasiError');
        errBox.classList.add('hidden');
        btn.disabled = true;
        document.getElementById('btnKirimText').textContent = 'MENGIRIM...';

        const formData = new FormData();
        formData.append('foto', state.fotoBlob, 'absensi.jpg');
        formData.append('latitude', state.latitude);
        formData.append('longitude', state.longitude);

        let url = urlMasuk;
        if (state.aksi === 'masuk') {
            formData.append('tipe', state.tipe);
            if (state.tipe === 'mengajar') {
                formData.append('timetable_id', state.timetableId);
            }
        } else {
            url = urlPulang;
        }

        fetch(url, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
            body: formData,
        })
        .then(async (res) => {
            const data = await res.json();
            btn.disabled = false;
            document.getElementById('btnKirimText').textContent = 'KIRIM ABSENSI';

            if (!res.ok || !data.ok) {
                errBox.textContent = data.message || 'Terjadi kesalahan. Silakan coba lagi.';
                errBox.classList.remove('hidden');
                return;
            }

            document.getElementById('txtBerhasilPesan').textContent = data.message;
            showStep('berhasil');
        })
        .catch(() => {
            btn.disabled = false;
            document.getElementById('btnKirimText').textContent = 'KIRIM ABSENSI';
            errBox.textContent = 'Gagal terhubung ke server. Periksa koneksi internet Anda.';
            errBox.classList.remove('hidden');
        });
    }

    // ── Selesai → reload halaman agar status ter-update ──
    document.getElementById('btnSelesai').addEventListener('click', () => {
        window.location.reload();
    });

})();
</script>
@endsection