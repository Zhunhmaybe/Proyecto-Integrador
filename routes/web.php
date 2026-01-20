<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\EspecialidadesController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RecepcionistaController;


Route::get('/', function () {
    return redirect()->route('inicial');
});

Route::get('/Inicio', function () {
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
    // Route Dispatcher based on Role
    Route::get('/home', function () {
        $role = Auth::user()->rol;
        switch ($role) {
            case 0:
                return redirect()->route('doctor.dashboard');
            case 1:
                return redirect()->route('admin.dashboard');
            case 2:
                return redirect()->route('auditor.dashboard');
            case 3:
                return redirect()->route('recepcionista.dashboard');
            case 4:
                return redirect()->route('usuario.dashboard');
            default:
                return view('home'); // Fallback
        }
    })->name('home');

    // Editar perfil
    Route::get('/perfil/editar', [AuthController::class, 'editProfile'])
        ->name('recepcionista.edit');

    Route::put('/perfil/actualizar', [AuthController::class, 'updateProfile'])
        ->name('perfil.update');


    // Rutas de perfil para gestionar 2FA
    Route::get('/profile/2fa', [ProfileController::class, 'show2FA'])->name('profile.2fa');
    Route::post('/profile/2fa/enable', [ProfileController::class, 'enable2FA'])->name('profile.2fa.enable');
    Route::post('/profile/2fa/disable', [ProfileController::class, 'disable2FA'])->name('profile.2fa.disable');
});

// Rutas con roles - DASHBOARDS
//Doctor
Route::middleware(['auth', 'role:0'])->group(function () {
    Route::get('/doctor', function () {
        return view('doctor.dashboard');
    })->name('doctor.dashboard');

    //Perfil
    Route::get('/perfil/Doctor/editar', [DoctorController::class, 'editProfile'])
        ->name('perfil.edit');

    Route::put('/perfil/Doctoractualizar', [DoctorController::class, 'updateProfile'])
        ->name('perfil.update');

    //Pacientes
    Route::get('/doctor/pacientes', [DoctorController::class, 'pacientesIndex'])
        ->name('doctor.pacientes.index');

    Route::get('/doctor/pacientes/crear', [DoctorController::class, 'pacientesCreate'])
        ->name('doctor.pacientes.create');

    Route::post('/doctor/pacientes', [DoctorController::class, 'pacientesStore'])
        ->name('doctor.pacientes.store');

    Route::put('/doctor/pacientes/{paciente}', [DoctorController::class, 'pacientesUpdate'])
        ->name('doctor.pacientes.update');

    Route::get('/doctor/pacientes/{paciente}/citas', [DoctorController::class, 'pacientesCitas'])
        ->name('doctor.pacientes.citas');
});

Route::middleware(['auth', 'role:1'])->group(function () {
    Route::get('/admin', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    //Perfil
    Route::get('/perfil/editar', [AdminController::class, 'editProfile'])
        ->name('admin.perfil.edit');

    Route::put('/perfil/actualizar', [AdminController::class, 'updateProfile'])
        ->name('perfil.update');

    //Doctores
    Route::get('/doctores', [DoctorController::class, 'index'])
        ->name('admin.doctores.index');

    Route::get('/doctores/crear', [DoctorController::class, 'create'])
        ->name('admin.doctores.create');

    Route::post('/doctores', [DoctorController::class, 'store'])
        ->name('admin.doctores.store');

    Route::get('/doctores/{doctor}/editar', [DoctorController::class, 'edit'])
        ->name('admin.doctores.edit');

    Route::put('/doctores/{doctor}', [DoctorController::class, 'update'])
        ->name('admin.doctores.update');

    // ===== ESPECIALIDADES =====

    Route::get('/especialidades', [EspecialidadesController::class, 'index'])
        ->name('admin.especialidades.index');

    Route::get('/especialidades/crear', [EspecialidadesController::class, 'create'])
        ->name('admin.especialidades.create');

    Route::post('/especialidades', [EspecialidadesController::class, 'store'])
        ->name('admin.especialidades.store');

    Route::get('/especialidades/{especialidad}/editar', [EspecialidadesController::class, 'edit'])
        ->name('admin.especialidades.edit');

    Route::put('/especialidades/{especialidad}', [EspecialidadesController::class, 'update'])
        ->name('admin.especialidades.update');

    Route::delete('/especialidades/{especialidad}', [EspecialidadesController::class, 'destroy'])
        ->name('admin.especialidades.destroy');

    // 👥 Pacientes (ADMIN)
    Route::get('/admin/pacientes', [AdminController::class, 'pacientesIndex'])
        ->name('admin.pacientes.index');

    Route::get('/admin/pacientes/crear', [AdminController::class, 'pacientesCreate'])
        ->name('admin.pacientes.create');

    Route::post('/admin/pacientes', [AdminController::class, 'pacientesStore'])
        ->name('admin.pacientes.store');

    Route::put('/admin/pacientes/{paciente}', [AdminController::class, 'pacientesUpdate'])
        ->name('admin.pacientes.update');

    Route::get('/admin/pacientes/{paciente}/citas', [AdminController::class, 'pacientesCitas'])
        ->name('admin.pacientes.citas');

    //Usuarios
    Route::get('/usuarios', [AdminController::class, 'usuariosIndex'])
        ->name('admin.usuarios.index');
    //Citas
    Route::get('/admin/citas', [AdminController::class, 'citasIndex'])
        ->name('admin.citas.index');

    Route::post('/admin/citas', [AdminController::class, 'Adminstore'])
        ->name('admin.citas.store');

    Route::get('/admin/citas/{cita}/editar', [AdminController::class, 'citasEdit'])
        ->name('admin.citas.edit');

    Route::put('/admin/citas/{cita}', [AdminController::class, 'citasUpdate'])
        ->name('admin.citas.update');

    Route::get('/admin/citas/create', [AdminController::class, 'Admincreate'])
        ->name('admin.citas.create');
    //Roles
    Route::get('/admin/roles', [AdminController::class, 'rolesIndex'])
        ->name('admin.roles.index');
});

//Auditor
Route::middleware(['auth', 'role:2'])->group(function () {
    Route::get('/auditor', function () {
        return view('auditor.dashboard');

        //Solo son vistas!!
    })->name('auditor.dashboard');
    Route::get('/auditoria/logs', function () {
        return view('auditor.logs.index');
    })->name('auditor.logs.index');
    Route::get('/auditoria/citas', function () {
        return view('auditor.tables.citas');
    })->name('auditor.tables.citas');

    Route::get('/auditoria/pacientes', function () {
        return view('auditor.tables.pacientes');
    })->name('auditor.logs.index');

    Route::get('/auditoria/users', function () {
        return view('auditor.tables.users');
    })->name('auditor.tables.users  ');
});

//Recepcionista 
Route::middleware(['auth', 'role:3'])->group(function () {
    Route::get('/secretaria', function () {
        return view('recepcionista.dashboard'); // Asegúrate de tener esta vista creada
    })->name('recepcionista.home');

    //Perfil
    Route::get('/perfil/secretaria/editar', [RecepcionistaController::class, 'editProfile'])
        ->name('perfil.edit');

    Route::put('/perfil/secretaria/actualizar', [RecepcionistaController::class, 'updateProfile'])
        ->name('perfil.update');

    // 👥 Pacientes 
    Route::get('/secretaria/pacientes', [RecepcionistaController::class, 'pacientesIndex'])
        ->name('secretaria.pacientes.index');

    Route::get('/secretaria/pacientes/crear', [RecepcionistaController::class, 'pacientesCreate'])
        ->name('secretaria.pacientes.create');

    Route::post('/secretaria/pacientes', [RecepcionistaController::class, 'pacientesStore'])
        ->name('secretaria.pacientes.store');

    Route::put('/secretaria/pacientes/{paciente}', [RecepcionistaController::class, 'pacientesUpdate'])
        ->name('secretaria.pacientes.update');

    Route::get('/secretaria/pacientes/{paciente}/citas', [RecepcionistaController::class, 'pacientesCitas'])
        ->name('secretaria.pacientes.citas');
        
    //Citas
    Route::get('/secretaria/citas', [RecepcionistaController::class, 'citasIndex'])
        ->name('secretaria.citas.index');

    Route::post('/secretaria/citas', [RecepcionistaController::class, 'Adminstore'])
        ->name('secretaria.citas.store');

    Route::get('/secretaria/citas/{cita}/editar', [RecepcionistaController::class, 'citasEdit'])
        ->name('secretaria.citas.edit');

    Route::put('/secretaria/citas/{cita}', [RecepcionistaController::class, 'citasUpdate'])
        ->name('secretaria.citas.update');

    Route::get('/secretaria /citas/create', [RecepcionistaController::class, 'Admincreate'])
        ->name('secretaria.citas.create');
    //Roles


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

//Ruta Chat

Route::middleware(['auth'])->group(function () {

    Route::get('/citas/create', [CitaController::class, 'create'])
        ->name('citas.create');
    Route::post('/citas', [CitaController::class, 'store'])
        ->name('citas.store');
});
