<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\KurikulumConttroller;
use App\Http\Controllers\ProfileController;
use App\Models\Kurikulum;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('kurikulum', KurikulumConttroller::class);
});

require __DIR__.'/auth.php';
