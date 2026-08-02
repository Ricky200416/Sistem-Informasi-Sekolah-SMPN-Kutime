<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ProfilController as AdminProfilController;
use App\Http\Controllers\Admin\AcademicPlannerController;
use App\Http\Controllers\Admin\StudyClassAssignmentController;
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

Route::get('/berita',        [PublicBeritaController::class, 'index'])->name('website.berita');
Route::get('/berita/{slug}', [PublicBeritaController::class, 'show'])->name('website.berita.show');

Route::get('/galeri',           [PublicGaleriController::class, 'index'])->name('website.galeri');
Route::get('/galeri/{galeri}',  [PublicGaleriController::class, 'show'])->name('website.galeri.show');

// =================================================================
// AUTH
// =================================================================
Route::middleware('guest')->group(function () {
    Route::get('/login',  [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
});

Route::post('/logout', [LoginController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

// =================================================================
// ADMIN
// =================================================================
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {

    // ── Dashboard ────────────────────────────────────────────────
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // ── Profil Admin ─────────────────────────────────────────────
    Route::get('/profil',      [AdminProfilController::class, 'show'])->name('profil');
    Route::get('/profil/edit', [AdminProfilController::class, 'edit'])->name('profil.edit');
    Route::put('/profil',      [AdminProfilController::class, 'update'])->name('profil.update');

    // ── Users ────────────────────────────────────────────────────
    // FIX: semua rute users digabung rapi dalam satu grup 'users.' —
    //      tidak ada lagi duplikasi nama/path di luar grup ini.
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/',                        [UserController::class, 'index'])->name('index');
        Route::post('/',                       [UserController::class, 'store'])->name('store');
        Route::get('/{user}',                  [UserController::class, 'show'])->name('show');
        Route::get('/{user}/edit',             [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}',                  [UserController::class, 'update'])->name('update');
        Route::patch('/{user}/reset-password', [UserController::class, 'resetPassword'])->name('reset-password');
        Route::delete('/bulk-destroy',         [UserController::class, 'bulkDestroy'])->name('bulk-destroy');
        Route::delete('/{user}',               [UserController::class, 'destroy'])->name('destroy');

        // Import & Export
        Route::post('/import',                 [UserController::class, 'import'])->name('import');
        Route::get('/export/excel',            [UserController::class, 'exportExcel'])->name('export-excel');
        Route::get('/export/pdf',              [UserController::class, 'exportPdf'])->name('export-pdf');
        Route::get('/template/import/{role?}', [UserController::class, 'downloadTemplate'])->name('template-import');
    });

    // ── Perizinan ────────────────────────────────────────────────
    Route::prefix('perizinan')->name('perizinan.')->group(function () {
        Route::get('/',                  [AdminPerizinanController::class, 'index'])->name('index');
        Route::post('/{perizinan}/setujui', [AdminPerizinanController::class, 'approve'])->name('setujui');
        Route::post('/{perizinan}/tolak',   [AdminPerizinanController::class, 'reject'])->name('tolak');
    });

    // ── Alumni ───────────────────────────────────────────────────
    // FIX KRUSIAL: dibungkus prefix('alumni')/name('alumni.') supaya
    //   TIDAK menghasilkan rute catch-all '/admin/{alumni}' yang sebelumnya
    //   "menelan" semua rute lain (kelas, absensi-guru, academic-planner,
    //   pengumuman, kelola-website, dst) yang didaftarkan setelahnya.
    //   Rute spesifik (siswa-aktif, graduate, export) diletakkan
    //   SEBELUM rute dinamis {alumni} agar tidak bentrok.
    Route::prefix('alumni')->name('alumni.')->group(function () {
        Route::get('/',                    [AlumniController::class, 'index'])->name('index');
        Route::get('/siswa-aktif',         [AlumniController::class, 'daftarSiswaAktif'])->name('siswa-aktif');
        Route::post('/luluskan',           [AlumniController::class, 'graduate'])->name('graduate');
        Route::get('/export/excel',        [AlumniController::class, 'exportExcel'])->name('export-excel');
        Route::get('/export/pdf',          [AlumniController::class, 'exportPdf'])->name('export-pdf');
        Route::get('/{alumni}',            [AlumniController::class, 'show'])->name('show');
        Route::post('/{alumni}/batalkan',  [AlumniController::class, 'batalkan'])->name('batalkan');
        Route::delete('/{alumni}',         [AlumniController::class, 'destroy'])->name('destroy');
    });

    // ── Kelola Kelas ──────────────────────────────────────────────
    Route::delete('kelas/bulk-destroy', [KelasController::class, 'bulkDestroy'])->name('kelas.bulk-destroy');
    Route::resource('kelas', KelasController::class);

    // ── Absensi Guru ──────────────────────────────────────────────
    Route::get('/absensi-guru',                  [AbsensiGuruController::class, 'index'])->name('absensi-guru.index');
    Route::post('/absensi-guru',                 [AbsensiGuruController::class, 'store'])->name('absensi-guru.store');
    Route::delete('/absensi-guru/{absensiGuru}', [AbsensiGuruController::class, 'destroy'])->name('absensi-guru.destroy');
    Route::get('/absensi-guru/rekap',            [AbsensiGuruController::class, 'rekap'])->name('absensi-guru.rekap');
    Route::get('/absensi-guru/export-excel',     [AbsensiGuruController::class, 'exportExcel'])->name('absensi-guru.export-excel');
    Route::get('/absensi-guru/export-pdf',       [AbsensiGuruController::class, 'exportPdf'])->name('absensi-guru.export-pdf');

    // ── Pengumuman ────────────────────────────────────────────────
    Route::get('/pengumuman',                       [PengumumanController::class, 'adminIndex'])->name('pengumuman');
    Route::get('/pengumuman/index',                 [PengumumanController::class, 'adminIndex'])->name('pengumuman.index');
    Route::get('/pengumuman/create',                [PengumumanController::class, 'adminCreate'])->name('pengumuman.create');
    Route::post('/pengumuman',                      [PengumumanController::class, 'adminStore'])->name('pengumuman.store');
    Route::delete('/pengumuman/bulk-destroy',       [PengumumanController::class, 'bulkDestroy'])->name('pengumuman.bulkDestroy');
    Route::get('/pengumuman/{pengumuman}/edit',     [PengumumanController::class, 'adminEdit'])->name('pengumuman.edit');
    Route::put('/pengumuman/{pengumuman}',          [PengumumanController::class, 'adminUpdate'])->name('pengumuman.update');
    Route::delete('/pengumuman/{pengumuman}',       [PengumumanController::class, 'adminDestroy'])->name('pengumuman.destroy');
    Route::patch('/pengumuman/{pengumuman}/toggle', [PengumumanController::class, 'adminToggle'])->name('pengumuman.toggle');
    Route::get('/pengumuman/{pengumuman}',          [PengumumanController::class, 'adminShow'])->name('pengumuman.show');

    // ── Kelola Website ────────────────────────────────────────────
    Route::get('/kelola-website',                          [WebsiteController::class, 'kelolaWebsite'])->name('kelola-website');
    Route::patch('/kelola-website/home',                   [WebsiteController::class, 'updateHome'])->name('kelola-website.update-home');
    Route::post('/kelola-website/hero-media',              [WebsiteController::class, 'updateHeroMedia'])->name('kelola-website.update-hero-media');
    Route::delete('/kelola-website/hero-media/file',       [WebsiteController::class, 'deleteHeroFile'])->name('kelola-website.delete-hero-file');
    Route::patch('/kelola-website/update-stats',           [WebsiteController::class, 'updateStats'])->name('kelola-website.update-stats');
    Route::patch('/kelola-website/update-kontak',          [WebsiteController::class, 'updateKontak'])->name('kelola-website.update-kontak');
    Route::patch('/kelola-website/update-school-settings', [WebsiteController::class, 'updateSchoolSettings'])->name('kelola-website.update-school-settings');
    Route::delete('/kelola-website/delete-logo',           [WebsiteController::class, 'deleteLogo'])->name('kelola-website.delete-logo');
    Route::delete('/kelola-website/delete-sambutan-foto',  [WebsiteController::class, 'deleteSambutanFoto'])->name('kelola-website.delete-sambutan-foto');
    Route::delete('/kelola-website/berita/{id}',           [WebsiteController::class, 'destroyTab'])->name('kelola-website.berita.destroy');
    Route::post('/kelola-website/berita',                  [WebsiteController::class, 'storeBerita'])->name('kelola-website.berita.store');

    // ── Berita ────────────────────────────────────────────────────
    Route::prefix('berita')->name('berita.')->group(function () {
        Route::get('/create',                   [BeritaController::class, 'create'])->name('create');
        Route::post('/',                        [BeritaController::class, 'store'])->name('store');
        Route::get('/{berita}/edit',            [BeritaController::class, 'edit'])->name('edit');
        Route::patch('/{berita}',               [BeritaController::class, 'update'])->name('update');
        Route::delete('/{berita}',              [BeritaController::class, 'destroy'])->name('destroy');
        Route::patch('/{berita}/toggle-status', [BeritaController::class, 'toggleStatus'])->name('toggle-status');
    });

    // ── Galeri ────────────────────────────────────────────────────
    Route::prefix('galeri')->name('galeri.')->group(function () {
        Route::get('/create',                   [GaleriController::class, 'create'])->name('create');
        Route::post('/',                        [GaleriController::class, 'store'])->name('store');
        Route::get('/{galeri}/edit',            [GaleriController::class, 'edit'])->name('edit');
        Route::patch('/{galeri}',               [GaleriController::class, 'update'])->name('update');
        Route::delete('/{galeri}',              [GaleriController::class, 'destroy'])->name('destroy');
        Route::patch('/{galeri}/toggle-status', [GaleriController::class, 'toggleStatus'])->name('toggle-status');
    });

    // ── Academic Planner ────────────────────────────────────────────
    Route::prefix('academic-planner')->name('academic-planner.')->group(function () {

        // MAIN PAGE
        Route::get('/', [AcademicPlannerController::class, 'index'])->name('index'); // admin.academic-planner.index

        // Study Groups (Kelas)
        Route::post('/study-group',                  [AcademicPlannerController::class, 'storeStudyGroup'])->name('study-group.store');
        Route::put('/study-group/{id}',               [AcademicPlannerController::class, 'updateStudyGroup'])->name('study-group.update');
        Route::delete('/study-group/{id}',            [AcademicPlannerController::class, 'destroyStudyGroup'])->name('study-group.destroy');
        Route::get('/study-group/{id}',               [AcademicPlannerController::class, 'showStudyGroup'])->name('study-group.show');
        Route::post('/{groupId}/store-jadwal',        [AcademicPlannerController::class, 'storeJadwal'])->name('jadwal.store');
        Route::put('/jadwal/{id}/update',             [AcademicPlannerController::class, 'updateJadwal'])->name('jadwal.update');
        Route::delete('/jadwal/{id}/delete',          [AcademicPlannerController::class, 'destroyJadwal'])->name('jadwal.destroy');

        // ── Study Subjects ─────────────────────────
        Route::prefix('study-subjects')->name('study-subjects.')->group(function () {
            Route::get('/',           [AcademicPlannerController::class, 'indexStudySubject'])->name('index');
            Route::post('/',          [AcademicPlannerController::class, 'storeStudySubject'])->name('store');
            Route::get('/{id}/edit',  [AcademicPlannerController::class, 'editStudySubject'])->name('edit');
            Route::put('/{id}',       [AcademicPlannerController::class, 'updateStudySubject'])->name('update');
            Route::delete('/{id}',    [AcademicPlannerController::class, 'destroyStudySubject'])->name('destroy');
        });

        // ── Timetables ─────────────────────────
        Route::prefix('timetables')->name('timetables.')->group(function () {
            Route::get('/create',    [AcademicPlannerController::class, 'createTimetable'])->name('create');
            Route::post('/',         [AcademicPlannerController::class, 'storeTimetable'])->name('store');
            Route::get('/{id}/edit', [AcademicPlannerController::class, 'editTimetable'])->name('edit');
            Route::put('/{id}',      [AcademicPlannerController::class, 'updateTimetable'])->name('update');
            Route::delete('/{id}',   [AcademicPlannerController::class, 'destroyTimetable'])->name('destroy');
        });

        // ── Assignments ─────────────────────────
        Route::prefix('assignments')->name('assignments.')->group(function () {
            Route::get('/create',    [StudyClassAssignmentController::class, 'create'])->name('create');
            Route::post('/',         [StudyClassAssignmentController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [StudyClassAssignmentController::class, 'edit'])->name('edit');
            Route::put('/{id}',      [StudyClassAssignmentController::class, 'update'])->name('update');
            Route::delete('/{id}',   [StudyClassAssignmentController::class, 'destroy'])->name('destroy');
            Route::post('/assign-teacher', [StudyClassAssignmentController::class, 'assignTeacher'])->name('assign-teacher');
        });
    });

    // ── Activity Log ─────────────────────────────────────────────
    Route::prefix('activity-log')->name('activity-log.')->group(function () {
        Route::get('/',        [ActivityLogController::class, 'index'])->name('index');
        Route::get('/data',    [ActivityLogController::class, 'data'])->name('data');
        Route::delete('/purge', [ActivityLogController::class, 'purge'])->name('purge');
        Route::delete('/{log}', [ActivityLogController::class, 'destroy'])->name('destroy');
    });

    // ── Dashboard widget data endpoints (AJAX / JSON) ─────────────
    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        Route::get('/jadwal-hari-ini', [AdminDashboardController::class, 'jadwalHariIni'])->name('jadwal');
        Route::get('/stats',           [AdminDashboardController::class, 'stats'])->name('stats');
    });

});

// =================================================================
// GURU
// =================================================================
Route::prefix('guru')->name('guru.')->middleware(['auth', 'role:guru'])->group(function () {

    Route::get('/dashboard',   [GuruDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profil',      [GuruProfilController::class, 'show'])->name('profil');
    Route::get('/profil/edit', [GuruProfilController::class, 'edit'])->name('profil.edit');
    Route::put('/profil',      [GuruProfilController::class, 'update'])->name('profil.update');

    Route::get('/pengumuman', fn() => view('guru.pengumuman.index'))->name('pengumuman');

    // ── Absensi Siswa (oleh Guru) ─────────────────────────────────────────
    Route::get('/absensi-siswa',       [AbsensiSiswaController::class, 'index'])->name('absensi-siswa.index');
    Route::post('/absensi-siswa',      [AbsensiSiswaController::class, 'store'])->name('absensi-siswa.store');
    Route::get('/absensi-siswa/rekap', [AbsensiSiswaController::class, 'rekap'])->name('absensi-siswa.rekap');

    Route::get('/wali-kelas', [WaliKelasController::class, 'index'])->name('wali-kelas');

    // =================================================================
    // JADWAL MENGAJAR
    // =================================================================
    Route::resource('jadwal-mengajar', JadwalMengajarController::class)
         ->names('jadwal-mengajar')
         ->only(['index', 'store', 'update', 'destroy']);

    // Mata Pelajaran (oleh guru sendiri)
    Route::resource('study-subject', App\Http\Controllers\Guru\StudySubjectController::class)
         ->names('study-subject')
         ->only(['store', 'update', 'destroy']);

    // ── Absensi Foto ─────────────────────────────────────────────
    Route::get('absensi-foto',         [AbsensiFotoController::class, 'index'])->name('absensi-foto.index');
    Route::post('absensi-foto/masuk',  [AbsensiFotoController::class, 'storeMasuk'])->name('absensi-foto.masuk');
    Route::post('absensi-foto/pulang', [AbsensiFotoController::class, 'storePulang'])->name('absensi-foto.pulang');
    Route::post('absensi-foto/kantor', [AbsensiFotoController::class, 'storeKantor'])->name('absensi-foto.kantor');

    // ── Perizinan ────────────────────────────────────────────────
    Route::get('perizinan',  [PerizinanController::class, 'index'])->name('perizinan.index');
    Route::post('perizinan', [PerizinanController::class, 'store'])->name('perizinan.store');

});

// =================================================================
// SISWA
// =================================================================
Route::prefix('siswa')->name('siswa.')->middleware(['auth', 'role:siswa'])->group(function () {
    Route::get('/dashboard',   [SiswaDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profil',      [SiswaProfilController::class, 'show'])->name('profil');
    Route::get('/profil/edit', [SiswaProfilController::class, 'edit'])->name('profil.edit');
    Route::put('/profil',      [SiswaProfilController::class, 'update'])->name('profil.update');
    Route::get('/pengumuman',  fn() => view('siswa.pengumuman.index'))->name('pengumuman');

    // Jadwal Pelajaran (read-only)
    Route::get('jadwal-pelajaran', [JadwalPelajaranController::class, 'index'])->name('jadwal-pelajaran');
});