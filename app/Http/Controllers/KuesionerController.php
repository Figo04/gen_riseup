<?php

namespace App\Http\Controllers;

use App\Models\HasilKuesionerDetail;
use App\Models\KuesionerSoal;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class KuesionerController extends Controller
{
    private const KATEGORI_SIKAP_FAVORABLE = ['SS' => 4, 'S' => 3, 'TS' => 2, 'STS' => 1];

    private const KATEGORI_SIKAP_UNFAVORABLE = ['SS' => 1, 'S' => 2, 'TS' => 3, 'STS' => 4];

    private const PESAN_SUDAH_MENGISI = [
        'pre' => 'Pre-test sudah pernah diisi dan tidak dapat diisi ulang.',
        'post' => 'Post-test sudah pernah diisi dan tidak dapat diisi ulang.',
    ];

    public function createPre(): View|RedirectResponse
    {
        return $this->create('pre');
    }

    public function storePre(Request $request): RedirectResponse
    {
        return $this->store($request, 'pre');
    }

    public function createPost(): View|RedirectResponse
    {
        return $this->create('post');
    }

    public function storePost(Request $request): RedirectResponse
    {
        return $this->store($request, 'post');
    }

    private function create(string $tipeSesi): View|RedirectResponse
    {
        if ($this->sudahMengisi($tipeSesi)) {
            return redirect()->route('dashboard')->with('status', self::PESAN_SUDAH_MENGISI[$tipeSesi]);
        }

        $soal = KuesionerSoal::where('is_aktif', true)->orderBy('tipe')->orderBy('urutan')->get();

        return view("kuesioner.{$tipeSesi}test", [
            'pengetahuan' => $soal->where('tipe', 'pengetahuan'),
            'sikap' => $soal->where('tipe', 'sikap'),
        ]);
    }

    private function store(Request $request, string $tipeSesi): RedirectResponse
    {
        if ($this->sudahMengisi($tipeSesi)) {
            return redirect()->route('dashboard')->with('status', self::PESAN_SUDAH_MENGISI[$tipeSesi]);
        }

        $soal = KuesionerSoal::where('is_aktif', true)->orderBy('urutan')->get()->keyBy('id');

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

        try {
            DB::transaction(function () use ($tipeSesi, $detail, $skorPengetahuan, $kategoriPengetahuan, $skorSikap, $now) {
                $hasil = Auth::user()->hasilKuesioner()->create([
                    'tipe_sesi' => $tipeSesi,
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
        } catch (UniqueConstraintViolationException) {
            // Dua submit paralel (double-click / dua tab): unique (user_id, tipe_sesi)
            // menolak yang kedua. Cek di awal method cuma fast-path, bukan jaminan.
            return redirect()->route('dashboard')->with('status', self::PESAN_SUDAH_MENGISI[$tipeSesi]);
        }

        $pesanSukses = $tipeSesi === 'pre'
            ? 'Pre-test berhasil dikirim.'
            : 'Post-test berhasil dikirim. Terima kasih sudah mengikuti seluruh rangkaian program.';

        return redirect()->route('dashboard')->with('status', $pesanSukses);
    }

    private function sudahMengisi(string $tipeSesi): bool
    {
        return Auth::user()->hasilKuesioner()->where('tipe_sesi', $tipeSesi)->exists();
    }
}
