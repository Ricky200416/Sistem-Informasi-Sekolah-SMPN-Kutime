{{--
╔══════════════════════════════════════════════════════════════════╗
║  resources/views/admin/users/_modal_detail.blade.php             ║
║  Data diisi via JS: openDetailModal(userId) → fetch API          ║
╚══════════════════════════════════════════════════════════════════╝
--}}
<div id="modalDetail"
     class="fixed inset-0 z-50 hidden items-center justify-center p-4"
     role="dialog" aria-modal="true" aria-labelledby="titleModalDetail">

    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm"
         onclick="closeModal('modalDetail')"></div>

    {{-- Panel --}}
    <div class="relative bg-white dark:bg-slate-800 rounded-2xl shadow-2xl
                w-full max-w-lg max-h-[92vh] flex flex-col">

        {{-- Header --}}
        <div class="flex items-center justify-between px-6 pt-5 pb-4
                    border-b border-slate-100 dark:border-slate-700 shrink-0">
            <div class="flex items-center gap-3">
                <div id="detailAvatarWrap"></div>
                <div>
                    <h2 id="detailName"
                        class="text-base font-bold text-slate-800 dark:text-slate-100 leading-tight">
                    </h2>
                    <p id="detailEmail"
                       class="text-xs text-slate-400 truncate max-w-[16rem]"></p>
                </div>
            </div>
            <button onclick="closeModal('modalDetail')"
                    class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600
                           hover:bg-slate-100 dark:hover:bg-slate-700 transition shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Loading state --}}
        <div id="detailLoading" class="flex items-center justify-center py-12">
            <div class="flex flex-col items-center gap-3 text-slate-400">
                <svg class="animate-spin w-6 h-6" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10"
                            stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                          d="M4 12a8 8 0 018-8v8z"></path>
                </svg>
                <p class="text-xs">Memuat data...</p>
            </div>
        </div>

        {{-- Body (diisi oleh JS) --}}
        <div id="detailBody"
             class="overflow-y-auto px-6 py-5 flex-1 space-y-5 text-sm hidden">
        </div>

        {{-- Footer --}}
        <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-700
                    bg-slate-50/50 dark:bg-slate-900/20 rounded-b-2xl shrink-0">
            <button type="button" onclick="closeModal('modalDetail')"
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200
                           dark:border-slate-600 text-slate-600 dark:text-slate-400
                           text-sm font-medium hover:bg-white dark:hover:bg-slate-700 transition">
                Tutup
            </button>
        </div>

    </div>
</div>

@push('scripts')
<script>
/**
 * openDetailModal — ambil data via fetch lalu render
 */
function openDetailModal(userId) {
    // Reset state
    document.getElementById('detailName').textContent   = '';
    document.getElementById('detailEmail').textContent  = '';
    document.getElementById('detailAvatarWrap').innerHTML = '';
    document.getElementById('detailBody').innerHTML     = '';
    document.getElementById('detailBody').classList.add('hidden');
    document.getElementById('detailLoading').classList.remove('hidden');

    openModal('modalDetail');

    fetch(`/admin/users/${userId}/show`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => buildDetailModal(data))
    .catch(() => {
        document.getElementById('detailLoading').innerHTML =
            '<p class="text-xs text-red-500 text-center py-8">Gagal memuat data. Coba lagi.</p>';
    });
}

/**
 * buildDetailModal — render seluruh konten modal detail
 */
