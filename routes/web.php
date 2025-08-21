<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SuratCutiController;
use App\Http\Controllers\DisposisiController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Public routes
Route::get('/', fn() => view('welcome'));
Route::get('/test', fn() => 'Laravel is working!');

// Authentication routes
Route::get('/register', [\App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [\App\Http\Controllers\Auth\RegisterController::class, 'register']);

// Development-only routes
if (app()->environment('local')) {
    Route::prefix('quick-login')->group(function () {
        Route::get('/', [\App\Http\Controllers\Debug\QuickLoginController::class, 'index'])->name('debug.quick-login');
        Route::get('/{role}', [\App\Http\Controllers\Debug\QuickLoginController::class, 'login'])->name('quick-login');
    });
}

// Authenticated routes
Route::middleware('auth')->group(function () {
    
    // Core application routes
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile management
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });

    // Surat Cuti management
    Route::prefix('surat-cuti')->name('surat-cuti.')->group(function () {
        Route::get('/', [SuratCutiController::class, 'index'])->name('index');
        Route::get('/create', [SuratCutiController::class, 'create'])->name('create');
        Route::post('/', [SuratCutiController::class, 'store'])->name('store');
        Route::get('/{suratCuti}', [SuratCutiController::class, 'show'])->name('show');
        Route::patch('/{suratCuti}/submit', [SuratCutiController::class, 'submit'])->name('submit');
        Route::get('/{suratCuti}/pdf', [SuratCutiController::class, 'pdf'])->name('pdf');
        Route::get('/{suratCuti}/download-pdf', [SuratCutiController::class, 'downloadPDF'])->name('download-pdf');
        Route::get('/{suratCuti}/download-pdf', [SuratCutiController::class, 'downloadPDF'])->name('download-pdf');
    });

    // Disposisi workflow
    Route::prefix('disposisi')->name('disposisi.')->group(function () {
        Route::get('/pending', [DisposisiController::class, 'pending'])->name('pending');
        Route::get('/history', [DisposisiController::class, 'history'])->name('history');
        Route::get('/{disposisi}', [DisposisiController::class, 'show'])->name('show');
        Route::patch('/{disposisi}/process', [DisposisiController::class, 'process'])->name('process');
    });

    // Digital signature management
    Route::prefix('signature')->name('signature.')->group(function () {
        Route::get('/', [\App\Http\Controllers\SignatureController::class, 'show'])->name('show');
        Route::get('/upload', [\App\Http\Controllers\SignatureController::class, 'uploadForm'])->name('upload');
        Route::post('/store', [\App\Http\Controllers\SignatureController::class, 'upload'])->name('store');
        Route::delete('/', [\App\Http\Controllers\SignatureController::class, 'delete'])->name('delete');
        Route::post('/canvas', [\App\Http\Controllers\SignatureController::class, 'saveCanvas'])->name('canvas');
    });

    // Employee PDF certificates
    Route::prefix('pegawai')->name('pegawai.')->group(function () {
        Route::get('/pdf', [\App\Http\Controllers\PegawaiPDFController::class, 'index'])->name('pdf.index');
        Route::get('/{pegawai}/pdf/download', [\App\Http\Controllers\PegawaiPDFController::class, 'generate'])->name('pdf.download');
        Route::get('/{pegawai}/pdf/stream', [\App\Http\Controllers\PegawaiPDFController::class, 'stream'])->name('pdf.stream');
        Route::get('/{pegawai}/surat-cuti-form', [\App\Http\Controllers\PegawaiPDFController::class, 'suratCutiForm'])->name('surat-cuti-form');
        Route::get('/{pegawai}/surat-cuti-resmi', [\App\Http\Controllers\PegawaiPDFController::class, 'suratCutiResmi'])->name('surat-cuti-resmi');
        Route::get('/{pegawai}/pdf/puskesmas', [\App\Http\Controllers\PegawaiPDFController::class, 'puskesmasCertificate'])->name('pdf.puskesmas');

    });

    // Standalone Blanko Cuti Resmi
    Route::get('/blanko-cuti-resmi', [\App\Http\Controllers\PegawaiPDFController::class, 'blankoCutiResmi'])->name('blanko-cuti-resmi');

    // Admin panel
    Route::prefix('admin')->name('admin.')->middleware('role:admin,kadin')->group(function () {
        
        // User management
        Route::resource('users', \App\Http\Controllers\Admin\UserManagementController::class);

        // Reports and analytics
        Route::prefix('laporan')->name('laporan.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\LaporanController::class, 'index'])->name('index');
            Route::get('/export', [\App\Http\Controllers\Admin\LaporanController::class, 'export'])->name('export');
            Route::get('/print', [\App\Http\Controllers\Admin\LaporanController::class, 'print'])->name('print');
            Route::get('/chart-data', [\App\Http\Controllers\Admin\LaporanController::class, 'chartData'])->name('chart-data');
        });

        // Digital stamp management
        Route::prefix('cap-stempel')->name('cap-stempel.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\CapStempelController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Admin\CapStempelController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\Admin\CapStempelController::class, 'store'])->name('store');
            Route::delete('/{user}', [\App\Http\Controllers\Admin\CapStempelController::class, 'destroy'])->name('destroy');
            Route::post('/{user}/toggle', [\App\Http\Controllers\Admin\CapStempelController::class, 'toggle'])->name('toggle');
            Route::get('/{user}/preview', [\App\Http\Controllers\Admin\CapStempelController::class, 'preview'])->name('preview');
            Route::post('/bulk-upload', [\App\Http\Controllers\Admin\CapStempelController::class, 'bulkUpload'])->name('bulk-upload');
        });

        // Signature management
        Route::prefix('signatures')->name('signatures.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\SignatureController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Admin\SignatureController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\Admin\SignatureController::class, 'store'])->name('store');
            Route::get('/{signature}/edit', [\App\Http\Controllers\Admin\SignatureController::class, 'edit'])->name('edit');
            Route::put('/{signature}', [\App\Http\Controllers\Admin\SignatureController::class, 'update'])->name('update');
            Route::delete('/{signature}', [\App\Http\Controllers\Admin\SignatureController::class, 'destroy'])->name('destroy');
        });

        // Bulk approval for debugging
        Route::prefix('surat-cuti')->name('surat-cuti.')->group(function () {
            Route::post('/bulk-approve-all', [SuratCutiController::class, 'bulkApproveAll'])->name('bulk-approve-all');
            Route::post('/bulk-reject-all', [SuratCutiController::class, 'bulkRejectAll'])->name('bulk-reject-all');
            Route::get('/admin-dashboard', [SuratCutiController::class, 'adminDashboard'])->name('admin-dashboard');
        });
    });

    // Development debug routes
    if (app()->environment('local')) {
        Route::prefix('debug')->name('debug.')->group(function () {
            Route::get('/signature', fn() => view('debug.signature'))->name('signature');
            Route::get('/test-accounts', fn() => view('debug.test-accounts'))->name('test-accounts');
        });
    }
});

require __DIR__.'/auth.php';