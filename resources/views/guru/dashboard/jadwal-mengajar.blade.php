{{-- resources/views/guru/dashboard/jadwal-mengajar.blade.php --}}
{{--
   Field ternormalisasi dari DashboardController (semua dari Timetable):
     $jadwal->_jam_mulai    = start_time
     $jadwal->_jam_selesai  = end_time
     $jadwal->_mapel        = studySubject->name
     $jadwal->_kelas        = studyGroup->name
     $jadwal->_ruangan      = room
     $jadwal->_kelas_id     = study_group_id  (link ke absensi)
     $jadwal->_warna        = studySubject->color  (opsional)
     $jadwal->_sumber       = 'Timetable'
--}}
@php
    $jadwalHariIni = $jadwalHariIni ?? collect();

    $hariMap = [
        'Sunday'    => 'Minggu',
        'Monday'    => 'Senin',
        'Tuesday'   => 'Selasa',
        'Wednesday' => 'Rabu',
        'Thursday'  => 'Kamis',
        'Friday'    => 'Jumat',
        'Saturday'  => 'Sabtu',
    ];
    $hariIniLabel = $hariMap[now()->format('l')] ?? now()->format('l');

    $warnaPalet = [
        '#4f46e5','#059669','#2563eb','#d97706',
        '#7c3aed','#0891b2','#db2777','#ea580c',
    ];

    $nowTime = now()->format('H:i');

    // Helper status berdasarkan jam sekarang vs jam jadwal
    $getStatus = function($jamMulai, $jamSelesai) use ($nowTime) {
        if (!$jamMulai || !$jamSelesai) return 'akan';
        $m = substr($jamMulai, 0, 5);
        $s = substr($jamSelesai, 0, 5);
        if ($nowTime >= $m && $nowTime < $s) return 'berlangsung';
        if ($nowTime >= $s) return 'selesai';
        return 'akan';
    };
@endphp

