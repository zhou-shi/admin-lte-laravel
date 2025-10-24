<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\KurikulumConttroller;
use App\Http\Controllers\Admin\JurusanController;
use App\Http\Controllers\Admin\MahasiswaController;
use App\Http\Controllers\Admin\ProdiController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    
    Route::get('jurusan/load-more', [JurusanController::class, 'loadMore'])->name('jurusan.loadMore');
    Route::resource('jurusan', JurusanController::class);
    
    Route::resource('prodi', ProdiController::class);

    Route::get('kurikulum/load-more', [MahasiswaController::class, 'loadMore'])->name('kurikulum.loadMore');
    Route::resource('kurikulum', KurikulumConttroller::class);

    Route::get('mahasiswa/load-more', [MahasiswaController::class, 'loadMore'])->name('mahasiswa.loadMore');
    Route::resource('mahasiswa', MahasiswaController::class);
});

require __DIR__.'/auth.php';
