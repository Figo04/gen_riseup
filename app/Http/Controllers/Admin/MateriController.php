<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Modul;
use App\Models\SubBagian;
use Illuminate\View\View;

// View-only (PRD §4.2/§4.3): materi tetap hardcoded developer, admin cuma
// melihat, tidak ada create/edit/delete di sini.
class MateriController extends Controller
{
    public function index(): View
    {
        return view('admin.materi.index', [
            'modul' => Modul::withCount('subBagian')->orderBy('urutan')->get(),
        ]);
    }

    public function show(Modul $modul): View
    {
        return view('admin.materi.show', [
            'modul' => $modul,
            'subBagian' => $modul->subBagian,
        ]);
    }

    public function subBagian(Modul $modul, SubBagian $subBagian): View
    {
        abort_if($subBagian->modul_id !== $modul->id, 404);

        return view('admin.materi.sub-bagian', [
            'modul' => $modul,
            'subBagian' => $subBagian,
        ]);
    }
}