function buildDetailModal(data) {
    const user    = data.user;
    const profile = data.profile;
    const role    = data.role;

    const nama = profile?.nama ?? user.name;

    // ── Avatar ──
    const avatarWrap = document.getElementById('detailAvatarWrap');
    if (user.photo) {
        avatarWrap.innerHTML = `
            <img src="/storage/${user.photo}" alt=""
                 class="w-11 h-11 rounded-xl object-cover border border-slate-200 shrink-0">`;
    } else {
        const initial = nama.charAt(0).toUpperCase();
        const color   = role === 'guru'
            ? 'bg-indigo-100 text-indigo-600'
            : 'bg-violet-100 text-violet-600';
        avatarWrap.innerHTML = `
            <div class="w-11 h-11 rounded-xl ${color} flex items-center justify-center
                        text-base font-bold shrink-0">${initial}</div>`;
    }

    document.getElementById('detailName').textContent  = nama;
    document.getElementById('detailEmail').textContent = user.email;

    // ── Badge role ──
    const roleBadge = role === 'guru'
        ? `<span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-semibold
                        bg-indigo-100 text-indigo-700">Guru</span>`
        : `<span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-semibold
                        bg-violet-100 text-violet-700">Siswa</span>`;

    // ── Helper row ──
    const row = (label, value) => {
        const val = value
            ? `<span class="text-slate-700 dark:text-slate-200 font-medium">${value}</span>`
            : `<span class="text-slate-300 dark:text-slate-600 italic text-xs">—</span>`;
        return `
            <div class="flex justify-between items-start gap-4 py-2
                        border-b border-slate-50 dark:border-slate-700/50 last:border-0">
                <span class="text-xs text-slate-500 dark:text-slate-400
                             shrink-0 min-w-[140px]">${label}</span>
                <span class="text-xs text-right">${val}</span>
            </div>`;
    };

    // ── Section header ──
    const section = (label) => `
        <div class="flex items-center gap-2 mb-3">
            <div class="h-px flex-1 bg-slate-100 dark:bg-slate-700"></div>
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                ${label}
            </span>
            <div class="h-px flex-1 bg-slate-100 dark:bg-slate-700"></div>
        </div>`;

    // ── Tanggal format ──
    const fmtDate = (d) => d
        ? new Date(d).toLocaleDateString('id-ID', {
            day: '2-digit', month: 'long', year: 'numeric'
          })
        : null;

    // ── Build HTML ──
    let bodyHtml = `
        <div>
            ${section('Info Akun')}
            <div class="space-y-0">
                ${row('Role', roleBadge)}
                ${row('Email', user.email)}
                ${row('Terdaftar', fmtDate(user.created_at))}
            </div>
        </div>`;

    if (role === 'guru' && profile) {
        const jkLabel = profile.jk === 'L' ? '♂ Laki-laki'
                      : profile.jk === 'P' ? '♀ Perempuan' : null;
        bodyHtml += `
        <div>
            ${section('Data Guru')}
            <div class="space-y-0">
                ${row('NIP',             profile.nip)}
                ${row('Jenis Kelamin',   jkLabel)}
                ${row('Tempat Lahir',    profile.tempat_lahir)}
                ${row('Tanggal Lahir',   fmtDate(profile.tanggal_lahir))}
                ${row('Pendidikan',      profile.pendidikan_terakhir)}
                ${row('Status Pegawai',  profile.status_pegawai)}
                ${row('Pangkat/Gol',     profile.pangkat_gol_ruang)}
                ${row('No. SK Pertama',  profile.no_sk_pertama)}
                ${row('No. SK Terakhir', profile.no_sk_terakhir)}
                ${row('No. Telepon',     profile.no_telp)}
                ${row('Wali Kelas',      profile.kelas?.name ?? profile.kelas?.nama)}
            </div>
        </div>`;
    }

    if (role === 'siswa' && profile) {
        const jkLabel = profile.jk === 'L' ? '♂ Laki-laki'
                      : profile.jk === 'P' ? '♀ Perempuan' : null;

        // Nama kelas: support field name atau nama
        const kelasNama = profile.kelas?.name ?? profile.kelas?.nama ?? null;

        // Info kelas tambahan
        let kelasDetail = kelasNama;
        if (kelasNama && profile.kelas) {
            const parts = [];
            if (profile.kelas.academic_year) parts.push(profile.kelas.academic_year);
            if (profile.kelas.semester)      parts.push(`Sem.${profile.kelas.semester}`);
            if (parts.length) kelasDetail += ` <span class="text-slate-400 font-normal">(${parts.join(' · ')})</span>`;
        }

        bodyHtml += `
        <div>
            ${section('Data Siswa')}
            <div class="space-y-0">
                ${row('NIS / NIPD',    profile.nidn)}
                ${row('NIK',           profile.nik)}
                ${row('Jenis Kelamin', jkLabel)}
                ${row('Tempat Lahir',  profile.tempat_lahir)}
                ${row('Tanggal Lahir', fmtDate(profile.tgl_lahir))}
                ${row('Agama',         profile.agama)}
                ${row('No. Telepon',   profile.no_telp)}
                ${row('SKHUN',         profile.shkun)}
                ${row('Kelas',         kelasDetail)}
            </div>
        </div>
        <div>
            ${section('Alamat & Lainnya')}
            <div class="space-y-0">
                ${row('Alamat',        profile.alamat)}
                ${row('RT / RW', (profile.rt && profile.rw)
                    ? `${profile.rt} / ${profile.rw}` : null)}
                ${row('Dusun',         profile.dusun)}
                ${row('Kecamatan',     profile.kecamatan)}
                ${row('Kode Pos',      profile.kode_pos)}
                ${row('Jenis Tinggal', profile.jenis_tinggal)}
                ${row('Transportasi',  profile.jalan_transportasi)}
                ${row('Penerima KPS',  profile.penerima_kps)}
                ${row('No. KPS',       profile.no_kps)}
            </div>
        </div>`;
    }

    document.getElementById('detailLoading').classList.add('hidden');
    const body = document.getElementById('detailBody');
    body.innerHTML = bodyHtml;
    body.classList.remove('hidden');
}
</script>
@endpush