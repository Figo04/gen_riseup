<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsurePretestSubmitted
{
    public function handle(Request $request, Closure $next): Response
    {
        $sudahPretest = Auth::user()->hasilKuesioner()->where('tipe_sesi', 'pre')->exists();

        if (! $sudahPretest) {
            return redirect()->route('kuesioner.pretest.create')
                ->with('status', 'Selesaikan pre-test terlebih dahulu untuk mengakses materi.');
        }

        return $next($request);
    }
}
