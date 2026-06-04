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
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Guru\DashboardController as GuruDashboardController;
use App\Http\Controllers\Guru\ProfilController as GuruProfilController;
use App\Http\Controllers\Guru\AbsensiSiswaController;
use App\Http\Controllers\Guru\WaliKelasController;
use App\Http\Controllers\Guru\JadwalMengajarController;
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

    // Users
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/',                        [UserController::class, 'index'])->name('index');
        Route::post('/',                       [UserController::class, 'store'])->name('store');
        Route::get('/{user}',                  [UserController::class, 'show'])->name('show');
        Route::get('/{user}/edit',             [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}',                  [UserController::class, 'update'])->name('update');
        Route::patch('/{user}/reset-password', [UserController::class, 'resetPassword'])->name('reset-password');
        Route::delete('/{user}',               [UserController::class, 'destroy'])->name('destroy');

        // Import & Export
        Route::post('/import',                 [UserController::class, 'import'])->name('import');
        Route::get('/export/excel',            [UserController::class, 'exportExcel'])->name('export-excel');
        Route::get('/export/pdf',              [UserController::class, 'exportPdf'])->name('export-pdf');
        Route::get('/template/import',         [UserController::class, 'downloadTemplate'])->name('template-import');

        Route::delete('bulk-destroy', [UserController::class, 'bulkDestroy'])->name('bulk-destroy');
        
    });
Route::delete('admin/users/bulk-destroy', [UserController::class, 'bulkDestroy'])->name('admin/users/bulkDestroy');
Route::delete('/admin/users/{id}', [UserController::class, 'destroy'])->name('admin.users.destroy');

// Pindahkan rute massal ini ke ATAS rute destroy tunggal
    Route::delete('admin/users/bulk-destroy', [UserController::class, 'bulkDestroy'])->name('admin.users.bulkDestroy');
    
    // Rute tunggal menggunakan parameter {id} diletakkan di BAWAHNYA
    Route::delete('admin/users/{id}', [UserController::class, 'destroy'])->name('admin.users.destroy');


    // DOWNLOAD TEMPLATE - DENGAN PARAMETER ROLE (INI YANG BARU)
    Route::get('/admin/users/template-import/{role}', [UserController::class, 'downloadTemplate'])->name('admin.users.template-import');
        Route::get('/pdf/guru', [UserController::class, 'exportPdf'])->name('pdf.guru');
        Route::get('/pdf/siswa', [UserController::class, 'exportPdf'])->name('pdf.siswa');


    // ── Kelola Kelas ──────────────────────────────────────────────
    Route::resource('kelas', KelasController::class);
    Route::delete('kelas/bulk-destroy', [KelasController::class, 'bulkDestroy'])->name('kelas.bulk-destroy');

    // ── Absensi Guru ──────────────────────────────────────────────
    //
    // FIX: route pakai '/absensi-guru' (BUKAN '/absensi-guru/index')
    //      supaya controller benar-benar dipanggil.
    //
    // FIX: hapus semua duplikat route name dan placeholder yang
    //      me-override controller dengan render view langsung.
    //
    Route::get('/absensi-guru',              [AbsensiGuruController::class, 'index'])->name('absensi-guru.index');
    Route::post('/absensi-guru',             [AbsensiGuruController::class, 'store'])->name('absensi-guru.store');
    Route::delete('/absensi-guru/{absensiGuru}', [AbsensiGuruController::class, 'destroy'])->name('absensi-guru.destroy');
    Route::get('/absensi-guru/rekap', [AbsensiGuruController::class, 'rekap'])->name('absensi-guru.rekap');

    Route::get('/absensi-guru/export-excel', [App\Http\Controllers\Admin\AbsensiGuruController::class, 'exportExcel'])->name('absensi-guru.export-excel');
    Route::get('/absensi-guru/export-pdf', [App\Http\Controllers\Admin\AbsensiGuruController::class, 'exportPdf'])->name('absensi-guru.export-pdf');
    Route::get('/admin/absensi-guru/rekap', [App\Http\Controllers\Admin\AbsensiGuruController::class, 'rekap'])->name('admin.absensi-guru.rekap');

    // ── Pengumuman ────────────────────────────────────────────────
    Route::get('/pengumuman',                 [PengumumanController::class, 'adminIndex'])->name('pengumuman');
    Route::get('/pengumuman/index',                 [PengumumanController::class, 'adminIndex'])->name('pengumuman.index');
    Route::get('/pengumuman/create',                [PengumumanController::class, 'adminCreate'])->name('pengumuman.create');
    Route::post('/pengumuman',                      [PengumumanController::class, 'adminStore'])->name('pengumuman.store');
    Route::get('/pengumuman/{pengumuman}/edit',     [PengumumanController::class, 'adminEdit'])->name('pengumuman.edit');
    Route::put('/pengumuman/{pengumuman}',          [PengumumanController::class, 'adminUpdate'])->name('pengumuman.update');
    Route::delete('/pengumuman/{pengumuman}',       [PengumumanController::class, 'adminDestroy'])->name('pengumuman.destroy');
    Route::get('/pengumuman/{pengumuman}',          [PengumumanController::class, 'adminShow'])->name('pengumuman.show');
    Route::patch('/pengumuman/{pengumuman}/toggle', [PengumumanController::class, 'adminToggle'])->name('pengumuman.toggle');
    // routes/web.php
    Route::delete('pengumuman/bulk-destroy', [PengumumanController::class, 'bulkDestroy'])->name('pengumuman.bulkDestroy');


    // ── Kelola Website ────────────────────────────────────────────
    Route::get('/kelola-website',                               [WebsiteController::class, 'kelolaWebsite'])->name('kelola-website');
    Route::patch('/kelola-website/home',                        [WebsiteController::class, 'updateHome'])->name('kelola-website.update-home');
    Route::post('/kelola-website/hero-media',                   [WebsiteController::class, 'updateHeroMedia'])->name('kelola-website.update-hero-media');
    Route::delete('/kelola-website/hero-media/file',            [WebsiteController::class, 'deleteHeroFile'])->name('kelola-website.delete-hero-file');
    Route::patch('/kelola-website/update-stats',                [WebsiteController::class, 'updateStats'])->name('kelola-website.update-stats');
    Route::patch('/kelola-website/update-kontak',               [WebsiteController::class, 'updateKontak'])->name('kelola-website.update-kontak');
    Route::patch('/kelola-website/update-school-settings',      [WebsiteController::class, 'updateSchoolSettings'])->name('kelola-website.update-school-settings');
    Route::delete('/kelola-website/delete-logo',                [WebsiteController::class, 'deleteLogo'])->name('kelola-website.delete-logo');
    Route::delete('/kelola-website/delete-sambutan-foto',       [WebsiteController::class, 'deleteSambutanFoto'])->name('kelola-website.delete-sambutan-foto');

    Route::delete('/kelola-website/berita/{id}',[WebsiteController::class, 'destroyTab'])->name('kelola-website.berita.destroy');
    Route::post('/kelola-website/berita',[WebsiteController::class, 'storeBerita'])->name('kelola-website.berita.store');
    

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


