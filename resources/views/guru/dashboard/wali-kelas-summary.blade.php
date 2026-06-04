{{-- resources/views/guru/dashboard/wali-kelas-summary.blade.php --}}
{{--
    ══════════════════════════════════════════════════════════════
    WIDGET WALI KELAS — DASHBOARD GURU
    ══════════════════════════════════════════════════════════════

    VARIABEL DARI DashboardController (setelah fix):
    ┌─────────────────────────────────────────────────────────────┐
    │  $kelasWaliData       → Kelas model, resolve via           │
    │                         resolveKelasWali() — SAMA PERSIS   │
    │                         dengan ProfilController             │
    │  $namaKelasWali       → string, nama kelas yang benar      │
    │  $totalSiswaWali      → int, count siswa di kelas tsb      │
    │  $isWaliKelas         → bool                               │
    │  $siswaRekapDashboard → Collection siswa di kelas benar    │
    │  $rekapDataDashboard  → array [siswa_id => rekap absensi]  │
    │  $absensiHariIni      → array [hadir,sakit,izin,alpha]     │
    │  $siswaBerisiko       → Collection                         │
    │  $rekapBulan / $rekapTahun → int                          │
    └─────────────────────────────────────────────────────────────┘

    KONSISTENSI KELAS:
    profil.blade.php  → $kelasWali   (ProfilController::resolveKelasWali)
    wali-kelas/index  → $kelas       (WaliKelasController, query by guru)
    widget ini        → $kelasWaliData (DashboardController::resolveKelasWali)

    Ketiga-tiganya sekarang pakai URUTAN STRATEGI YANG SAMA:
      Prioritas 1: kelas_id di tabel guru (FK langsung dari form Profil)
      Prioritas 2: relasi kelas() di model Guru
      Prioritas 3+: FK di tabel kelas (wali_guru_id, wali_kelas_id, dll)
══════════════════════════════════════════════════════════════ --}}
@php
    /* ──────────────────────────────────────────────────────────
       GUARD — hanya tampil jika guru adalah wali kelas
    ────────────────────────────────────────────────────────── */
    $isWK = isset($isWaliKelas) && (bool) $isWaliKelas;

    /* ──────────────────────────────────────────────────────────
       SUMBER DATA UTAMA
       $kelasWaliData dikirim DashboardController — sudah resolve
       dengan urutan prioritas sama dengan ProfilController.
       JANGAN override / ganti dengan variabel lain di sini.
    ────────────────────────────────────────────────────────── */
    $kelasWali      = $kelasWaliData ?? null;            // Kelas model (pasti benar setelah fix)
    $namaKelasLabel = $namaKelasWali                     // string nama, sudah final dari controller
                   ?? $kelasWali?->nama
                   ?? $kelasWali?->name
                   ?? null;

    $jmlSiswa = (int) ($totalSiswaWali ?? 0);            // count di kelas yang BENAR

    /* ──────────────────────────────────────────────────────────
       REKAP & PERIODE
       Sudah di-query controller berdasarkan $kelasWaliData->id
       yang benar. Gunakan apa adanya.
    ────────────────────────────────────────────────────────── */
    $rekapDash  = is_array($rekapDataDashboard ?? null) ? $rekapDataDashboard : [];
    $rekapBulan = $rekapBulan ?? now()->month;
    $rekapTahun = $rekapTahun ?? now()->year;

    /* Format bulan berdasarkan $rekapBulan/$rekapTahun (bukan now())
       agar konsisten saat user filter bulan berbeda */
    $bulanNm = \Carbon\Carbon::createFromDate((int) $rekapTahun, (int) $rekapBulan, 1)
                ->locale('id')->isoFormat('MMMM Y');

    /* ──────────────────────────────────────────────────────────
       ABSENSI HARI INI
       Sudah di-filter controller untuk kelas yang benar.
    ────────────────────────────────────────────────────────── */
    $absiHariIni  = $absensiHariIni ?? ['hadir' => 0, 'sakit' => 0, 'izin' => 0, 'alpha' => 0];
    $hadirToday   = (int) ($absiHariIni['hadir'] ?? 0);
    $sakitToday   = (int) ($absiHariIni['sakit'] ?? 0);
    $izinToday    = (int) ($absiHariIni['izin']  ?? 0);
    $alphaToday   = (int) ($absiHariIni['alpha'] ?? 0);
    $sudahAbsensi = $hadirToday + $sakitToday + $izinToday + $alphaToday;
    $belumToday   = max(0, $jmlSiswa - $sudahAbsensi);

    /* ──────────────────────────────────────────────────────────
       SUB-INFO HEADER (tingkat + tahun ajaran kelas)
    ────────────────────────────────────────────────────────── */
    $kelasTingkat     = $kelasWali?->tingkat      ?? null;
    $kelasTahunAjaran = $kelasWali?->tahun_ajaran ?? null;
    $subInfoParts     = array_filter([$kelasTingkat, $kelasTahunAjaran, $bulanNm]);
    $subInfo          = implode(' · ', $subInfoParts) ?: $bulanNm;

    /* ──────────────────────────────────────────────────────────
       SISWA PREVIEW
       $siswaRekapDashboard = Collection siswa dari kelas yang BENAR
       (sudah di-query controller dengan $kelasWaliData->id).
    ────────────────────────────────────────────────────────── */
    $siswaPreview   = isset($siswaRekapDashboard) && $siswaRekapDashboard->isNotEmpty()
                    ? $siswaRekapDashboard
                    : collect();

    /* ──────────────────────────────────────────────────────────
       SISWA BERISIKO
    ────────────────────────────────────────────────────────── */
    $jumlahBerisiko = isset($siswaBerisiko) ? (int) $siswaBerisiko->count() : 0;

    /* ──────────────────────────────────────────────────────────
       RESOLVE ROUTES
    ────────────────────────────────────────────────────────── */
    $routeWaliKelas    = null;
    $routeAbsensiIndex = null;
    $routeAbsensiRekap = null;
    $kelasIdParam      = $kelasWali ? ['kelas_id' => $kelasWali->id] : [];

    foreach (['guru.wali-kelas.index', 'guru.wali-kelas', 'guru.kelas-wali'] as $_r) {
        if (\Illuminate\Support\Facades\Route::has($_r)) {
            try { $routeWaliKelas = route($_r, $kelasIdParam); break; } catch (\Exception $e) {}
        }
    }
    if (!$routeWaliKelas && $kelasWali) {
        $routeWaliKelas = url('/guru/wali-kelas?kelas_id=' . $kelasWali->id);
    }

    foreach (['guru.absensi-siswa.index', 'guru.absensi.index'] as $_r) {
        if (\Illuminate\Support\Facades\Route::has($_r)) {
            try { $routeAbsensiIndex = route($_r, $kelasIdParam); break; } catch (\Exception $e) {}
        }
    }
    foreach (['guru.absensi-siswa.rekap', 'guru.absensi.rekap'] as $_r) {
        if (\Illuminate\Support\Facades\Route::has($_r) && $kelasWali) {
            try { $routeAbsensiRekap = route($_r, $kelasIdParam); break; } catch (\Exception $e) {}
        }
    }
