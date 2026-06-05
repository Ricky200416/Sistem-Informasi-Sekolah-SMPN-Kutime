

<?php if (! $__env->hasRenderedOnce('b64cc89d-fe16-4596-9528-b0e05c2599de')): $__env->markAsRenderedOnce('b64cc89d-fe16-4596-9528-b0e05c2599de'); ?>
<script>
    (function() {
        'use strict';

        /* ── Cegah double-define jika halaman pengumuman sudah load script ini ── */
        if (window.__pgModalInitialized) return;
        window.__pgModalInitialized = true;

        /* ════════════════════════════════════════════
           BUKA & TUTUP MODAL
        ════════════════════════════════════════════ */
        window.pgBuka = function(d) {
            var konten = document.getElementById('pgModalKonten');
            if (!konten) return;
            konten.innerHTML = pgBuatHtml(d);
            var overlay = document.getElementById('pgModal');
            if (!overlay) return;
            overlay.classList.remove('hidden');
            overlay.classList.add('flex');
            document.body.style.overflow = 'hidden';
        };

        window.pgTutup = function() {
            var overlay = document.getElementById('pgModal');
            if (!overlay) return;
            overlay.classList.add('hidden');
            overlay.classList.remove('flex');
            document.body.style.overflow = '';
        };

        /* ── Handler error gambar ── */
        window.pgImgError = function(el) {
            var wrap = el.closest('.pg-img-wrap');
            if (wrap) {
                wrap.innerHTML = '<div class="p-8 text-center">' +
                    '<div class="text-5xl mb-3">🖼️</div>' +
                    '<p class="text-sm text-slate-400">Gambar tidak dapat dimuat.</p>' +
                    '</div>';
            }
        };

        /* ── ESC untuk tutup ── */
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') pgTutup();
        });

        /* ════════════════════════════════════════════
           RENDER HTML MODAL
           Struktur sama persis dengan pgBuatHtml
           di halaman pengumuman penuh.
        ════════════════════════════════════════════ */
        function pgBuatHtml(d) {
            var h = '';

            /* ── Header: icon + judul + badge ── */
            h += '<div class="flex items-start gap-4 mb-5 pr-10">';
            h += '<div class="text-3xl shrink-0 mt-0.5 leading-none">' + (d.tipeIcon || '📋') + '</div>';
            h += '<div class="flex-1 min-w-0">';
            h += '<h2 class="text-xl font-bold text-slate-800 dark:text-slate-100 leading-snug break-words">' +
                esc(d.judul) + '</h2>';
            h += '<div class="flex gap-2 mt-2 flex-wrap">';
            if (d.audience) {
                h += '<span class="px-2.5 py-1 rounded-full text-xs font-semibold ' + (d.audienceColor || 'bg-indigo-100 text-indigo-700') + '">' +
                    esc(d.audience) + '</span>';
            }
            h += '<span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300 capitalize">' +
                esc(d.tipe || 'teks') + '</span>';
            h += '</div></div></div>';

            /* ── Meta ── */
            h += '<div class="flex flex-wrap gap-x-4 gap-y-1 text-xs text-slate-400 mb-5 pb-5 border-b border-slate-200 dark:border-slate-700">';
            if (d.tanggal) h += '<span>📅 ' + esc(d.tanggal) + '</span>';
            if (d.creator) h += '<span>👤 ' + esc(d.creator) + '</span>';
            if (d.diffHumans) h += '<span>🕐 ' + esc(d.diffHumans) + '</span>';
            h += '</div>';

            /* ── GAMBAR ── */
            if (d.tipe === 'gambar') {
                if (d.fileUrl && d.fileUrl !== '') {
                    h += '<div class="pg-img-wrap rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-600 mb-5 bg-slate-50 dark:bg-slate-900 flex items-center justify-center min-h-[120px]">';
                    h += '<img src="' + esc(d.fileUrl) + '" alt="' + esc(d.judul) + '"' +
                        ' class="w-full max-h-[420px] object-contain block"' +
                        ' onerror="pgImgError(this)">';
                    h += '</div>';
                } else {
                    h += '<div class="p-8 mb-5 bg-slate-50 dark:bg-slate-900/40 rounded-2xl text-center">' +
                        '<div class="text-4xl mb-2">🖼️</div>' +
                        '<p class="text-sm text-slate-400">Tidak ada file gambar.</p></div>';
                }
            }

            /* ── ISI TEKS ── */
            if (d.isi && d.isi.trim() !== '') {
                var adaHtml = /<[a-z][\s\S]*>/i.test(d.isi);
                h += adaHtml ?
                    '<div class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed mb-5 prose prose-sm dark:prose-invert max-w-none">' +
                    bersihHtml(d.isi) + '</div>' :
                    '<div class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed mb-5 whitespace-pre-line">' +
                    esc(d.isi) + '</div>';
            }

            /* ── DOKUMEN ── */
            if (d.tipe === 'dokumen') {
                if (d.fileUrl && d.fileUrl !== '') {
                    h += '<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 p-4 bg-amber-50 dark:bg-amber-900/30 rounded-2xl border border-amber-200 dark:border-amber-700 mb-5">';
                    h += '<div class="flex items-center gap-3">';
                    h += '<div class="w-12 h-12 bg-amber-100 dark:bg-amber-800 rounded-xl flex items-center justify-center text-2xl">📄</div>';
                    h += '<div><p class="text-sm font-bold text-amber-700 dark:text-amber-300">' +
                        esc(d.fileExt || 'FILE') + ' Dokumen</p>';
                    if (d.fileName) h += '<p class="text-xs text-slate-400 max-w-[220px] truncate">' + esc(d.fileName) + '</p>';
                    h += '</div></div>';
                    h += '<a href="' + esc(d.fileUrl) + '" target="_blank" download onclick="event.stopPropagation()"' +
                        ' class="shrink-0 inline-flex items-center gap-2 px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-white text-sm font-semibold rounded-xl no-underline">' +
                        '<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">' +
                        '<path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>' +
                        'Unduh Dokumen</a>';
                    h += '</div>';
                } else {
                    h += '<div class="p-8 mb-5 bg-slate-50 dark:bg-slate-900/40 rounded-2xl text-center">' +
                        '<div class="text-4xl mb-2">📄</div>' +
                        '<p class="text-sm text-slate-400">Tidak ada file dokumen.</p></div>';
                }
            }

            /* ── LINK ── */
            if (d.tipe === 'link') {
                if (d.linkUrl && d.linkUrl !== '') {
                    h += '<div class="p-4 bg-sky-50 dark:bg-sky-900/30 rounded-2xl border border-sky-200 dark:border-sky-700 mb-5">';
                    h += '<p class="text-xs text-slate-500 dark:text-slate-400 mb-3 font-medium">🔗 Tautan Pengumuman</p>';
                    h += '<a href="' + esc(d.linkUrl) + '" target="_blank" rel="noopener noreferrer" onclick="event.stopPropagation()"' +
                        ' class="inline-flex items-center gap-2 px-5 py-2.5 bg-sky-600 hover:bg-sky-700 text-white text-sm font-semibold rounded-xl no-underline">' +
                        '<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">' +
                        '<path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>' +
                        esc(d.linkLabel || 'Buka Link') + '</a>';
                    h += '<p class="text-xs text-slate-400 mt-2 break-all">' + esc(d.linkUrl) + '</p>';
                    h += '</div>';
                } else {
                    h += '<div class="p-8 mb-5 bg-slate-50 dark:bg-slate-900/40 rounded-2xl text-center">' +
                        '<div class="text-4xl mb-2">🔗</div>' +
                        '<p class="text-sm text-slate-400">Tidak ada tautan.</p></div>';
                }
            }

            /* ── Tanggal selesai ── */
            if (d.tglSelesai && d.tglSelesai !== '') {
                h += '<div class="flex items-center gap-3 px-4 py-3 bg-amber-50 dark:bg-amber-900/20 rounded-2xl border border-amber-200 dark:border-amber-700 mb-4">' +
                    '<span class="text-xl">⏰</span>' +
                    '<p class="text-xs text-amber-700 dark:text-amber-300 font-medium">Berakhir: <strong>' +
                    esc(d.tglSelesai) + '</strong></p></div>';
            }

            /* ── Tombol tutup ── */
            h += '<div class="flex justify-end pt-2">' +
                '<button onclick="pgTutup()" class="px-6 py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 text-sm font-semibold rounded-2xl transition-colors">Tutup</button>' +
                '</div>';

            return h;
        }

        /* ── Escape HTML ── */
        function esc(str) {
            if (str === null || str === undefined) return '';
            return String(str)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        /* ── Bersihkan HTML dari script berbahaya ── */
        function bersihHtml(html) {
            if (!html) return '';
            return html
                .replace(/<script[\s\S]*?<\/script>/gi, '')
                .replace(/<iframe[\s\S]*?<\/iframe>/gi, '')
                .replace(/\bon\w+\s*=\s*["'][^"']*["']/gi, '')
                .replace(/javascript\s*:/gi, '#');
        }

    })();
</script>
<?php endif; ?><?php /**PATH S:\PA3\smpn-kutime\resources\views/guru/dashboard/scripts-modal.blade.php ENDPATH**/ ?>