Route::post('academic-planner/{groupId}/store-jadwal', [AcademicPlannerController::class, 'storeJadwal']);
Route::put('academic-planner/jadwal/{id}/update', [AcademicPlannerController::class, 'updateJadwal']);
Route::delete('academic-planner/jadwal/{id}/delete', [AcademicPlannerController::class, 'destroyJadwal']);

    // ── Academic Planner (FIXED) ───────────────────────────────
    Route::prefix('academic-planner')->name('academic-planner.')->group(function () {

        // MAIN PAGE
        Route::get('/', [AcademicPlannerController::class, 'index'])->name('index'); // admin.academic-planner.index

        // Study Groups (Kelas)
        Route::post('/study-group', [AcademicPlannerController::class, 'storeStudyGroup'])->name('study-group.store');
        Route::put('/study-group/{id}', [AcademicPlannerController::class, 'updateStudyGroup'])->name('study-group.update');
        Route::delete('/study-group/{id}', [AcademicPlannerController::class, 'destroyStudyGroup'])->name('study-group.destroy');
        Route::get('/study-group/{id}', [AcademicPlannerController::class, 'showStudyGroup'])->name('study-group.show');
        Route::get('/study-group/{id}', [AcademicPlannerController::class, 'showStudyGroup'])->name('show-study-group');
        Route::get('/study-group/{id}', [AcademicPlannerController::class, 'show'])->name('study-group.show');

        // ── Study Subjects ─────────────────────────
        Route::prefix('study-subjects')->name('study-subjects.')->group(function () {
            Route::get('/', [AcademicPlannerController::class, 'indexStudySubject'])->name('index');
            Route::post('/', [AcademicPlannerController::class, 'storeStudySubject'])->name('store');
            Route::get('/{id}/edit', [AcademicPlannerController::class, 'editStudySubject'])->name('edit');
            Route::put('/{id}', [AcademicPlannerController::class, 'updateStudySubject'])->name('update');
            Route::delete('/{id}', [AcademicPlannerController::class, 'destroyStudySubject'])->name('destroy');
        });


        // ── Timetables ─────────────────────────
        Route::prefix('timetables')->group(function () {

            Route::get('/create', [AcademicPlannerController::class, 'createTimetable'])->name('timetables.create');
            Route::post('/', [AcademicPlannerController::class, 'storeTimetable'])->name('timetables.store');
            Route::get('/{id}/edit', [AcademicPlannerController::class, 'editTimetable'])->name('timetables.edit');
            Route::put('/{id}', [AcademicPlannerController::class, 'updateTimetable'])->name('timetables.update');
            Route::delete('/{id}', [AcademicPlannerController::class, 'destroyTimetable'])->name('timetables.destroy');
        });


        // ── Assignments ─────────────────────────
        Route::prefix('assignments')->group(function () {

            Route::get('/create', [StudyClassAssignmentController::class, 'create'])
                ->name('assignments.create');

            Route::post('/', [StudyClassAssignmentController::class, 'store'])
                ->name('assignments.store');

            Route::get('/{id}/edit', [StudyClassAssignmentController::class, 'edit'])
                ->name('assignments.edit');

            Route::put('/{id}', [StudyClassAssignmentController::class, 'update'])
                ->name('assignments.update');

            Route::delete('/{id}', [StudyClassAssignmentController::class, 'destroy'])
                ->name('assignments.destroy');

            Route::post('/assign-teacher', [StudyClassAssignmentController::class, 'assignTeacher'])
                ->name('assignments.assign-teacher');
        });
    }); // ✅ cukup satu penutup

           // ── Activity Log ─────────────────────────────────────────────
        //
        //  GET  /admin/activity-log          → daftar log (full page)
        //  GET  /admin/activity-log/data     → JSON untuk live-reload widget
        //  DELETE /admin/activity-log/{id}   → hapus 1 entri (opsional)
        //  DELETE /admin/activity-log/purge  → hapus semua log > 12 jam (manual trigger)
        //
        Route::prefix('activity-log')->name('activity-log.')->group(function () {
 
            Route::get('/',      [ActivityLogController::class, 'index'])
                ->name('index');
 
            Route::get('/data',  [ActivityLogController::class, 'data'])
                ->name('data');                              // JSON endpoint widget
 
            Route::delete('/purge', [ActivityLogController::class, 'purge'])
                ->name('purge');                             // manual purge
 
            Route::delete('/{log}', [ActivityLogController::class, 'destroy'])
                ->name('destroy');
        });
 
        // ── Dashboard widget data endpoints (AJAX / JSON) ─────────────
        //
        //  GET  /admin/dashboard/jadwal-hari-ini   → JSON jadwal hari ini
        //  GET  /admin/dashboard/stats             → JSON statistik ringkasan
        //
        Route::prefix('dashboard')->name('dashboard.')->group(function () {
 
            Route::get('/jadwal-hari-ini', [AdminDashboardController::class, 'jadwalHariIni'])
                ->name('jadwal');                            // optional live-refresh
 
            Route::get('/stats',           [AdminDashboardController::class, 'stats'])
                ->name('stats');                             // optional live-refresh
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

    Route::get('/absensi-siswa',   fn() => view('guru.absensi-siswa.index'))->name('absensi-siswa');
    Route::get('/pengumuman',      fn() => view('guru.pengumuman.index'))->name('pengumuman');

    // ── Absensi Siswa (oleh Guru) ─────────────────────────────────────────
    Route::get('/absensi-siswa',          [AbsensiSiswaController::class, 'index'])->name('absensi-siswa.index');
    Route::post('/absensi-siswa',         [AbsensiSiswaController::class, 'store'])->name('absensi-siswa.store');
    Route::get('/absensi-siswa/rekap',    [AbsensiSiswaController::class, 'rekap'])->name('absensi-siswa.rekap');

    Route::get('/wali-kelas', [WaliKelasController::class, 'index'])->name('wali-kelas');

    // =================================================================
    // JADWAL MENGAJAR
    // =================================================================
    // Route manual (tetap dipertahankan)
    Route::get('jadwal-mengajar',          [JadwalMengajarController::class, 'index'])->name('jadwal-mengajar.index');
    Route::post('jadwal-mengajar',         [JadwalMengajarController::class, 'store'])->name('jadwal-mengajar.store');
    Route::put('jadwal-mengajar/{jadwalMengajar}',    [JadwalMengajarController::class, 'update'])->name('jadwal-mengajar.update');
    Route::delete('jadwal-mengajar/{jadwalMengajar}', [JadwalMengajarController::class, 'destroy'])->name('jadwal-mengajar.destroy');

    // Route::resource (diperbaiki agar tidak conflict dan nama route benar)
    Route::resource('jadwal-mengajar', JadwalMengajarController::class)
         ->names([
             'index'   => 'jadwal-mengajar.index',
             'store'   => 'jadwal-mengajar.store',
             'update'  => 'jadwal-mengajar.update',
             'destroy' => 'jadwal-mengajar.destroy',
         ])
         ->only(['index', 'store', 'update', 'destroy']);

    // Mata Pelajaran (oleh guru sendiri)
    Route::resource('study-subject', App\Http\Controllers\Guru\StudySubjectController::class)
         ->names('study-subject')
         ->only(['store', 'update', 'destroy']);

    
});

