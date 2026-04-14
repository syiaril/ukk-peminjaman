<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

// Middleware untuk mengontrol akses berdasarkan peran pengguna
class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Peran cocok, lanjutkan
        if (in_array($user->peran, $roles)) {
            return $next($request);
        }

        // Peran tidak cocok, redirect ke dashboard masing-masing
        if ($user->peran === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->peran === 'petugas') {
            return redirect()->route('petugas.dashboard');
        } else {
            return redirect()->route('peminjam.dashboard');
        }
    }
}
