<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class IdleTimeout
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {

            $lastActivity = session('lastActivityTime');

            if ($lastActivity) {
                if (Carbon::now()->diffInSeconds($lastActivity) > 120) {
                    Auth::logout();
                    session()->invalidate();
                    session()->regenerateToken();

                    return redirect()->route('login')
                        ->with('session_expired', true);
                }
            }

            session(['lastActivityTime' => Carbon::now()]);
        }

        return $next($request);
    }
}
