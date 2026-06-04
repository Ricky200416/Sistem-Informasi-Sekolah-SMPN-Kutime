{{--
|--------------------------------------------------------------------------
| resources/views/components/pengumuman-modal.blade.php
|--------------------------------------------------------------------------
| Partial reusable: Modal detail pengumuman.
| Di-include di: admin/pengumuman/index, guru/pengumuman/index,
|                siswa/pengumuman/index, dan semua view yang menampilkan
|                kartu pengumuman.
|
| Cara pakai di view:
|   @include('components.pengumuman-modal')
|
| Cara buka modal dari JS:
|   bukaModal(dataObject)
|
| Format dataObject: lihat @php $modalData di view masing-masing.
|--------------------------------------------------------------------------
--}}

{{-- ── OVERLAY + CONTAINER ── --}}
<div id="modalOverlay"
     onclick="if(event.target===this) tutupModal()"
     class="fixed inset-0 z-50 hidden items-center justify-center p-4"
     style="background:rgba(0,0,0,.6); backdrop-filter:blur(6px)">

    <div id="modalBox"
         class="relative w-full max-w-2xl max-h-[92vh] overflow-y-auto
                bg-white dark:bg-slate-800 rounded-3xl shadow-2xl
                border border-slate-200 dark:border-slate-700
                transform transition-all duration-200 scale-95 opacity-0"
         style="scroll-behavior:smooth">

        {{-- Tombol tutup --}}
        <button onclick="tutupModal()"
                class="absolute top-4 right-4 z-20 w-9 h-9 flex items-center justify-center
                       bg-slate-100 hover:bg-red-100 dark:bg-slate-700 dark:hover:bg-red-900/40
                       text-slate-500 hover:text-red-600 dark:text-slate-400 dark:hover:text-red-400
                       rounded-2xl transition-all shadow-sm">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        {{-- Konten dinamis diisi lewat JS --}}
        <div id="modalContent" class="p-6 sm:p-8"></div>
    </div>
</div>

@push('scripts')
<script>
/* ============================================================
   PENGUMUMAN MODAL — shared JS
   Semua view pengumuman menggunakan fungsi ini.
   ============================================================ */

/**
 * Buka modal dengan data pengumuman.
 *
 * @param {Object} d  - Data pengumuman (lihat format di view)
 */
function bukaModal(d) {
    const content = document.getElementById('modalContent');
    content.innerHTML = buildModalHtml(d);

    const overlay = document.getElementById('modalOverlay');
    const box     = document.getElementById('modalBox');

    overlay.classList.remove('hidden');
    overlay.classList.add('flex');
    document.body.style.overflow = 'hidden';

    // Animasi masuk
    requestAnimationFrame(() => {
        box.classList.remove('scale-95', 'opacity-0');
        box.classList.add('scale-100', 'opacity-100');
    });
}

/**
 * Tutup modal.
 */
function tutupModal() {
    const overlay = document.getElementById('modalOverlay');
    const box     = document.getElementById('modalBox');

    box.classList.remove('scale-100', 'opacity-100');
    box.classList.add('scale-95', 'opacity-0');

    setTimeout(() => {
        overlay.classList.add('hidden');
        overlay.classList.remove('flex');
        document.body.style.overflow = '';
    }, 180);
}

/**
 * Build HTML konten modal dari data pengumuman.
 */
