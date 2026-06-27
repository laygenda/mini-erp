<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // 1. Pastikan pengguna sudah login
        if (!auth()->check()) {
            return redirect('/login');
        }

        // 2. Cek apakah role pengguna cocok dengan role yang disyaratkan oleh URL
        if (auth()->user()->role !== $role) {
            // Jika tidak cocok, lemparkan error 403 (Akses Dilarang)
            abort(403, 'Akses Ditolak: Anda tidak memiliki izin untuk halaman ini.');
        }

        // 3. Jika cocok, silakan masuk ke halaman tujuan
        return $next($request);
    }
}