<?php

namespace App\Http\Controllers;

use App\Models\HasilKuesioner;
use App\Models\HasilKuesionerDetail;
use App\Models\KuesionerSoal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PretestController extends Controller
{
    private const KATEGORI_SIKAP_FAVORABLE = ['SS' => 4, 'S' => 3, 'TS' => 2, 'STS' => 1];

    private const KATEGORI_SIKAP_UNFAVORABLE = ['SS' => 1, 'S' => 2, 'TS' => 3, 'STS' => 4];

    public function create(): View|RedirectResponse
    {
        if ($this->sudahMengisi()) {
            return redirect()->route('dashboard')->with('status', 'Pre-test sudah pernah diisi dan tidak dapat diisi ulang.');
        }

        $soal = KuesionerSoal::orderBy('tipe')->orderBy('urutan')->get();

        return view('pretest.create', [
            'pengetahuan' => $soal->where('tipe', 'pengetahuan'),
            'sikap' => $soal->where('tipe', 'sikap'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        if ($this->sudahMengisi()) {
            return redirect()->route('dashboard')->with('status', 'Pre-test sudah pernah diisi dan tidak dapat diisi ulang.');
        }

        $soal = KuesionerSoal::orderBy('urutan')->get()->keyBy('id');

        $request->validate(array_merge(
            $soal->where('tipe', 'pengetahuan')->mapWithKeys(fn ($s) => ["jawaban.$s->id" => 'required|in:B,S'])->all(),
            $soal->where('tipe', 'sikap')->mapWithKeys(fn ($s) => ["jawaban.$s->id" => 'required|in:SS,S,TS,STS'])->all(),
        ));

        $jawaban = $request->input('jawaban');

        $benar = 0;
        $skorSikap = 0;
        $detail = [];
        $now = now();

        foreach ($soal as $s) {
            $isian = $jawaban[$s->id];

            if ($s->tipe === 'pengetahuan') {
                if ($isian === $s->jawaban_benar) {
                    $benar++;
                }
            } else {
                $peta = $s->reverse_scored ? self::KATEGORI_SIKAP_UNFAVORABLE : self::KATEGORI_SIKAP_FAVORABLE;
                $skorSikap += $peta[$isian];
            }

            $detail[] = [
                'soal_id' => $s->id,
                'jawaban_siswa' => $isian,
            ];
        }

        $skorPengetahuan = round($benar / $soal->where('tipe', 'pengetahuan')->count() * 100, 2);
        $kategoriPengetahuan = match (true) {
            $skorPengetahuan >= 76 => 'Baik',
            $skorPengetahuan >= 56 => 'Cukup',
            default => 'Kurang',
        };

        DB::transaction(function () use ($detail, $skorPengetahuan, $kategoriPengetahuan, $skorSikap, $now) {
            $hasil = HasilKuesioner::create([
                'user_id' => Auth::id(),
                'tipe_sesi' => 'pre',
                'skor_pengetahuan' => $skorPengetahuan,
                'kategori_pengetahuan' => $kategoriPengetahuan,
                'skor_sikap' => $skorSikap,
                'submitted_at' => $now,
            ]);

            foreach ($detail as $d) {
                $d['hasil_kuesioner_id'] = $hasil->id;
                HasilKuesionerDetail::create($d);
            }
        });

        return redirect()->route('dashboard')->with('status', 'Pre-test berhasil dikirim.');
    }

    private function sudahMengisi(): bool
    {
        return Auth::user()->hasilKuesioner()->where('tipe_sesi', 'pre')->exists();
    }
}
