<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KalenderHaid;
use Illuminate\View\View;

// View-only (PRD §4.2): admin cuma melihat riwayat, tidak ada tambah/edit/hapus
// di sini. Refleksi & tracker gizi TIDAK PERNAH di-query (PRD §4.3/§7 privacy).
class KalenderHaidController extends Controller
{
    public function index(): View
    {
        return view('admin.kalender-haid.index', [
            'entri' => KalenderHaid::with('user')->orderByDesc('tanggal_mulai')->get(),
        ]);
    }
}
