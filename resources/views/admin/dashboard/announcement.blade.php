{{-- resources/views/admin/dashboard/announcement.blade.php
     Variabel: $widgetPengumuman (Collection<Pengumuman>)
--}}

{{-- Modal Detail Pengumuman --}}
<div id="wdgModal-admin"
     onclick="if(event.target===this)wdgTutup('admin')"
     class="fixed inset-0 z-[9999] hidden items-center justify-center p-4"
     style="background:rgba(0,0,0,.55);backdrop-filter:blur(5px)">
    <div class="relative w-full max-w-lg max-h-[88vh] overflow-y-auto
                bg-white dark:bg-slate-800 rounded-2xl shadow-2xl
                border border-slate-200 dark:border-slate-700">
        <button onclick="wdgTutup('admin')"
                class="absolute top-3 right-3 z-10 w-6 h-6 flex items-center justify-center
                       bg-slate-100 hover:bg-red-100 dark:bg-slate-700 dark:hover:bg-red-900/40
                       text-slate-500 hover:text-red-500 rounded-lg transition-all">
            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
        <div id="wdgModalKonten-admin" class="p-4"></div>
    </div>
</div>

{{-- Widget Card --}}
<div class="bg-white dark:bg-slate-800 rounded-2xl
            border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">

    {{-- Header --}}
    <div class="flex items-center justify-between px-3 py-2.5
                border-b border-slate-100 dark:border-slate-700/60">
        <div class="flex items-center gap-2">
            <div class="w-6 h-6 rounded-lg bg-gradient-to-br from-indigo-500 to-violet-600
                        flex items-center justify-center text-white leading-none shrink-0"
                 style="font-size:.7rem">📢</div>
            <div>
                <p class="text-xs font-bold text-slate-800 dark:text-slate-100 leading-tight">
                    Pengumuman Terbaru
                </p>
                <p class="text-[10px] text-slate-400 leading-none mt-0.5">
                    {{ $widgetPengumuman->count() }} ditampilkan
                </p>
            </div>
        </div>
        @if(Route::has('admin.pengumuman'))
        <a href="{{ route('admin.pengumuman') }}"
           class="text-[10px] font-semibold text-indigo-600 hover:text-indigo-700
                  flex items-center gap-0.5 transition-colors">
            Kelola
            <svg class="w-2.5 h-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
        @endif
    </div>

    {{-- List --}}
    @if($widgetPengumuman->isEmpty())
        <div class="px-4 py-8 text-center">
            <div class="text-2xl mb-1.5">📭</div>
            <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">
                Belum ada pengumuman.
            </p>
            <p class="text-[10px] text-slate-400 mt-0.5 leading-snug">
                Centang "Tampil di Dashboard" saat membuat.
            </p>
            @if(Route::has('admin.pengumuman.create'))
            <a href="{{ route('admin.pengumuman.create') }}"
               class="mt-2 inline-flex items-center gap-1 text-[10px] font-semibold
                      text-indigo-600 hover:text-indigo-700">
                + Buat Pengumuman
            </a>
            @endif
        </div>
    @else
        <ul class="divide-y divide-slate-100 dark:divide-slate-700/50">
            @foreach($widgetPengumuman as $item)
            @php
                $wFileUrl = $item->file_path ? asset('storage/' . $item->file_path) : '';
                $wData = [
                    'judul'         => (string)($item->judul ?? ''),
                    'isi'           => (string)($item->isi ?? ''),
                    'tipe'          => (string)($item->tipe_konten ?? 'teks'),
                    'tipeIcon'      => (string)($item->tipeIcon()),
                    'audience'      => (string)($item->audienceLabel()),
                    'audienceColor' => (string)($item->audienceBadgeColor()),
                    'fileUrl'       => $wFileUrl,
                    'fileName'      => (string)($item->file_name ?? ''),
                    'fileExt'       => (string)($item->fileExtension() ?? ''),
                    'linkUrl'       => (string)($item->link_url ?? ''),
                    'linkLabel'     => (string)($item->link_label ?? 'Buka Link'),
                    'tanggal'       => $item->created_at->isoFormat('D MMMM Y, HH:mm'),
                    'diffHumans'    => $item->created_at->diffForHumans(),
                    'creator'       => (string)(optional($item->creator)->name ?? 'Admin'),
                    'tglSelesai'    => $item->tanggal_selesai
                                        ? $item->tanggal_selesai->isoFormat('D MMM Y, HH:mm')
                                        : '',
                    'widgetRole'    => 'admin',
                ];
                $wJson = json_encode($wData, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_UNESCAPED_UNICODE);
                $ikBg = match($item->tipe_konten) {
                    'gambar'  => 'bg-rose-50 dark:bg-rose-900/20',
                    'dokumen' => 'bg-indigo-50 dark:bg-indigo-900/20',
                    'link'    => 'bg-sky-50 dark:bg-sky-900/20',
                    default   => 'bg-emerald-50 dark:bg-emerald-900/20',
                };
            @endphp
            <li>
                <button type="button" onclick='wdgBuka({{ $wJson }})'
                        class="group w-full text-left transition-colors overflow-hidden
                               hover:bg-slate-50 dark:hover:bg-slate-700/30 focus:outline-none">

                    @if($item->tipe_konten === 'gambar' && $item->file_path)
                    <div class="w-full h-14 overflow-hidden bg-slate-100 dark:bg-slate-700">
                        <img src="{{ asset('storage/' . $item->file_path) }}"
                             alt="{{ $item->judul }}" loading="lazy"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                             onerror="wdgImgError(this)">
                    </div>
                    @endif

                    <div class="flex items-start gap-2 px-3 py-2.5">
                        @if($item->tipe_konten !== 'gambar')
                        <div class="shrink-0 w-6 h-6 rounded-lg {{ $ikBg }}
                                    flex items-center justify-center mt-0.5"
                             style="font-size:.75rem">
                            {{ $item->tipeIcon() }}
                        </div>
                        @endif

                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold text-slate-800 dark:text-slate-100
                                      line-clamp-1 leading-snug
                                      group-hover:text-indigo-600 dark:group-hover:text-indigo-400
                                      transition-colors">
                                {{ $item->judul }}
                            </p>

                            @if($item->tipe_konten === 'teks' && $item->isi)
                            <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-0.5 line-clamp-1">
                                {{ strip_tags($item->isi) }}
                            </p>
                            @elseif($item->tipe_konten === 'dokumen' && $item->file_path)
                            <p class="text-[10px] font-bold text-indigo-500 mt-0.5">
                                📄 {{ strtoupper($item->fileExtension() ?: 'FILE') }}
                                <span class="font-normal text-slate-400">— {{ $item->file_name }}</span>
                            </p>
                            @elseif($item->tipe_konten === 'link' && $item->link_url)
                            <p class="text-[10px] text-sky-500 mt-0.5 truncate">
                                🔗 {{ $item->link_label ?: $item->link_url }}
                            </p>
                            @endif

                            <div class="flex items-center gap-1 mt-1 flex-wrap">
                                <span class="text-[9px] text-slate-400">
                                    {{ $item->created_at->diffForHumans() }}
                                </span>
                                <span class="text-slate-300 dark:text-slate-600 text-[9px] select-none">•</span>
                                <span class="inline-flex px-1.5 py-0.5 rounded-full text-[9px] font-semibold
                                             {{ $item->audienceBadgeColor() }}">
                                    {{ $item->audienceLabel() }}
                                </span>
                            </div>
                        </div>

                        <div class="shrink-0 self-center">
                            <svg class="w-2.5 h-2.5 text-slate-300 dark:text-slate-600
                                        group-hover:text-indigo-400 transition-all duration-150"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                </button>
            </li>
            @endforeach
        </ul>

        @if(Route::has('admin.pengumuman'))
        <div class="px-3 py-2 border-t border-slate-100 dark:border-slate-700/60">
            <a href="{{ route('admin.pengumuman') }}"
               class="flex items-center justify-center gap-1 text-[10px] font-semibold
                      text-indigo-600 hover:text-indigo-700 transition-colors py-0.5">
                Lihat semua
                <svg class="w-2.5 h-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
        @endif
    @endif
