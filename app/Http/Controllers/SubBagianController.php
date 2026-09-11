<?php

namespace App\Http\Controllers;

use App\Models\Modul;
use App\Models\ProgressModul;
use App\Models\SubBagian;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SubBagianController extends Controller
{
    public function show(Modul $modul, SubBagian $subBagian): View
    {
        abort_if($subBagian->modul_id !== $modul->id, 404);

        $sudahSelesai = Auth::user()->progressModul()
            ->where('sub_bagian_id', $subBagian->id)
            ->where('materi_selesai', true)
            ->exists();

        return view('modul.materi', compact('modul', 'subBagian', 'sudahSelesai'));
    }

    public function selesai(Modul $modul, SubBagian $subBagian): RedirectResponse
    {
        abort_if($subBagian->modul_id !== $modul->id, 404);

        ProgressModul::updateOrCreate(
            ['user_id' => Auth::id(), 'sub_bagian_id' => $subBagian->id],
            ['materi_selesai' => true, 'materi_selesai_at' => now()],
        );

        return redirect()->route('modul.show', $modul)->with('status', 'Materi ditandai selesai.');
    }
}
