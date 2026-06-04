{{--
| resources/views/siswa/dashboard/announcement.blade.php
| Partial: Widget Pengumuman untuk Dashboard Siswa
| Usage: @include('siswa.dashboard.announcement', ['widgetPengumuman' => $widgetPengumuman])
--}}

{{-- ══ MODAL PENGUMUMAN ══════════════════════════════════════════════════════ --}}
<div id="annModal"
     onclick="if(event.target===this)annClose()"
     class="fixed inset-0 z-[9999] hidden items-center justify-center p-4"
     style="background:rgba(0,0,0,.6);backdrop-filter:blur(8px)">
    <div class="relative w-full max-w-2xl max-h-[90vh] overflow-y-auto
                bg-white dark:bg-slate-800 rounded-3xl shadow-2xl
                border border-slate-200 dark:border-slate-700 animate-ann-modal">
        <button onclick="annClose()"
                class="absolute top-4 right-4 z-10 w-8 h-8 flex items-center justify-center
                       bg-slate-100 hover:bg-red-100 dark:bg-slate-700 dark:hover:bg-red-900/40
                       text-slate-400 hover:text-red-500 rounded-xl transition-all text-sm">✕</button>
        <div id="annModalBody" class="p-6 sm:p-8"></div>
    </div>
</div>

{{-- ══ WIDGET CARD ═══════════════════════════════════════════════════════════ --}}
<div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200
            dark:border-slate-700 shadow-sm overflow-hidden">

    {{-- Header --}}
    <div class="flex items-center justify-between px-4 py-3.5 border-b
                border-slate-100 dark:border-slate-700/60
                bg-gradient-to-r from-sky-50 to-blue-50
                dark:from-sky-900/10 dark:to-blue-900/10">
        <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-sky-500 to-blue-600
                        flex items-center justify-center shadow-sm text-base">📢</div>
            <div>
                <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100">Pengumuman</h3>
                <p class="text-[10px] text-slate-400 leading-none mt-0.5">
                    {{ $widgetPengumuman->count() }} terbaru
                </p>
            </div>
        </div>
        @if(Route::has('siswa.pengumuman'))
        <a href="{{ route('siswa.pengumuman') }}"
           class="text-[11px] font-semibold text-sky-600 hover:text-sky-700
                  dark:text-sky-400 dark:hover:text-sky-300
                  flex items-center gap-0.5 transition-colors">
            Semua
            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
        @endif
    </div>

    @if($widgetPengumuman->isEmpty())
    <div class="flex flex-col items-center justify-center py-10 text-center px-4">
        <div class="text-4xl mb-2">📭</div>
        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400">Belum ada pengumuman</p>
        <p class="text-[10px] text-slate-400 mt-0.5">Cek kembali nanti ya.</p>
    </div>
    @else
    <div class="divide-y divide-slate-100 dark:divide-slate-700/50">
        @foreach($widgetPengumuman as $item)
        @php
            $tipeKonten = $item->tipe_konten ?? 'teks';

            // Gambar — support tipe_konten='gambar' maupun kolom langsung
            $gambarUrl = null;
            if ($tipeKonten === 'gambar' && $item->file_path) {
                $gambarUrl = asset('storage/' . $item->file_path);
            } elseif ($item->gambar ?? $item->image ?? $item->foto ?? null) {
                $g = $item->gambar ?? $item->image ?? $item->foto;
                $gambarUrl = str_starts_with($g, 'http') ? $g : asset('storage/' . $g);
            }

            // File dokumen
            $fileUrl  = ($tipeKonten === 'dokumen' && $item->file_path)
                        ? asset('storage/' . $item->file_path) : null;
            $fileName = $item->file_name ?? null;
            $fileExt  = $fileName ? strtoupper(pathinfo($fileName, PATHINFO_EXTENSION)) : '';

            // Link
            $linkUrl   = $item->link_url ?? null;
            $linkLabel = $item->link_label ?? 'Buka Link';

            // Tipe icon
            $tipeIcon = match($tipeKonten) {
                'gambar'   => '🖼️',
                'dokumen'  => '📄',
                'link'     => '🔗',
                default    => '📋',
            };

            // Audience
            $audience      = method_exists($item, 'audienceLabel')     ? $item->audienceLabel()      : ucfirst($item->target_audience ?? 'Semua');
            $audienceColor = method_exists($item, 'audienceBadgeColor') ? $item->audienceBadgeColor() : 'bg-indigo-100 text-indigo-700';

            // Tanggal
            $tglDetail  = $item->created_at->isoFormat('D MMMM Y, HH:mm');
            $diffHumans = $item->created_at->diffForHumans();

            // Tanggal selesai
            $tglSelesai = $item->tanggal_selesai
                          ? $item->tanggal_selesai->isoFormat('D MMM Y, HH:mm')
                          : '';

            // Data untuk modal
            $annData = [
                'judul'         => (string)($item->judul ?? ''),
                'isi'           => (string)($item->isi ?? ''),
                'tipe'          => $tipeKonten,
                'tipeIcon'      => $tipeIcon,
                'audience'      => $audience,
                'audienceColor' => $audienceColor,
                'fileUrl'       => (string)($gambarUrl ?? $fileUrl ?? ''),
                'fileName'      => (string)($fileName ?? ''),
                'fileExt'       => (string)$fileExt,
                'linkUrl'       => (string)($linkUrl ?? ''),
                'linkLabel'     => (string)$linkLabel,
                'tanggal'       => $tglDetail,
                'diffHumans'    => $diffHumans,
                'creator'       => (string)(optional($item->creator)->name ?? 'Admin'),
                'tglSelesai'    => $tglSelesai,
            ];
            $annJson = json_encode($annData, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_UNESCAPED_UNICODE);
            $isNew   = $item->created_at->gt(now()->subHours(24));
        @endphp

        <button type="button"
                onclick='annOpen({{ $annJson }})'
                class="group w-full text-left
                       hover:bg-sky-50/60 dark:hover:bg-sky-900/10
                       transition-colors focus:outline-none">

            {{-- ── Thumbnail gambar (hanya tipe gambar) ─────────────────────── --}}
            @if($gambarUrl)
            <div class="w-full h-28 overflow-hidden bg-slate-100 dark:bg-slate-700">
                <img src="{{ $gambarUrl }}"
                     alt="{{ $item->judul }}"
                     class="w-full h-full object-cover transition-transform duration-300
                            group-hover:scale-105"
                     onerror="this.parentElement.style.display='none'">
            </div>
            @endif

            {{-- ── Konten item ───────────────────────────────────────────────── --}}
            <div class="flex items-start gap-3 px-4 py-3.5">
                {{-- Icon tipe --}}
                <div class="shrink-0 w-9 h-9 rounded-xl flex items-center justify-center text-base mt-0.5
                    {{ $tipeKonten === 'dokumen'
                        ? 'bg-indigo-100 dark:bg-indigo-900/30'
                        : ($tipeKonten === 'link'
                            ? 'bg-sky-100 dark:bg-sky-900/30'
                            : ($tipeKonten === 'gambar'
                                ? 'bg-rose-100 dark:bg-rose-900/30'
                                : 'bg-emerald-100 dark:bg-emerald-900/30')) }}">
                    {{ $tipeIcon }}
                </div>

                {{-- Teks konten --}}
                <div class="flex-1 min-w-0">
                    {{-- Judul + badge BARU --}}
                    <div class="flex items-center gap-1.5 flex-wrap mb-0.5">
                        <p class="text-xs font-semibold text-slate-800 dark:text-slate-100
                                  group-hover:text-sky-600 dark:group-hover:text-sky-400
                                  transition-colors leading-snug line-clamp-1">
                            {{ $item->judul }}
                        </p>
                        @if($isNew)
                        <span class="shrink-0 inline-flex items-center px-1.5 py-0.5 rounded-full
                                     text-[9px] font-bold bg-emerald-100 text-emerald-700
                                     dark:bg-emerald-900/40 dark:text-emerald-400">BARU</span>
                        @endif
                    </div>

                    {{-- Preview isi teks --}}
                    @if($item->isi && $tipeKonten === 'teks')
                    <p class="text-[11px] text-slate-400 line-clamp-1 leading-relaxed">
                        {{ strip_tags($item->isi) }}
                    </p>
                    @endif

                    {{-- Preview badge: dokumen --}}
                    @if($tipeKonten === 'dokumen' && $fileUrl)
                    <div class="inline-flex items-center gap-1 mt-1 px-2 py-0.5 rounded-md
                                bg-amber-50 border border-amber-200
                                text-[10px] font-bold text-amber-700
                                dark:bg-amber-900/20 dark:border-amber-700 dark:text-amber-400">
                        📄 {{ $fileExt ?: 'FILE' }} &nbsp;·&nbsp; Klik untuk unduh
                    </div>
                    @endif

                    {{-- Preview badge: link --}}
                    @if($tipeKonten === 'link' && $linkUrl)
                    <div class="inline-flex items-center gap-1 mt-1 px-2 py-0.5 rounded-md
                                bg-sky-50 border border-sky-200
                                text-[10px] font-bold text-sky-700
                                dark:bg-sky-900/20 dark:border-sky-700 dark:text-sky-400">
                        🔗 {{ Str::limit($linkLabel, 35) }}
                    </div>
                    @endif

                    {{-- Waktu --}}
                    <p class="text-[10px] text-slate-400 mt-1">
                        {{ $diffHumans }}
                    </p>
                </div>

                {{-- Arrow --}}
                <div class="shrink-0 self-center text-slate-300 dark:text-slate-600
                            group-hover:text-sky-400 group-hover:translate-x-0.5 transition-all">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </div>
        </button>
        @endforeach
    </div>

    @if(Route::has('siswa.pengumuman'))
    <div class="px-4 py-3 border-t border-slate-100 dark:border-slate-700/50">
        <a href="{{ route('siswa.pengumuman') }}"
           class="flex items-center justify-center gap-1.5 text-[11px] font-semibold
                  text-sky-600 hover:text-sky-700 dark:text-sky-400
                  transition-colors py-1 hover:underline">
            Lihat semua pengumuman
            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
    </div>
    @endif
    @endif
</div>

@once
@push('styles')
<style>
.animate-ann-modal { animation: annPop .22s cubic-bezier(.34,1.56,.64,1); }
@keyframes annPop { from { opacity:0; transform:scale(.92) translateY(12px); } to { opacity:1; transform:none; } }
</style>
@endpush

@push('scripts')
<script>
(function(){
    'use strict';
    window.annOpen = function(d) {
        document.getElementById('annModalBody').innerHTML = annBuildHtml(d);
        var el = document.getElementById('annModal');
        el.classList.remove('hidden'); el.classList.add('flex');
        document.body.style.overflow = 'hidden';
    };
    window.annClose = function() {
        var el = document.getElementById('annModal');
        el.classList.add('hidden'); el.classList.remove('flex');
        document.body.style.overflow = '';
    };
    document.addEventListener('keydown', function(ev){ if(ev.key==='Escape') annClose(); });

    function annBuildHtml(d) {
        var h = '';
        h += '<div class="flex items-start gap-4 mb-5 pr-10">';
        h += '<div class="text-3xl shrink-0 mt-0.5 leading-none">'+d.tipeIcon+'</div>';
        h += '<div class="flex-1 min-w-0">';
        h += '<h2 class="text-xl font-bold text-slate-800 dark:text-slate-100 leading-snug break-words">'+e(d.judul)+'</h2>';
        h += '<div class="flex gap-2 mt-2 flex-wrap">';
        h += '<span class="px-2.5 py-1 rounded-full text-xs font-semibold '+d.audienceColor+'">'+e(d.audience)+'</span>';
        h += '<span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300 capitalize">'+e(d.tipe)+'</span>';
        h += '</div></div></div>';
        h += '<div class="flex flex-wrap gap-x-4 gap-y-1 text-xs text-slate-400 mb-5 pb-5 border-b border-slate-200 dark:border-slate-700">';
        h += '<span>📅 '+e(d.tanggal)+'</span><span>👤 '+e(d.creator)+'</span><span>🕐 '+e(d.diffHumans)+'</span></div>';

        // Gambar
        if(d.tipe==='gambar'&&d.fileUrl){
            h += '<div class="rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-600 mb-5 bg-slate-50 dark:bg-slate-900 flex items-center justify-center min-h-[120px]">';
            h += '<img src="'+d.fileUrl+'" alt="'+e(d.judul)+'" class="w-full max-h-[420px] object-contain block" onerror="this.closest(\'div\').innerHTML=\'<div class=\\\"p-8 text-center\\\"><div class=\\\"text-5xl mb-3\\\">🖼️</div><p class=\\\"text-sm text-slate-400\\\">Gambar tidak dapat dimuat.</p></div>\'">';
            h += '</div>';
        }

        // Isi teks
        if(d.isi&&d.isi.trim()){
            var isHtml = /<[a-z][\s\S]*>/i.test(d.isi);
            h += isHtml
                ? '<div class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed mb-5 prose prose-sm dark:prose-invert max-w-none">'+sanitize(d.isi)+'</div>'
                : '<div class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed mb-5 whitespace-pre-line">'+e(d.isi)+'</div>';
        }

        // Dokumen
        if(d.tipe==='dokumen'&&d.fileUrl){
            h += '<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 p-4 bg-indigo-50 dark:bg-indigo-900/30 rounded-2xl border border-indigo-200 dark:border-indigo-700 mb-5">';
            h += '<div class="flex items-center gap-3"><div class="w-12 h-12 bg-indigo-100 dark:bg-indigo-800 rounded-xl flex items-center justify-center text-2xl">📄</div>';
            h += '<div><p class="text-sm font-bold text-indigo-700 dark:text-indigo-300">'+e(d.fileExt||'FILE')+' Dokumen</p><p class="text-xs text-slate-400 max-w-[220px] truncate">'+e(d.fileName)+'</p></div></div>';
            h += '<a href="'+d.fileUrl+'" target="_blank" download onclick="event.stopPropagation()" class="shrink-0 inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl no-underline">⬇️ Unduh</a></div>';
        }

        // Link
        if(d.tipe==='link'&&d.linkUrl){
            h += '<div class="p-4 bg-sky-50 dark:bg-sky-900/30 rounded-2xl border border-sky-200 dark:border-sky-700 mb-5">';
            h += '<p class="text-xs text-slate-500 mb-3 font-medium">🔗 Tautan Pengumuman</p>';
            h += '<a href="'+d.linkUrl+'" target="_blank" rel="noopener noreferrer" onclick="event.stopPropagation()" class="inline-flex items-center gap-2 px-5 py-2.5 bg-sky-600 hover:bg-sky-700 text-white text-sm font-semibold rounded-xl no-underline">🔗 '+e(d.linkLabel||'Kunjungi Link')+'</a>';
            h += '<p class="text-xs text-slate-400 mt-2 break-all">'+e(d.linkUrl)+'</p></div>';
        }

        // Tanggal selesai
        if(d.tglSelesai){
            h += '<div class="flex items-center gap-3 px-4 py-3 bg-amber-50 dark:bg-amber-900/20 rounded-2xl border border-amber-200 dark:border-amber-700 mb-4"><span class="text-xl">⏰</span><p class="text-xs text-amber-700 dark:text-amber-300 font-medium">Berakhir: <strong>'+e(d.tglSelesai)+'</strong></p></div>';
        }

        h += '<div class="flex justify-end pt-2"><button onclick="annClose()" class="px-6 py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 text-sm font-semibold rounded-2xl transition-colors">Tutup</button></div>';
        return h;
    }
    function e(v){if(v==null)return '';return String(v).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#039;');}
    function sanitize(h){return(h||'').replace(/<script[\s\S]*?<\/script>/gi,'').replace(/<iframe[\s\S]*?<\/iframe>/gi,'').replace(/\bon\w+\s*=\s*["'][^"']*["']/gi,'').replace(/javascript\s*:/gi,'#');}
})();
</script>
@endpush
@endonce