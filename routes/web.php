<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ProfilController as AdminProfilController;
use App\Http\Controllers\Admin\AcademicPlannerController;
use App\Http\Controllers\StudyClassAssignmentController;
use App\Http\Controllers\Admin\WebsiteController;
use App\Http\Controllers\Admin\BeritaController;
use App\Http\Controllers\Admin\GaleriController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\KelasController;
use App\Http\Controllers\Admin\AbsensiGuruController;
use App\Http\Controllers\Admin\AlumniController;
use App\Http\Controllers\Admin\PerizinanController as AdminPerizinanController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Guru\DashboardController as GuruDashboardController;
use App\Http\Controllers\Guru\ProfilController as GuruProfilController;
use App\Http\Controllers\Guru\AbsensiSiswaController;
use App\Http\Controllers\Guru\WaliKelasController;
use App\Http\Controllers\Guru\JadwalMengajarController;
use App\Http\Controllers\Guru\AbsensiFotoController;
use App\Http\Controllers\Guru\PerizinanController;
use App\Http\Controllers\Guru\StudySubjectController as GuruStudySubjectController;
use App\Http\Controllers\Siswa\DashboardController as SiswaDashboardController;
use App\Http\Controllers\Siswa\ProfilController as SiswaProfilController;
use App\Http\Controllers\PengumumanController;
use App\Http\Controllers\PublicBeritaController;
use App\Http\Controllers\PublicGaleriController;
use App\Http\Controllers\Siswa\JadwalPelajaranController;
use Illuminate\Support\Facades\Route;

// =================================================================
// PUBLIC
// =================================================================
Route::get('/', fn() => view('website.home'))->name('website.home');
Route::get('/berita', [PublicBeritaController::class, 'index'])->name('website.berita');
Route::get('/berita/{slug}', [PublicBeritaController::class, 'show'])->name('website.berita.show');
Route::get('/galeri', [PublicGaleriController::class, 'index'])->name('website.galeri');
Route::get('/galeri/{galeri}', [PublicGaleriController::class, 'show'])->name('website.galeri.show');

Route::post('/komentar', [CommentController::class, 'store'])->name('comments.store');

// =================================================================
// AUTH
// =================================================================
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'login'])->name('login.post')->middleware('guest');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');


