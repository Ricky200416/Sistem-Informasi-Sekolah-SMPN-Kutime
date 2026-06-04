@extends('layouts.app')

@section('title', 'Data Diri Guru')

@section('content')
@php
    $g = $user->guru;

    /*
     * KELAS WALI — ambil dari variabel eksplisit $kelasWali (dikirim controller)
     * jika tidak ada, fallback ke relasi $g->kelas
     * Ini memastikan data tampil meski struktur DB berbeda-beda.
     */
    $kelasWaliObj  = $kelasWali ?? $g?->kelas ?? null;
    $kelasWaliId   = $kelasWaliObj?->id
                  ?? $g?->kelas_id
                  ?? $g?->kelas_wali_id
                  ?? null;

    /*
     * $kelasList dikirim dari controller->show().
     * Fallback collect() agar tidak undefined.
     */
    $kelasList = $kelasList ?? collect();
@endphp

{{-- ── Flash Messages ─────────────────────────────────────── --}}
@if(session('success'))
    <div class="px-3 py-2 bg-green-50 border border-green-200 text-green-700 rounded-lg text-xs mb-4">
        {{ session('success') }}
    </div>
@endif
@if(session('error') || $errors->any())
    <div class="px-3 py-2 bg-red-50 border border-red-200 text-red-700 rounded-lg text-xs mb-4">
        {{ session('error') }}
        @if($errors->any())
            <ul class="list-disc list-inside mt-1">
                @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
            </ul>
        @endif
    </div>
@endif