</div>

@once
<script>
(function () {
    'use strict';

    /* ── Helper: gambar gagal dimuat (list thumbnail) ── */
    window.wdgImgError = function (img) {
        var wrap = img.closest('div');
        if (wrap) {
            wrap.innerHTML = '<div class="flex items-center justify-center h-14 text-slate-300 text-sm">🖼️</div>';
        }
    };

    /* ── Helper: gambar gagal dimuat (modal) ── */
    window.wdgModalImgError = function (img) {
        var wrap = img.closest('div');
        if (wrap) {
            wrap.innerHTML = '<div class="p-6 text-center"><div class="text-3xl mb-1.5">🖼️</div><p class="text-xs text-slate-400">Gambar tidak dapat dimuat.</p></div>';
        }
    };

    window.wdgBuka = function (d) {
        var role    = d.widgetRole || 'admin';
        var konten  = document.getElementById('wdgModalKonten-' + role);
        var overlay = document.getElementById('wdgModal-' + role);
        if (!konten || !overlay) return;
        konten.innerHTML = wdgHtml(d);
        overlay.classList.remove('hidden');
        overlay.classList.add('flex');
        document.body.style.overflow = 'hidden';
    };

    window.wdgTutup = function (role) {
        var overlay = document.getElementById('wdgModal-' + (role || 'admin'));
        if (!overlay) return;
        overlay.classList.add('hidden');
        overlay.classList.remove('flex');
        document.body.style.overflow = '';
    };

    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') wdgTutup('admin'); });

    function wdgHtml(d) {
        var h = '';
        h += '<div class="flex items-start gap-2.5 mb-3.5 pr-7">';
        h += '<div class="text-xl shrink-0 mt-0.5 leading-none">' + d.tipeIcon + '</div>';
        h += '<div class="flex-1 min-w-0">';
        h += '<h2 class="text-sm font-bold text-slate-800 dark:text-slate-100 leading-snug break-words">' + e(d.judul) + '</h2>';
        h += '<div class="flex gap-1.5 mt-1.5 flex-wrap">';
        h += '<span class="px-1.5 py-0.5 rounded-full text-[10px] font-semibold ' + d.audienceColor + '">' + e(d.audience) + '</span>';
        h += '<span class="px-1.5 py-0.5 rounded-full text-[10px] font-semibold bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300 capitalize">' + e(d.tipe) + '</span>';
        h += '</div></div></div>';
        h += '<div class="flex flex-wrap gap-x-3 gap-y-1 text-[10px] text-slate-400 mb-3 pb-3 border-b border-slate-200 dark:border-slate-700">';
        h += '<span>📅 ' + e(d.tanggal) + '</span><span>👤 ' + e(d.creator) + '</span><span>🕐 ' + e(d.diffHumans) + '</span>';
        h += '</div>';

        /* Gambar */
        if (d.tipe === 'gambar' && d.fileUrl) {
            h += '<div class="rounded-xl overflow-hidden border border-slate-200 dark:border-slate-600 mb-3 bg-slate-50 dark:bg-slate-900 flex items-center justify-center">';
            h += '<img src="' + d.fileUrl + '" alt="' + e(d.judul) + '" class="w-full max-h-64 object-contain block" onerror="wdgModalImgError(this)">';
            h += '</div>';
        }

        /* Isi teks */
        if (d.isi && d.isi.trim()) {
            var adaTag = /<[a-z][\s\S]*>/i.test(d.isi);
            h += adaTag
                ? '<div class="text-xs text-slate-700 dark:text-slate-300 leading-relaxed mb-3 prose prose-sm dark:prose-invert max-w-none">' + s(d.isi) + '</div>'
                : '<div class="text-xs text-slate-700 dark:text-slate-300 leading-relaxed mb-3 whitespace-pre-line">' + e(d.isi) + '</div>';
        }

        /* Dokumen */
        if (d.tipe === 'dokumen' && d.fileUrl) {
            h += '<div class="flex items-center justify-between gap-2 p-3 bg-indigo-50 dark:bg-indigo-900/30 rounded-xl border border-indigo-200 dark:border-indigo-700 mb-3">';
            h += '<div class="flex items-center gap-2"><div class="w-7 h-7 bg-indigo-100 dark:bg-indigo-800 rounded-lg flex items-center justify-center text-sm">📄</div>';
            h += '<div><p class="text-xs font-bold text-indigo-700 dark:text-indigo-300">' + e(d.fileExt || 'FILE') + '</p>';
            h += '<p class="text-[10px] text-slate-400 max-w-[160px] truncate">' + e(d.fileName) + '</p></div></div>';
            h += '<a href="' + d.fileUrl + '" target="_blank" download onclick="event.stopPropagation()" class="inline-flex items-center gap-1 px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg no-underline shrink-0">';
            h += '<svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>';
            h += 'Unduh</a></div>';
        }

        /* Link */
        if (d.tipe === 'link' && d.linkUrl) {
            h += '<div class="p-3 bg-sky-50 dark:bg-sky-900/30 rounded-xl border border-sky-200 dark:border-sky-700 mb-3">';
            h += '<p class="text-[10px] text-slate-500 mb-2 font-medium">🔗 Tautan</p>';
            h += '<a href="' + d.linkUrl + '" target="_blank" rel="noopener" onclick="event.stopPropagation()" class="inline-flex items-center gap-1 px-3 py-1.5 bg-sky-600 hover:bg-sky-700 text-white text-xs font-semibold rounded-lg no-underline">';
            h += '<svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>';
            h += e(d.linkLabel || 'Buka Link') + '</a></div>';
        }

        /* Tanggal selesai */
        if (d.tglSelesai) {
            h += '<div class="flex items-center gap-2 px-3 py-2 bg-amber-50 dark:bg-amber-900/20 rounded-xl border border-amber-200 dark:border-amber-700 mb-3">';
            h += '<span>⏰</span><p class="text-[10px] text-amber-700 dark:text-amber-300 font-medium">Berakhir: <strong>' + e(d.tglSelesai) + '</strong></p></div>';
        }

        h += '<div class="flex justify-end pt-1"><button onclick="wdgTutup(\'admin\')" class="px-4 py-1.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 text-xs font-semibold rounded-xl transition-colors">Tutup</button></div>';
        return h;
    }

    function e(v) {
        if (v == null) return '';
        return String(v)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function s(h) {
        return (h || '')
            .replace(/<script[\s\S]*?<\/script>/gi, '')
            .replace(/<iframe[\s\S]*?<\/iframe>/gi, '')
            .replace(/\bon\w+=["'][^"']*["']/gi, '')
            .replace(/javascript:/gi, '#');
    }
})();
</script>
@endonce