function buildModalHtml(d) {
    let html = '';

    /* ── HEADER: ikon + judul + badges ── */
    html += `
    <div class="flex items-start gap-4 mb-5 pr-10">
        <div class="text-3xl shrink-0 mt-0.5 leading-none">${d.tipeIcon}</div>
        <div class="flex-1 min-w-0">
            <h2 class="text-xl font-bold text-slate-800 dark:text-slate-100 leading-snug break-words">
                ${escHtml(d.judul)}
            </h2>
            <div class="flex gap-2 mt-2 flex-wrap">
                <span class="px-2.5 py-1 rounded-full text-xs font-semibold ${d.audienceColor}">
                    ${escHtml(d.audience)}
                </span>
                <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                             bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300 capitalize">
                    ${escHtml(d.tipe)}
                </span>
            </div>
        </div>
    </div>`;

    /* ── META: tanggal, pembuat, waktu relatif ── */
    html += `
    <div class="flex flex-wrap gap-x-4 gap-y-1 text-xs text-slate-400
                mb-5 pb-5 border-b border-slate-200 dark:border-slate-700">
        <span class="flex items-center gap-1">
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            ${escHtml(d.tanggal)}
        </span>
        <span class="flex items-center gap-1">
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            ${escHtml(d.creator)}
        </span>
        <span>${escHtml(d.diffHumans)}</span>
    </div>`;

    /* ── KONTEN GAMBAR ── */
    if (d.tipe === 'gambar') {
        if (d.fileUrl && d.fileUrl !== '') {
            html += `
            <div class="rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-600
                        mb-5 bg-slate-50 dark:bg-slate-900/60
                        flex items-center justify-center min-h-[140px]">
                <img
                    src="${d.fileUrl}"
                    alt="${escHtml(d.judul)}"
                    class="w-full max-h-[420px] object-contain block"
                    onerror="
                        this.parentElement.innerHTML=
                        '<div class=\\'p-8 text-center\\'>'
                        +'<div class=\\'text-5xl mb-3\\'>🖼️</div>'
                        +'<p class=\\'text-sm text-slate-400 font-medium\\'>Gambar tidak dapat dimuat.</p>'
                        +'<p class=\\'text-xs text-slate-400 mt-1\\'>Jalankan: <code class=\\'bg-slate-100 dark:bg-slate-700 px-1 rounded\\'>php artisan storage:link</code></p>'
                        +'</div>'
                    "
                >
            </div>`;
        } else {
            html += `
            <div class="p-8 mb-5 bg-slate-50 dark:bg-slate-900/50 rounded-2xl text-center">
                <div class="text-4xl mb-2">🖼️</div>
                <p class="text-sm text-slate-400">Tidak ada file gambar yang diunggah.</p>
            </div>`;
        }
    }

    /* ── KONTEN TEKS / ISI ── */
    if (d.isi && d.isi.trim() !== '') {
        const hasHtmlTags = /<[a-z][\s\S]*>/i.test(d.isi);
        if (hasHtmlTags) {
            // Konten dari rich-text editor — render sebagai HTML setelah sanitasi
            html += `
            <div class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed mb-5
                        prose prose-sm dark:prose-invert max-w-none
                        prose-img:rounded-xl prose-a:text-indigo-600 prose-a:no-underline">
                ${sanitizeHtml(d.isi)}
            </div>`;
        } else {
            // Plain text — escape dan tampilkan whitespace
            html += `
            <div class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed mb-5 whitespace-pre-line">
                ${escHtml(d.isi)}
            </div>`;
        }
    }

    /* ── KONTEN DOKUMEN ── */
    if (d.tipe === 'dokumen') {
        if (d.fileUrl && d.fileUrl !== '') {
            html += `
            <div class="flex flex-col sm:flex-row items-start sm:items-center
                        justify-between gap-3 p-4
                        bg-indigo-50 dark:bg-indigo-900/30
                        rounded-2xl border border-indigo-200 dark:border-indigo-700 mb-5">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-indigo-100 dark:bg-indigo-800 rounded-xl
                                flex items-center justify-center text-2xl shrink-0">📄</div>
                    <div class="min-w-0">
                        <p class="text-sm font-bold text-indigo-700 dark:text-indigo-300">
                            ${escHtml(d.fileExt || 'FILE')} &nbsp;<span class="font-normal opacity-70">Dokumen</span>
                        </p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 truncate max-w-[220px]">
                            ${escHtml(d.fileName)}
                        </p>
                    </div>
                </div>
                <a href="${d.fileUrl}"
                   target="_blank"
                   download
                   onclick="event.stopPropagation()"
                   class="shrink-0 inline-flex items-center gap-2 px-5 py-2.5
                          bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800
                          text-white text-sm font-semibold rounded-xl transition-colors
                          no-underline shadow-sm hover:shadow-indigo-200 dark:hover:shadow-indigo-900">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Unduh Dokumen
                </a>
            </div>`;
        } else {
            html += `
            <div class="p-8 mb-5 bg-slate-50 dark:bg-slate-900/50 rounded-2xl text-center">
                <div class="text-4xl mb-2">📄</div>
                <p class="text-sm text-slate-400">Tidak ada file dokumen yang diunggah.</p>
            </div>`;
        }
    }

    /* ── KONTEN LINK ── */
    if (d.tipe === 'link') {
        if (d.linkUrl && d.linkUrl !== '') {
            html += `
            <div class="p-4 bg-sky-50 dark:bg-sky-900/30
                        rounded-2xl border border-sky-200 dark:border-sky-700 mb-5">
                <p class="text-xs text-slate-500 dark:text-slate-400 mb-3 font-medium flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656
                                 l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                    </svg>
                    Tautan Pengumuman
                </p>
                <a href="${d.linkUrl}"
                   target="_blank"
                   rel="noopener noreferrer"
                   onclick="event.stopPropagation()"
                   class="inline-flex items-center gap-2 px-5 py-2.5
                          bg-sky-600 hover:bg-sky-700 active:bg-sky-800
                          text-white text-sm font-semibold rounded-xl transition-colors
                          no-underline shadow-sm">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4
                                 M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    ${escHtml(d.linkLabel || 'Buka Link')}
                </a>
                <p class="text-xs text-slate-400 mt-2 break-all">${escHtml(d.linkUrl)}</p>
            </div>`;
        } else {
            html += `
            <div class="p-8 mb-5 bg-slate-50 dark:bg-slate-900/50 rounded-2xl text-center">
                <div class="text-4xl mb-2">🔗</div>
                <p class="text-sm text-slate-400">Tidak ada tautan yang ditambahkan.</p>
            </div>`;
        }
    }

    /* ── TANGGAL BERAKHIR ── */
    if (d.tglSelesai && d.tglSelesai !== '') {
        html += `
        <div class="flex items-center gap-3 px-4 py-3
                    bg-amber-50 dark:bg-amber-900/20
                    rounded-2xl border border-amber-200 dark:border-amber-700 mb-4">
            <span class="text-xl shrink-0">⏰</span>
            <p class="text-xs text-amber-700 dark:text-amber-300 font-medium">
                Pengumuman berakhir pada: <strong>${escHtml(d.tglSelesai)}</strong>
            </p>
        </div>`;
    }

    /* ── FOOTER ── */
    html += `
    <div class="flex justify-end pt-2">
        <button onclick="tutupModal()"
                class="px-6 py-2.5 bg-slate-100 hover:bg-slate-200
                       dark:bg-slate-700 dark:hover:bg-slate-600
                       text-slate-700 dark:text-slate-200
                       text-sm font-semibold rounded-2xl transition-colors">
            Tutup
        </button>
    </div>`;

    return html;
}

/* ── Utilities ── */

/**
 * Escape HTML entities — untuk teks biasa (judul, nama, dll).
 * JANGAN pakai untuk konten rich-text yang memang berisi HTML.
 */
function escHtml(str) {
    if (str === null || str === undefined) return '';
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

/**
 * Sanitasi HTML sederhana — buang script dan event handler berbahaya.
 * Dipakai untuk konten isi yang mengandung tag HTML dari rich-text editor.
 */
function sanitizeHtml(html) {
    if (!html) return '';
    return html
        .replace(/<script[\s\S]*?<\/script>/gi, '')
        .replace(/<iframe[\s\S]*?<\/iframe>/gi, '')
        .replace(/\bon\w+\s*=\s*["'][^"']*["']/gi, '')
        .replace(/\bon\w+\s*=\s*[^\s>]*/gi, '')
        .replace(/javascript\s*:/gi, 'nojs:');
}

/* Tutup modal dengan tombol Escape */
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') tutupModal();
});
</script>
@endpush