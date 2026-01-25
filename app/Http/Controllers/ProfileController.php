<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show2FA()
    {
        $role = Auth::user()->rol;

        return match ($role) {
            0 => view('doctor.2fa'),
            1 => view('admin.2fa'),
            2 => view('auditor.2fa'),
            3 => view('recepcionista.2fa'),
            default => redirect()->back()
        };
    }

    public function enable2FA(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->two_factor_enabled) {
            return redirect()->back()->with('error', 'La autenticación de dos factores ya está habilitada.');
        }

        $user->two_factor_enabled = true;
        $user->save();

        return redirect()->back()->with('success', '¡Autenticación de dos factores habilitada! Se solicitará un código en tu próximo inicio de sesión.');
    }

    public function disable2FA(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->two_factor_enabled) {
            return redirect()->back()->with('error', 'La autenticación de dos factores no está habilitada.');
        }

        $user->two_factor_enabled = false;
        $user->resetTwoFactorCode();
        $user->save();

        return redirect()->back()->with('success', 'Autenticación de dos factores deshabilitada.');
    }
}
