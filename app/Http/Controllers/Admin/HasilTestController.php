<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

// View-only (PRD §4.2): skor pre vs post per siswa, bentuknya sama dengan
// ExportController supaya layar dan file CSV tidak pernah beda isi.
// Refleksi & tracker gizi TIDAK PERNAH di-query (PRD §4.3 / CLAUDE.md rule #9).
class HasilTestController extends Controller
{
    public function index(Request $request): View
    {
        $cari = $request->query('cari');

        $siswa = User::query()
            // Hanya siswa yang sudah pernah submit — yang belum ada di halaman Responden.
            ->whereHas('hasilKuesioner')
            ->when($cari, fn ($q) => $q->where(fn ($w) => $w
                ->where('name', 'like', "%{$cari}%")
                ->orWhere('email', 'like', "%{$cari}%")
                ->orWhere('sekolah', 'like', "%{$cari}%")))
            ->with('hasilKuesioner')
            ->orderBy('name')
            ->paginate(25)
            ->withQueryString();

        return view('admin.hasil-test.index', [
            'siswa' => $siswa,
            'cari' => $cari,
        ]);
    }
}
