<?php

use App\Http\Controllers\Public\LandingController;
use App\Http\Controllers\Public\SurveyController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UnitPelayananController;
use App\Http\Controllers\Admin\LayananController;
use App\Http\Controllers\Admin\PeriodeSurveiController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\DataSurveiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES - Survei Kepuasan Masyarakat
|--------------------------------------------------------------------------
| Halaman yang dapat diakses oleh masyarakat umum tanpa login.
*/

// Halaman beranda
Route::get('/', [LandingController::class, 'index'])->name('landing');

// Alur survei publik
Route::prefix('survey')->name('survey.')->group(function () {
    // Halaman pilih OPD
    Route::get('/opd', [SurveyController::class, 'selectOpd'])->name('select-opd');
    
    // Halaman identitas responden
    Route::get('/identitas/{unitId}', [SurveyController::class, 'identitas'])->name('identitas');
    Route::post('/identitas/{unitId}', [SurveyController::class, 'storeIdentitas'])->name('store-identitas');
    
    // Halaman pertanyaan survei
    Route::get('/pertanyaan/{surveiId}', [SurveyController::class, 'pertanyaan'])->name('pertanyaan');
    Route::post('/pertanyaan/{surveiId}', [SurveyController::class, 'storeJawaban'])->name('store-jawaban');
    
    // Halaman selesai / terima kasih
    Route::get('/selesai', [SurveyController::class, 'selesai'])->name('selesai');
});

// Route AJAX untuk mendapatkan layanan berdasarkan OPD
Route::get('/get-layanan/{unitId}', [SurveyController::class, 'getLayanan'])->name('get-layanan');

/*
|--------------------------------------------------------------------------
| AUTH ROUTES - Login & Logout
|--------------------------------------------------------------------------
| Halaman autentikasi untuk akses dashboard admin.
*/

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES - Dashboard dan Manajemen
|--------------------------------------------------------------------------
| Semua route di bawah ini memerlukan autentikasi dan dibatasi berdasarkan role.
*/

Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    
    // ================================================================
    // DASHBOARD UTAMA (Semua Role)
    // ================================================================
    // Semua role bisa akses dashboard, tetapi konten berbeda sesuai role
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // ================================================================
    // SUPER ADMIN ONLY
    // ================================================================
    Route::middleware(['role:super_admin'])->group(function () {
        
        // ---- Manajemen Unit Pelayanan (CRUD) ----
        Route::resource('unit-pelayanan', UnitPelayananController::class)->parameters([
            'unit-pelayanan' => 'unit_pelayanan'
        ]);
        Route::patch('unit-pelayanan/{unit_pelayanan}/toggle-active', [UnitPelayananController::class, 'toggleActive'])
            ->name('unit-pelayanan.toggle-active');
        
        // ---- Manajemen Periode Survei (CRUD) ----
        Route::resource('periode', PeriodeSurveiController::class);
        Route::post('periode/{periode}/activate', [PeriodeSurveiController::class, 'activate'])
            ->name('periode.activate');
        
        // ---- Manajemen User (CRUD) ----
        Route::resource('users', UserController::class);
        
        // ---- Audit Log ----
        Route::get('audit-logs', [AuditLogController::class, 'index'])->name('audit-logs');
        Route::get('audit-logs/{auditLog}', [AuditLogController::class, 'show'])->name('audit-logs.show');
        
        // ---- Layanan (Super Admin untuk pengawasan) ----
        // Super Admin bisa melihat dan menghapus semua layanan
        Route::get('layanan', [LayananController::class, 'index'])->name('layanan.index');
        Route::get('layanan/{layanan}', [LayananController::class, 'show'])->name('layanan.show');
        Route::delete('layanan/{layanan}', [LayananController::class, 'destroy'])->name('layanan.destroy');
    });
    
    // ================================================================
    // ADMIN UNIT & SUPER ADMIN
    // ================================================================
    Route::middleware(['role:super_admin,admin_unit'])->group(function () {
        
        // ---- Manajemen Layanan (CRUD per OPD sendiri) ----
        // Admin Unit hanya bisa mengelola layanan di unitnya sendiri
        // Super Admin bisa mengelola semua (tapi lebih baik via route terpisah di atas)
        Route::prefix('layanan')->group(function () {
            Route::get('/', [LayananController::class, 'index'])->name('layanan.index');
            Route::get('/create', [LayananController::class, 'create'])->name('layanan.create');
            Route::post('/', [LayananController::class, 'store'])->name('layanan.store');
            Route::get('/{layanan}/edit', [LayananController::class, 'edit'])->name('layanan.edit');
            Route::put('/{layanan}', [LayananController::class, 'update'])->name('layanan.update');
            Route::delete('/{layanan}', [LayananController::class, 'destroy'])->name('layanan.destroy');
            Route::patch('/{layanan}/toggle', [LayananController::class, 'toggleActive'])->name('layanan.toggle');
        });
        
        // ---- Data Survei (untuk unitnya sendiri) ----
        Route::prefix('data-survei')->group(function () {
            Route::get('/', [DataSurveiController::class, 'index'])->name('data-survei');
            Route::get('/{survei}', [DataSurveiController::class, 'show'])->name('data-survei.show');
            Route::delete('/{survei}', [DataSurveiController::class, 'destroy'])->name('data-survei.destroy');
            Route::get('/{survei}/export-pdf', [DataSurveiController::class, 'exportPdf'])->name('data-survei.export-pdf');
        });
        
        // ---- Laporan (untuk unitnya sendiri) ----
        Route::prefix('reports')->group(function () {
            Route::get('/', [ReportController::class, 'index'])->name('reports.index');
            Route::post('/generate', [ReportController::class, 'generate'])->name('reports.generate');
            Route::get('/export-pdf', [ReportController::class, 'exportPdf'])->name('reports.export-pdf');
            Route::get('/export-excel', [ReportController::class, 'exportExcel'])->name('reports.export-excel');
        });
    });
    
    // ================================================================
    // PIMPINAN UNIT & PIMPINAN UTAMA (READ-ONLY)
    // ================================================================
    Route::middleware(['role:pimpinan_unit,pimpinan_utama'])->group(function () {
        
        // ---- Laporan Read-Only ----
        Route::prefix('laporan')->group(function () {
            Route::get('/', [ReportController::class, 'pimpinanIndex'])->name('laporan.index');
            Route::get('/export-pdf', [ReportController::class, 'pimpinanExportPdf'])->name('laporan.export-pdf');
            Route::get('/export-excel', [ReportController::class, 'pimpinanExportExcel'])->name('laporan.export-excel');
        });
    });
});

/*
|--------------------------------------------------------------------------
| FALLBACK ROUTE (Opsional)
|--------------------------------------------------------------------------
| Route untuk menangani 404 jika diperlukan.
*/

// Route::fallback(function () {
//     return view('errors.404');
// });