<?php

use App\Http\Controllers\JurusanController;
use App\Http\Controllers\PendaftarController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Public user - tidak perlu login
Route::get('pendaftar/create', [PendaftarController::class, 'create'])->name('pendaftar.create');//menampilkan form pendaftaran
Route::post('pendaftar', [PendaftarController::class, 'store'])->name('pendaftar.store');//menyimpan data pendaftar baru
    
// Admin - wajib login
Route::middleware(['auth'])->group(function () {
    Route::resource('jurusan', JurusanController::class)->except(['show']);//Crud untuk jurusan tanpa show
    Route::resource('pendaftar', PendaftarController::class)->except(['create', 'store', 'show']);//crud untuk pendaftar tanpa create, store, dan show
});

require __DIR__.'/auth.php';