<?php

use App\Http\Controllers\ModulController;
use App\Http\Controllers\PretestController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RefleksiController;
use App\Http\Controllers\SubBagianController;
use App\Http\Controllers\TrackerController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/pretest', [PretestController::class, 'create'])->name('pretest.create');
    Route::post('/pretest', [PretestController::class, 'store'])->name('pretest.store');

    Route::middleware('pretest.completed')->group(function () {
        Route::get('/modul', [ModulController::class, 'index'])->name('modul.index');
        Route::get('/modul/{modul}', [ModulController::class, 'show'])->name('modul.show');
        Route::get('/modul/{modul}/{subBagian}', [SubBagianController::class, 'show'])->name('modul.sub-bagian.show');
        Route::post('/modul/{modul}/{subBagian}/selesai', [SubBagianController::class, 'selesai'])->name('modul.sub-bagian.selesai');
        Route::get('/modul/{modul}/{subBagian}/refleksi', [RefleksiController::class, 'show'])->name('modul.sub-bagian.refleksi');
        Route::post('/modul/{modul}/{subBagian}/refleksi', [RefleksiController::class, 'store'])->name('modul.sub-bagian.refleksi.store');

        Route::get('/tracker-gizi', [TrackerController::class, 'showGizi'])->name('tracker-gizi.show');
        Route::post('/tracker-gizi', [TrackerController::class, 'storeGizi'])->name('tracker-gizi.store');
    });
});

require __DIR__.'/auth.php';
