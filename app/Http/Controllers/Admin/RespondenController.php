<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubBagian;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

// View-only (PRD §4.2): daftar siswa + status pengerjaannya. Refleksi &
// tracker gizi TIDAK PERNAH di-query di sini (PRD §4.3 / CLAUDE.md rule #9).
class RespondenController extends Controller
{
    public function index(Request $request): View
    {
        $cari = $request->query('cari');

        $siswa = User::query()
            ->when($cari, fn ($q) => $q->where(fn ($w) => $w
                ->where('name', 'like', "%{$cari}%")
                ->orWhere('email', 'like', "%{$cari}%")
                ->orWhere('sekolah', 'like', "%{$cari}%")))
            // unique(user_id, tipe_sesi) → maks 2 baris per siswa, aman di-eager load.
            ->with('hasilKuesioner:id,user_id,tipe_sesi')
            ->withCount(['progressModul as materi_selesai_count' => fn ($q) => $q->where('materi_selesai', true)])
            ->orderBy('name')
            ->paginate(25)
            ->withQueryString();

        return view('admin.responden.index', [
            'siswa' => $siswa,
            'totalSubBagian' => SubBagian::count(),
            'cari' => $cari,
        ]);
    }
}
