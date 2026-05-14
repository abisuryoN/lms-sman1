<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Guru;
use App\Http\Controllers\Siswa;

// ── Auth Routes ──────────────────────────────────────────
Route::get('/', fn() => redirect()->route('login'));
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ── Admin Routes ─────────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::get('profil', [Admin\ProfilController::class, 'edit'])->name('profil.edit');
    Route::put('profil', [Admin\ProfilController::class, 'update'])->name('profil.update');

    Route::resource('guru', Admin\GuruController::class);
    Route::post('guru/{guru}/reset-password', [Admin\GuruController::class, 'resetPassword'])->name('guru.reset-password');

    Route::resource('siswa', Admin\SiswaController::class);
    Route::post('siswa/{siswa}/reset-password', [Admin\SiswaController::class, 'resetPassword'])->name('siswa.reset-password');

    Route::resource('kelas', Admin\KelasController::class);
    Route::resource('mapel', Admin\MapelController::class);

    Route::resource('guru-kelas', Admin\GuruKelasController::class)->only(['index', 'store', 'edit', 'update', 'destroy']);

    Route::get('tahun-ajaran', [Admin\TahunAjaranController::class, 'index'])->name('tahun-ajaran.index');
    Route::get('tahun-ajaran/create', [Admin\TahunAjaranController::class, 'create'])->name('tahun-ajaran.create');
    Route::post('tahun-ajaran', [Admin\TahunAjaranController::class, 'store'])->name('tahun-ajaran.store');
    Route::post('tahun-ajaran/{tahunAjaran}/activate', [Admin\TahunAjaranController::class, 'activate'])->name('tahun-ajaran.activate');
    Route::post('tahun-ajaran/akhiri', [Admin\TahunAjaranController::class, 'akhiriTahunAjaran'])->name('tahun-ajaran.akhiri');

    Route::get('import/siswa', [Admin\ImportController::class, 'siswaForm'])->name('import.siswa');
    Route::post('import/siswa', [Admin\ImportController::class, 'importSiswa'])->name('import.siswa.process');
    Route::get('import/guru', [Admin\ImportController::class, 'guruForm'])->name('import.guru');
    Route::post('import/guru', [Admin\ImportController::class, 'importGuru'])->name('import.guru.process');
});

// ── Guru Routes ──────────────────────────────────────────
Route::prefix('guru')->name('guru.')->middleware(['auth', 'role:guru'])->group(function () {
    Route::get('/dashboard', [Guru\DashboardController::class, 'index'])->name('dashboard');

    Route::get('profil', [Guru\ProfilController::class, 'edit'])->name('profil.edit');
    Route::put('profil', [Guru\ProfilController::class, 'update'])->name('profil.update');

    Route::resource('materi', Guru\MateriController::class)->only(['index', 'create', 'store', 'destroy']);
    Route::get('materi/{materi}/logs', [Guru\MateriController::class, 'logs'])->name('materi.logs');
    Route::resource('tugas', Guru\TugasController::class)->only(['index', 'create', 'store', 'show']);
    Route::post('tugas/{tuga}/check-similarity', [Guru\TugasController::class, 'checkSimilarity'])->name('tugas.check-similarity');

    Route::get('nilai', [Guru\NilaiController::class, 'index'])->name('nilai.index');
    Route::get('nilai/{tuga}', [Guru\NilaiController::class, 'edit'])->name('nilai.edit');
    Route::put('nilai/{tuga}', [Guru\NilaiController::class, 'update'])->name('nilai.update');

    Route::get('similarity', [Guru\SimilarityController::class, 'index'])->name('similarity.index');
    Route::get('similarity/{tuga}', [Guru\SimilarityController::class, 'detail'])->name('similarity.detail');
    Route::post('similarity/{tuga}/run', [Guru\SimilarityController::class, 'runCheck'])->name('similarity.run');
    Route::get('similarity/{tuga}/status', [Guru\SimilarityController::class, 'checkStatus'])->name('similarity.status');
    Route::get('similarity/file/{jawaban}', [Guru\SimilarityController::class, 'viewFile'])->name('similarity.view-file');
    Route::get('similarity/ocr-text/{jawaban}', [Guru\SimilarityController::class, 'viewOcrText'])->name('similarity.view-ocr');

    Route::get('kelas', [Guru\KelasController::class, 'index'])->name('kelas.index');
    Route::get('kelas/{kela}', [Guru\KelasController::class, 'show'])->name('kelas.show');
    Route::get('jawaban/{jawaban}/download', [Guru\NilaiController::class, 'downloadJawaban'])->name('jawaban.download');
});

// ── Siswa Routes ─────────────────────────────────────────
Route::prefix('siswa')->name('siswa.')->middleware(['auth', 'role:siswa'])->group(function () {
    Route::get('/dashboard', [Siswa\DashboardController::class, 'index'])->name('dashboard');

    Route::get('profil', [Siswa\ProfilController::class, 'edit'])->name('profil.edit');
    Route::put('profil', [Siswa\ProfilController::class, 'update'])->name('profil.update');

    Route::get('materi', [Siswa\MateriController::class, 'index'])->name('materi.index');
    Route::get('materi/{materi}/download', [Siswa\MateriController::class, 'download'])->name('materi.download');
    Route::get('tugas', [Siswa\TugasController::class, 'index'])->name('tugas.index');
    Route::get('tugas/{tuga}', [Siswa\TugasController::class, 'show'])->name('tugas.show');
    Route::get('tugas/{tuga}/download', [Siswa\TugasController::class, 'download'])->name('tugas.download');
    Route::post('tugas/{tuga}/submit', [Siswa\TugasController::class, 'submit'])->name('tugas.submit');
    Route::get('jawaban/{jawaban}/file', [Siswa\TugasController::class, 'viewFile'])->name('jawaban.view-file');
    Route::get('nilai', [Siswa\NilaiController::class, 'index'])->name('nilai.index');
});
