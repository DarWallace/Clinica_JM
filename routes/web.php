<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Http\Controllers\Landing\HomeController;
use App\Http\Controllers\Auth\PatientAuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/paciente/acceso', [PatientAuthController::class, 'showForm'])->name('patient.login');
    Route::post('/paciente/registro', [PatientAuthController::class, 'register'])->name('patient.auth.register');
    Route::post('/paciente/login', [PatientAuthController::class, 'login'])->name('patient.auth.login');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/mi-area', function () {
        return view('patient.dashboard_p');
    })->name('patient.dashboard');

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
