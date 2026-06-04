{{--
╔══════════════════════════════════════════════════════════════════════════════╗
║  resources/views/components/dashboard-pengumuman.blade.php                  ║
║                                                                              ║
║  Widget Pengumuman untuk Dashboard Admin / Guru / Siswa                     ║
║                                                                              ║
║  CARA PAKAI di dashboard:                                                   ║
║    @php                                                                      ║
║        $widgetPengumuman = \App\Models\Pengumuman::where('is_active', 1)    ║
║            ->where('show_di_dashboard', 1)                                  ║
║            ->whereIn('target_audience', ['guru', 'semua'])  // sesuaikan   ║
║            ->latest()->limit(4)->get();                                     ║
║    @endphp                                                                   ║
║    @include('components.dashboard-pengumuman', [                            ║
║        'widgetPengumuman' => $widgetPengumuman,                             ║
║        'widgetRole'       => 'guru',   // 'admin' | 'guru' | 'siswa'       ║
║    ])                                                                        ║
║                                                                              ║
║  PENTING:                                                                    ║
║  - Script JS ditulis INLINE di sini, TIDAK pakai @push                     ║
║  - Gambar menggunakan asset('storage/...') bukan Storage::url()            ║
║  - Seluruh modal ditangani JS langsung di dalam file ini                   ║
╚══════════════════════════════════════════════════════════════════════════════╝
--}}

@php
    /*
     * Fallback: jika tidak di-pass dari luar, query sendiri.
     * $widgetRole default 'guru' — ubah sesuai konteks dashboard.
     */
    $widgetRole ??= 'guru';

    if (!isset($widgetPengumuman)) {
        $targets = match($widgetRole) {
            'admin' => ['guru', 'siswa', 'semua'],
            'siswa' => ['siswa', 'semua'],
            default => ['guru', 'semua'],
        };

        $widgetPengumuman = \App\Models\Pengumuman::where('is_active', 1)
            ->where('show_di_dashboard', 1)
            ->whereIn('target_audience', $targets)
            ->latest()
            ->limit(4)
            ->get();
    }

    /*
     * Warna aksen berdasarkan role
     */
    $widgetAccent = match($widgetRole) {
        'admin' => ['from' => 'from-indigo-600', 'to' => 'to-violet-700', 'ring' => 'focus:ring-indigo-400', 'badge' => 'bg-indigo-100 text-indigo-700', 'link' => 'text-indigo-600 hover:text-indigo-700'],
        'siswa' => ['from' => 'from-sky-500',    'to' => 'to-blue-600',   'ring' => 'focus:ring-sky-400',    'badge' => 'bg-sky-100 text-sky-700',    'link' => 'text-sky-600 hover:text-sky-700'],
        default => ['from' => 'from-violet-600', 'to' => 'to-indigo-700', 'ring' => 'focus:ring-violet-400', 'badge' => 'bg-violet-100 text-violet-700', 'link' => 'text-violet-600 hover:text-violet-700'],
    };

    $lihatSemuaRoute = match($widgetRole) {
        'admin' => 'admin.pengumuman',
        'siswa' => 'siswa.pengumuman',
        default => 'guru.pengumuman',
    };
@endphp

{{-- ═══════════════════════════════════════════════════════════════
     MODAL DETAIL — inline di widget ini sendiri
     ID unik per-role agar tidak konflik jika ada beberapa widget
═══════════════════════════════════════════════════════════════ --}}
<div id="wdgModal-{{ $widgetRole }}"
     onclick="if(event.target===this)wdgTutup('{{ $widgetRole }}')"
     class="fixed inset-0 z-[9999] hidden items-center justify-center p-4"
     style="background:rgba(0,0,0,.65);backdrop-filter:blur(6px)">

    <div class="relative w-full max-w-2xl max-h-[92vh] overflow-y-auto
                bg-white dark:bg-slate-800 rounded-3xl shadow-2xl
                border border-slate-200 dark:border-slate-700">

        <button onclick="wdgTutup('{{ $widgetRole }}')"
                class="absolute top-4 right-4 z-10 w-9 h-9 flex items-center justify-center
                       bg-slate-100 hover:bg-red-100 dark:bg-slate-700 dark:hover:bg-red-900/40
                       text-slate-500 hover:text-red-500 rounded-2xl transition-all">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        <div id="wdgModalKonten-{{ $widgetRole }}" class="p-6 sm:p-8"></div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════════
     WIDGET CARD
