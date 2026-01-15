<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PasswordResetController;

Route::get('/', function () {
    return redirect()->route('inicial');
});

Route::get('/Inicial', function () {
    return view('inicial');
})->name('inicial');

Route::get('/servicios', function () {
    return view('servicios');
})->name('servicios');

Route::get('/contacto', function () {
    return view('contacto');
})->name('contacto');

// Rutas de autenticación
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Rutas 2FA (fuera de guest para que funcionen después del primer login)
Route::get('/2fa/verify', [AuthController::class, 'showTwoFactorForm'])->name('2fa.verify');
Route::post('/2fa/verify', [AuthController::class, 'verifyTwoFactor'])->name('2fa.verify.post');
Route::post('/2fa/resend', [AuthController::class, 'resendTwoFactorCode'])->name('2fa.resend');

// Rutas protegidas
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/home', function () {
        return view('home');
    })->name('home');

    // Rutas de perfil para gestionar 2FA
    Route::get('/profile/2fa', [ProfileController::class, 'show2FA'])->name('profile.2fa');
    Route::post('/profile/2fa/enable', [ProfileController::class, 'enable2FA'])->name('profile.2fa.enable');
    Route::post('/profile/2fa/disable', [ProfileController::class, 'disable2FA'])->name('profile.2fa.disable');
});

// Rutas con roles
Route::middleware(['auth', 'role:1'])->group(function () {
    Route::get('/admin', function () {
        return view('admin.dashboard');
    });
});

Route::middleware(['auth', 'role:1,2'])->group(function () {
    Route::get('/operaciones', function () {
        return view('operaciones.index');
    });
});

//consentimiento informado
Route::get('/consentimiento', function () {
    return view('acciones.consentimiento');
})->name('consentimiento');

//Resetear contraseña
Route::get('/forgot-password', [PasswordResetController::class, 'form'])
    ->name('password.request');

Route::post('/forgot-password', [PasswordResetController::class, 'send'])
    ->name('password.email');

Route::get('/reset-password/{token}', [PasswordResetController::class, 'resetForm'])
    ->name('password.reset');

Route::post('/reset-password', [PasswordResetController::class, 'update'])
    ->name('password.update');
        
//Desbloquear cuenta
Route::get('/unlock', [AuthController::class, 'unlockForm'])->name('lock.form');
Route::post('/unlock', [AuthController::class, 'unlock'])->name('lock.verify');
