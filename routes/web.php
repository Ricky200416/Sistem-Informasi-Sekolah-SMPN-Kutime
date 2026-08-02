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

Route::get('/berita',        [PublicBeritaController::class, 'index'])->name('website.berita');
Route::get('/berita/{slug}', [PublicBeritaController::class, 'show'])->name('website.berita.show');

Route::get('/galeri',           [PublicGaleriController::class, 'index'])->name('website.galeri');
Route::get('/galeri/{galeri}',  [PublicGaleriController::class, 'show'])->name('website.galeri.show');

// =================================================================
// AUTH
// =================================================================
Route::get('/login',  [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'login'])->name('login.post')->middleware('guest');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// =================================================================
// ADMIN — SEMUA ROUTE DASHBOARD ADMIN ADA DI SINI, TIDAK DIKELOMPOKKAN
// =================================================================

// ── Dashboard ──────────────────────────────────────────────────────
Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard')->middleware(['auth', 'role:admin']);
Route::get('/admin/dashboard/jadwal-hari-ini', [AdminDashboardController::class, 'jadwalHariIni'])->name('admin.dashboard.jadwal')->middleware(['auth', 'role:admin']);
Route::get('/admin/dashboard/stats', [AdminDashboardController::class, 'stats'])->name('admin.dashboard.stats')->middleware(['auth', 'role:admin']);

// ── Profil Admin ─────────────────────────────────────────────────
Route::get('/admin/profil', [AdminProfilController::class, 'show'])->name('admin.profil')->middleware(['auth', 'role:admin']);
Route::get('/admin/profil/edit', [AdminProfilController::class, 'edit'])->name('admin.profil.edit')->middleware(['auth', 'role:admin']);
Route::put('/admin/profil', [AdminProfilController::class, 'update'])->name('admin.profil.update')->middleware(['auth', 'role:admin']);