═══════════════════════════════════════════════════════════════ --}}
<div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">

    {{-- Header widget --}}
    <div class="flex items-center justify-between px-5 py-4
                border-b border-slate-100 dark:border-slate-700/50">
        <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-xl bg-gradient-to-br {{ $widgetAccent['from'] }} {{ $widgetAccent['to'] }}
                        flex items-center justify-center text-white text-sm shadow-sm">
                📢
            </div>
            <div>
                <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100">Pengumuman Terbaru</h3>
                <p class="text-xs text-slate-400">
                    {{ $widgetPengumuman->count() }} pengumuman aktif
                </p>
            </div>
        </div>
        @if(Route::has($lihatSemuaRoute))
        <a href="{{ route($lihatSemuaRoute) }}"
           class="text-xs font-semibold {{ $widgetAccent['link'] }} transition-colors
                  flex items-center gap-1 hover:gap-1.5">
            Lihat semua
            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
        @endif
    </div>

    {{-- Konten --}}
    @if($widgetPengumuman->isEmpty())
        <div class="px-5 py-10 text-center">
            <div class="text-4xl mb-3">📭</div>
            <p class="text-sm text-slate-500 dark:text-slate-400 font-medium">Belum ada pengumuman.</p>
            <p class="text-xs text-slate-400 mt-1">Pengumuman baru akan muncul di sini.</p>
        </div>

    @else
        <div class="divide-y divide-slate-100 dark:divide-slate-700/50">
            @foreach($widgetPengumuman as $item)
            @php
                /*
                 * =====================================================
                 * KRITIS: Bangun URL file langsung dengan asset()
                 * JANGAN pakai $item->fileUrl() karena Storage::url()
                 * bisa menghasilkan path relatif yang tidak bekerja
                 * sebagai src="" di <img>.
                 * =====================================================
                 */
                $wFileUrl = $item->file_path
                    ? asset('storage/' . $item->file_path)
                    : '';

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
                    'widgetRole'    => $widgetRole,
                ];

                $wJson = json_encode(
                    $wData,
                    JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE
                );
            @endphp

            <button type="button"
                    onclick='wdgBuka({{ $wJson }})'
                    class="group w-full text-left transition-colors
                           hover:bg-slate-50 dark:hover:bg-slate-700/40
                           focus:outline-none {{ $widgetAccent['ring'] }} focus:ring-inset
                           overflow-hidden">

                {{-- Preview gambar jika tipe gambar --}}
                @if($item->tipe_konten === 'gambar' && $item->file_path)
                <div class="w-full h-32 overflow-hidden bg-slate-100 dark:bg-slate-700">
                    <img src="{{ asset('storage/' . $item->file_path) }}"
                         alt="{{ $item->judul }}"
                         loading="lazy"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                         onerror="this.closest('div').innerHTML=
                             '<div class=\'w-full h-32 flex flex-col items-center justify-center text-slate-400 text-xs gap-1\'>'
                             +'<span class=\'text-3xl\'>🖼️</span>'
                             +'<span>Gambar tidak tersedia</span>'
                             +'</div>'">
                </div>
                @endif

                <div class="flex items-start gap-3 px-5 py-3.5">

                    {{-- Ikon tipe (hanya tampil jika bukan gambar) --}}
                    @if($item->tipe_konten !== 'gambar')
                    <div class="shrink-0 w-9 h-9 rounded-xl flex items-center justify-center text-base shadow-sm mt-0.5
                        @if($item->tipe_konten === 'dokumen') bg-indigo-100 dark:bg-indigo-900/50
                        @elseif($item->tipe_konten === 'link') bg-sky-100 dark:bg-sky-900/50
                        @else bg-emerald-100 dark:bg-emerald-900/50 @endif">
                        {{ $item->tipeIcon() }}
                    </div>
                    @endif

                    <div class="flex-1 min-w-0">
                        {{-- Judul --}}
                        <p class="text-sm font-semibold text-slate-800 dark:text-slate-100 line-clamp-1
                                  group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                            {{ $item->judul }}
                        </p>

                        {{-- Preview teks --}}
                        @if($item->isi && $item->tipe_konten !== 'gambar')
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 line-clamp-1">
                            {{ strip_tags($item->isi) }}
                        </p>
                        @endif

                        {{-- Preview info file dokumen --}}
                        @if($item->tipe_konten === 'dokumen' && $item->file_path)
                        <div class="flex items-center gap-1.5 mt-1">
                            <span class="text-xs font-bold text-indigo-500">{{ $item->fileExtension() ?: 'FILE' }}</span>
                            <span class="text-xs text-slate-400 truncate max-w-[140px]">{{ $item->file_name }}</span>
                        </div>
                        @endif

                        {{-- Preview link --}}
                        @if($item->tipe_konten === 'link' && $item->link_url)
                        <p class="text-xs text-sky-500 mt-0.5 truncate">{{ $item->link_url }}</p>
                        @endif

                        {{-- Meta: waktu + badge --}}
                        <div class="flex items-center gap-2 mt-1.5 flex-wrap">
                            <span class="text-xs text-slate-400">{{ $item->created_at->diffForHumans() }}</span>
                            <span class="inline-flex px-1.5 py-0.5 rounded-full text-xs font-medium {{ $item->audienceBadgeColor() }}">
                                {{ $item->audienceLabel() }}
                            </span>
                        </div>
                    </div>

                    {{-- Arrow --}}
                    <div class="shrink-0 self-center text-slate-300 dark:text-slate-600
                                group-hover:text-indigo-400 group-hover:translate-x-0.5 transition-all">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </div>
            </button>
            @endforeach
        </div>

        {{-- Footer: lihat semua --}}
        @if(Route::has($lihatSemuaRoute))
        <div class="px-5 py-3 border-t border-slate-100 dark:border-slate-700/50">
            <a href="{{ route($lihatSemuaRoute) }}"
               class="flex items-center justify-center gap-1.5 text-xs font-semibold
                      {{ $widgetAccent['link'] }} transition-colors py-1">
                Lihat semua pengumuman
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
        @endif
    @endif
