<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Http\Controllers\Landing\HomeController;
use App\Http\Controllers\Auth\PatientAuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Patient\PatientDashboardController;

Route::get('/', HomeController::class)->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/paciente/acceso', [PatientAuthController::class, 'showForm'])->name('patient.login');
    Route::post('/paciente/registro', [PatientAuthController::class, 'register'])->name('patient.auth.register');
    Route::post('/paciente/login', [PatientAuthController::class, 'login'])->name('patient.auth.login');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/mi-area', [PatientDashboardController::class, 'index'])->name('patient.dashboard');
    Route::post('/mi-area/reservar', [PatientDashboardController::class, 'reserve'])->name('patient.dashboard.reserve');
    Route::post('/mi-area/citas/{reservation}/confirmar', [PatientDashboardController::class, 'confirm'])->name('patient.dashboard.confirm');
    Route::post('/mi-area/citas/{reservation}/anular', [PatientDashboardController::class, 'cancel'])->name('patient.dashboard.cancel');
    Route::post('/mi-area/perfil', [PatientDashboardController::class, 'updateProfile'])->name('patient.dashboard.profile.update');

    Route::post('/paciente/logout', [PatientAuthController::class, 'logout'])->name('patient.logout');

    Route::redirect('settings', 'settings/profile');
    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

require __DIR__.'/auth.php';
