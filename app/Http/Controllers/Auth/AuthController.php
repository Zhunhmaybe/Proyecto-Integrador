<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\LoginNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AuthController extends Controller
{
    // Mostrar formulario de login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Procesar login
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required' => 'El email es obligatorio',
            'email.email' => 'Debe ser un email válido',
            'password.required' => 'La contraseña es obligatoria',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $user = Auth::user();

            // Verificar si el usuario está activo
            if ($user->estado != 1) {
                Auth::logout();
                return redirect()->back()
                    ->withErrors(['email' => 'Tu cuenta está inactiva. Contacta al administrador.'])
                    ->withInput();
            }

            $request->session()->regenerate();

            // Enviar notificación por email de forma segura
            try {
                $loginTime = Carbon::now()->format('d/m/Y H:i:s');
                $ipAddress = $request->ip();
                $user->notify(new LoginNotification($loginTime, $ipAddress));
            } catch (\Exception $e) {
                // Registrar el error pero no bloquear el login
                Log::error('Error al enviar notificación de login: ' . $e->getMessage());
            }

            return redirect()->intended('/home');
        }

        return redirect()->back()
            ->withErrors(['email' => 'Las credenciales no coinciden con nuestros registros.'])
            ->withInput();
    }

    // Mostrar formulario de registro
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // Procesar registro
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:100',
            'tel' => 'nullable|string|max:20',
            'email' => 'required|string|email|max:100|unique:usuarios,email',
            'password' => 'required|string|min:6|confirmed',
            'rol' => 'nullable|integer|in:1,2,3',
        ], [
            'nombre.required' => 'El nombre es obligatorio',
            'email.required' => 'El email es obligatorio',
            'email.email' => 'Debe ser un email válido',
            'email.unique' => 'Este email ya está registrado',
            'password.required' => 'La contraseña es obligatoria',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres',
            'password.confirmed' => 'Las contraseñas no coinciden',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::create([
            'nombre' => $request->nombre,
            'tel' => $request->tel,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rol' => $request->rol ?? 3, // Por defecto Usuario
            'estado' => 1, // Activo por defecto
        ]);

        Auth::login($user);

        // Enviar notificación de registro de forma segura
        try {
            $loginTime = Carbon::now()->format('d/m/Y H:i:s');
            $ipAddress = $request->ip();
            $user->notify(new LoginNotification($loginTime, $ipAddress));
        } catch (\Exception $e) {
            // Registrar el error pero no bloquear el registro
            Log::error('Error al enviar notificación de registro: ' . $e->getMessage());
        }

        return redirect('/home');
    }

    // Cerrar sesión
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