// =================================================================
// ADMIN — satu group untuk prefix/name/middleware, isi FLAT tanpa sub-group
// =================================================================
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {

    // ── Dashboard ──────────────────────────────────────────────
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/jadwal-hari-ini', [AdminDashboardController::class, 'jadwalHariIni'])->name('dashboard.jadwal');
    Route::get('/dashboard/stats', [AdminDashboardController::class, 'stats'])->name('dashboard.stats');

    // ── Profil Admin ───────────────────────────────────────────
    Route::get('/profil', [AdminProfilController::class, 'show'])->name('profil');
    Route::get('/profil/edit', [AdminProfilController::class, 'edit'])->name('profil.edit');
    Route::put('/profil', [AdminProfilController::class, 'update'])->name('profil.update');

    // ── Kelola User ────────────────────────────────────────────
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::post('/users/import', [UserController::class, 'import'])->name('users.import');
    Route::get('/users/export/excel', [UserController::class, 'exportExcel'])->name('users.export-excel');
    Route::get('/users/export/pdf', [UserController::class, 'exportPdf'])->name('users.export-pdf');
    Route::get('/users/template-import/{role?}', [UserController::class, 'downloadTemplate'])->name('users.template-import');
    Route::delete('/users/bulk-destroy', [UserController::class, 'bulkDestroy'])->name('users.bulk-destroy');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::patch('/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::get('/pdf/guru', [UserController::class, 'guru'])->name('pdf.guru');
    Route::get('/pdf/siswa', [UserController::class, 'siswa'])->name('pdf.siswa');

    // ── Kelola Kelas ───────────────────────────────────────────
    // NOTE: KelasController tidak punya method create/show/edit terpisah —
    // hanya index, store, update, destroy, bulkDestroy.
    Route::get('/kelas', [KelasController::class, 'index'])->name('kelas.index');
    Route::post('/kelas', [KelasController::class, 'store'])->name('kelas.store');
    Route::delete('/kelas/bulk-destroy', [KelasController::class, 'bulkDestroy'])->name('kelas.bulk-destroy');
    Route::put('/kelas/{id}', [KelasController::class, 'update'])->name('kelas.update');
    Route::delete('/kelas/{id}', [KelasController::class, 'destroy'])->name('kelas.destroy');

    // ── Alumni ─────────────────────────────────────────────────
    // Rute statis (siswa-aktif, luluskan, export) diletakkan SEBELUM
    // rute dinamis {alumni} agar tidak tertangkap sebagai parameter.
    Route::get('/alumni', [AlumniController::class, 'index'])->name('alumni.index');
    Route::get('/alumni/siswa-aktif', [AlumniController::class, 'daftarSiswaAktif'])->name('alumni.siswa-aktif');
    Route::post('/alumni/luluskan', [AlumniController::class, 'graduate'])->name('alumni.graduate');
    Route::get('/alumni/export/excel', [AlumniController::class, 'exportExcel'])->name('alumni.export-excel');
    Route::get('/alumni/export/pdf', [AlumniController::class, 'exportPdf'])->name('alumni.export-pdf');
    Route::get('/alumni/{alumni}', [AlumniController::class, 'show'])->name('alumni.show');
    Route::post('/alumni/{alumni}/batalkan', [AlumniController::class, 'batalkan'])->name('alumni.batalkan');
    Route::delete('/alumni/{alumni}', [AlumniController::class, 'destroy'])->name('alumni.destroy');

    Route::get('alumni/{alumni}/edit', [AlumniController::class, 'edit'])->name('admin.alumni.edit');
    Route::put('alumni/{alumni}', [AlumniController::class, 'update'])->name('admin.alumni.update');

    // ── Pengumuman ─────────────────────────────────────────────
    Route::get('/pengumuman', [PengumumanController::class, 'adminIndex'])->name('pengumuman');
    Route::get('/pengumuman/index', [PengumumanController::class, 'adminIndex'])->name('pengumuman.index');
    Route::get('/pengumuman/create', [PengumumanController::class, 'adminCreate'])->name('pengumuman.create');
    Route::post('/pengumuman', [PengumumanController::class, 'adminStore'])->name('pengumuman.store');
    Route::delete('/pengumuman/bulk-destroy', [PengumumanController::class, 'bulkDestroy'])->name('pengumuman.bulkDestroy');
    Route::get('/pengumuman/{pengumuman}/edit', [PengumumanController::class, 'adminEdit'])->name('pengumuman.edit');
    Route::put('/pengumuman/{pengumuman}', [PengumumanController::class, 'adminUpdate'])->name('pengumuman.update');
    Route::delete('/pengumuman/{pengumuman}', [PengumumanController::class, 'adminDestroy'])->name('pengumuman.destroy');
    Route::patch('/pengumuman/{pengumuman}/toggle', [PengumumanController::class, 'adminToggle'])->name('pengumuman.toggle');
    Route::get('/pengumuman/{pengumuman}', [PengumumanController::class, 'adminShow'])->name('pengumuman.show');

    // ── Absensi Guru ───────────────────────────────────────────
    // NOTE: controller tidak punya method rekap(), jadi route rekap dihapus.
    Route::get('/absensi-guru', [AbsensiGuruController::class, 'index'])->name('absensi-guru.index');
    Route::post('/absensi-guru', [AbsensiGuruController::class, 'store'])->name('absensi-guru.store');
    Route::get('/absensi-guru/export-excel', [AbsensiGuruController::class, 'exportExcel'])->name('absensi-guru.export-excel');
    Route::get('/absensi-guru/export-pdf', [AbsensiGuruController::class, 'exportPdf'])->name('absensi-guru.export-pdf');
    Route::delete('/absensi-guru/{absensiGuru}', [AbsensiGuruController::class, 'destroy'])->name('absensi-guru.destroy');

    Route::get('/absensi-guru/rekap', [AbsensiGuruController::class, 'rekap'])->name('absensi-guru.rekap');

    // ── Perizinan (Admin) ────────────────────────────────────────
    Route::get('/perizinan', [AdminPerizinanController::class, 'index'])->name('perizinan.index');
    Route::post('/perizinan/{perizinan}/setujui', [AdminPerizinanController::class, 'approve'])->name('perizinan.setujui');
    Route::post('/perizinan/{perizinan}/tolak', [AdminPerizinanController::class, 'reject'])->name('perizinan.tolak');

    // ── Kelola Website (tab home/kontak/identitas + inline berita/galeri) ──
    Route::get('/kelola-website', [WebsiteController::class, 'kelolaWebsite'])->name('kelola-website');
    Route::patch('/kelola-website/home', [WebsiteController::class, 'updateHome'])->name('kelola-website.update-home');
    Route::post('/kelola-website/hero-media', [WebsiteController::class, 'updateHeroMedia'])->name('kelola-website.update-hero-media');
    Route::delete('/kelola-website/hero-media/file', [WebsiteController::class, 'deleteHeroFile'])->name('kelola-website.delete-hero-file');
    Route::patch('/kelola-website/update-stats', [WebsiteController::class, 'updateStats'])->name('kelola-website.update-stats');
    Route::patch('/kelola-website/update-kontak', [WebsiteController::class, 'updateKontak'])->name('kelola-website.update-kontak');
    Route::patch('/kelola-website/update-school-settings', [WebsiteController::class, 'updateSchoolSettings'])->name('kelola-website.update-school-settings');
    Route::delete('/kelola-website/delete-logo', [WebsiteController::class, 'deleteLogo'])->name('kelola-website.delete-logo');
    Route::delete('/kelola-website/delete-sambutan-foto', [WebsiteController::class, 'deleteSambutanFoto'])->name('kelola-website.delete-sambutan-foto');
    Route::post('/kelola-website/berita', [WebsiteController::class, 'storeBerita'])->name('kelola-website.berita.store');
    Route::put('/kelola-website/berita/{berita}', [WebsiteController::class, 'updateBerita'])->name('kelola-website.berita.update');
    Route::patch('/kelola-website/berita/{berita}/toggle-status', [WebsiteController::class, 'toggleStatusBerita'])->name('kelola-website.berita.toggle-status');
    Route::delete('/kelola-website/berita/{berita}', [WebsiteController::class, 'destroyBerita'])->name('kelola-website.berita.destroy');
    Route::post('/kelola-website/galeri', [WebsiteController::class, 'storeGaleri'])->name('kelola-website.galeri.store');
    Route::put('/kelola-website/galeri/{galeri}', [WebsiteController::class, 'updateGaleri'])->name('kelola-website.galeri.update');
    Route::patch('/kelola-website/galeri/{galeri}/toggle-status', [WebsiteController::class, 'toggleStatusGaleri'])->name('kelola-website.galeri.toggle-status');
    Route::delete('/kelola-website/galeri/{galeri}', [WebsiteController::class, 'destroyGaleri'])->name('kelola-website.galeri.destroy');

    // ── Berita (halaman penuh create/edit terpisah dari kelola-website) ──
    Route::get('/berita/create', [BeritaController::class, 'create'])->name('berita.create');
    Route::post('/berita', [BeritaController::class, 'store'])->name('berita.store');
    Route::get('/berita/{berita}/edit', [BeritaController::class, 'edit'])->name('berita.edit');
    Route::patch('/berita/{berita}', [BeritaController::class, 'update'])->name('berita.update');
    Route::delete('/berita/{berita}', [BeritaController::class, 'destroy'])->name('berita.destroy');
    Route::patch('/berita/{berita}/toggle-status', [BeritaController::class, 'toggleStatus'])->name('berita.toggle-status');

    // ── Galeri (halaman penuh create/edit terpisah dari kelola-website) ──
    Route::get('/galeri/create', [GaleriController::class, 'create'])->name('galeri.create');
    Route::post('/galeri', [GaleriController::class, 'store'])->name('galeri.store');
    Route::get('/galeri/{galeri}/edit', [GaleriController::class, 'edit'])->name('galeri.edit');
    Route::patch('/galeri/{galeri}', [GaleriController::class, 'update'])->name('galeri.update');
    Route::delete('/galeri/{galeri}', [GaleriController::class, 'destroy'])->name('galeri.destroy');
    Route::patch('/galeri/{galeri}/toggle-status', [GaleriController::class, 'toggleStatus'])->name('galeri.toggle-status');

    // ── Data Akademik / Academic Planner ─────────────────────────
    Route::get('/academic-planner', [AcademicPlannerController::class, 'index'])->name('academic-planner.index');
    Route::get('/academic-planner/study-group/{id}', [AcademicPlannerController::class, 'showStudyGroup'])->name('academic-planner.study-group.show');
    Route::post('/academic-planner/study-group', [AcademicPlannerController::class, 'storeStudyGroup'])->name('academic-planner.study-group.store');
    Route::post('/academic-planner/{groupId}/store-jadwal', [AcademicPlannerController::class, 'storeJadwal'])->name('academic-planner.jadwal.store');
    Route::put('/academic-planner/jadwal/{id}/update', [AcademicPlannerController::class, 'updateJadwal'])->name('academic-planner.jadwal.update');
    Route::delete('/academic-planner/jadwal/{id}/delete', [AcademicPlannerController::class, 'destroyJadwal'])->name('academic-planner.jadwal.destroy');
    Route::get('/academic-planner/study-subjects', [AcademicPlannerController::class, 'indexStudySubject'])->name('academic-planner.study-subjects.index');
    Route::post('/academic-planner/study-subjects', [AcademicPlannerController::class, 'storeStudySubject'])->name('academic-planner.study-subjects.store');
    Route::put('/academic-planner/study-subjects/{id}', [AcademicPlannerController::class, 'updateStudySubject'])->name('academic-planner.study-subjects.update');
    Route::delete('/academic-planner/study-subjects/{id}', [AcademicPlannerController::class, 'destroyStudySubject'])->name('academic-planner.study-subjects.destroy');

    // ── Assignments (Penugasan Guru — controller di namespace App\Http\Controllers) ──
    Route::get('/academic-planner/assignments', [StudyClassAssignmentController::class, 'index'])->name('academic-planner.assignments.index');
    Route::get('/academic-planner/assignments/create', [StudyClassAssignmentController::class, 'create'])->name('academic-planner.assignments.create');
    Route::post('/academic-planner/assignments', [StudyClassAssignmentController::class, 'store'])->name('academic-planner.assignments.store');
    Route::get('/academic-planner/assignments/{studyClassAssignment}/edit', [StudyClassAssignmentController::class, 'edit'])->name('academic-planner.assignments.edit');
    Route::put('/academic-planner/assignments/{studyClassAssignment}', [StudyClassAssignmentController::class, 'update'])->name('academic-planner.assignments.update');
    Route::delete('/academic-planner/assignments/{studyClassAssignment}', [StudyClassAssignmentController::class, 'destroy'])->name('academic-planner.assignments.destroy');
    Route::post('/academic-planner/assignments/assign-teacher', [StudyClassAssignmentController::class, 'assignTeacher'])->name('academic-planner.assignments.assign-teacher');

    // ── Activity Log ───────────────────────────────────────────
    Route::get('/activity-log', [ActivityLogController::class, 'index'])->name('activity-log.index');
    Route::get('/activity-log/data', [ActivityLogController::class, 'data'])->name('activity-log.data');
    Route::delete('/activity-log/purge', [ActivityLogController::class, 'purge'])->name('activity-log.purge');
    Route::delete('/activity-log/{log}', [ActivityLogController::class, 'destroy'])->name('activity-log.destroy');

    // - Komentar --
    Route::get('/comments', [CommentController::class, 'indexAdmin'])->name('comments.index');
    Route::patch('/comments/{id}/toggle', [CommentController::class, 'toggleStatusAdmin'])->name('comments.toggle');
    Route::delete('/comments/{id}', [CommentController::class, 'destroyAdmin'])->name('comments.destroy');

});