// ── Kelola User ──────────────────────────────────────────────────
Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index')->middleware(['auth', 'role:admin']);
Route::post('/admin/users', [UserController::class, 'store'])->name('admin.users.store')->middleware(['auth', 'role:admin']);
Route::get('/admin/users/export/excel', [UserController::class, 'exportExcel'])->name('admin.users.export-excel')->middleware(['auth', 'role:admin']);
Route::get('/admin/pdf/guru', [UserController::class, 'exportPdf'])->name('admin.pdf.guru')->middleware(['auth', 'role:admin']);
Route::get('/admin/users/template-import/{role?}', [UserController::class, 'downloadTemplate'])->name('admin.users.template-import')->middleware(['auth', 'role:admin']);
Route::post('/admin/users/import', [UserController::class, 'import'])->name('admin.users.import')->middleware(['auth', 'role:admin']);
Route::delete('/admin/users/bulk-destroy', [UserController::class, 'bulkDestroy'])->name('admin.users.bulk-destroy')->middleware(['auth', 'role:admin']);
Route::get('/admin/users/{user}', [UserController::class, 'show'])->name('admin.users.show')->middleware(['auth', 'role:admin']);
Route::get('/admin/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit')->middleware(['auth', 'role:admin']);
Route::put('/admin/users/{user}', [UserController::class, 'update'])->name('admin.users.update')->middleware(['auth', 'role:admin']);
Route::patch('/admin/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('admin.users.reset-password')->middleware(['auth', 'role:admin']);
Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy')->middleware(['auth', 'role:admin']);

// ── Kelola Kelas ─────────────────────────────────────────────────
Route::get('/admin/kelas', [KelasController::class, 'index'])->name('admin.kelas.index')->middleware(['auth', 'role:admin']);
Route::get('/admin/kelas/create', [KelasController::class, 'create'])->name('admin.kelas.create')->middleware(['auth', 'role:admin']);
Route::post('/admin/kelas', [KelasController::class, 'store'])->name('admin.kelas.store')->middleware(['auth', 'role:admin']);
Route::delete('/admin/kelas/bulk-destroy', [KelasController::class, 'bulkDestroy'])->name('admin.kelas.bulk-destroy')->middleware(['auth', 'role:admin']);
Route::get('/admin/kelas/{kelas}', [KelasController::class, 'show'])->name('admin.kelas.show')->middleware(['auth', 'role:admin']);
Route::get('/admin/kelas/{kelas}/edit', [KelasController::class, 'edit'])->name('admin.kelas.edit')->middleware(['auth', 'role:admin']);
Route::put('/admin/kelas/{kelas}', [KelasController::class, 'update'])->name('admin.kelas.update')->middleware(['auth', 'role:admin']);
Route::delete('/admin/kelas/{kelas}', [KelasController::class, 'destroy'])->name('admin.kelas.destroy')->middleware(['auth', 'role:admin']);

// ── Alumni ───────────────────────────────────────────────────────
// PENTING: route spesifik (siswa-aktif, luluskan, export) diletakkan
// SEBELUM route dinamis {alumni} agar tidak tertangkap sebagai parameter.
Route::get('/admin/alumni', [AlumniController::class, 'index'])->name('admin.alumni.index')->middleware(['auth', 'role:admin']);
Route::get('/admin/alumni/siswa-aktif', [AlumniController::class, 'daftarSiswaAktif'])->name('admin.alumni.siswa-aktif')->middleware(['auth', 'role:admin']);
Route::post('/admin/alumni/luluskan', [AlumniController::class, 'graduate'])->name('admin.alumni.graduate')->middleware(['auth', 'role:admin']);
Route::get('/admin/alumni/export/excel', [AlumniController::class, 'exportExcel'])->name('admin.alumni.export-excel')->middleware(['auth', 'role:admin']);
Route::get('/admin/alumni/export/pdf', [AlumniController::class, 'exportPdf'])->name('admin.alumni.export-pdf')->middleware(['auth', 'role:admin']);
Route::get('/admin/alumni/{alumni}', [AlumniController::class, 'show'])->name('admin.alumni.show')->middleware(['auth', 'role:admin']);
Route::post('/admin/alumni/{alumni}/batalkan', [AlumniController::class, 'batalkan'])->name('admin.alumni.batalkan')->middleware(['auth', 'role:admin']);
Route::delete('/admin/alumni/{alumni}', [AlumniController::class, 'destroy'])->name('admin.alumni.destroy')->middleware(['auth', 'role:admin']);

// ── Pengumuman ───────────────────────────────────────────────────
Route::get('/admin/pengumuman', [PengumumanController::class, 'adminIndex'])->name('admin.pengumuman')->middleware(['auth', 'role:admin']);
Route::get('/admin/pengumuman/index', [PengumumanController::class, 'adminIndex'])->name('admin.pengumuman.index')->middleware(['auth', 'role:admin']);
Route::get('/admin/pengumuman/create', [PengumumanController::class, 'adminCreate'])->name('admin.pengumuman.create')->middleware(['auth', 'role:admin']);
Route::post('/admin/pengumuman', [PengumumanController::class, 'adminStore'])->name('admin.pengumuman.store')->middleware(['auth', 'role:admin']);
Route::delete('/admin/pengumuman/bulk-destroy', [PengumumanController::class, 'bulkDestroy'])->name('admin.pengumuman.bulkDestroy')->middleware(['auth', 'role:admin']);
Route::get('/admin/pengumuman/{pengumuman}/edit', [PengumumanController::class, 'adminEdit'])->name('admin.pengumuman.edit')->middleware(['auth', 'role:admin']);
Route::put('/admin/pengumuman/{pengumuman}', [PengumumanController::class, 'adminUpdate'])->name('admin.pengumuman.update')->middleware(['auth', 'role:admin']);
Route::delete('/admin/pengumuman/{pengumuman}', [PengumumanController::class, 'adminDestroy'])->name('admin.pengumuman.destroy')->middleware(['auth', 'role:admin']);
Route::patch('/admin/pengumuman/{pengumuman}/toggle', [PengumumanController::class, 'adminToggle'])->name('admin.pengumuman.toggle')->middleware(['auth', 'role:admin']);
Route::get('/admin/pengumuman/{pengumuman}', [PengumumanController::class, 'adminShow'])->name('admin.pengumuman.show')->middleware(['auth', 'role:admin']);

// ── Absensi Guru ─────────────────────────────────────────────────
Route::get('/admin/absensi-guru', [AbsensiGuruController::class, 'index'])->name('admin.absensi-guru.index')->middleware(['auth', 'role:admin']);
Route::post('/admin/absensi-guru', [AbsensiGuruController::class, 'store'])->name('admin.absensi-guru.store')->middleware(['auth', 'role:admin']);
Route::get('/admin/absensi-guru/rekap', [AbsensiGuruController::class, 'rekap'])->name('admin.absensi-guru.rekap')->middleware(['auth', 'role:admin']);
Route::get('/admin/absensi-guru/export-excel', [AbsensiGuruController::class, 'exportExcel'])->name('admin.absensi-guru.export-excel')->middleware(['auth', 'role:admin']);
Route::get('/admin/absensi-guru/export-pdf', [AbsensiGuruController::class, 'exportPdf'])->name('admin.absensi-guru.export-pdf')->middleware(['auth', 'role:admin']);
Route::delete('/admin/absensi-guru/{absensiGuru}', [AbsensiGuruController::class, 'destroy'])->name('admin.absensi-guru.destroy')->middleware(['auth', 'role:admin']);

// ── Perizinan (Admin) ────────────────────────────────────────────
Route::get('/admin/perizinan', [AdminPerizinanController::class, 'index'])->name('admin.perizinan.index')->middleware(['auth', 'role:admin']);
Route::post('/admin/perizinan/{perizinan}/setujui', [AdminPerizinanController::class, 'approve'])->name('admin.perizinan.setujui')->middleware(['auth', 'role:admin']);
Route::post('/admin/perizinan/{perizinan}/tolak', [AdminPerizinanController::class, 'reject'])->name('admin.perizinan.tolak')->middleware(['auth', 'role:admin']);

// ── Kelola Website ───────────────────────────────────────────────
Route::get('/admin/kelola-website', [WebsiteController::class, 'kelolaWebsite'])->name('admin.kelola-website')->middleware(['auth', 'role:admin']);
Route::patch('/admin/kelola-website/home', [WebsiteController::class, 'updateHome'])->name('admin.kelola-website.update-home')->middleware(['auth', 'role:admin']);
Route::post('/admin/kelola-website/hero-media', [WebsiteController::class, 'updateHeroMedia'])->name('admin.kelola-website.update-hero-media')->middleware(['auth', 'role:admin']);
Route::delete('/admin/kelola-website/hero-media/file', [WebsiteController::class, 'deleteHeroFile'])->name('admin.kelola-website.delete-hero-file')->middleware(['auth', 'role:admin']);
Route::patch('/admin/kelola-website/update-stats', [WebsiteController::class, 'updateStats'])->name('admin.kelola-website.update-stats')->middleware(['auth', 'role:admin']);
Route::patch('/admin/kelola-website/update-kontak', [WebsiteController::class, 'updateKontak'])->name('admin.kelola-website.update-kontak')->middleware(['auth', 'role:admin']);
Route::patch('/admin/kelola-website/update-school-settings', [WebsiteController::class, 'updateSchoolSettings'])->name('admin.kelola-website.update-school-settings')->middleware(['auth', 'role:admin']);
Route::delete('/admin/kelola-website/delete-logo', [WebsiteController::class, 'deleteLogo'])->name('admin.kelola-website.delete-logo')->middleware(['auth', 'role:admin']);
Route::delete('/admin/kelola-website/delete-sambutan-foto', [WebsiteController::class, 'deleteSambutanFoto'])->name('admin.kelola-website.delete-sambutan-foto')->middleware(['auth', 'role:admin']);
Route::delete('/admin/kelola-website/berita/{id}', [WebsiteController::class, 'destroyTab'])->name('admin.kelola-website.berita.destroy')->middleware(['auth', 'role:admin']);
Route::post('/admin/kelola-website/berita', [WebsiteController::class, 'storeBerita'])->name('admin.kelola-website.berita.store')->middleware(['auth', 'role:admin']);

// ── Berita ───────────────────────────────────────────────────────
Route::get('/admin/berita/create', [BeritaController::class, 'create'])->name('admin.berita.create')->middleware(['auth', 'role:admin']);
Route::post('/admin/berita', [BeritaController::class, 'store'])->name('admin.berita.store')->middleware(['auth', 'role:admin']);
Route::get('/admin/berita/{berita}/edit', [BeritaController::class, 'edit'])->name('admin.berita.edit')->middleware(['auth', 'role:admin']);
Route::patch('/admin/berita/{berita}', [BeritaController::class, 'update'])->name('admin.berita.update')->middleware(['auth', 'role:admin']);
Route::delete('/admin/berita/{berita}', [BeritaController::class, 'destroy'])->name('admin.berita.destroy')->middleware(['auth', 'role:admin']);
Route::patch('/admin/berita/{berita}/toggle-status', [BeritaController::class, 'toggleStatus'])->name('admin.berita.toggle-status')->middleware(['auth', 'role:admin']);

// ── Galeri ───────────────────────────────────────────────────────
Route::get('/admin/galeri/create', [GaleriController::class, 'create'])->name('admin.galeri.create')->middleware(['auth', 'role:admin']);
Route::post('/admin/galeri', [GaleriController::class, 'store'])->name('admin.galeri.store')->middleware(['auth', 'role:admin']);
Route::get('/admin/galeri/{galeri}/edit', [GaleriController::class, 'edit'])->name('admin.galeri.edit')->middleware(['auth', 'role:admin']);
Route::patch('/admin/galeri/{galeri}', [GaleriController::class, 'update'])->name('admin.galeri.update')->middleware(['auth', 'role:admin']);
Route::delete('/admin/galeri/{galeri}', [GaleriController::class, 'destroy'])->name('admin.galeri.destroy')->middleware(['auth', 'role:admin']);
Route::patch('/admin/galeri/{galeri}/toggle-status', [GaleriController::class, 'toggleStatus'])->name('admin.galeri.toggle-status')->middleware(['auth', 'role:admin']);

// ── Data Akademik / Academic Planner ─────────────────────────────
Route::get('/admin/academic-planner', [AcademicPlannerController::class, 'index'])->name('admin.academic-planner.index')->middleware(['auth', 'role:admin']);

// Study Group (Kelas Akademik)
Route::post('/admin/academic-planner/study-group', [AcademicPlannerController::class, 'storeStudyGroup'])->name('admin.academic-planner.study-group.store')->middleware(['auth', 'role:admin']);
Route::put('/admin/academic-planner/study-group/{id}', [AcademicPlannerController::class, 'updateStudyGroup'])->name('admin.academic-planner.study-group.update')->middleware(['auth', 'role:admin']);
Route::delete('/admin/academic-planner/study-group/{id}', [AcademicPlannerController::class, 'destroyStudyGroup'])->name('admin.academic-planner.study-group.destroy')->middleware(['auth', 'role:admin']);
Route::get('/admin/academic-planner/study-group/{id}', [AcademicPlannerController::class, 'showStudyGroup'])->name('admin.academic-planner.study-group.show')->middleware(['auth', 'role:admin']);

// Jadwal (dalam Study Group)
Route::post('/admin/academic-planner/{groupId}/store-jadwal', [AcademicPlannerController::class, 'storeJadwal'])->name('admin.academic-planner.jadwal.store')->middleware(['auth', 'role:admin']);
Route::put('/admin/academic-planner/jadwal/{id}/update', [AcademicPlannerController::class, 'updateJadwal'])->name('admin.academic-planner.jadwal.update')->middleware(['auth', 'role:admin']);
Route::delete('/admin/academic-planner/jadwal/{id}/delete', [AcademicPlannerController::class, 'destroyJadwal'])->name('admin.academic-planner.jadwal.destroy')->middleware(['auth', 'role:admin']);

// Study Subjects (Mata Pelajaran Master)
Route::get('/admin/academic-planner/study-subjects', [AcademicPlannerController::class, 'indexStudySubject'])->name('admin.academic-planner.study-subjects.index')->middleware(['auth', 'role:admin']);
Route::post('/admin/academic-planner/study-subjects', [AcademicPlannerController::class, 'storeStudySubject'])->name('admin.academic-planner.study-subjects.store')->middleware(['auth', 'role:admin']);
Route::get('/admin/academic-planner/study-subjects/{id}/edit', [AcademicPlannerController::class, 'editStudySubject'])->name('admin.academic-planner.study-subjects.edit')->middleware(['auth', 'role:admin']);
Route::put('/admin/academic-planner/study-subjects/{id}', [AcademicPlannerController::class, 'updateStudySubject'])->name('admin.academic-planner.study-subjects.update')->middleware(['auth', 'role:admin']);
Route::delete('/admin/academic-planner/study-subjects/{id}', [AcademicPlannerController::class, 'destroyStudySubject'])->name('admin.academic-planner.study-subjects.destroy')->middleware(['auth', 'role:admin']);

// Timetables (Jadwal Pelajaran Master)
Route::get('/admin/academic-planner/timetables/create', [AcademicPlannerController::class, 'createTimetable'])->name('admin.academic-planner.timetables.create')->middleware(['auth', 'role:admin']);
Route::post('/admin/academic-planner/timetables', [AcademicPlannerController::class, 'storeTimetable'])->name('admin.academic-planner.timetables.store')->middleware(['auth', 'role:admin']);
Route::get('/admin/academic-planner/timetables/{id}/edit', [AcademicPlannerController::class, 'editTimetable'])->name('admin.academic-planner.timetables.edit')->middleware(['auth', 'role:admin']);
Route::put('/admin/academic-planner/timetables/{id}', [AcademicPlannerController::class, 'updateTimetable'])->name('admin.academic-planner.timetables.update')->middleware(['auth', 'role:admin']);
Route::delete('/admin/academic-planner/timetables/{id}', [AcademicPlannerController::class, 'destroyTimetable'])->name('admin.academic-planner.timetables.destroy')->middleware(['auth', 'role:admin']);

// Assignments (Penugasan Guru ke Kelas/Mapel)
Route::get('/admin/academic-planner/assignments/create', [StudyClassAssignmentController::class, 'create'])->name('admin.academic-planner.assignments.create')->middleware(['auth', 'role:admin']);
Route::post('/admin/academic-planner/assignments', [StudyClassAssignmentController::class, 'store'])->name('admin.academic-planner.assignments.store')->middleware(['auth', 'role:admin']);
Route::get('/admin/academic-planner/assignments/{id}/edit', [StudyClassAssignmentController::class, 'edit'])->name('admin.academic-planner.assignments.edit')->middleware(['auth', 'role:admin']);
Route::put('/admin/academic-planner/assignments/{id}', [StudyClassAssignmentController::class, 'update'])->name('admin.academic-planner.assignments.update')->middleware(['auth', 'role:admin']);
Route::delete('/admin/academic-planner/assignments/{id}', [StudyClassAssignmentController::class, 'destroy'])->name('admin.academic-planner.assignments.destroy')->middleware(['auth', 'role:admin']);
Route::post('/admin/academic-planner/assignments/assign-teacher', [StudyClassAssignmentController::class, 'assignTeacher'])->name('admin.academic-planner.assignments.assign-teacher')->middleware(['auth', 'role:admin']);

// ── Activity Log ─────────────────────────────────────────────────
Route::get('/admin/activity-log', [ActivityLogController::class, 'index'])->name('admin.activity-log.index')->middleware(['auth', 'role:admin']);
Route::get('/admin/activity-log/data', [ActivityLogController::class, 'data'])->name('admin.activity-log.data')->middleware(['auth', 'role:admin']);
Route::delete('/admin/activity-log/purge', [ActivityLogController::class, 'purge'])->name('admin.activity-log.purge')->middleware(['auth', 'role:admin']);
Route::delete('/admin/activity-log/{log}', [ActivityLogController::class, 'destroy'])->name('admin.activity-log.destroy')->middleware(['auth', 'role:admin']);


// =================================================================
// GURU — SEMUA ROUTE DASHBOARD GURU ADA DI SINI, TIDAK DIKELOMPOKKAN
// =================================================================

// ── Dashboard & Profil ───────────────────────────────────────────
Route::get('/guru/dashboard', [GuruDashboardController::class, 'index'])->name('guru.dashboard')->middleware(['auth', 'role:guru']);
Route::get('/guru/profil', [GuruProfilController::class, 'show'])->name('guru.profil')->middleware(['auth', 'role:guru']);
Route::get('/guru/profil/edit', [GuruProfilController::class, 'edit'])->name('guru.profil.edit')->middleware(['auth', 'role:guru']);
Route::put('/guru/profil', [GuruProfilController::class, 'update'])->name('guru.profil.update')->middleware(['auth', 'role:guru']);

// ── Jadwal Mengajar ──────────────────────────────────────────────
Route::get('/guru/jadwal-mengajar', [JadwalMengajarController::class, 'index'])->name('guru.jadwal-mengajar.index')->middleware(['auth', 'role:guru']);
Route::post('/guru/jadwal-mengajar', [JadwalMengajarController::class, 'store'])->name('guru.jadwal-mengajar.store')->middleware(['auth', 'role:guru']);
Route::put('/guru/jadwal-mengajar/{jadwalMengajar}', [JadwalMengajarController::class, 'update'])->name('guru.jadwal-mengajar.update')->middleware(['auth', 'role:guru']);
Route::delete('/guru/jadwal-mengajar/{jadwalMengajar}', [JadwalMengajarController::class, 'destroy'])->name('guru.jadwal-mengajar.destroy')->middleware(['auth', 'role:guru']);

// ── Mata Pelajaran (dikelola oleh guru sendiri) ──────────────────
Route::post('/guru/study-subject', [GuruStudySubjectController::class, 'store'])->name('guru.study-subject.store')->middleware(['auth', 'role:guru']);
Route::put('/guru/study-subject/{studySubject}', [GuruStudySubjectController::class, 'update'])->name('guru.study-subject.update')->middleware(['auth', 'role:guru']);
Route::delete('/guru/study-subject/{studySubject}', [GuruStudySubjectController::class, 'destroy'])->name('guru.study-subject.destroy')->middleware(['auth', 'role:guru']);

// ── Absensi Foto ─────────────────────────────────────────────────
Route::get('/guru/absensi-foto', [AbsensiFotoController::class, 'index'])->name('guru.absensi-foto.index')->middleware(['auth', 'role:guru']);
Route::post('/guru/absensi-foto/masuk', [AbsensiFotoController::class, 'storeMasuk'])->name('guru.absensi-foto.masuk')->middleware(['auth', 'role:guru']);
Route::post('/guru/absensi-foto/pulang', [AbsensiFotoController::class, 'storePulang'])->name('guru.absensi-foto.pulang')->middleware(['auth', 'role:guru']);
Route::post('/guru/absensi-foto/kantor', [AbsensiFotoController::class, 'storeKantor'])->name('guru.absensi-foto.kantor')->middleware(['auth', 'role:guru']);

// ── Absensi Siswa (oleh Guru) ────────────────────────────────────
Route::get('/guru/absensi-siswa', [AbsensiSiswaController::class, 'index'])->name('guru.absensi-siswa.index')->middleware(['auth', 'role:guru']);
Route::post('/guru/absensi-siswa', [AbsensiSiswaController::class, 'store'])->name('guru.absensi-siswa.store')->middleware(['auth', 'role:guru']);
Route::get('/guru/absensi-siswa/rekap', [AbsensiSiswaController::class, 'rekap'])->name('guru.absensi-siswa.rekap')->middleware(['auth', 'role:guru']);

// ── Perizinan (Guru) ─────────────────────────────────────────────
Route::get('/guru/perizinan', [PerizinanController::class, 'index'])->name('guru.perizinan.index')->middleware(['auth', 'role:guru']);
Route::post('/guru/perizinan', [PerizinanController::class, 'store'])->name('guru.perizinan.store')->middleware(['auth', 'role:guru']);

// ── Pengumuman ───────────────────────────────────────────────────
Route::get('/guru/pengumuman', fn() => view('guru.pengumuman.index'))->name('guru.pengumuman')->middleware(['auth', 'role:guru']);

// ── Wali Kelas ───────────────────────────────────────────────────
Route::get('/guru/wali-kelas', [WaliKelasController::class, 'index'])->name('guru.wali-kelas')->middleware(['auth', 'role:guru']);


// =================================================================
// SISWA — SEMUA ROUTE DASHBOARD SISWA ADA DI SINI, TIDAK DIKELOMPOKKAN
// =================================================================

// ── Dashboard & Profil ───────────────────────────────────────────
Route::get('/siswa/dashboard', [SiswaDashboardController::class, 'index'])->name('siswa.dashboard')->middleware(['auth', 'role:siswa']);
Route::get('/siswa/profil', [SiswaProfilController::class, 'show'])->name('siswa.profil')->middleware(['auth', 'role:siswa']);
Route::get('/siswa/profil/edit', [SiswaProfilController::class, 'edit'])->name('siswa.profil.edit')->middleware(['auth', 'role:siswa']);
Route::put('/siswa/profil', [SiswaProfilController::class, 'update'])->name('siswa.profil.update')->middleware(['auth', 'role:siswa']);

// ── Jadwal Pelajaran (read-only) ─────────────────────────────────
Route::get('/siswa/jadwal-pelajaran', [JadwalPelajaranController::class, 'index'])->name('siswa.jadwal-pelajaran')->middleware(['auth', 'role:siswa']);

// ── Pengumuman ───────────────────────────────────────────────────
Route::get('/siswa/pengumuman', fn() => view('siswa.pengumuman.index'))->name('siswa.pengumuman')->middleware(['auth', 'role:siswa']);


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