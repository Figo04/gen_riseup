<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HasilKuesioner;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalRespondents = User::count();
        // unique(user_id, tipe_sesi) di hasil_kuesioner: 1 user = maks 1 baris per tipe,
        // jadi count() di sini sudah otomatis "jumlah siswa", tak perlu distinct().
        $preCompleted = HasilKuesioner::where('tipe_sesi', 'pre')->count();
        $postCompleted = HasilKuesioner::where('tipe_sesi', 'post')->count();
        $prePostComplete = User::whereHas('hasilKuesioner', fn ($q) => $q->where('tipe_sesi', 'pre'))
            ->whereHas('hasilKuesioner', fn ($q) => $q->where('tipe_sesi', 'post'))
            ->count();

        $recentActivity = HasilKuesioner::with('user')
            ->latest('submitted_at')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalRespondents', 'preCompleted', 'postCompleted', 'prePostComplete', 'recentActivity'
        ));
    }
}