</div>

{{-- ═══════════════════════════════════════════════════════════════
     JAVASCRIPT — ditulis LANGSUNG di sini, BUKAN @push
     Menggunakan IIFE + window.wdgBuka/wdgTutup agar bisa
     dipanggil dari onclick di Blade dan tidak konflik jika
     widget muncul lebih dari satu kali di halaman.
═══════════════════════════════════════════════════════════════ --}}
<script>
(function () {
    'use strict';

    /* ── Buka modal ─────────────────────────────────────── */
    window.wdgBuka = function (d) {
        var role    = d.widgetRole || 'guru';
        var konten  = document.getElementById('wdgModalKonten-' + role);
        var overlay = document.getElementById('wdgModal-' + role);

        if (!konten || !overlay) {
            /* fallback: coba pgModal jika ada di halaman yang sama */
            var pgKonten = document.getElementById('pgModalKonten');
            var pgOverlay = document.getElementById('pgModal');
            if (pgKonten && pgOverlay) {
                pgKonten.innerHTML = wdgBuatHtml(d);
                pgOverlay.classList.remove('hidden');
                pgOverlay.classList.add('flex');
                document.body.style.overflow = 'hidden';
            }
            return;
        }

        konten.innerHTML = wdgBuatHtml(d);
        overlay.classList.remove('hidden');
        overlay.classList.add('flex');
        document.body.style.overflow = 'hidden';
    };

    /* ── Tutup modal ────────────────────────────────────── */
    window.wdgTutup = function (role) {
        role = role || 'guru';
        var overlay = document.getElementById('wdgModal-' + role);
        if (!overlay) return;
        overlay.classList.add('hidden');
        overlay.classList.remove('flex');
        document.body.style.overflow = '';
    };

    /* Escape key */
    document.addEventListener('keydown', function (e) {
        if (e.key !== 'Escape') return;
        ['admin', 'guru', 'siswa'].forEach(function (r) {
            var o = document.getElementById('wdgModal-' + r);
            if (o && !o.classList.contains('hidden')) wdgTutup(r);
        });
    });

    /* ── Build HTML konten modal ────────────────────────── */
    function wdgBuatHtml(d) {
        var h = '';

        /* Header */
        h += '<div class="flex items-start gap-4 mb-5 pr-10">';
        h += '<div class="text-3xl shrink-0 mt-0.5 leading-none">' + d.tipeIcon + '</div>';
        h += '<div class="flex-1 min-w-0">';
        h += '<h2 class="text-xl font-bold text-slate-800 dark:text-slate-100 leading-snug break-words">' + esc(d.judul) + '</h2>';
        h += '<div class="flex gap-2 mt-2 flex-wrap">';
        h += '<span class="px-2.5 py-1 rounded-full text-xs font-semibold ' + d.audienceColor + '">' + esc(d.audience) + '</span>';
        h += '<span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300 capitalize">' + esc(d.tipe) + '</span>';
        h += '</div></div></div>';

        /* Meta */
        h += '<div class="flex flex-wrap gap-x-4 gap-y-1 text-xs text-slate-400 mb-5 pb-5 border-b border-slate-200 dark:border-slate-700">';
        h += '<span>📅 ' + esc(d.tanggal) + '</span>';
        h += '<span>👤 ' + esc(d.creator) + '</span>';
        h += '<span>🕐 ' + esc(d.diffHumans) + '</span>';
        h += '</div>';

        /* ── GAMBAR ── */
        if (d.tipe === 'gambar') {
            if (d.fileUrl && d.fileUrl !== '') {
                h += '<div class="rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-600 mb-5 bg-slate-50 dark:bg-slate-900/60 flex items-center justify-center min-h-[100px]">';
                h += '<img'
                  + '  src="' + d.fileUrl + '"'
                  + '  alt="' + esc(d.judul) + '"'
                  + '  class="w-full max-h-[420px] object-contain block"'
                  + '  onerror="'
                  +    'this.closest(\'div\').innerHTML='
                  +    '\'<div class=\\\"p-8 text-center\\\">\''
                  +    '+\'<div class=\\\"text-5xl mb-3\\\">🖼️</div>\''
                  +    '+\'<p class=\\\"text-sm text-slate-400 font-medium\\\">Gambar tidak dapat dimuat.</p>\''
                  +    '+\'<p class=\\\"text-xs text-slate-400 mt-1\\\">Pastikan sudah menjalankan:<br><code class=\\\"bg-slate-100 dark:bg-slate-700 px-1 rounded\\\">php artisan storage:link</code></p>\''
                  +    '+\'</div>\''
                  + '"'
                  + '>';
                h += '</div>';
            } else {
                h += '<div class="p-8 mb-5 bg-slate-50 dark:bg-slate-900 rounded-2xl text-center">'
                  +  '<div class="text-4xl mb-2">🖼️</div>'
                  +  '<p class="text-sm text-slate-400">Tidak ada file gambar yang diunggah.</p>'
                  +  '</div>';
            }
        }

        /* ── ISI / TEKS ── */
        if (d.isi && d.isi.trim() !== '') {
            var adaHtml = /<[a-z][\s\S]*>/i.test(d.isi);
            if (adaHtml) {
                h += '<div class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed mb-5 prose prose-sm dark:prose-invert max-w-none prose-img:rounded-xl prose-a:text-indigo-600">'
                  +  bersih(d.isi) + '</div>';
            } else {
                h += '<div class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed mb-5 whitespace-pre-line">'
                  +  esc(d.isi) + '</div>';
            }
        }

        /* ── DOKUMEN ── */
        if (d.tipe === 'dokumen') {
            if (d.fileUrl && d.fileUrl !== '') {
                h += '<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 p-4 bg-indigo-50 dark:bg-indigo-900/30 rounded-2xl border border-indigo-200 dark:border-indigo-700 mb-5">';
                h += '  <div class="flex items-center gap-3">';
                h += '    <div class="w-12 h-12 bg-indigo-100 dark:bg-indigo-800 rounded-xl flex items-center justify-center text-2xl shrink-0">📄</div>';
                h += '    <div class="min-w-0">';
                h += '      <p class="text-sm font-bold text-indigo-700 dark:text-indigo-300">' + esc(d.fileExt || 'FILE') + ' <span class="font-normal opacity-80">Dokumen</span></p>';
                h += '      <p class="text-xs text-slate-500 dark:text-slate-400 truncate max-w-[200px]">' + esc(d.fileName) + '</p>';
                h += '    </div>';
                h += '  </div>';
                h += '  <a href="' + d.fileUrl + '"'
                  + '     target="_blank" download'
                  + '     onclick="event.stopPropagation()"'
                  + '     class="shrink-0 inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl transition-colors no-underline shadow-sm">';
                h += '    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>';
                h += '    Unduh Dokumen';
                h += '  </a>';
                h += '</div>';
            } else {
                h += '<div class="p-8 mb-5 bg-slate-50 dark:bg-slate-900 rounded-2xl text-center">'
                  +  '<div class="text-4xl mb-2">📄</div>'
                  +  '<p class="text-sm text-slate-400">Tidak ada file dokumen yang diunggah.</p>'
                  +  '</div>';
            }
        }

        /* ── LINK ── */
        if (d.tipe === 'link') {
            if (d.linkUrl && d.linkUrl !== '') {
                h += '<div class="p-4 bg-sky-50 dark:bg-sky-900/30 rounded-2xl border border-sky-200 dark:border-sky-700 mb-5">';
                h += '  <p class="text-xs text-slate-500 dark:text-slate-400 mb-3 font-medium">🔗 Tautan Pengumuman</p>';
                h += '  <a href="' + d.linkUrl + '"'
                  + '     target="_blank" rel="noopener noreferrer"'
                  + '     onclick="event.stopPropagation()"'
                  + '     class="inline-flex items-center gap-2 px-5 py-2.5 bg-sky-600 hover:bg-sky-700 text-white text-sm font-semibold rounded-xl transition-colors no-underline">';
                h += '    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>';
                h += '    ' + esc(d.linkLabel || 'Buka Link');
                h += '  </a>';
                h += '  <p class="text-xs text-slate-400 mt-2 break-all">' + esc(d.linkUrl) + '</p>';
                h += '</div>';
            } else {
                h += '<div class="p-8 mb-5 bg-slate-50 dark:bg-slate-900 rounded-2xl text-center">'
                  +  '<div class="text-4xl mb-2">🔗</div>'
                  +  '<p class="text-sm text-slate-400">Tidak ada tautan yang ditambahkan.</p>'
                  +  '</div>';
            }
        }

        /* ── Tanggal berakhir ── */
        if (d.tglSelesai && d.tglSelesai !== '') {
            h += '<div class="flex items-center gap-3 px-4 py-3 bg-amber-50 dark:bg-amber-900/20 rounded-2xl border border-amber-200 dark:border-amber-700 mb-4">';
            h += '  <span class="text-xl shrink-0">⏰</span>';
            h += '  <p class="text-xs text-amber-700 dark:text-amber-300 font-medium">Berakhir pada: <strong>' + esc(d.tglSelesai) + '</strong></p>';
            h += '</div>';
        }

        /* Footer */
        var role = d.widgetRole || 'guru';
        h += '<div class="flex justify-end pt-2">';
        h += '  <button onclick="wdgTutup(\'' + role + '\')"'
          + '          class="px-6 py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 text-sm font-semibold rounded-2xl transition-colors">';
        h += '    Tutup';
        h += '  </button>';
        h += '</div>';

        return h;
    }

    /* ── Escape HTML untuk teks biasa ──────────────────── */
    function esc(str) {
        if (str === null || str === undefined) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    /* ── Sanitasi HTML untuk rich-text editor ──────────── */
    function bersih(html) {
        if (!html) return '';
        return html
            .replace(/<script[\s\S]*?<\/script>/gi, '')
            .replace(/<iframe[\s\S]*?<\/iframe>/gi, '')
            .replace(/\bon\w+\s*=\s*["'][^"']*["']/gi, '')
            .replace(/javascript\s*:/gi, '#');
    }

})();
</script>