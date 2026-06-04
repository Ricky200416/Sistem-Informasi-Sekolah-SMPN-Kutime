{{--
    ╔══════════════════════════════════════════════════════════╗
    ║  WIDGET PENGUMUMAN — bisa di-include ke semua dashboard  ║
    ║                                                          ║
    ║  Cara pakai:                                             ║
    ║  @include('components.pengumuman-widget', [              ║
    ║      'role' => 'guru',   // atau 'siswa' atau 'admin'    ║
    ║  ])                                                      ║
    ╚══════════════════════════════════════════════════════════╝
--}}

@php
    $roleTarget    = $role ?? 'semua';
    $widgetItems   = \App\Http\Controllers\PengumumanController::dashboardWidget($roleTarget, 4);
    $routeAll      = match($roleTarget) {
        'guru'  => route('guru.pengumuman'),
        'siswa' => route('siswa.pengumuman'),
        default => route('admin.pengumuman'),
    };
@endphp

<div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">

    {{-- Header widget --}}
    <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100 dark:border-slate-700/50">
        <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 bg-indigo-100 dark:bg-indigo-900/40 rounded-xl flex items-center justify-center">
                <span class="text-base">📢</span>
            </div>
            <h3 class="font-bold text-slate-800 dark:text-slate-100 text-sm">Pengumuman Terbaru</h3>
        </div>
        <a href="{{ $routeAll }}"
           class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:underline flex items-center gap-1">
            Lihat semua
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
    </div>

    {{-- List items --}}
    @if($widgetItems->isEmpty())
        <div class="text-center py-10 text-slate-400">
            <span class="text-3xl block mb-2">📭</span>
            <p class="text-sm">Belum ada pengumuman</p>
        </div>
    @else
    <div class="divide-y divide-slate-100 dark:divide-slate-700/50">
        @foreach($widgetItems as $item)
        <div class="flex items-start gap-3 px-5 py-4 hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors">
            {{-- Ikon --}}
            <div class="shrink-0 w-9 h-9 rounded-xl flex items-center justify-center text-base mt-0.5
                @if($item->tipe_konten==='gambar') bg-violet-100 dark:bg-violet-900/40
                @elseif($item->tipe_konten==='dokumen') bg-indigo-100 dark:bg-indigo-900/40
                @elseif($item->tipe_konten==='link') bg-sky-100 dark:bg-sky-900/40
                @else bg-emerald-100 dark:bg-emerald-900/40 @endif">
                {{ $item->tipeIcon() }}
            </div>

            <div class="flex-1 min-w-0">
                <div class="flex items-start justify-between gap-2">
                    <p class="text-sm font-semibold text-slate-700 dark:text-slate-200 leading-snug line-clamp-1">
                        {{ $item->judul }}
                    </p>
                    <span class="shrink-0 text-xs text-slate-400 mt-0.5">
                        {{ $item->created_at->diffForHumans(null, true) }}
                    </span>
                </div>

                @if($item->isi)
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 line-clamp-1">
                    {{ strip_tags($item->isi) }}
                </p>
                @endif

                <div class="flex items-center gap-2 mt-1.5 flex-wrap">
                    <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $item->audienceBadgeColor() }}">
                        {{ $item->audienceLabel() }}
                    </span>

                    @if($item->tipe_konten === 'dokumen' && $item->file_path)
                        <a href="{{ $item->fileUrl() }}" target="_blank" download
                           class="inline-flex items-center gap-1 text-xs text-indigo-600 dark:text-indigo-400 font-medium hover:underline">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            Unduh
                        </a>
                    @elseif($item->tipe_konten === 'link' && $item->link_url)
                        <a href="{{ $item->link_url }}" target="_blank"
                           class="inline-flex items-center gap-1 text-xs text-sky-600 dark:text-sky-400 font-medium hover:underline">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            Buka Link
                        </a>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>