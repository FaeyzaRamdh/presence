<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PresenceController;
use Maatwebsite\Excel\Concerns\FromCollection;


// Halaman login (unauthenticated)
Route::get('/', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'loginAuth'])->name('login.store');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Middleware auth - SEMUA ROUTE DALAM GROUP INI
Route::middleware(['auth'])->group(function () {

    // Dashboard berdasarkan role
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
    Route::get('/home', [UserController::class, 'home'])->name('user.home');

   Route::prefix('users')->name('users.')->group(function () {

    // CRUD utama
    Route::get('/', [UserController::class, 'index'])->name('index');
    Route::get('/create', [UserController::class, 'create'])->name('create');
    Route::post('/store', [UserController::class, 'store'])->name('store');
    Route::get('/edit/{id}', [UserController::class, 'edit'])->name('edit');
    Route::put('/update/{id}', [UserController::class, 'update'])->name('update');
    Route::delete('/delete/{id}', [UserController::class, 'destroy'])->name('delete');

    // RECYCLE BIN (softdelete)
    Route::get('/trash', [UserController::class, 'trash'])->name('trash');
    Route::get('/restore/{id}', [UserController::class, 'restore'])->name('restore');
    Route::delete('/force-delete/{id}', [UserController::class, 'forceDelete'])->name('force-delete');

    // EXPORT
    Route::get('/export', [UserController::class, 'export'])->name('export');
    Route::get('/export-pdf', [UserController::class, 'exportPDF'])->name('exportPDF');
});


    // Absensi karyawan (user & admin) - GUNAKAN RESOURCE
    
    // Route tambahan untuk absen pulang (jika perlu custom)
    Route::get('/presence/{id}/pulang', [PresenceController::class, 'pulang'])->name('presence.pulang');
    Route::put('/presence/{id}/update-pulang', [PresenceController::class, 'updatePulang'])->name('presence.update-pulang');
    Route::get('/presence/export', [PresenceController::class, 'export'])->name('presence.export');
    Route::resource('presence', PresenceController::class)->except(['show']);
    Route::get('presence/export-pdf', [PresenceController::class, 'exportPDF'])->name('presence.exportPDF');
    Route::get('presence/trash', [PresenceController::class, 'trash'])->name('presence.trash');
    Route::get('presence/restore/{id}', [PresenceController::class, 'restore'])->name('presence.restore');
    Route::delete('presence/force-delete/{id}', [PresenceController::class, 'forceDelete'])->name('presence.forceDelete');

});