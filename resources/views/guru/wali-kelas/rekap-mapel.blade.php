@extends('layouts.app')
@section('title', 'Rekap Semua Mapel - Wali Kelas')

@push('styles')
<style>
.rm-wrap{max-width:100%;}
.rm-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;flex-wrap:wrap;gap:10px;}
.rm-title{font-size:15px;font-weight:800;color:#0f172a;display:flex;align-items:center;gap:8px;margin:0;}
.rm-title i{color:#7c3aed;}
.rm-back{display:inline-flex;align-items:center;gap:5px;background:#eef2ff;border:1px solid #c7d2fe;border-radius:7px;padding:5px 12px;font-size:11px;font-weight:700;color:#4338ca;text-decoration:none;}
.rm-filter{background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:14px 16px;margin-bottom:14px;display:flex;align-items:flex-end;gap:10px;flex-wrap:wrap;}
.rm-fg{display:flex;flex-direction:column;gap:4px;}
.rm-fg label{font-size:10px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.05em;}
.rm-fg select,.rm-fg input{border:1.5px solid #e2e8f0;border-radius:8px;padding:7px 10px;font-size:12px;font-family:inherit;background:#f8fafc;min-width:150px;}
.rm-fg select:focus,.rm-fg input:focus{border-color:#7c3aed;background:#fff;outline:none;}
.rm-btn{background:#7c3aed;color:#fff;border:none;border-radius:8px;padding:8px 18px;font-size:12px;font-weight:700;cursor:pointer;}
.rm-btn:hover{background:#6d28d9;}
.rm-mode-tab{display:flex;gap:4px;background:#fff;border:1px solid #e2e8f0;border-radius:8px;padding:3px;}
.rm-mode-btn{padding:6px 14px;border-radius:6px;font-size:11.5px;font-weight:700;color:#64748b;text-decoration:none;}
.rm-mode-btn.active{background:#7c3aed;color:#fff;}
.rm-mapel-group{background:#fff;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;margin-bottom:14px;box-shadow:0 1px 4px rgba(0,0,0,.04);}
.rm-mapel-head{background:#faf5ff;border-bottom:1px solid #e9d5ff;padding:10px 16px;font-size:13px;font-weight:800;color:#6d28d9;display:flex;align-items:center;gap:8px;}
.rm-tbl{width:100%;border-collapse:collapse;font-size:12px;}
.rm-tbl th{background:#f8fafc;color:#64748b;font-size:10px;font-weight:700;text-transform:uppercase;padding:8px 10px;border-bottom:1.5px solid #e2e8f0;text-align:left;}
.rm-tbl td{padding:8px 10px;border-bottom:1px solid #f1f5f9;}
.rm-tbl tr:hover td{background:#faf5ff;}
.rm-chip{display:inline-flex;align-items:center;padding:2px 9px;border-radius:6px;font-size:10.5px;font-weight:700;}
.rm-chip-hadir{background:#dcfce7;color:#15803d;}
.rm-chip-sakit{background:#fef9c3;color:#a16207;}
.rm-chip-izin{background:#e0f2fe;color:#0369a1;}
.rm-chip-alpha{background:#fee2e2;color:#b91c1c;}
.rm-guru-tag{font-size:10.5px;color:#7c3aed;font-weight:700;}
.rm-empty{padding:40px;text-align:center;color:#94a3b8;}
.rm-warn-strip{
    background:#fffbeb;border:1px solid #fde68a;border-radius:8px;
    padding:8px 12px;margin-bottom:12px;font-size:11.5px;color:#92400e;
    display:flex;align-items:center;gap:7px;
}
</style>
@endpush

@section('content')
@php
    $bulanList   = $bulanList   ?? [];
    $mode        = $mode        ?? 'harian';
    $daftarMapel = $daftarMapel ?? collect(); // ← BARU: semua mapel untuk dropdown
    $mapelFilter = request('mata_pelajaran', '');

    $statusChip = fn($s) => match($s){
        'hadir' => 'rm-chip-hadir', 'sakit' => 'rm-chip-sakit',
        'izin'  => 'rm-chip-izin',  'alpha' => 'rm-chip-alpha', default => '',
    };
    $statusLabel = fn($s) => match($s){
        'hadir'=>'Hadir','sakit'=>'Sakit','izin'=>'Izin','alpha'=>'Alpha', default=>'-',
    };

    // Filter tampilan berdasarkan mapel yang dipilih (filter di sisi view,
    // supaya controller tidak perlu diubah drastis — data tetap dari semua guru).
    $absensiHarianTampil  = $absensiHarian  ?? collect();
    $absensiBulananTampil = $absensiBulanan ?? collect();
    if ($mapelFilter !== '') {
        $absensiHarianTampil  = $absensiHarianTampil->filter(fn($items, $mapel) => $mapel === $mapelFilter);
        $absensiBulananTampil = $absensiBulananTampil->filter(fn($items, $mapel) => $mapel === $mapelFilter);
    }
@endphp

<div class="rm-wrap">
    <div class="rm-header">
        <h5 class="rm-title">
            <i class="bi bi-collection-fill"></i>
            Rekap Semua Mapel — {{ $kelas->name ?? $kelas->nama ?? '-' }}
        </h5>
        <a href="{{ route('guru.wali-kelas.index') }}" class="rm-back">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div style="background:#faf5ff;border:1px solid #e9d5ff;border-radius:8px;padding:8px 12px;margin-bottom:12px;font-size:11.5px;color:#6d28d9;">
        <i class="bi bi-info-circle-fill me-1"></i>
        Sebagai <strong>wali kelas</strong>, Anda dapat melihat absensi dari <strong>semua guru mata pelajaran</strong>
        yang mengajar di kelas ini — bukan hanya milik Anda sendiri.
    </div>

    @if($daftarMapel->isEmpty())
        <div class="rm-warn-strip">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <span>Belum ada data master Mata Pelajaran. Hubungi admin untuk melengkapi data mata pelajaran.</span>
        </div>
    @endif

    <div class="rm-mode-tab" style="width:fit-content;margin-bottom:12px;">
        <a href="{{ route('guru.wali-kelas.rekap-mapel', ['mode'=>'harian','tanggal'=>$tanggal]) }}"
           class="rm-mode-btn {{ $mode==='harian'?'active':'' }}">Per Hari</a>
        <a href="{{ route('guru.wali-kelas.rekap-mapel', ['mode'=>'bulanan','bulan'=>$bulan,'tahun'=>$tahun]) }}"
           class="rm-mode-btn {{ $mode==='bulanan'?'active':'' }}">Per Bulan</a>
    </div>

    @if($mode === 'harian')
        <form method="GET" action="{{ route('guru.wali-kelas.rekap-mapel') }}" class="rm-filter">
            <input type="hidden" name="mode" value="harian">
            <div class="rm-fg">
                <label>Tanggal</label>
                <input type="date" name="tanggal" value="{{ $tanggal }}" max="{{ date('Y-m-d') }}">
            </div>
            <div class="rm-fg">
                <label>Mata Pelajaran</label>
                {{-- ── DROPDOWN mata pelajaran (BUKAN input teks manual lagi) ── --}}
                <select name="mata_pelajaran">
                    <option value="">— Semua Mata Pelajaran —</option>
                    @foreach($daftarMapel as $mp)
                        <option value="{{ $mp }}" {{ $mapelFilter === $mp ? 'selected' : '' }}>
                            {{ $mp }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="rm-btn"><i class="bi bi-funnel-fill"></i> Tampilkan</button>
        </form>

        @forelse($absensiHarianTampil as $mapel => $items)
            <div class="rm-mapel-group">
                <div class="rm-mapel-head">
                    <i class="bi bi-book-half"></i> {{ $mapel }}
                    <span style="margin-left:auto;font-size:10.5px;color:#94a3b8;font-weight:600;">{{ $items->count() }} data</span>
                </div>
                <table class="rm-tbl">
                    <thead>
                        <tr><th>Siswa</th><th>Status</th><th>Keterangan</th><th>Diinput oleh (Guru)</th></tr>
                    </thead>
                    <tbody>
                        @foreach($items as $it)
                        <tr>
                            <td>{{ $it->siswa->nama ?? $it->siswa->user?->name ?? '-' }}</td>
                            <td><span class="rm-chip {{ $statusChip($it->status) }}">{{ $statusLabel($it->status) }}</span></td>
                            <td>{{ $it->keterangan ?: '-' }}</td>
                            <td class="rm-guru-tag">{{ $it->guru->nama ?? $it->guru->user?->name ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @empty
            <div class="rm-mapel-group"><div class="rm-empty">
                <i class="bi bi-inbox" style="font-size:2rem;display:block;margin-bottom:8px;"></i>
                Belum ada absensi dari guru manapun untuk tanggal ini{{ $mapelFilter ? ' pada mapel '.$mapelFilter : '' }}.
            </div></div>
        @endforelse

    @else
        <form method="GET" action="{{ route('guru.wali-kelas.rekap-mapel') }}" class="rm-filter">
            <input type="hidden" name="mode" value="bulanan">
            <div class="rm-fg">
                <label>Bulan</label>
                <select name="bulan">
                    @foreach($bulanList as $n=>$nm)
                        <option value="{{ $n }}" {{ $bulan==$n?'selected':'' }}>{{ $nm }}</option>
                    @endforeach
                </select>
            </div>
            <div class="rm-fg">
                <label>Tahun</label>
                <select name="tahun">
                    @foreach(range(date('Y'), date('Y')-3) as $t)
                        <option value="{{ $t }}" {{ $tahun==$t?'selected':'' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <div class="rm-fg">
                <label>Mata Pelajaran</label>
                {{-- ── DROPDOWN mata pelajaran (BUKAN input teks manual lagi) ── --}}
                <select name="mata_pelajaran">
                    <option value="">— Semua Mata Pelajaran —</option>
                    @foreach($daftarMapel as $mp)
                        <option value="{{ $mp }}" {{ $mapelFilter === $mp ? 'selected' : '' }}>
                            {{ $mp }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="rm-btn"><i class="bi bi-funnel-fill"></i> Tampilkan</button>
        </form>

        @forelse($absensiBulananTampil as $mapel => $items)
            @php
                $byGuru = $items->groupBy(fn($i) => $i->guru->nama ?? $i->guru->user?->name ?? '-');
            @endphp
            <div class="rm-mapel-group">
                <div class="rm-mapel-head">
                    <i class="bi bi-book-half"></i> {{ $mapel }}
                    <span style="margin-left:auto;font-size:10.5px;color:#94a3b8;font-weight:600;">{{ $items->count() }} data</span>
                </div>
                @foreach($byGuru as $namaGuru => $rows)
                <table class="rm-tbl" style="margin-bottom:2px;">
                    <thead>
                        <tr>
                            <th colspan="5" style="background:#faf5ff;color:#7c3aed;">
                                <i class="bi bi-person-badge-fill me-1"></i>Guru: {{ $namaGuru }}
                            </th>
                        </tr>
                        <tr><th>Siswa</th><th>Hadir</th><th>Sakit</th><th>Izin</th><th>Alpha</th></tr>
                    </thead>
                    <tbody>
                        @foreach($rows->groupBy('siswa_id') as $sid => $rowsBySiswa)
                        <tr>
                            <td>{{ $rowsBySiswa->first()->siswa->nama ?? '-' }}</td>
                            <td>{{ $rowsBySiswa->where('status','hadir')->count() }}</td>
                            <td>{{ $rowsBySiswa->where('status','sakit')->count() }}</td>
                            <td>{{ $rowsBySiswa->where('status','izin')->count() }}</td>
                            <td>{{ $rowsBySiswa->where('status','alpha')->count() }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endforeach
            </div>
        @empty
            <div class="rm-mapel-group"><div class="rm-empty">
                <i class="bi bi-inbox" style="font-size:2rem;display:block;margin-bottom:8px;"></i>
                Belum ada absensi bulan ini{{ $mapelFilter ? ' pada mapel '.$mapelFilter : '' }}.
            </div></div>
        @endforelse
    @endif
</div>
@endsection