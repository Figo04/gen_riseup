<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HasilKuesioner;
use App\Models\SubBagian;
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

        // Donut "Status Test": 3 bucket dari angka yang sudah dihitung di atas
        // (belum pretest → pretest saja → pre+post), tidak nambah query baru.
        $testStatus = [
            'Belum Pre-Test' => $totalRespondents - $preCompleted,
            'Pre-Test Saja' => $preCompleted - $prePostComplete,
            'Pre+Post Selesai' => $prePostComplete,
        ];

        // Donut "Progress Materi": user yang progress_modul-nya sudah menutupi
        // semua sub_bagian (pola sama User::materiSelesaiSemua(), versi agregat).
        $totalSubBagian = SubBagian::count();
        $materialCompleted = $totalSubBagian > 0
            ? User::whereHas('progressModul', fn ($q) => $q->where('materi_selesai', true), '>=', $totalSubBagian)->count()
            : 0;
        $materialStatus = [
            'Selesai Semua Materi' => $materialCompleted,
            'Belum Selesai' => $totalRespondents - $materialCompleted,
        ];

        $genderDistribution = User::selectRaw('jenis_kelamin, count(*) as total')
            ->groupBy('jenis_kelamin')
            ->pluck('total', 'jenis_kelamin');

        $ageDistribution = User::selectRaw('usia, count(*) as total')
            ->groupBy('usia')
            ->orderBy('usia')
            ->pluck('total', 'usia');

        return view('admin.dashboard', compact(
            'totalRespondents', 'preCompleted', 'postCompleted', 'prePostComplete', 'recentActivity',
            'testStatus', 'materialStatus', 'genderDistribution', 'ageDistribution'
        ));
    }
}
