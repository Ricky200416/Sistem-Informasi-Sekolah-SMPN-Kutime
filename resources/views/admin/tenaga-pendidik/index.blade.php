@extends('layouts.app')
@section('title', 'Kelola Tenaga Pendidik')

@section('content')
<div class="space-y-4 max-w-full mx-auto container-fluid px-2 pb-6"
     x-data="{
        editOpen:false,
        editData:{},
        openEdit(g){
            this.editData = { ...g };
            this.editOpen = true;
        }
     }">

    {{-- ── Header ── --}}
    <div class="relative overflow-hidden flex items-center justify-between flex-wrap gap-3 bg-white dark:bg-slate-800 px-4 py-3 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm">
        <div class="flex items-center gap-3 relative z-10">
            <div class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-900/30 flex items-center justify-center text-xl shadow-inner">
                🧑‍🏫
            </div>
            <div>
                <h2 class="text-sm font-black text-slate-800 dark:text-slate-100 leading-tight">Kelola Tenaga Pendidik</h2>
                <p class="text-[10px] text-slate-400 dark:text-slate-500 font-bold tracking-wide uppercase">
                    Atur jabatan, mata pelajaran, kontak & tampilan di website publik
                </p>
            </div>
        </div>
        <div class="text-[10px] font-bold text-slate-400 relative z-10">
            Total: {{ $guruList->count() }} guru &bull; Tampil di website: {{ $guruList->where('tampil_website', true)->count() }}
        </div>
    </div>

    {{-- ── Alert ── --}}
    @if(session('success'))
    <div class="alert alert-success">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- ── Grid Kartu Guru ── --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3" id="guruCardGrid">
        @forelse($guruList as $g)
        @php
            $waliKelas = $g->user?->homeroomGroups?->first()
                ?? (($g->kelas_id ?? null) && class_exists('App\Models\StudyGroup')
                    ? \App\Models\StudyGroup::find($g->kelas_id) : null);
        @endphp
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden guru-admin-card"
             data-id="{{ $g->id }}">

            <div class="relative">
                <div class="aspect-square bg-slate-100 dark:bg-slate-700 flex items-center justify-center overflow-hidden">
                    @if($g->user?->photo)
                        <img src="{{ asset('storage/'.$g->user->photo) }}" class="w-full h-full object-cover" alt="">
                    @else
                        <span class="font-lora font-bold text-2xl text-white flex items-center justify-center w-full h-full"
                              style="background:linear-gradient(135deg,#0e2356,#183580)">
                            {{ strtoupper(substr($g->nama ?? $g->user->name ?? '-', 0, 1)) }}
                        </span>
                    @endif
                </div>

                {{-- Toggle tampil di website --}}
                <form action="{{ route('admin.tenaga-pendidik.toggle', $g->id) }}" method="POST" class="absolute top-2 right-2">
                    @csrf
                    <button type="submit" title="Tampilkan / Sembunyikan di website"
                        class="w-6 h-6 rounded-full flex items-center justify-center shadow-md
                               {{ $g->tampil_website ? 'bg-emerald-500' : 'bg-slate-400' }} text-white">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            @if($g->tampil_website)
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            @else
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/>
                            @endif
                        </svg>
                    </button>
                </form>
            </div>

            <div class="p-2.5">
                <p class="text-xs font-bold text-slate-800 dark:text-slate-100 truncate">
                    {{ $g->nama ?? $g->user->name ?? '-' }}
                </p>
                <p class="text-[10px] text-slate-400 truncate mb-1.5">{{ $g->jabatan ?: 'Belum diatur' }}</p>

                @if($g->mata_pelajaran)
                <span class="inline-block px-1.5 py-0.5 rounded-md bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 text-[9px] font-semibold mb-1.5 truncate max-w-full">
                    {{ $g->mata_pelajaran }}
                </span>
                @endif

                @if($waliKelas)
                <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-md bg-amber-50 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 text-[9px] font-semibold mb-1.5 ml-1">
                    Wali {{ $waliKelas->name }}
                </span>
                @endif

                <button type="button"
                    @click="openEdit({
                        id: {{ $g->id }},
                        nama: @js($g->nama ?? $g->user->name ?? '-'),
                        jabatan: @js($g->jabatan),
                        mata_pelajaran: @js($g->mata_pelajaran),
                        no_hp: @js($g->no_hp),
                        tampil_website: {{ $g->tampil_website ? 'true' : 'false' }}
                    })"
                    class="w-full mt-1 inline-flex items-center justify-center gap-1 px-2 py-1.5 rounded-lg
                           bg-slate-50 dark:bg-slate-700 hover:bg-slate-100 dark:hover:bg-slate-600
                           text-slate-600 dark:text-slate-300 text-[10px] font-bold transition">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit Profil Publik
                </button>
            </div>
        </div>
        @empty
        <div class="col-span-full">
            <div class="empty-state">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <p>Belum ada data guru. Tambahkan melalui menu Kelola User.</p>
            </div>
        </div>
        @endforelse
    </div>

    {{-- ── Modal Edit ── --}}
    <div x-show="editOpen" x-cloak class="modal-overlay" style="position:fixed;z-index:100" @click.self="editOpen=false">
        <div class="modal-box" @click.stop>
            <form :action="'/admin/tenaga-pendidik/' + editData.id" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <p class="modal-title">Edit Profil Publik — <span x-text="editData.nama"></span></p>
                    <button type="button" class="btn-close" @click="editOpen=false"></button>
                </div>
                <div class="modal-body space-y-3">
                    <div class="form-group">
                        <label>Jabatan</label>
                        <input type="text" name="jabatan" x-model="editData.jabatan"
                               placeholder="Contoh: Guru Mata Pelajaran / Kepala Sekolah">
                    </div>
                    <div class="form-group">
                        <label>Mata Pelajaran</label>
                        <input type="text" name="mata_pelajaran" x-model="editData.mata_pelajaran"
                               placeholder="Contoh: Matematika, IPA">
                        <p class="form-text">Pisahkan dengan koma jika lebih dari satu.</p>
                    </div>
                    <div class="form-group">
                        <label>No. HP / WhatsApp (untuk ditampilkan publik)</label>
                        <input type="text" name="no_hp" x-model="editData.no_hp" placeholder="08xxxxxxxxxx">
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="tampil_website" value="1" x-model="editData.tampil_website" id="tampilCb">
                        <label for="tampilCb" class="form-check-label">Tampilkan di halaman Beranda website</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="editOpen=false">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection