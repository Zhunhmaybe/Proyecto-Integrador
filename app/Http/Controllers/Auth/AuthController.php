<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\LoginNotification;
use App\Notifications\TwoFactorCodeNotification;
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
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return redirect()->back()
                ->withErrors(['email' => 'Las credenciales no coinciden con nuestros registros.'])
                ->withInput();
        }

        // Verificar si el usuario está activo
        if ($user->estado != 1) {
            return redirect()->back()
                ->withErrors(['email' => 'Tu cuenta está inactiva. Contacta al administrador.'])
                ->withInput();
        }

        // Si tiene 2FA habilitado, generar código
        if ($user->two_factor_enabled) {
            $code = $user->generateTwoFactorCode();

            // Enviar código por email
            try {
                $user->notify(new TwoFactorCodeNotification($code));
            } catch (\Exception $e) {
                Log::error('Error al enviar código 2FA: ' . $e->getMessage());
            }

            // Guardar el ID del usuario en sesión temporal
            $request->session()->put('2fa:user:id', $user->id);
            $request->session()->put('2fa:remember', $request->filled('remember'));

            return redirect()->route('2fa.verify');
        }

        // Login normal sin 2FA
        Auth::login($user, $request->filled('remember'));
        $request->session()->regenerate();

        // Enviar notificación de login
        try {
            $loginTime = Carbon::now()->format('d/m/Y H:i:s');
            $ipAddress = $request->ip();
            $user->notify(new LoginNotification($loginTime, $ipAddress));
        } catch (\Exception $e) {
            Log::error('Error al enviar notificación de login: ' . $e->getMessage());
        }

        return redirect()->intended('/home');
    }

    // Mostrar formulario de verificación 2FA
    public function showTwoFactorForm(Request $request)
    {
        if (!$request->session()->has('2fa:user:id')) {
            return redirect()->route('login');
        }

        return view('auth.two-factor');
    }

    // Verificar código 2FA
    public function verifyTwoFactor(Request $request)
    {
        $request->validate([
            'code' => 'required|digits:6',
        ], [
            'code.required' => 'El código es obligatorio',
            'code.digits' => 'El código debe tener 6 dígitos',
        ]);

        $userId = $request->session()->get('2fa:user:id');
        $user = User::find($userId);

        if (!$user) {
            return redirect()->route('login')
                ->withErrors(['code' => 'Sesión expirada. Por favor, inicia sesión nuevamente.']);
        }

        if (!$user->validateTwoFactorCode($request->code)) {
            return redirect()->back()
                ->withErrors(['code' => 'Código inválido o expirado.'])
                ->withInput();
        }

        // Código válido, limpiar y hacer login
        $user->resetTwoFactorCode();
        $remember = $request->session()->get('2fa:remember', false);

        Auth::login($user, $remember);

        $request->session()->forget(['2fa:user:id', '2fa:remember']);
        $request->session()->regenerate();

        // Enviar notificación de login
        try {
            $loginTime = Carbon::now()->format('d/m/Y H:i:s');
            $ipAddress = $request->ip();
            $user->notify(new LoginNotification($loginTime, $ipAddress));
        } catch (\Exception $e) {
            Log::error('Error al enviar notificación de login: ' . $e->getMessage());
        }

        return redirect()->intended('/home');
    }

    // Reenviar código 2FA
    public function resendTwoFactorCode(Request $request)
    {
        $userId = $request->session()->get('2fa:user:id');
        $user = User::find($userId);

        if (!$user) {
            return redirect()->route('login')
                ->withErrors(['code' => 'Sesión expirada.']);
        }

        $code = $user->generateTwoFactorCode();

        try {
            $user->notify(new TwoFactorCodeNotification($code));
            return redirect()->back()->with('success', 'Código reenviado correctamente.');
        } catch (\Exception $e) {
            Log::error('Error al reenviar código 2FA: ' . $e->getMessage());
            return redirect()->back()->withErrors(['code' => 'Error al enviar el código.']);
        }
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
            'rol' => $request->rol ?? 3,
            'estado' => 1,
            'two_factor_enabled' => false, // 2FA deshabilitado por defecto
        ]);

        Auth::login($user);

        // Enviar notificación de registro
        try {
            $loginTime = Carbon::now()->format('d/m/Y H:i:s');
            $ipAddress = $request->ip();
            $user->notify(new LoginNotification($loginTime, $ipAddress));
        } catch (\Exception $e) {
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