{{-- ══ LAYOUT 2 KOLOM × 2 BARIS ══ --}}
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

    {{-- ┌─ KARTU 1: PROFIL AKUN ─────────────────────────────── --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Profil Akun</h3>
            <button onclick="bukaModal('modalAkun')"
                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-600
                           text-xs font-semibold hover:bg-indigo-100 transition">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                </svg>
                Edit
            </button>
        </div>

        <div class="flex items-center gap-3">
            @if($user->photo)
                <img src="{{ Storage::url($user->photo) }}" alt="Foto"
                     class="w-14 h-[74px] object-cover rounded-lg border border-slate-200 shrink-0">
            @else
                <div class="w-14 h-[74px] rounded-lg bg-indigo-100 flex items-center justify-center
                            text-indigo-600 text-2xl font-bold shrink-0">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
            @endif
            <div class="min-w-0 space-y-1.5">
                <div>
                    <p class="text-[10px] text-slate-400 font-medium">Nama Akun</p>
                    <p class="text-sm font-semibold text-slate-800 truncate">{{ $user->name }}</p>
                </div>
                <div>
                    <p class="text-[10px] text-slate-400 font-medium">Email</p>
                    <p class="text-xs text-slate-600 truncate">{{ $user->email }}</p>
                </div>
                <div>
                    <p class="text-[10px] text-slate-400 font-medium">NIP</p>
                    <p class="text-xs text-slate-600">{{ $g?->nip ?? '—' }}</p>
                </div>
            </div>
        </div>

        {{-- Wali Kelas di kartu profil --}}
        @if($kelasWaliObj)
            <div class="mt-3 pt-3 border-t border-slate-100">
                <p class="text-[10px] text-slate-400 font-medium mb-0.5">Wali Kelas</p>
                <p class="text-xs font-semibold text-indigo-700">
                    {{ $kelasWaliObj->nama }}
                    <span class="text-slate-400 font-normal">
                        @if($kelasWaliObj->tingkat) · {{ $kelasWaliObj->tingkat }} @endif
                        @if($kelasWaliObj->tahun_ajaran) · {{ $kelasWaliObj->tahun_ajaran }} @endif
                    </span>
                </p>
            </div>
        @else
            <div class="mt-3 pt-3 border-t border-slate-100">
                <p class="text-[10px] text-slate-400 font-medium mb-0.5">Wali Kelas</p>
                <p class="text-xs text-slate-400 italic">Belum menjadi wali kelas</p>
            </div>
        @endif
    </div>

    {{-- ┌─ KARTU 2: IDENTITAS & TUGAS ───────────────────────── --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Identitas & Tugas</h3>
            <button onclick="bukaModal('modalIdentitas')"
                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-600
                           text-xs font-semibold hover:bg-indigo-100 transition">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                </svg>
                Edit
            </button>
        </div>
        <div class="grid grid-cols-2 gap-x-4 gap-y-3">
            @foreach([
                ['NIP', $g?->nip ?? '—'],
                ['Nama Lengkap', $g?->nama ?? '—'],
                ['Jenis Kelamin', $g?->jk === 'L' ? 'Laki-laki' : ($g?->jk === 'P' ? 'Perempuan' : '—')],
                ['Tempat Lahir', $g?->tempat_lahir ?? '—'],
                ['Tanggal Lahir', $g?->tanggal_lahir?->translatedFormat('d F Y') ?? ($g?->tanggal_lahir ? \Carbon\Carbon::parse($g->tanggal_lahir)->translatedFormat('d F Y') : '—')],
                ['Pendidikan', $g?->pendidikan_terakhir ?? '—'],
            ] as [$lbl, $val])
            <div>
                <p class="text-[10px] text-slate-400 font-medium">{{ $lbl }}</p>
                <p class="text-xs text-slate-700 font-medium mt-0.5 truncate" title="{{ $val }}">{{ $val }}</p>
            </div>
            @endforeach

            {{-- Wali kelas di kartu identitas --}}
            <div class="col-span-2">
                <p class="text-[10px] text-slate-400 font-medium">Wali Kelas</p>
                @if($kelasWaliObj)
                    <div class="flex items-center gap-1.5 mt-0.5">
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md
                                     bg-amber-50 border border-amber-200 text-amber-700
                                     text-[10px] font-semibold">
                            ⭐ {{ $kelasWaliObj->nama }}
                            @if($kelasWaliObj->tingkat)
                                <span class="text-amber-500">· {{ $kelasWaliObj->tingkat }}</span>
                            @endif
                        </span>
                    </div>
                @else
                    <p class="text-xs text-slate-400 italic mt-0.5">Belum menjadi wali kelas</p>
                @endif
            </div>
        </div>
    </div>

    {{-- ┌─ KARTU 3: DATA PRIBADI ─────────────────────────────── --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Data Pribadi</h3>
            <button onclick="bukaModal('modalPribadi')"
                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-600
                           text-xs font-semibold hover:bg-indigo-100 transition">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                </svg>
                Edit
            </button>
        </div>
        <div class="grid grid-cols-2 gap-x-4 gap-y-3">
            @foreach([
                ['Nama Lengkap', $g?->nama ?? '—'],
                ['Jenis Kelamin', $g?->jk === 'L' ? 'Laki-laki' : ($g?->jk === 'P' ? 'Perempuan' : '—')],
                ['Tempat Lahir', $g?->tempat_lahir ?? '—'],
                ['Tanggal Lahir', $g?->tanggal_lahir?->translatedFormat('d F Y') ?? ($g?->tanggal_lahir ? \Carbon\Carbon::parse($g->tanggal_lahir)->translatedFormat('d F Y') : '—')],
                ['Pendidikan Terakhir', $g?->pendidikan_terakhir ?? '—'],
            ] as [$lbl, $val])
            <div>
                <p class="text-[10px] text-slate-400 font-medium">{{ $lbl }}</p>
                <p class="text-xs text-slate-700 font-medium mt-0.5 truncate" title="{{ $val }}">{{ $val }}</p>
            </div>
            @endforeach
        </div>
    </div>

    {{-- ┌─ KARTU 4: DATA KEPEGAWAIAN ────────────────────────── --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Data Kepegawaian</h3>
            <button onclick="bukaModal('modalKepegawaian')"
                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-600
                           text-xs font-semibold hover:bg-indigo-100 transition">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                </svg>
                Edit
            </button>
        </div>
        <div class="grid grid-cols-2 gap-x-4 gap-y-3">
            @foreach([
                ['Status Pegawai', $g?->status_pegawai ?? '—'],
                ['Pangkat / Gol.', $g?->pangkat_gol_ruang ?? '—'],
                ['No. SK Pertama', $g?->no_sk_pertama ?? '—'],
                ['No. SK Terakhir', $g?->no_sk_terakhir ?? '—'],
            ] as [$lbl, $val])
            <div>
                <p class="text-[10px] text-slate-400 font-medium">{{ $lbl }}</p>
                <p class="text-xs text-slate-700 font-medium mt-0.5 truncate" title="{{ $val }}">{{ $val }}</p>
            </div>
            @endforeach
        </div>
    </div>

</div>{{-- /grid --}}


{{-- ══════════════════════════════════════════════════════
     OVERLAY MODAL — AKUN
══════════════════════════════════════════════════════ --}}
<div id="modalAkun" onclick="if(event.target===this)tutupModal('modalAkun')"
     class="fixed inset-0 z-[999] hidden items-center justify-center p-4"
     style="background:rgba(0,0,0,.45);backdrop-filter:blur(4px)">
    <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden">
        <div class="flex items-center justify-between px-5 py-3.5 border-b border-slate-100 bg-slate-50">
            <h4 class="text-sm font-bold text-slate-700">Edit Profil Akun</h4>
            <button onclick="tutupModal('modalAkun')"
                    class="w-7 h-7 flex items-center justify-center rounded-lg hover:bg-slate-200
                           text-slate-400 hover:text-slate-600 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form action="{{ route('guru.profil.update') }}" method="POST"
              enctype="multipart/form-data" class="p-5 space-y-4">
            @csrf @method('PUT')
            <input type="hidden" name="_section" value="akun">

            {{-- Foto --}}
            <div class="flex items-center gap-4">
                <div id="akun_photoDropZone"
                     class="shrink-0 w-16 h-[85px] border-2 border-dashed border-slate-300 rounded-xl
                            flex items-center justify-center overflow-hidden cursor-pointer
                            hover:border-indigo-400 transition-colors relative"
                     onclick="document.getElementById('akun_photoInput').click()">
                    <div id="akun_photoPreviewWrap" class="w-full h-full">
                        @if($user->photo)
                            <img id="akun_photoPreview" src="{{ Storage::url($user->photo) }}"
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-2xl">📸</div>
                        @endif
                    </div>
                    <input type="file" name="photo" id="akun_photoInput" accept="image/*" class="hidden">
                </div>
                <div class="text-xs text-slate-500 leading-relaxed">
                    Klik foto untuk ganti.<br>
                    Maks <strong>2 MB</strong> · JPG, PNG, WebP<br>
                    Rasio <strong>3:4</strong> disarankan
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div class="col-span-2">
                    <label class="block text-xs font-semibold text-slate-600 mb-1">
                        Nama Akun <span class="text-red-400">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400">
                </div>
                <div class="col-span-2">
                    <label class="block text-xs font-semibold text-slate-600 mb-1">
                        Email <span class="text-red-400">*</span>
                    </label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Password Baru</label>
                    <input type="password" name="password"
                           placeholder="Kosongkan jika tidak diubah" autocomplete="new-password"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" autocomplete="new-password"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400">
                </div>
            </div>

            <div class="flex justify-end gap-2 pt-1">
                <button type="button" onclick="tutupModal('modalAkun')"
                        class="px-4 py-2 rounded-lg border border-slate-300 text-slate-600
                               text-xs font-semibold hover:bg-slate-50 transition">Batal</button>
                <button type="submit"
                        class="px-5 py-2 rounded-lg bg-indigo-600 text-white text-xs font-semibold
                               hover:bg-indigo-700 transition shadow-sm">Simpan</button>
            </div>
        </form>
    </div>
</div>


{{-- ══════════════════════════════════════════════════════
     OVERLAY MODAL — IDENTITAS & TUGAS
     KEY FIX: dropdown wali kelas otomatis terpilih
     berdasarkan $kelasWaliId yang sudah di-resolve controller
══════════════════════════════════════════════════════ --}}
<div id="modalIdentitas" onclick="if(event.target===this)tutupModal('modalIdentitas')"
     class="fixed inset-0 z-[999] hidden items-center justify-center p-4"
     style="background:rgba(0,0,0,.45);backdrop-filter:blur(4px)">
    <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden">
        <div class="flex items-center justify-between px-5 py-3.5 border-b border-slate-100 bg-slate-50">
            <h4 class="text-sm font-bold text-slate-700">Edit Identitas & Tugas</h4>
            <button onclick="tutupModal('modalIdentitas')"
                    class="w-7 h-7 flex items-center justify-center rounded-lg hover:bg-slate-200
                           text-slate-400 hover:text-slate-600 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form action="{{ route('guru.profil.update') }}" method="POST" class="p-5 space-y-4">
            @csrf @method('PUT')
            <input type="hidden" name="_section" value="identitas">

            {{-- NIP --}}
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">NIP</label>
                <input type="text" name="nip" value="{{ old('nip', $g?->nip) }}" maxlength="30"
                       placeholder="Nomor Induk Pegawai"
                       class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                              focus:outline-none focus:ring-2 focus:ring-indigo-400">
            </div>

            {{-- ── WALI KELAS DROPDOWN ── --}}
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">
                    Wali Kelas
                </label>

                {{-- Preview kelas saat ini (muncul jika sudah ada) --}}
                @if($kelasWaliObj)
                <div id="kelasWaliCurrentBadge"
                     class="flex items-center gap-2 mb-2 px-3 py-2 rounded-lg
                            bg-amber-50 border border-amber-200">
                    <span class="text-amber-500 text-sm">⭐</span>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-amber-800 truncate">
                            {{ $kelasWaliObj->nama }}
                            @if($kelasWaliObj->tingkat) · {{ $kelasWaliObj->tingkat }} @endif
                            @if($kelasWaliObj->tahun_ajaran) · {{ $kelasWaliObj->tahun_ajaran }} @endif
                        </p>
                        <p class="text-[10px] text-amber-600">Kelas wali saat ini</p>
                    </div>
                </div>
                @endif

                <select name="wali_kelas" id="selectWaliKelas"
                        onchange="updateKelasPreview(this)"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                               focus:outline-none focus:ring-2 focus:ring-indigo-400">
                    <option value="">— Tidak Menjadi Wali Kelas —</option>
                    @foreach($kelasList as $kls)
                        @php
                            /*
                             * old() diutamakan (setelah validation error).
                             * Jika tidak ada old, bandingkan dengan $kelasWaliId
                             * yang sudah di-resolve oleh controller.
                             */
                            $isSelected = old('wali_kelas') !== null
                                ? (string) old('wali_kelas') === (string) $kls->id
                                : (string) $kelasWaliId === (string) $kls->id;
                        @endphp
                        <option value="{{ $kls->id }}"
                                data-label="{{ $kls->nama }}{{ $kls->tingkat ? ' · '.$kls->tingkat : '' }}{{ $kls->tahun_ajaran ? ' · '.$kls->tahun_ajaran : '' }}"
                                {{ $isSelected ? 'selected' : '' }}>
                            {{ $kls->nama }}
                            @if($kls->tingkat) · {{ $kls->tingkat }} @endif
                            @if($kls->tahun_ajaran) · {{ $kls->tahun_ajaran }} @endif
                        </option>
                    @endforeach
                </select>

                {{-- Info dinamis kelas yang dipilih dari dropdown --}}
                <div id="kelasWaliSelectedInfo" class="mt-2 hidden">
                    <div class="flex items-center gap-2 px-3 py-2 rounded-lg bg-indigo-50 border border-indigo-200">
                        <span class="text-indigo-500 text-sm">🏫</span>
                        <div class="flex-1 min-w-0">
                            <p id="kelasWaliSelectedLabel"
                               class="text-xs font-semibold text-indigo-800 truncate"></p>
                            <p class="text-[10px] text-indigo-500">Dipilih — klik Simpan untuk menyimpan</p>
                        </div>
                    </div>
                </div>

                <p class="text-[10px] text-slate-400 mt-1.5">
                    Satu guru hanya bisa menjadi wali satu kelas.
                    Pilih "— Tidak Menjadi Wali Kelas —" untuk melepas tugas.
                </p>
            </div>

            <div class="flex justify-end gap-2 pt-1">
                <button type="button" onclick="tutupModal('modalIdentitas')"
                        class="px-4 py-2 rounded-lg border border-slate-300 text-slate-600
                               text-xs font-semibold hover:bg-slate-50 transition">Batal</button>
                <button type="submit"
                        class="px-5 py-2 rounded-lg bg-indigo-600 text-white text-xs font-semibold
                               hover:bg-indigo-700 transition shadow-sm">Simpan</button>
            </div>
        </form>
    </div>
</div>


{{-- ══════════════════════════════════════════════════════
     OVERLAY MODAL — DATA PRIBADI
══════════════════════════════════════════════════════ --}}
<div id="modalPribadi" onclick="if(event.target===this)tutupModal('modalPribadi')"
     class="fixed inset-0 z-[999] hidden items-center justify-center p-4"
     style="background:rgba(0,0,0,.45);backdrop-filter:blur(4px)">
    <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden">
        <div class="flex items-center justify-between px-5 py-3.5 border-b border-slate-100 bg-slate-50">
            <h4 class="text-sm font-bold text-slate-700">Edit Data Pribadi</h4>
            <button onclick="tutupModal('modalPribadi')"
                    class="w-7 h-7 flex items-center justify-center rounded-lg hover:bg-slate-200
                           text-slate-400 hover:text-slate-600 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form action="{{ route('guru.profil.update') }}" method="POST" class="p-5 space-y-3">
            @csrf @method('PUT')
            <input type="hidden" name="_section" value="pribadi">

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Nama Lengkap</label>
                <input type="text" name="nama" value="{{ old('nama', $g?->nama) }}"
                       class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                              focus:outline-none focus:ring-2 focus:ring-indigo-400">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Jenis Kelamin</label>
                    <select name="jk"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                                   focus:outline-none focus:ring-2 focus:ring-indigo-400">
                        <option value="">— Pilih —</option>
                        <option value="L" {{ old('jk', $g?->jk) === 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('jk', $g?->jk) === 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $g?->tempat_lahir) }}"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-indigo-400">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir"
                           value="{{ old('tanggal_lahir', $g?->tanggal_lahir
                               ? (is_string($g->tanggal_lahir)
                                   ? \Carbon\Carbon::parse($g->tanggal_lahir)->format('Y-m-d')
                                   : $g->tanggal_lahir->format('Y-m-d'))
                               : '') }}"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-indigo-400">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Pendidikan Terakhir</label>
                    <select name="pendidikan_terakhir"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                                   focus:outline-none focus:ring-2 focus:ring-indigo-400">
                        <option value="">— Pilih —</option>
                        @foreach(['SMA/SMK','D1','D2','D3','D4','S1','S2','S3'] as $pend)
                            <option value="{{ $pend }}"
                                {{ old('pendidikan_terakhir', $g?->pendidikan_terakhir) === $pend ? 'selected' : '' }}>
                                {{ $pend }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex justify-end gap-2 pt-1">
                <button type="button" onclick="tutupModal('modalPribadi')"
                        class="px-4 py-2 rounded-lg border border-slate-300 text-slate-600
                               text-xs font-semibold hover:bg-slate-50 transition">Batal</button>
                <button type="submit"
                        class="px-5 py-2 rounded-lg bg-indigo-600 text-white text-xs font-semibold
                               hover:bg-indigo-700 transition shadow-sm">Simpan</button>
            </div>
        </form>
    </div>
</div>


{{-- ══════════════════════════════════════════════════════
     OVERLAY MODAL — DATA KEPEGAWAIAN
══════════════════════════════════════════════════════ --}}
<div id="modalKepegawaian" onclick="if(event.target===this)tutupModal('modalKepegawaian')"
     class="fixed inset-0 z-[999] hidden items-center justify-center p-4"
     style="background:rgba(0,0,0,.45);backdrop-filter:blur(4px)">
    <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden">
        <div class="flex items-center justify-between px-5 py-3.5 border-b border-slate-100 bg-slate-50">
            <h4 class="text-sm font-bold text-slate-700">Edit Data Kepegawaian</h4>
            <button onclick="tutupModal('modalKepegawaian')"
                    class="w-7 h-7 flex items-center justify-center rounded-lg hover:bg-slate-200
                           text-slate-400 hover:text-slate-600 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form action="{{ route('guru.profil.update') }}" method="POST" class="p-5 space-y-3">
            @csrf @method('PUT')
            <input type="hidden" name="_section" value="kepegawaian">

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Status Pegawai</label>
                    <select name="status_pegawai"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                                   focus:outline-none focus:ring-2 focus:ring-indigo-400">
                        <option value="">— Pilih —</option>
                        @foreach(['PNS','PPPK','Honorer','Kontrak','GTT','Lainnya'] as $sp)
                            <option value="{{ $sp }}"
                                {{ old('status_pegawai', $g?->status_pegawai) === $sp ? 'selected' : '' }}>
                                {{ $sp }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Pangkat / Gol. Ruang</label>
                    <input type="text" name="pangkat_gol_ruang"
                           value="{{ old('pangkat_gol_ruang', $g?->pangkat_gol_ruang) }}"
                           placeholder="Contoh: Penata Muda / III-a" maxlength="100"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-indigo-400">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">No. SK Pertama</label>
                    <input type="text" name="no_sk_pertama"
                           value="{{ old('no_sk_pertama', $g?->no_sk_pertama) }}" maxlength="150"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-indigo-400">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">No. SK Terakhir</label>
                    <input type="text" name="no_sk_terakhir"
                           value="{{ old('no_sk_terakhir', $g?->no_sk_terakhir) }}" maxlength="150"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-indigo-400">
                </div>
            </div>

            <div class="flex justify-end gap-2 pt-1">
                <button type="button" onclick="tutupModal('modalKepegawaian')"
                        class="px-4 py-2 rounded-lg border border-slate-300 text-slate-600
                               text-xs font-semibold hover:bg-slate-50 transition">Batal</button>
                <button type="submit"
                        class="px-5 py-2 rounded-lg bg-indigo-600 text-white text-xs font-semibold
                               hover:bg-indigo-700 transition shadow-sm">Simpan</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
/* ══════════════════════════════════════════════════════
   MODAL HELPERS
══════════════════════════════════════════════════════ */
function bukaModal(id) {
    const el = document.getElementById(id);
    if (!el) return;
    el.classList.remove('hidden');
    el.classList.add('flex');
    document.body.style.overflow = 'hidden';
}
function tutupModal(id) {
    const el = document.getElementById(id);
    if (!el) return;
    el.classList.add('hidden');
    el.classList.remove('flex');
    document.body.style.overflow = '';
}
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
        ['modalAkun','modalIdentitas','modalPribadi','modalKepegawaian'].forEach(tutupModal);
    }
});

/* ══════════════════════════════════════════════════════
   PREVIEW FOTO
══════════════════════════════════════════════════════ */
(function () {
    const input = document.getElementById('akun_photoInput');
    const wrap  = document.getElementById('akun_photoPreviewWrap');
    if (!input || !wrap) return;
    input.addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;
        if (!file.type.startsWith('image/')) { alert('Hanya file gambar yang diperbolehkan.'); return; }
        if (file.size > 2 * 1024 * 1024) { alert('Ukuran file maksimal 2 MB.'); return; }
        const reader = new FileReader();
        reader.onload = e => {
            wrap.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
        };
        reader.readAsDataURL(file);
    });
})();

/* ══════════════════════════════════════════════════════
   WALI KELAS DROPDOWN — feedback visual saat memilih
══════════════════════════════════════════════════════ */
function updateKelasPreview(selectEl) {
    const infoBox   = document.getElementById('kelasWaliSelectedInfo');
    const labelEl   = document.getElementById('kelasWaliSelectedLabel');
    const badge     = document.getElementById('kelasWaliCurrentBadge');

    if (!infoBox || !labelEl) return;

    const selectedOpt = selectEl.options[selectEl.selectedIndex];

    if (selectEl.value === '') {
        // Pilih "— Tidak Menjadi Wali Kelas —"
        infoBox.classList.add('hidden');
        if (badge) badge.style.opacity = '0.4';
    } else {
        const label = selectedOpt.getAttribute('data-label') || selectedOpt.text;
        labelEl.textContent = label;
        infoBox.classList.remove('hidden');
        if (badge) badge.style.opacity = '1';
    }
}

/* ══════════════════════════════════════════════════════
   AUTO-INIT:
   1. Jalankan updateKelasPreview saat modal identitas dibuka
      agar preview sinkron dengan nilai dropdown saat ini.
   2. Auto-buka modal jika ada session '_section'
      (setelah validasi error redirect back).
══════════════════════════════════════════════════════ */
document.addEventListener('DOMContentLoaded', function () {
    // Sync preview saat halaman load
    const sel = document.getElementById('selectWaliKelas');
    if (sel) updateKelasPreview(sel);
});

@php
    /*
     * Auto-buka modal jika ada validasi error dari session _section.
     * ucfirst() di PHP karena id modal menggunakan PascalCase.
     */
    $sectionFlash = session('_section', $errors->any() ? 'identitas' : null);
    $modalToOpen  = $sectionFlash ? 'modal' . ucfirst($sectionFlash) : null;
@endphp
@if($modalToOpen)
    bukaModal('{{ $modalToOpen }}');
@endif
</script>
@endpush

@endsection