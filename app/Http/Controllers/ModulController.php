<?php

namespace App\Http\Controllers;

use App\Models\Modul;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ModulController extends Controller
{
    public function index(): View
    {
        $modul = Modul::with(['subBagian'])->orderBy('urutan')->get();
        $selesaiIds = Auth::user()->progressModul()->where('materi_selesai', true)->pluck('sub_bagian_id');

        return view('modul.index', compact('modul', 'selesaiIds'));
    }

    public function show(Modul $modul): View
    {
        $modul->load(['subBagian']);
        $selesaiIds = Auth::user()->progressModul()->where('materi_selesai', true)->pluck('sub_bagian_id');

        return view('modul.show', compact('modul', 'selesaiIds'));
    }
}
