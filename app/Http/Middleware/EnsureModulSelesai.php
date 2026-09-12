<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureModulSelesai
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::user()->materiSelesaiSemua()) {
            return redirect()->route('dashboard')
                ->with('status', 'Selesaikan semua materi terlebih dahulu untuk mengakses post-test.');
        }

        return $next($request);
    }
}