@endphp

@if($isWK)
@php
    /* ──────────────────────────────────────────────────────────
       DISTRIBUSI KEHADIRAN BULAN INI
       Iterasi $rekapDash — array [siswa_id => [hadir,sakit,izin,alpha]]
       dari controller (kelas yang benar).
    ────────────────────────────────────────────────────────── */
    $pctTinggi      = 0;
    $pctSedang      = 0;
    $pctRendah      = 0;
    $adaDataAbsensi = false;

    foreach ($rekapDash as $sid => $r) {
        if (!is_array($r)) continue;
        $tot = array_sum($r);
        if ($tot <= 0) continue;
        $adaDataAbsensi = true;
        $hadir = (int) ($r['hadir'] ?? 0);
        $pct   = round($hadir / $tot * 100);
        if ($pct >= 80)     $pctTinggi++;
        elseif ($pct >= 60) $pctSedang++;
        else                $pctRendah++;
    }

    $totalDenganData = $pctTinggi + $pctSedang + $pctRendah;
    $belumAbsensi    = max(0, $jmlSiswa - $totalDenganData);

    $avgPct   = $totalDenganData > 0
        ? round(($pctTinggi * 90 + $pctSedang * 70 + $pctRendah * 40) / $totalDenganData)
        : 0;
    $avgColor = $avgPct >= 80 ? '#059669' : ($avgPct >= 60 ? '#a16207' : '#b91c1c');
