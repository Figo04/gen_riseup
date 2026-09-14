<?php

use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ExportController;
use App\Http\Controllers\Admin\HasilTestController;
use App\Http\Controllers\Admin\KalenderHaidController;
use App\Http\Controllers\Admin\MateriController;
use App\Http\Controllers\Admin\RespondenController;
use App\Http\Controllers\Admin\SoalController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest:admin')->group(function () {
        Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
        Route::post('login', [AuthenticatedSessionController::class, 'store']);
    });

    Route::middleware('auth:admin')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('responden', [RespondenController::class, 'index'])->name('responden.index');
        Route::get('hasil-test', [HasilTestController::class, 'index'])->name('hasil-test.index');

        Route::resource('soal', SoalController::class)->except('show');

        // View-only (PRD §4.2/§4.3): tidak ada create/edit/delete di sini.
        Route::get('materi', [MateriController::class, 'index'])->name('materi.index');
        Route::get('materi/{modul}', [MateriController::class, 'show'])->name('materi.show');
        Route::get('materi/{modul}/{subBagian}', [MateriController::class, 'subBagian'])->name('materi.sub-bagian');

        Route::get('kalender-haid', [KalenderHaidController::class, 'index'])->name('kalender-haid.index');

        Route::get('export', ExportController::class)->name('export');

        Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    });
});