// =================================================================
// GURU — satu group untuk prefix/name/middleware, isi FLAT tanpa sub-group
// =================================================================
Route::prefix('guru')->name('guru.')->middleware(['auth', 'role:guru'])->group(function () {

    // ── Dashboard & Profil ────────────────────────────────────
    Route::get('/dashboard', [GuruDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profil', [GuruProfilController::class, 'show'])->name('profil');
    Route::get('/profil/edit', [GuruProfilController::class, 'edit'])->name('profil.edit');
    Route::put('/profil', [GuruProfilController::class, 'update'])->name('profil.update');

    // ── Jadwal Mengajar ────────────────────────────────────────
    Route::get('/jadwal-mengajar', [JadwalMengajarController::class, 'index'])->name('jadwal-mengajar.index');
    Route::post('/jadwal-mengajar', [JadwalMengajarController::class, 'store'])->name('jadwal-mengajar.store');
    Route::put('/jadwal-mengajar/{jadwalMengajar}', [JadwalMengajarController::class, 'update'])->name('jadwal-mengajar.update');
    Route::delete('/jadwal-mengajar/{jadwalMengajar}', [JadwalMengajarController::class, 'destroy'])->name('jadwal-mengajar.destroy');

    // ── Mata Pelajaran (dikelola guru sendiri) ─────────────────
    Route::post('/study-subject', [GuruStudySubjectController::class, 'store'])->name('study-subject.store');
    Route::put('/study-subject/{studySubject}', [GuruStudySubjectController::class, 'update'])->name('study-subject.update');
    Route::delete('/study-subject/{studySubject}', [GuruStudySubjectController::class, 'destroy'])->name('study-subject.destroy');

    // ── Absensi Foto ───────────────────────────────────────────
    Route::get('/absensi-foto', [AbsensiFotoController::class, 'index'])->name('absensi-foto.index');
    Route::post('/absensi-foto/masuk', [AbsensiFotoController::class, 'storeMasuk'])->name('absensi-foto.masuk');
    Route::post('/absensi-foto/pulang', [AbsensiFotoController::class, 'storePulang'])->name('absensi-foto.pulang');
    Route::post('/absensi-foto/kantor', [AbsensiFotoController::class, 'storeKantor'])->name('absensi-foto.kantor');
    Route::post('/masuk',  [\App\Http\Controllers\Guru\AbsensiFotoController::class, 'storeMasuk'])->name('masuk');
    Route::post('/pulang', [\App\Http\Controllers\Guru\AbsensiFotoController::class, 'storePulang'])->name('pulang');

    // ── Absensi Siswa (oleh Guru) ──────────────────────────────
    Route::get('/absensi-siswa', [AbsensiSiswaController::class, 'index'])->name('absensi-siswa.index');
    Route::post('/absensi-siswa', [AbsensiSiswaController::class, 'store'])->name('absensi-siswa.store');
    Route::get('/absensi-siswa/rekap', [AbsensiSiswaController::class, 'rekap'])->name('absensi-siswa.rekap');
    Route::get('/wali-kelas/rekap-mapel', [\App\Http\Controllers\Guru\WaliKelasController::class, 'rekapSemuaMapel'])->name('guru.wali-kelas.rekap-mapel');

    // ── Perizinan (Guru) ───────────────────────────────────────
    Route::get('/perizinan', [PerizinanController::class, 'index'])->name('perizinan.index');
    Route::post('/perizinan', [PerizinanController::class, 'store'])->name('perizinan.store');

    // ── Pengumuman ─────────────────────────────────────────────
    Route::get('/pengumuman', fn() => view('guru.pengumuman.index'))->name('pengumuman');

    // ── Wali Kelas ─────────────────────────────────────────────
    Route::get('/wali-kelas', [WaliKelasController::class, 'index'])->name('wali-kelas');

});


// =================================================================
// SISWA — satu group untuk prefix/name/middleware, isi FLAT tanpa sub-group
// =================================================================
Route::prefix('siswa')->name('siswa.')->middleware(['auth', 'role:siswa'])->group(function () {

    // ── Dashboard & Profil ────────────────────────────────────
    Route::get('/dashboard', [SiswaDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profil', [SiswaProfilController::class, 'show'])->name('profil');
    Route::get('/profil/edit', [SiswaProfilController::class, 'edit'])->name('profil.edit');
    Route::put('/profil', [SiswaProfilController::class, 'update'])->name('profil.update');

    // ── Jadwal Pelajaran (read-only) ───────────────────────────
    Route::get('/jadwal-pelajaran', [JadwalPelajaranController::class, 'index'])->name('jadwal-pelajaran');

    // ── Pengumuman ─────────────────────────────────────────────
    Route::get('/pengumuman', fn() => view('siswa.pengumuman.index'))->name('pengumuman');

});


// =================================================================
// DEBUG (opsional — hapus di production)
// =================================================================
Route::get('/debug-wali', function () {
    $user = auth()->user();
    $guru = $user->guru;

    return response()->json([
        'user_id'           => $user->id,
        'guru_id'           => $guru?->id,
        'via_wali_guru_id'  => \App\Models\Kelas::where('wali_guru_id', $guru?->id)->first()?->toArray(),
        'via_wali_kelas_id' => \App\Models\Kelas::where('wali_kelas_id', $guru?->id)->first()?->toArray(),
        'method_isWaliKelas_exists' => method_exists($user, 'isWaliKelas'),
        'method_isWaliKelas_result' => method_exists($user, 'isWaliKelas') ? $user->isWaliKelas() : 'method tidak ada',
        'guru_relations'    => $guru ? array_keys($guru->getRelations()) : [],
        'guru_attributes'   => $guru?->getAttributes(),
        'kelas_columns'     => \Illuminate\Support\Facades\Schema::getColumnListing('kelas'),
    ]);
})->middleware('auth');