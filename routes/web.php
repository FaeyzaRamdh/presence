<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PresenceController;

// Halaman login (unauthenticated)
Route::get('/', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'loginAuth'])->name('login.store');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Middleware auth - SEMUA ROUTE DALAM GROUP INI
Route::middleware(['auth'])->group(function () {

    // Dashboard berdasarkan role
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
    Route::get('/home', [UserController::class, 'home'])->name('user.home');

    // User management (admin only)
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/store', [UserController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [UserController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [UserController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [UserController::class, 'destroy'])->name('delete');
    });

    // Absensi karyawan (user & admin) - GUNAKAN RESOURCE
    Route::resource('presence', PresenceController::class);
    
    // Route tambahan untuk absen pulang (jika perlu custom)
    Route::get('/presence/{id}/pulang', [PresenceController::class, 'pulang'])->name('presence.pulang');
    Route::put('/presence/{id}/update-pulang', [PresenceController::class, 'updatePulang'])->name('presence.update-pulang');
});