// =================================================================
// SISWA
// =================================================================
Route::prefix('siswa')->name('siswa.')->middleware(['auth', 'role:siswa'])->group(function () {
    Route::get('/dashboard',   [SiswaDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profil',      [SiswaProfilController::class, 'show'])->name('profil');
    Route::get('/profil/edit', [SiswaProfilController::class, 'edit'])->name('profil.edit');
    Route::put('/profil',      [SiswaProfilController::class, 'update'])->name('profil.update');
    // Route::get('/jadwal-pelajaran', fn() => view('siswa.jadwal-pelajaran.index'))->name('jadwal-pelajaran');
    Route::get('/pengumuman',       fn() => view('siswa.pengumuman.index'))->name('pengumuman');

    // Jadwal Pelajaran (read-only)
    Route::get('jadwal-pelajaran', [JadwalPelajaranController::class, 'index'])->name('jadwal-pelajaran');
});




// TEMPORARY DEBUG — hapus setelah selesai
Route::get('/debug-wali', function () {
    $user = auth()->user();
    $guru = $user->guru;
    
    return response()->json([
        'user_id'           => $user->id,
        'guru_id'           => $guru?->id,
        // Cek semua kemungkinan kolom di tabel kelas
        'via_wali_guru_id'  => \App\Models\Kelas::where('wali_guru_id', $guru?->id)->first()?->toArray(),
        'via_wali_kelas_id' => \App\Models\Kelas::where('wali_kelas_id', $guru?->id)->first()?->toArray(),
        // Cek method di User model
        'method_isWaliKelas_exists' => method_exists($user, 'isWaliKelas'),
        'method_isWaliKelas_result' => method_exists($user, 'isWaliKelas') ? $user->isWaliKelas() : 'method tidak ada',
        // Cek relasi guru
        'guru_relations'    => $guru ? array_keys($guru->getRelations()) : [],
        'guru_attributes'   => $guru?->getAttributes(),
        // Cek tabel kelas semua kolom
        'kelas_columns'     => \Illuminate\Support\Facades\Schema::getColumnListing('kelas'),
    ]);
})->middleware('auth');