@extends('layouts.app')
@section('title', 'Perizinan')

@section('content')
<div class="space-y-4">

    <div>
        <h2 class="text-sm font-bold text-slate-800 dark:text-slate-100">Pengajuan Izin</h2>
        <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">
            Ajukan izin tidak masuk kepada Kepala Sekolah melalui formulir di bawah ini.
        </p>
    </div>

    @if(session('success'))
    <div class="flex items-center gap-2 px-3.5 py-2.5 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-100 dark:border-emerald-800">
        <p class="text-[11px] text-emerald-700 dark:text-emerald-300 font-medium">{{ session('success') }}</p>
    </div>
    @endif

    @if($errors->any())
    <div class="p-3.5 rounded-xl bg-rose-50 dark:bg-rose-900/20 border border-rose-100 dark:border-rose-800 space-y-1">
        @foreach($errors->all() as $error)
            <p class="text-[11px] text-rose-700 dark:text-rose-300 font-medium">• {{ $error }}</p>
        @endforeach
    </div>
    @endif

    {{-- ── Formulir Perizinan ─────────────────────────────────── --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm p-4">
        <h3 class="text-xs font-bold text-slate-700 dark:text-slate-200 mb-3">Formulir Perizinan</h3>
        <form action="{{ route('guru.perizinan.store') }}" method="POST" class="space-y-3">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1">Nama Lengkap</label>
                    <input type="text" name="nama" value="{{ old('nama', auth()->user()->name) }}"
                           class="w-full text-xs rounded-lg border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 dark:text-slate-100" required>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1">Jabatan</label>
                    <select name="jabatan" class="w-full text-xs rounded-lg border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 dark:text-slate-100" required>
                        <option value="Guru" {{ old('jabatan') == 'Guru' ? 'selected' : '' }}>Guru</option>
                        <option value="Wali Kelas" {{ old('jabatan') == 'Wali Kelas' ? 'selected' : '' }}>Wali Kelas</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1">No. HP</label>
                    <input type="text" name="no_hp" value="{{ old('no_hp') }}" placeholder="08xxxxxxxxxx"
                           class="w-full text-xs rounded-lg border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 dark:text-slate-100" required>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1">Tanggal Izin</label>
                    <input type="date" name="tanggal_izin" value="{{ old('tanggal_izin') }}"
                           class="w-full text-xs rounded-lg border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 dark:text-slate-100" required>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1">Tujuan</label>
                <input type="text" name="tujuan" value="{{ old('tujuan') }}" placeholder="Ex: Menghadiri acara keluarga"
                       class="w-full text-xs rounded-lg border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 dark:text-slate-100" required>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1">Lama Izin</label>
                <select name="lama_izin" class="w-full text-xs rounded-lg border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 dark:text-slate-100" required>
                    <option value="" disabled {{ old('lama_izin') ? '' : 'selected' }}>-- Pilih Lama Izin --</option>
                    <option value="Setengah Hari" {{ old('lama_izin') == 'Setengah Hari' ? 'selected' : '' }}>Setengah Hari</option>
                    <option value="1 Hari" {{ old('lama_izin') == '1 Hari' ? 'selected' : '' }}>1 Hari</option>
                    <option value="2 Hari" {{ old('lama_izin') == '2 Hari' ? 'selected' : '' }}>2 Hari</option>
                    <option value="3 Hari" {{ old('lama_izin') == '3 Hari' ? 'selected' : '' }}>3 Hari</option>
                    <option value="Lebih dari 3 Hari" {{ old('lama_izin') == 'Lebih dari 3 Hari' ? 'selected' : '' }}>Lebih dari 3 Hari</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1">Alasan</label>
                <textarea name="alasan" rows="3" placeholder="Jelaskan alasan izin Anda..."
                          class="w-full text-xs rounded-lg border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 dark:text-slate-100" required>{{ old('alasan') }}</textarea>
            </div>

            <div class="flex justify-end pt-1">
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-xs font-semibold rounded-lg hover:bg-indigo-700 shadow transition">
                    <i class="bi bi-send me-1"></i> Kirim Pengajuan Izin
                </button>
            </div>
        </form>
    </div>

    {{-- ── Riwayat Pengajuan Izin ─────────────────────────────────── --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
        <div class="px-3.5 py-3 border-b border-slate-100 dark:border-slate-700">
            <h3 class="text-xs font-bold text-slate-700 dark:text-slate-200">Riwayat Pengajuan Izin Saya</h3>
        </div>
        <div class="divide-y divide-slate-50 dark:divide-slate-700/30">
            @forelse($riwayat as $r)
            <div class="px-3.5 py-3">
                <div class="flex items-start justify-between gap-2">
                    <div class="min-w-0">
                        <p class="text-xs font-semibold text-slate-700 dark:text-slate-200">{{ $r->tujuan }}</p>
                        <p class="text-[10px] text-slate-400 mt-0.5">
                            Tanggal Izin: {{ $r->tanggal_izin->translatedFormat('d M Y') }} · Lama: {{ $r->lama_izin }}
                        </p>
                        <p class="text-[10px] text-slate-400">Diajukan: {{ $r->created_at->translatedFormat('d M Y, H:i') }}</p>
                    </div>
                    @if($r->status === 'pending')
                        <span class="shrink-0 text-[10px] font-semibold px-2 py-0.5 rounded-full bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300">Menunggu</span>
                    @elseif($r->status === 'disetujui')
                        <span class="shrink-0 text-[10px] font-semibold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300">Disetujui</span>
                    @else
                        <span class="shrink-0 text-[10px] font-semibold px-2 py-0.5 rounded-full bg-rose-100 text-rose-700 dark:bg-rose-900/40 dark:text-rose-300">Ditolak</span>
                    @endif
                </div>
                @if($r->catatan_admin)
                <p class="text-[10px] text-slate-500 bg-slate-50 dark:bg-slate-900 mt-2 px-2 py-1.5 rounded-lg italic">
                    Catatan Kepala Sekolah: {{ $r->catatan_admin }}
                </p>
                @endif
            </div>
            @empty
            <div class="text-center py-8">
                <p class="text-slate-400 text-xs">Belum ada pengajuan izin.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection