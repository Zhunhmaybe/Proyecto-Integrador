<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


// Rutas de autenticación
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Rutas protegidas
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/home', function () {
        return view('home');
    })->name('home');
});
Route::middleware(['auth', 'role:1'])->group(function () {
    // Solo administradores
    Route::get('/admin', function () {
        return view('admin.dashboard');
    });
});

Route::middleware(['auth', 'role:1,2'])->group(function () {
    // Administradores y Operadores
    Route::get('/operaciones', function () {
        return view('operaciones.index');
    });
});

Route::get('/test-email', function () {
    try {
        \Illuminate\Support\Facades\Mail::raw('Este es un email de prueba', function ($message) {
            $message->to('davidramoz132@gmail.com')
                    ->subject('Prueba de Email');
        });
        return 'Email enviado correctamente!';
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
});
