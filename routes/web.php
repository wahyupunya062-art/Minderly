<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TugasController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KuesionerController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TeamChatController;
use App\Http\Controllers\ProyekController;
use App\Http\Controllers\AutoTeamController;
use App\Http\Controllers\KalenderController;
use App\Http\Controllers\RiwayatTugasController;
/*
|--------------------------------------------------------------------------
| Halaman Utama
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('dashboard');
});

/*
|--------------------------------------------------------------------------
| Login dan Register
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {

    // Login
    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.process');

    // Register
    Route::get('/register', [AuthController::class, 'showRegister'])
        ->name('register');

    Route::post('/register', [AuthController::class, 'register'])
        ->name('register.process');

    // Lupa password
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])
        ->name('password.request');

    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])
        ->name('password.email');

    // Form password baru
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])
        ->name('password.reset');

    Route::post('/reset-password', [AuthController::class, 'resetPassword'])
        ->name('password.update');
});

/*
|--------------------------------------------------------------------------
| Halaman yang Memerlukan Login
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

    // Kuesioner profil pengguna
    Route::get('/kuesioner', [KuesionerController::class, 'create'])
        ->name('kuesioner.create');

    Route::post('/kuesioner', [KuesionerController::class, 'store'])
        ->name('kuesioner.store');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');
    // Pengaturan Akun
    Route::get('/profile/edit', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::put('/profile/update', [ProfileController::class, 'update'])
        ->name('profile.update');
    // Halaman Notifikasi
    Route::get('/notifikasi', [NotifikasiController::class, 'index'])
        ->name('notifikasi.index');
    
    // Kolaborasi Tim
    Route::get('/teams', [TeamController::class, 'index'])
        ->name('teams.index');

    Route::get('/teams/create', [TeamController::class, 'create'])
        ->name('teams.create');

    Route::post('/teams', [TeamController::class, 'store'])
        ->name('teams.store');

    Route::get('/teams/invite/{token}', [TeamController::class, 'joinByInvite'])
        ->name('teams.invite.join');

    Route::get('/teams/{team}/chat', [TeamChatController::class, 'index'])
        ->name('teams.chat.index');

    Route::post('/teams/{team}/chat', [TeamChatController::class, 'store'])
        ->name('teams.chat.store');

    Route::post('/teams/{team}/members', [TeamController::class, 'addMember'])
        ->name('teams.members.store');

    Route::post('/teams/{team}/invite', [TeamController::class, 'generateInvite'])
        ->name('teams.invite.generate');

    Route::get('/teams/{team}', [TeamController::class, 'show'])
        ->name('teams.show');
    // Kalender & Jadwal
    Route::get('/kalender', [KalenderController::class, 'index'])
        ->name('kalender.index');

    Route::post('/kalender/agenda', [KalenderController::class, 'store'])
        ->name('kalender.store');

    Route::delete('/kalender/agenda/{agenda}', [KalenderController::class, 'destroy'])
        ->name('kalender.destroy');
    // Riwayat Tugas
    Route::get('/riwayat-tugas', [RiwayatTugasController::class, 'index'])
        ->name('riwayat.index');
    // Manajemen tugas
    Route::prefix('tugas')->name('tugas.')->group(function () {

        Route::get('/', [TugasController::class, 'index'])
            ->name('index');

        Route::get('/tambah', [TugasController::class, 'create'])
            ->name('create');

        Route::post('/simpan', [TugasController::class, 'store'])
            ->name('store');

        Route::get('/edit/{id}', [TugasController::class, 'edit'])
            ->name('edit');

        Route::put('/update/{id}', [TugasController::class, 'update'])
            ->name('update');

        Route::delete('/hapus/{id}', [TugasController::class, 'destroy'])
            ->name('destroy');
    });
});