@endphp

{{-- ══════════════════════════════════════════════
     WRAPPER CARD
══════════════════════════════════════════════ --}}
<div style="background:#fff;border-radius:12px;border:1.5px solid #fde68a;
             box-shadow:0 1px 6px rgba(245,158,11,.1);overflow:hidden;">

    {{-- ── HEADER ────────────────────────────────────────────────
         Menampilkan nama kelas dari $namaKelasLabel
         = $namaKelasWali (string dari controller, BUKAN dari relasi blade)
         Ini menjamin nama sama dengan yang tampil di profil & index.
    ────────────────────────────────────────────────────────── --}}
    <div style="background:linear-gradient(135deg,#fffbeb 0%,#fef3c7 100%);
                padding:10px 14px;border-bottom:1.5px solid #fde68a;">

        <div style="display:flex;align-items:center;gap:8px;">
            {{-- Ikon --}}
            <div style="width:30px;height:30px;border-radius:9px;flex-shrink:0;
                        background:linear-gradient(135deg,#f59e0b,#d97706);
                        display:flex;align-items:center;justify-content:center;
                        font-size:.9rem;box-shadow:0 2px 6px rgba(245,158,11,.3);">⭐</div>

            {{-- Nama kelas --}}
            <div style="flex:1;min-width:0;">
                <p style="font-size:.72rem;font-weight:800;color:#92400e;margin:0;line-height:1.2;">
                    Wali Kelas
                    @if($namaKelasLabel)
                        &mdash; {{ $namaKelasLabel }}
                    @elseif($kelasWali)
                        &mdash; (ID #{{ $kelasWali->id }})
                    @endif
                </p>
                <p style="font-size:.58rem;color:#a16207;margin:0;line-height:1.35;">
                    {{ $kelasWali ? $subInfo : 'Kelas belum diset' }}
                </p>
            </div>

            {{-- Pill jumlah siswa ($totalSiswaWali dari controller — kelas benar) --}}
            <div style="background:rgba(217,119,6,.18);border:1px solid rgba(217,119,6,.35);
                        border-radius:9px;padding:4px 10px;text-align:center;flex-shrink:0;">
                <span style="font-size:.95rem;font-weight:800;color:#92400e;
                             display:block;line-height:1.1;">{{ $jmlSiswa }}</span>
                <span style="font-size:.5rem;color:#a16207;font-weight:600;
                             display:block;text-transform:uppercase;letter-spacing:.04em;">siswa</span>
            </div>
        </div>

        {{-- Tombol buka halaman wali kelas --}}
        @if($routeWaliKelas)
        <a href="{{ $routeWaliKelas }}"
           style="display:flex;align-items:center;justify-content:center;gap:5px;
                  margin-top:9px;padding:6px 10px;border-radius:8px;
                  background:linear-gradient(135deg,#f59e0b,#d97706);
                  color:#fff;font-size:.62rem;font-weight:700;text-decoration:none;
                  box-shadow:0 2px 6px rgba(245,158,11,.35);transition:filter .15s;"
           onmouseover="this.style.filter='brightness(1.08)'"
           onmouseout="this.style.filter='brightness(1)'">
            <span style="font-size:.8rem;">👥</span>
            Buka Data Kelas Lengkap
            <svg style="width:10px;height:10px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
        @endif
    </div>{{-- /header --}}

    {{-- ── BODY ────────────────────────────────────────────────── --}}
    <div style="padding:12px 14px;display:flex;flex-direction:column;gap:10px;">

        {{-- ┌─ ABSENSI HARI INI ─────────────────────────────── --}}
        <div>
            <p style="font-size:.58rem;font-weight:700;color:#64748b;
                      text-transform:uppercase;letter-spacing:.06em;margin:0 0 6px;">
                Absensi Hari Ini
            </p>

            @if($jmlSiswa === 0)
                <div style="padding:8px;text-align:center;background:#f8fafc;
                             border-radius:8px;border:1px solid #e2e8f0;">
                    <p style="font-size:.6rem;color:#94a3b8;margin:0;">Belum ada siswa terdaftar</p>
                </div>
            @else
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:4px;">

                    {{-- Hadir --}}
                    @if($routeAbsensiIndex)
                    <a href="{{ $routeAbsensiIndex }}&status_filter=hadir" style="text-decoration:none;">
                    @endif
                    <div style="display:flex;align-items:center;justify-content:space-between;
                                 padding:5px 9px;border-radius:7px;
                                 background:#ecfdf5;border:1px solid #a7f3d0;
                                 cursor:pointer;transition:filter .12s;"
                         onmouseover="this.style.filter='brightness(.96)'"
                         onmouseout="this.style.filter='brightness(1)'">
                        <span style="font-size:.58rem;font-weight:600;color:#059669;">✅ Hadir</span>
                        <span style="font-size:.78rem;font-weight:800;color:#059669;">{{ $hadirToday }}</span>
                    </div>
                    @if($routeAbsensiIndex) </a> @endif

                    {{-- Sakit --}}
                    <div style="display:flex;align-items:center;justify-content:space-between;
                                 padding:5px 9px;border-radius:7px;
                                 background:#fef9c3;border:1px solid #fde68a;">
                        <span style="font-size:.58rem;font-weight:600;color:#a16207;">🤒 Sakit</span>
                        <span style="font-size:.78rem;font-weight:800;color:#a16207;">{{ $sakitToday }}</span>
                    </div>

                    {{-- Izin --}}
                    <div style="display:flex;align-items:center;justify-content:space-between;
                                 padding:5px 9px;border-radius:7px;
                                 background:#e0f2fe;border:1px solid #bae6fd;">
                        <span style="font-size:.58rem;font-weight:600;color:#0369a1;">📋 Izin</span>
                        <span style="font-size:.78rem;font-weight:800;color:#0369a1;">{{ $izinToday }}</span>
                    </div>

                    {{-- Alpha --}}
                    <div style="display:flex;align-items:center;justify-content:space-between;
                                 padding:5px 9px;border-radius:7px;
                                 background:#fee2e2;border:1px solid #fecaca;">
                        <span style="font-size:.58rem;font-weight:600;color:#b91c1c;">❌ Alpha</span>
                        <span style="font-size:.78rem;font-weight:800;color:#b91c1c;">{{ $alphaToday }}</span>
                    </div>
                </div>

                @if($belumToday > 0)
                <div style="margin-top:4px;display:flex;align-items:center;
                             justify-content:space-between;padding:5px 9px;border-radius:7px;
                             background:#f1f5f9;border:1px solid #e2e8f0;">
                    <span style="font-size:.58rem;font-weight:600;color:#64748b;">⏳ Belum Absensi</span>
                    <span style="font-size:.72rem;font-weight:800;color:#64748b;">{{ $belumToday }}</span>
                </div>
                @endif
            @endif
        </div>

        {{-- ┌─ ALERT SISWA BERISIKO ─────────────────────────── --}}
        @if($jumlahBerisiko > 0)
        @if($routeWaliKelas)
        <a href="{{ $routeWaliKelas }}#risikoSection" style="text-decoration:none;">
        @endif
        <div style="display:flex;align-items:center;gap:8px;padding:7px 10px;
                     border-radius:8px;background:#fef2f2;border:1px solid #fecaca;
                     cursor:pointer;transition:filter .12s;"
             onmouseover="this.style.filter='brightness(.97)'"
             onmouseout="this.style.filter='brightness(1)'">
            <span style="font-size:.85rem;flex-shrink:0;animation:wkPulse 2s infinite;">⚠️</span>
            <div style="flex:1;min-width:0;">
                <p style="font-size:.62rem;font-weight:700;color:#b91c1c;margin:0;line-height:1.3;">
                    {{ $jumlahBerisiko }} Siswa Perlu Perhatian
                </p>
                <p style="font-size:.56rem;color:#ef4444;margin:0;line-height:1.3;">
                    Kehadiran &lt; 75% bulan ini
                </p>
            </div>
            <svg style="width:11px;height:11px;color:#b91c1c;flex-shrink:0;"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
            </svg>
        </div>
        @if($routeWaliKelas) </a> @endif
        @endif

        {{-- ┌─ DISTRIBUSI KEHADIRAN BULAN INI ──────────────── --}}
        <div>
            <p style="font-size:.58rem;font-weight:700;color:#64748b;
                      text-transform:uppercase;letter-spacing:.06em;margin:0 0 6px;">
                Kehadiran {{ $bulanNm }}
            </p>

            @if($jmlSiswa === 0)
                <div style="padding:10px;text-align:center;background:#f8fafc;
                             border-radius:8px;border:1px solid #e2e8f0;">
                    <p style="font-size:.62rem;color:#94a3b8;margin:0;">Belum ada siswa terdaftar</p>
                </div>

            @elseif(!$adaDataAbsensi)
                <div style="padding:8px 10px;border-radius:8px;background:#f8fafc;border:1px solid #e2e8f0;">
                    <p style="font-size:.62rem;color:#94a3b8;margin:0;text-align:center;">
                        Belum ada data absensi bulan ini
                    </p>
                </div>

            @else
                <div style="display:flex;flex-direction:column;gap:4px;">
                    @if($pctTinggi > 0)
                    <div style="display:flex;align-items:center;justify-content:space-between;
                                 padding:5px 9px;border-radius:7px;background:#ecfdf5;">
                        <span style="font-size:.6rem;font-weight:600;color:#059669;">≥ 80% Hadir</span>
                        <span style="font-size:.72rem;font-weight:800;color:#059669;">
                            {{ $pctTinggi }}<span style="font-size:.52rem;font-weight:600;"> siswa</span>
                        </span>
                    </div>
                    @endif
                    @if($pctSedang > 0)
                    <div style="display:flex;align-items:center;justify-content:space-between;
                                 padding:5px 9px;border-radius:7px;background:#fef9c3;">
                        <span style="font-size:.6rem;font-weight:600;color:#a16207;">60–79% Hadir</span>
                        <span style="font-size:.72rem;font-weight:800;color:#a16207;">
                            {{ $pctSedang }}<span style="font-size:.52rem;font-weight:600;"> siswa</span>
                        </span>
                    </div>
                    @endif
                    @if($pctRendah > 0)
                    <div style="display:flex;align-items:center;justify-content:space-between;
                                 padding:5px 9px;border-radius:7px;background:#fee2e2;">
                        <span style="font-size:.6rem;font-weight:600;color:#b91c1c;">&lt; 60% Hadir</span>
                        <span style="font-size:.72rem;font-weight:800;color:#b91c1c;">
                            {{ $pctRendah }}<span style="font-size:.52rem;font-weight:600;"> siswa</span>
                        </span>
                    </div>
                    @endif
                    @if($belumAbsensi > 0)
                    <div style="display:flex;align-items:center;justify-content:space-between;
                                 padding:5px 9px;border-radius:7px;background:#f1f5f9;">
                        <span style="font-size:.6rem;font-weight:600;color:#64748b;">Belum Absensi</span>
                        <span style="font-size:.72rem;font-weight:800;color:#64748b;">
                            {{ $belumAbsensi }}<span style="font-size:.52rem;font-weight:600;"> siswa</span>
                        </span>
                    </div>
                    @endif
                </div>

                @if($avgPct > 0)
                <div style="margin-top:6px;padding:7px 10px;background:#f8fafc;
                             border-radius:8px;border:1px solid #e2e8f0;">
                    <div style="display:flex;justify-content:space-between;
                                 align-items:center;margin-bottom:4px;">
                        <p style="font-size:.57rem;font-weight:600;color:#64748b;margin:0;">
                            Est. Rata-rata Kelas
                        </p>
                        <p style="font-size:.72rem;font-weight:800;color:{{ $avgColor }};margin:0;">
                            ~{{ $avgPct }}%
                        </p>
                    </div>
                    <div style="height:5px;background:#e2e8f0;border-radius:99px;overflow:hidden;">
                        <div style="height:100%;width:{{ $avgPct }}%;background:{{ $avgColor }};
                                     border-radius:99px;transition:width .4s;"></div>
                    </div>
                </div>
                @endif
            @endif
        </div>

        {{-- ┌─ PREVIEW SISWA ────────────────────────────────── --}}
        {{--
            $siswaPreview = $siswaRekapDashboard dari controller
            = siswa dari $kelasWaliData->id yang BENAR
            = nama yang sama dengan yang tampil di halaman index & profil
        --}}
        @if($siswaPreview->isNotEmpty())
        <div>
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px;">
                <p style="font-size:.58rem;font-weight:700;color:#64748b;
                          text-transform:uppercase;letter-spacing:.06em;margin:0;">
                    Preview Siswa
                    {{-- Label kelas ditampilkan agar mudah diverifikasi --}}
                    @if($namaKelasLabel)
                    <span style="font-weight:600;color:#f59e0b;text-transform:none;
                                 background:#fffbeb;border:1px solid #fde68a;
                                 border-radius:4px;padding:1px 6px;font-size:.56rem;">
                        {{ $namaKelasLabel }}
                    </span>
                    @endif
                </p>
                @if($routeWaliKelas)
                <a href="{{ $routeWaliKelas }}"
                   style="font-size:.55rem;font-weight:700;color:#f59e0b;text-decoration:none;">
                    Lihat Semua →
                </a>
                @endif
            </div>

            <div style="display:flex;flex-direction:column;gap:3px;">
                @foreach($siswaPreview->take(3) as $sv)
                @php
                    $svNama    = $sv->nama ?? '—';
                    $svInisial = strtoupper(mb_substr($svNama, 0, 2));

                    /*
                     * Lookup rekap: cast key ke string untuk menghindari
                     * PHP int vs string key mismatch di array.
                     */
                    $svRekap = $rekapDash[(string) $sv->id]
                            ?? $rekapDash[$sv->id]
                            ?? ['hadir'=>0,'sakit'=>0,'izin'=>0,'alpha'=>0];

                    $svTot   = array_sum($svRekap);
                    $svPct   = $svTot > 0 ? round(($svRekap['hadir'] / $svTot) * 100) : 0;
                    $svColor = $svPct >= 80 ? '#059669' : ($svPct >= 60 ? '#a16207' : '#b91c1c');
                    $svBg    = $svPct >= 80 ? '#ecfdf5' : ($svPct >= 60 ? '#fef9c3' : '#fee2e2');
                @endphp
                <div style="display:flex;align-items:center;gap:7px;padding:5px 8px;
                             border-radius:7px;background:#f8fafc;border:1px solid #f1f5f9;">
                    {{-- Avatar --}}
                    <div style="width:24px;height:24px;border-radius:6px;flex-shrink:0;
                                 background:linear-gradient(135deg,#eef2ff,#e0e7ff);
                                 color:#4f46e5;font-size:.52rem;font-weight:800;
                                 display:flex;align-items:center;justify-content:center;">
                        {{ $svInisial }}
                    </div>
                    {{-- Nama & Rekap --}}
                    <div style="flex:1;min-width:0;">
                        <p style="font-size:.62rem;font-weight:700;color:#1e293b;
                                   margin:0;line-height:1.2;white-space:nowrap;
                                   overflow:hidden;text-overflow:ellipsis;">
                            {{ $svNama }}
                        </p>
                        <p style="font-size:.52rem;color:#94a3b8;margin:0;line-height:1.2;">
                            H:{{ $svRekap['hadir'] }} S:{{ $svRekap['sakit'] }}
                            I:{{ $svRekap['izin'] }} A:{{ $svRekap['alpha'] }}
                        </p>
                    </div>
                    {{-- % Kehadiran --}}
                    <div style="background:{{ $svBg }};border-radius:5px;
                                 padding:2px 6px;flex-shrink:0;">
                        <span style="font-size:.6rem;font-weight:800;color:{{ $svColor }};">
                            {{ $svPct }}%
                        </span>
                    </div>
                </div>
                @endforeach

                @if($siswaPreview->count() > 3)
                <p style="font-size:.55rem;color:#94a3b8;text-align:center;margin:2px 0 0;">
                    +{{ $siswaPreview->count() - 3 }} siswa lainnya
                </p>
                @endif
            </div>
        </div>
        @endif

        {{-- ┌─ QUICK LINKS ──────────────────────────────────── --}}
        <div style="display:flex;flex-direction:column;gap:5px;
                     padding-top:8px;border-top:1px solid #f1f5f9;">

            @if($routeWaliKelas)
            <a href="{{ $routeWaliKelas }}"
               style="display:flex;align-items:center;gap:6px;padding:6px 10px;border-radius:8px;
                      font-size:.63rem;font-weight:600;color:#92400e;text-decoration:none;
                      background:#fffbeb;border:1px solid #fde68a;transition:background .15s;"
               onmouseover="this.style.background='#fef3c7'"
               onmouseout="this.style.background='#fffbeb'">
                <span style="font-size:.8rem;">👥</span>
                Data Kelas &amp; Daftar Siswa
                <svg style="width:9px;height:9px;margin-left:auto;" fill="none"
                     stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
            @endif

            @if($routeAbsensiRekap)
            <a href="{{ $routeAbsensiRekap }}"
               style="display:flex;align-items:center;gap:6px;padding:6px 10px;border-radius:8px;
                      font-size:.63rem;font-weight:600;color:#4338ca;text-decoration:none;
                      background:#eef2ff;border:1px solid #c7d2fe;transition:background .15s;"
               onmouseover="this.style.background='#e0e7ff'"
               onmouseout="this.style.background='#eef2ff'">
                <span style="font-size:.8rem;">📊</span>
                Rekap Absensi Kelas
                <svg style="width:9px;height:9px;margin-left:auto;" fill="none"
                     stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
            @endif

            @if($routeAbsensiIndex)
            <a href="{{ $routeAbsensiIndex }}"
               style="display:flex;align-items:center;gap:6px;padding:6px 10px;border-radius:8px;
                      font-size:.63rem;font-weight:600;color:#065f46;text-decoration:none;
                      background:#ecfdf5;border:1px solid #a7f3d0;transition:background .15s;"
               onmouseover="this.style.background='#d1fae5'"
               onmouseout="this.style.background='#ecfdf5'">
                <span style="font-size:.8rem;">✅</span>
                Catat Absensi Hari Ini
                <svg style="width:9px;height:9px;margin-left:auto;" fill="none"
                     stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
            @endif

            @if(!$routeWaliKelas && !$routeAbsensiRekap && !$routeAbsensiIndex)
            <p style="font-size:.58rem;color:#94a3b8;text-align:center;padding:6px 0;">
                Route belum terdaftar — hubungi developer
            </p>
            @endif
        </div>

    </div>{{-- /body --}}
</div>{{-- /wrapper --}}

<style>
@keyframes wkPulse { 0%,100%{opacity:1} 50%{opacity:.55} }
</style>

@endif {{-- end @if($isWK) --}}