<div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">

    {{-- ─── Header ─────────────────────────────────────────────── --}}
    <div style="display:flex;align-items:center;justify-content:space-between;
                padding:10px 14px;border-bottom:1px solid #f1f5f9;background:#fff;">
        <div style="display:flex;align-items:center;gap:8px;">
            <div style="width:30px;height:30px;border-radius:9px;
                        background:linear-gradient(135deg,#4f46e5,#7c3aed);
                        display:flex;align-items:center;justify-content:center;font-size:.9rem;">
                🗓️
            </div>
            <div>
                <p style="font-size:.78rem;font-weight:700;color:#1e293b;line-height:1.2;margin:0;">
                    Jadwal Mengajar Hari Ini
                </p>
                <p style="font-size:.6rem;color:#94a3b8;line-height:1.3;margin:0;">
                    {{ $hariIniLabel }}, {{ now()->isoFormat('D MMMM Y') }}
                </p>
            </div>
        </div>
        <div style="display:flex;align-items:center;gap:6px;">
            @if($jadwalHariIni->isNotEmpty())
                <span style="font-size:.58rem;font-weight:700;background:#ecfdf5;color:#059669;
                             border:1px solid #a7f3d0;border-radius:99px;padding:2px 8px;">
                    {{ $jadwalHariIni->count() }} sesi
                </span>
            @endif
            @if(Route::has('guru.jadwal-mengajar.index'))
                <a href="{{ route('guru.jadwal-mengajar.index') }}"
                   style="font-size:.6rem;font-weight:700;color:#4f46e5;text-decoration:none;
                          background:#eef2ff;border:1px solid #c7d2fe;border-radius:6px;
                          padding:4px 10px;white-space:nowrap;">
                    Semua Jadwal →
                </a>
            @endif
        </div>
    </div>

    {{-- ─── Empty State ─────────────────────────────────────────── --}}
    @if($jadwalHariIni->isEmpty())
        <div style="padding:40px 20px;text-align:center;">
            <div style="width:60px;height:60px;border-radius:18px;background:#f1f5f9;
                        display:flex;align-items:center;justify-content:center;
                        font-size:1.8rem;margin:0 auto 12px;">😊</div>
            <p style="font-size:.75rem;font-weight:700;color:#475569;margin:0 0 4px;">
                Tidak ada jadwal mengajar hari ini
            </p>
            <p style="font-size:.62rem;color:#94a3b8;margin:0;">
                Selamat menikmati hari {{ $hariIniLabel }}!
            </p>
            @if(Route::has('guru.jadwal-mengajar.index'))
                <a href="{{ route('guru.jadwal-mengajar.index') }}"
                   style="display:inline-flex;align-items:center;gap:4px;margin-top:12px;
                          font-size:.62rem;font-weight:700;color:#4f46e5;text-decoration:none;
                          background:#eef2ff;border:1px solid #c7d2fe;border-radius:7px;
                          padding:5px 12px;">
                    ➕ Kelola Jadwal
                </a>
            @endif
        </div>

    @else
    {{-- ─── Tabel Jadwal ────────────────────────────────────────── --}}
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;min-width:500px;">
            <thead>
                <tr style="background:#f8fafc;">
                    <th style="padding:8px 14px;text-align:left;font-size:.575rem;font-weight:700;
                               color:#64748b;text-transform:uppercase;letter-spacing:.05em;
                               border-bottom:1.5px solid #e2e8f0;white-space:nowrap;width:115px;">
                        Jam
                    </th>
                    <th style="padding:8px 12px;text-align:left;font-size:.575rem;font-weight:700;
                               color:#64748b;text-transform:uppercase;letter-spacing:.05em;
                               border-bottom:1.5px solid #e2e8f0;">
                        Mata Pelajaran
                    </th>
                    <th style="padding:8px 10px;text-align:center;font-size:.575rem;font-weight:700;
                               color:#64748b;text-transform:uppercase;letter-spacing:.05em;
                               border-bottom:1.5px solid #e2e8f0;white-space:nowrap;width:85px;">
                        Kelas
                    </th>
                    <th style="padding:8px 10px;text-align:center;font-size:.575rem;font-weight:700;
                               color:#64748b;text-transform:uppercase;letter-spacing:.05em;
                               border-bottom:1.5px solid #e2e8f0;white-space:nowrap;width:80px;">
                        Ruangan
                    </th>
                    <th style="padding:8px 10px;text-align:center;font-size:.575rem;font-weight:700;
                               color:#64748b;text-transform:uppercase;letter-spacing:.05em;
                               border-bottom:1.5px solid #e2e8f0;white-space:nowrap;width:115px;">
                        Status
                    </th>
                    <th style="padding:8px 14px;text-align:center;font-size:.575rem;font-weight:700;
                               color:#64748b;text-transform:uppercase;letter-spacing:.05em;
                               border-bottom:1.5px solid #e2e8f0;white-space:nowrap;width:95px;">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach($jadwalHariIni as $idx => $jadwal)
                @php
                    // Baca field yang sudah dinormalisasi di controller
                    $jamMulai   = $jadwal->_jam_mulai   ?? null;
                    $jamSelesai = $jadwal->_jam_selesai ?? null;
                    $mapel      = $jadwal->_mapel       ?? '—';
                    $namaKelas  = $jadwal->_kelas       ?? '—';
                    $ruangan    = $jadwal->_ruangan     ?? null;
                    $kelasId    = $jadwal->_kelas_id    ?? null;
                    $jadwalId   = $jadwal->id           ?? null;
                    $warna      = $jadwal->_warna       ?? null;

                    // Warna strip: pakai warna dari studySubject->color jika ada
                    $stripColor = $warna ?? $warnaPalet[$idx % count($warnaPalet)];

                    // Hitung status
                    $status        = $getStatus($jamMulai, $jamSelesai);
                    $isBerlangsung = $status === 'berlangsung';
                    $isSelesai     = $status === 'selesai';

                    // Style per status
                    if ($isBerlangsung) {
                        $rowBg        = '#f0fdf4';
                        $statusLabel  = 'Berlangsung';
                        $statusBg     = '#dcfce7';
                        $statusColor  = '#059669';
                        $statusBorder = '#a7f3d0';
                    } elseif ($isSelesai) {
                        $rowBg        = $idx % 2 === 0 ? '#fff' : '#fafbfd';
                        $statusLabel  = 'Selesai';
                        $statusBg     = '#f1f5f9';
                        $statusColor  = '#94a3b8';
                        $statusBorder = '#e2e8f0';
                    } else {
                        $rowBg        = $idx % 2 === 0 ? '#fff' : '#fafbfd';
                        $statusLabel  = 'Akan Datang';
                        $statusBg     = '#eff6ff';
                        $statusColor  = '#2563eb';
                        $statusBorder = '#bfdbfe';
                    }

                    // Parameter link absensi
                    $absensiParams = array_filter(['kelas_id' => $kelasId, 'jadwal_id' => $jadwalId]);
                @endphp
                <tr style="background:{{ $rowBg }};border-bottom:1px solid #f1f5f9;"
                    onmouseover="this.style.background='{{ $isBerlangsung ? '#dcfce7' : '#f0f4ff' }}'"
                    onmouseout="this.style.background='{{ $rowBg }}'">

                    {{-- ── Jam ── --}}
                    <td style="padding:9px 14px;white-space:nowrap;">
                        <div style="display:flex;align-items:center;gap:8px;">
                            {{-- Color strip --}}
                            <div style="width:3px;height:40px;border-radius:3px;flex-shrink:0;
                                         background:{{ $isBerlangsung ? '#059669' : ($isSelesai ? '#cbd5e1' : $stripColor) }};"></div>
                            {{-- Jam box --}}
                            <div style="text-align:center;min-width:62px;
                                         background:{{ $isBerlangsung ? '#dcfce7' : '#f8fafc' }};
                                         border:1.5px solid {{ $isBerlangsung ? '#a7f3d0' : '#e2e8f0' }};
                                         border-radius:8px;padding:5px 9px;">
                                @if($jamMulai && $jamSelesai)
                                    <p style="font-size:.7rem;font-weight:800;line-height:1.2;margin:0;
                                               color:{{ $isBerlangsung ? '#059669' : ($isSelesai ? '#94a3b8' : '#1e293b') }};">
                                        {{ substr($jamMulai, 0, 5) }}
                                    </p>
                                    <div style="width:16px;height:1px;background:#e2e8f0;margin:2px auto;"></div>
                                    <p style="font-size:.58rem;color:#94a3b8;line-height:1.2;margin:0;">
                                        {{ substr($jamSelesai, 0, 5) }}
                                    </p>
                                @else
                                    <p style="font-size:.62rem;color:#94a3b8;margin:0;">—</p>
                                @endif
                            </div>
                        </div>
                    </td>

                    {{-- ── Mata Pelajaran ── --}}
                    <td style="padding:9px 12px;">
                        <div style="display:flex;align-items:center;gap:8px;">
                            {{-- Badge inisial mapel --}}
                            <div style="width:30px;height:30px;border-radius:9px;flex-shrink:0;
                                         display:flex;align-items:center;justify-content:center;
                                         font-size:.6rem;font-weight:800;color:#fff;
                                         background:{{ $isSelesai ? '#cbd5e1' : $stripColor }};
                                         opacity:{{ $isSelesai ? '.7' : '1' }};">
                                {{ strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $mapel), 0, 2)) ?: '??' }}
                            </div>
                            <div style="min-width:0;">
                                <p style="font-size:.72rem;font-weight:700;line-height:1.25;margin:0;
                                           color:{{ $isSelesai ? '#94a3b8' : '#1e293b' }};
                                           white-space:nowrap;overflow:hidden;text-overflow:ellipsis;
                                           max-width:180px;">
                                    {{ $mapel }}
                                </p>
                                @if($isBerlangsung)
                                    <p style="font-size:.55rem;font-weight:700;color:#059669;
                                               line-height:1.2;margin:0;">
                                        ● Sedang berlangsung
                                    </p>
                                @endif
                            </div>
                        </div>
                    </td>

                    {{-- ── Kelas ── --}}
                    <td style="padding:9px 10px;text-align:center;">
                        <span style="display:inline-flex;align-items:center;justify-content:center;
                                      padding:3px 10px;border-radius:6px;font-size:.65rem;font-weight:700;
                                      white-space:nowrap;
                                      background:{{ $isBerlangsung ? '#eef2ff' : '#f8fafc' }};
                                      color:{{ $isBerlangsung ? '#4338ca' : ($isSelesai ? '#94a3b8' : '#475569') }};
                                      border:1px solid {{ $isBerlangsung ? '#c7d2fe' : '#e2e8f0' }};">
                            {{ $namaKelas }}
                        </span>
                    </td>

                    {{-- ── Ruangan ── --}}
                    <td style="padding:9px 10px;text-align:center;">
                        @if($ruangan)
                            <span style="font-size:.65rem;font-weight:600;
                                          color:{{ $isSelesai ? '#94a3b8' : '#475569' }};">
                                {{ $ruangan }}
                            </span>
                        @else
                            <span style="font-size:.65rem;color:#cbd5e1;">—</span>
                        @endif
                    </td>

                    {{-- ── Status ── --}}
                    <td style="padding:9px 10px;text-align:center;">
                        <span style="display:inline-flex;align-items:center;justify-content:center;
                                      gap:3px;padding:3px 9px;border-radius:99px;font-size:.58rem;
                                      font-weight:700;white-space:nowrap;
                                      background:{{ $statusBg }};
                                      color:{{ $statusColor }};
                                      border:1px solid {{ $statusBorder }};">
                            @if($isBerlangsung)🟢
                            @elseif($isSelesai)✓
                            @else🕐
                            @endif
                            {{ $statusLabel }}
                        </span>
                    </td>

                    {{-- ── Aksi ── --}}
                    <td style="padding:9px 14px;text-align:center;">
                        @if(Route::has('guru.absensi-siswa.index'))
                            @if($isBerlangsung)
                                <a href="{{ route('guru.absensi-siswa.index', $absensiParams) }}"
                                   style="display:inline-flex;align-items:center;justify-content:center;
                                          gap:3px;padding:5px 11px;border-radius:7px;font-size:.6rem;
                                          font-weight:700;text-decoration:none;white-space:nowrap;
                                          background:#4f46e5;color:#fff;
                                          box-shadow:0 2px 8px rgba(79,70,229,.3);">
                                    ✅ Absensi
                                </a>
                            @elseif($isSelesai)
                                <a href="{{ route('guru.absensi-siswa.index', $absensiParams) }}"
                                   style="display:inline-flex;align-items:center;justify-content:center;
                                          gap:3px;padding:5px 11px;border-radius:7px;font-size:.6rem;
                                          font-weight:600;text-decoration:none;white-space:nowrap;
                                          background:#f8fafc;color:#94a3b8;border:1px solid #e2e8f0;">
                                    👁 Lihat
                                </a>
                            @else
                                <a href="{{ route('guru.absensi-siswa.index', $absensiParams) }}"
                                   style="display:inline-flex;align-items:center;justify-content:center;
                                          gap:3px;padding:5px 11px;border-radius:7px;font-size:.6rem;
                                          font-weight:600;text-decoration:none;white-space:nowrap;
                                          background:#f8fafc;color:#475569;border:1px solid #e2e8f0;">
                                    ✅ Absensi
                                </a>
                            @endif
                        @else
                            <span style="font-size:.6rem;color:#94a3b8;">—</span>
                        @endif
                    </td>

                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- ─── Footer ringkasan ────────────────────────────────────── --}}
    @php
        $jmlBerlangsung = $jadwalHariIni->filter(function($j) use ($getStatus) {
            return $getStatus($j->_jam_mulai ?? null, $j->_jam_selesai ?? null) === 'berlangsung';
        })->count();
        $jmlSelesai = $jadwalHariIni->filter(function($j) use ($getStatus) {
            return $getStatus($j->_jam_mulai ?? null, $j->_jam_selesai ?? null) === 'selesai';
        })->count();
        $jmlAkan = $jadwalHariIni->count() - $jmlBerlangsung - $jmlSelesai;
    @endphp
    <div style="padding:8px 14px;border-top:1px solid #f1f5f9;background:#f8fafc;
                 display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:6px;">
        <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
            @if($jmlBerlangsung > 0)
                <span style="font-size:.6rem;font-weight:700;color:#059669;">
                    🟢 {{ $jmlBerlangsung }} berlangsung
                </span>
            @endif
            @if($jmlSelesai > 0)
                <span style="font-size:.6rem;color:#94a3b8;">
                    ✓ {{ $jmlSelesai }} selesai
                </span>
            @endif
            @if($jmlAkan > 0)
                <span style="font-size:.6rem;color:#64748b;">
                    🕐 {{ $jmlAkan }} akan datang
                </span>
            @endif
        </div>
        @if(Route::has('guru.jadwal-mengajar.index'))
            <a href="{{ route('guru.jadwal-mengajar.index') }}"
               style="font-size:.6rem;font-weight:700;color:#4f46e5;text-decoration:none;">
                Lihat jadwal minggu ini →
            </a>
        @endif
    </div>
    @endif

</div>