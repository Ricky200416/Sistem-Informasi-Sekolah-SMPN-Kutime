@extends('layouts.app')
@section('title', 'Perizinan Guru')

@section('content')
<div class="space-y-4">

    <div>
        <h2 class="text-sm font-bold text-slate-800 dark:text-slate-100">Perizinan Guru</h2>
        <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">
            Kelola pengajuan izin dari guru — setujui atau tolak permohonan.
        </p>
    </div>

    @if(session('success'))
    <div class="flex items-center gap-2 px-3.5 py-2.5 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-100 dark:border-emerald-800">
        <p class="text-[11px] text-emerald-700 dark:text-emerald-300 font-medium">{{ session('success') }}</p>
    </div>
    @endif

    {{-- ── KPI ─────────────────────────────────── --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        <a href="{{ route('admin.perizinan.index') }}" class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm px-4 py-3">
            <p class="text-lg font-black text-slate-700 dark:text-slate-200 leading-none">{{ $ringkasan['total'] }}</p>
            <p class="text-[10px] text-slate-400 mt-0.5">Total Pengajuan</p>
        </a>
        <a href="{{ route('admin.perizinan.index', ['status' => 'pending']) }}" class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm px-4 py-3">
            <p class="text-lg font-black text-amber-600 dark:text-amber-400 leading-none">{{ $ringkasan['pending'] }}</p>
            <p class="text-[10px] text-slate-400 mt-0.5">Menunggu</p>
        </a>
        <a href="{{ route('admin.perizinan.index', ['status' => 'disetujui']) }}" class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm px-4 py-3">
            <p class="text-lg font-black text-emerald-600 dark:text-emerald-400 leading-none">{{ $ringkasan['disetujui'] }}</p>
            <p class="text-[10px] text-slate-400 mt-0.5">Disetujui</p>
        </a>
        <a href="{{ route('admin.perizinan.index', ['status' => 'ditolak']) }}" class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm px-4 py-3">
            <p class="text-lg font-black text-rose-600 dark:text-rose-400 leading-none">{{ $ringkasan['ditolak'] }}</p>
            <p class="text-[10px] text-slate-400 mt-0.5">Ditolak</p>
        </a>
    </div>

    {{-- ── Tabel Daftar Perizinan ─────────────────────────────────── --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Guru</th>
                        <th>Tujuan</th>
                        <th>Tanggal Izin</th>
                        <th>Lama</th>
                        <th>Diajukan</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($perizinans as $p)
                    <tr>
                        <td>
                            <p class="font-semibold text-slate-700 dark:text-slate-200">{{ $p->nama }}</p>
                            <p class="text-[10px] text-slate-400">{{ $p->jabatan }} · {{ $p->no_hp }}</p>
                        </td>
                        <td>{{ $p->tujuan }}</td>
                        <td>{{ $p->tanggal_izin->translatedFormat('d M Y') }}</td>
                        <td>{{ $p->lama_izin }}</td>
                        <td>{{ $p->created_at->translatedFormat('d M Y, H:i') }}</td>
                        <td>
                            @if($p->status === 'pending')
                                <span class="badge bg-warning">Menunggu</span>
                            @elseif($p->status === 'disetujui')
                                <span class="badge bg-success">Disetujui</span>
                            @else
                                <span class="badge bg-danger">Ditolak</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalDetail{{ $p->id }}">
                                <i class="bi bi-eye"></i> Detail
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-8 text-slate-400">Belum ada pengajuan izin.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ── MODAL DETAIL PER-BARIS ─────────────────────────────────── --}}
@foreach($perizinans as $p)
<div class="modal fade" id="modalDetail{{ $p->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Pengajuan Izin</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body space-y-2">
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <p class="text-[10px] text-slate-400">Nama</p>
                        <p class="text-xs font-semibold text-slate-700 dark:text-slate-200">{{ $p->nama }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-400">Jabatan</p>
                        <p class="text-xs font-semibold text-slate-700 dark:text-slate-200">{{ $p->jabatan }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-400">No. HP</p>
                        <p class="text-xs font-semibold text-slate-700 dark:text-slate-200">{{ $p->no_hp }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-400">Tanggal Izin</p>
                        <p class="text-xs font-semibold text-slate-700 dark:text-slate-200">{{ $p->tanggal_izin->translatedFormat('d M Y') }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-400">Lama Izin</p>
                        <p class="text-xs font-semibold text-slate-700 dark:text-slate-200">{{ $p->lama_izin }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-400">Status</p>
                        <p class="text-xs font-semibold text-slate-700 dark:text-slate-200">{{ ucfirst($p->status) }}</p>
                    </div>
                </div>
                <div>
                    <p class="text-[10px] text-slate-400">Tujuan</p>
                    <p class="text-xs text-slate-700 dark:text-slate-200">{{ $p->tujuan }}</p>
                </div>
                <div>
                    <p class="text-[10px] text-slate-400">Alasan</p>
                    <p class="text-xs text-slate-700 dark:text-slate-200">{{ $p->alasan }}</p>
                </div>

                @if($p->status === 'pending')
                <form action="{{ route('admin.perizinan.tolak', $p->id) }}" method="POST" class="pt-2 border-t border-slate-100 dark:border-slate-700 mt-2">
                    @csrf
                    <label class="text-[10px] text-slate-400">Catatan (wajib diisi jika ditolak)</label>
                    <textarea name="catatan_admin" rows="2" class="form-control mt-1" placeholder="Alasan penolakan / catatan persetujuan..."></textarea>
                    <div class="flex justify-end gap-2 mt-2">
                        <button type="submit" formaction="{{ route('admin.perizinan.tolak', $p->id) }}" class="btn btn-outline-danger btn-sm">
                            <i class="bi bi-x-lg"></i> Tidak Setujui
                        </button>
                        <button type="submit" formaction="{{ route('admin.perizinan.setujui', $p->id) }}" class="btn btn-success btn-sm">
                            <i class="bi bi-check-lg"></i> Setujui
                        </button>
                    </div>
                </form>
                @elseif($p->catatan_admin)
                <div class="pt-2 border-t border-slate-100 dark:border-slate-700 mt-2">
                    <p class="text-[10px] text-slate-400">Catatan Kepala Sekolah</p>
                    <p class="text-xs text-slate-700 dark:text-slate-200 italic">{{ $p->catatan_admin }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection