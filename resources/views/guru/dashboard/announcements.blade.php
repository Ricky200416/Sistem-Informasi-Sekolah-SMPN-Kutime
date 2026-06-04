{{-- resources/views/guru/dashboard/announcements.blade.php --}}
{{--
   Pengumuman dashboard — selaraskan dengan index.blade.php (pengumuman penuh).
   Mendukung tipe_konten: teks, gambar, dokumen, link.
   Modal detail memakai pgBuka() yang sama dengan halaman pengumuman penuh.
--}}
@php
$widgetPengumuman = $widgetPengumuman ?? collect();
@endphp

<div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">

    {{-- Header --}}
    <div style="display:flex;align-items:center;justify-content:space-between;
                padding:10px 14px;border-bottom:1px solid #f1f5f9;">
        <div style="display:flex;align-items:center;gap:7px;">
            <div style="width:28px;height:28px;border-radius:8px;
                        background:linear-gradient(135deg,#f59e0b,#ef4444);
                        display:flex;align-items:center;justify-content:center;font-size:.85rem;
                        flex-shrink:0;">
                📢
            </div>
            <p style="font-size:.75rem;font-weight:700;color:#1e293b;margin:0;">Pengumuman</p>
        </div>
        <div style="display:flex;align-items:center;gap:6px;">
            @if($widgetPengumuman->count() > 0)
            <span style="font-size:.55rem;font-weight:700;background:#eef2ff;color:#4f46e5;
                             border:1px solid #c7d2fe;border-radius:99px;padding:2px 7px;">
                {{ $widgetPengumuman->count() }}
            </span>
            @endif
            @if(Route::has('guru.pengumuman'))
            <a href="{{ route('guru.pengumuman') }}"
                style="font-size:.58rem;font-weight:700;color:#4f46e5;text-decoration:none;">
                Semua →
            </a>
            @endif
        </div>
    </div>

    @if($widgetPengumuman->isEmpty())
    <div style="padding:32px 16px;text-align:center;">
        <div style="width:48px;height:48px;border-radius:14px;background:#f1f5f9;
                        display:flex;align-items:center;justify-content:center;
                        font-size:1.3rem;margin:0 auto 8px;">📭</div>
        <p style="font-size:.68rem;font-weight:600;color:#475569;margin:0 0 2px;">
            Belum ada pengumuman
        </p>
        <p style="font-size:.58rem;color:#94a3b8;margin:0;">
            Tidak ada pengumuman aktif saat ini
        </p>
    </div>

    @else
    <div>
        @foreach($widgetPengumuman as $p)
        @php
        /*
        * Selaraskan dengan model Pengumuman di index.blade.php:
        * - tipe_konten: 'teks' | 'gambar' | 'dokumen' | 'link'
        * - file_path : path file gambar/dokumen (storage)
        * - file_name : nama file asli
        * - link_url : URL untuk tipe link
        * - link_label : label tombol link
        */
        $tipeKonten = $p->tipe_konten ?? 'teks';

        // Gambar — support tipe_konten='gambar' maupun kolom langsung
        $gambarUrl = null;
        if ($tipeKonten === 'gambar' && $p->file_path) {
        $gambarUrl = asset('storage/' . $p->file_path);
        } elseif ($p->gambar ?? $p->image ?? $p->foto ?? null) {
        $g = $p->gambar ?? $p->image ?? $p->foto;
        $gambarUrl = str_starts_with($g, 'http') ? $g : asset('storage/' . $g);
        }

        // File dokumen
        $fileUrl = ($tipeKonten === 'dokumen' && $p->file_path)
        ? asset('storage/' . $p->file_path) : null;
        $fileName = $p->file_name ?? null;
        $fileExt = $fileName ? strtoupper(pathinfo($fileName, PATHINFO_EXTENSION)) : '';

        // Link
        $linkUrl = $p->link_url ?? null;
        $linkLabel = $p->link_label ?? 'Buka Link';

        // Kategori / audience badge
        $catMap = [
        'penting' => ['bg'=>'#fee2e2','color'=>'#b91c1c','label'=>'Penting'],
        'info' => ['bg'=>'#e0f2fe','color'=>'#0369a1','label'=>'Info'],
        'umum' => ['bg'=>'#f8fafc','color'=>'#475569','label'=>'Umum'],
        'kegiatan' => ['bg'=>'#fef9c3','color'=>'#a16207','label'=>'Kegiatan'],
        'libur' => ['bg'=>'#ecfdf5','color'=>'#059669','label'=>'Libur'],
        ];
        $cat = $catMap[strtolower($p->kategori ?? '')]
        ?? ['bg'=>'#eef2ff','color'=>'#4f46e5','label'=>ucfirst($p->kategori ?? 'Umum')];

        // Tipe icon
        $tipeIcon = match($tipeKonten) {
        'gambar' => '🖼️',
        'dokumen' => '📄',
        'link' => '🔗',
        default => '📋',
        };

        // Audience label (jika ada method)
        $audience = method_exists($p, 'audienceLabel') ? $p->audienceLabel() : ucfirst($p->target_audience ?? 'Semua');
        $audienceColor = method_exists($p, 'audienceBadgeColor') ? $p->audienceBadgeColor() : 'bg-indigo-100 text-indigo-700';

        // Tanggal
        $tglTampil = \Carbon\Carbon::parse($p->tanggal_tayang ?? $p->created_at)->isoFormat('D MMM Y');
        $tglDetail = \Carbon\Carbon::parse($p->tanggal_tayang ?? $p->created_at)->isoFormat('D MMMM Y, HH:mm');
        $diffHuman = \Carbon\Carbon::parse($p->tanggal_tayang ?? $p->created_at)->diffForHumans();
        $tglSelesai = $p->tanggal_selesai
        ? \Carbon\Carbon::parse($p->tanggal_selesai)->isoFormat('D MMM Y, HH:mm')
        : '';

        // Data untuk modal (struktur sama dengan pgBuka di index.blade.php)
        $pgData = json_encode([
        'judul' => (string)($p->judul ?? ''),
        'isi' => (string)($p->isi ?? ''),
        'tipe' => $tipeKonten,
        'tipeIcon' => $tipeIcon,
        'audience' => $audience,
        'audienceColor' => $audienceColor,
        'fileUrl' => (string)($gambarUrl ?? $fileUrl ?? ''),
        'fileName' => (string)($fileName ?? ''),
        'fileExt' => (string)$fileExt,
        'linkUrl' => (string)($linkUrl ?? ''),
        'linkLabel' => (string)$linkLabel,
        'tanggal' => $tglDetail,
        'diffHumans' => $diffHuman,
        'creator' => (string)(optional($p->creator ?? null)->name ?? 'Admin'),
        'tglSelesai' => $tglSelesai,
        ], JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);

        $isNew = \Carbon\Carbon::parse($p->created_at)->gt(now()->subHours(24));
        @endphp

        {{-- Item card --}}
        <div style="border-bottom:1px solid #f1f5f9;cursor:pointer;transition:background .15s;"
            onmouseover="this.style.background='#f8fafc'"
            onmouseout="this.style.background='transparent'"
            onclick="pgBuka({{ $pgData }})">

            {{-- Thumbnail gambar (tipe gambar) --}}
            @if($gambarUrl)
            <div style="width:100%;height:100px;overflow:hidden;background:#f1f5f9;">
                <img src="{{ $gambarUrl }}"
                    alt="{{ $p->judul }}"
                    style="width:100%;height:100%;object-fit:cover;display:block;
                                    transition:transform .3s;"
                    onmouseover="this.style.transform='scale(1.04)'"
                    onmouseout="this.style.transform='scale(1)'"
                    onerror="this.parentElement.style.display='none'">
            </div>
            @endif

            <div style="padding:9px 13px;">

                {{-- Baris atas: tipe icon + kategori + tanggal --}}
                <div style="display:flex;align-items:center;justify-content:space-between;
                                 gap:6px;margin-bottom:4px;flex-wrap:wrap;">
                    <div style="display:flex;align-items:center;gap:4px;flex-wrap:wrap;">
                        <span style="font-size:.7rem;">{{ $tipeIcon }}</span>
                        <span style="padding:2px 7px;border-radius:5px;font-size:.54rem;
                                          font-weight:700;background:{{ $cat['bg'] }};
                                          color:{{ $cat['color'] }};">
                            {{ $cat['label'] }}
                        </span>
                        @if($isNew)
                        <span style="padding:2px 6px;border-radius:5px;font-size:.5rem;
                                              font-weight:800;background:#ecfdf5;color:#059669;
                                              border:1px solid #a7f3d0;letter-spacing:.02em;">
                            ✦ BARU
                        </span>
                        @endif
                    </div>
                    <span style="font-size:.56rem;color:#94a3b8;white-space:nowrap;">
                        {{ $tglTampil }}
                    </span>
                </div>

                {{-- Judul --}}
                <p style="font-size:.7rem;font-weight:700;color:#1e293b;line-height:1.35;
                               margin:0 0 4px;display:-webkit-box;-webkit-line-clamp:2;
                               -webkit-box-orient:vertical;overflow:hidden;">
                    {{ $p->judul }}
                </p>

                {{-- Preview isi --}}
                @if($p->isi && $tipeKonten === 'teks')
                <p style="font-size:.6rem;color:#64748b;line-height:1.45;margin:0 0 4px;
                                   display:-webkit-box;-webkit-line-clamp:2;
                                   -webkit-box-orient:vertical;overflow:hidden;">
                    {{ Str::limit(strip_tags($p->isi), 80) }}
                </p>
                @endif

                {{-- Preview khusus tipe --}}
                @if($tipeKonten === 'dokumen' && $fileUrl)
                <div style="display:inline-flex;align-items:center;gap:4px;
                                     padding:3px 8px;border-radius:6px;
                                     background:#fffbeb;border:1px solid #fde68a;
                                     font-size:.58rem;font-weight:700;color:#a16207;">
                    📄 {{ $fileExt ?: 'FILE' }} &nbsp;·&nbsp; Klik untuk unduh
                </div>
                @elseif($tipeKonten === 'link' && $linkUrl)
                <div style="display:inline-flex;align-items:center;gap:4px;
                                     padding:3px 8px;border-radius:6px;
                                     background:#e0f2fe;border:1px solid #bae6fd;
                                     font-size:.58rem;font-weight:700;color:#0369a1;">
                    🔗 {{ Str::limit($linkLabel, 35) }}
                </div>
                @endif

                {{-- Footer hint --}}
                <p style="font-size:.53rem;color:#94a3b8;margin-top:4px;">
                    Klik untuk lihat detail →
                </p>
            </div>
        </div>
        @endforeach
    </div>

    <div style="padding:7px 14px;border-top:1px solid #f1f5f9;background:#f8fafc;text-align:center;">
        @if(Route::has('guru.pengumuman'))
        <a href="{{ route('guru.pengumuman') }}"
            style="font-size:.6rem;font-weight:700;color:#4f46e5;text-decoration:none;">
            Lihat semua pengumuman →
        </a>
        @endif
    </div>
    @endif

</div>

{{-- ══════════════════════════════════════════════════════════
     MODAL DETAIL PENGUMUMAN (shared dengan index.blade.php)
     Pakai pgBuka() / pgTutup() — sama persis dengan halaman
     pengumuman penuh, agar konsisten tampil gambar, dokumen, link
══════════════════════════════════════════════════════════ --}}
@once
<div id="pgModal"
    onclick="if(event.target===this)pgTutup()"
    class="fixed inset-0 z-[999] hidden items-center justify-center p-4"
    style="background:rgba(0,0,0,.55);backdrop-filter:blur(6px)">
    <div class="relative w-full max-w-2xl max-h-[90vh] overflow-y-auto
                bg-white dark:bg-slate-800 rounded-3xl shadow-2xl
                border border-slate-200 dark:border-slate-700">
        <button onclick="pgTutup()"
            class="absolute top-4 right-4 z-10 w-9 h-9 flex items-center justify-center
                       bg-slate-100 hover:bg-red-100 dark:bg-slate-700 dark:hover:bg-red-900/40
                       text-slate-500 hover:text-red-500 rounded-2xl transition-all">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
        <div id="pgModalKonten" class="p-6 sm:p-8"></div>
    </div>
</div>
@endonce