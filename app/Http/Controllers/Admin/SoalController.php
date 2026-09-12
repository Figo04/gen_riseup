<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KuesionerSoal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SoalController extends Controller
{
    public function index(): View
    {
        $soal = KuesionerSoal::where('is_aktif', true)->orderBy('tipe')->orderBy('urutan')->get();

        return view('admin.soal.index', [
            'pengetahuan' => $soal->where('tipe', 'pengetahuan'),
            'sikap' => $soal->where('tipe', 'sikap'),
        ]);
    }

    public function create(): View
    {
        return view('admin.soal.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        $data['urutan'] = KuesionerSoal::where('tipe', $data['tipe'])->max('urutan') + 1;

        KuesionerSoal::create($data);

        return redirect()->route('admin.soal.index')->with('status', 'Soal berhasil ditambahkan.');
    }

    public function edit(KuesionerSoal $soal): View
    {
        return view('admin.soal.edit', ['soal' => $soal]);
    }

    public function update(Request $request, KuesionerSoal $soal): RedirectResponse
    {
        // Ubah jawaban_benar/reverse_scored di sini TIDAK menghitung ulang skor
        // hasil_kuesioner yang sudah tersimpan (kunci baru hanya berlaku ke
        // submission berikutnya) — dikonfirmasi via interview Sesi 24.
        $soal->update($this->validated($request, $soal->tipe));

        return redirect()->route('admin.soal.index')->with('status', 'Soal berhasil diperbarui.');
    }

    public function destroy(KuesionerSoal $soal): RedirectResponse
    {
        // Soft delete via is_aktif, bukan hapus baris: FK hasil_kuesioner_detail.soal_id
        // tidak boleh putus supaya jawaban historis siswa tetap aman.
        $soal->update(['is_aktif' => false]);

        return redirect()->route('admin.soal.index')->with('status', 'Soal berhasil dihapus.');
    }

    /** @return array<string, mixed> */
    private function validated(Request $request, ?string $tipeLama = null): array
    {
        $tipe = $tipeLama ?? $request->input('tipe');

        $rules = [
            'pertanyaan' => 'required|string',
            'jawaban_benar' => $tipe === 'pengetahuan' ? 'required|in:B,S' : 'prohibited',
            'reverse_scored' => $tipe === 'sikap' ? 'boolean' : 'prohibited',
        ];

        if (! $tipeLama) {
            $rules['tipe'] = 'required|in:pengetahuan,sikap';
        }

        $data = $request->validate($rules);

        if ($tipe === 'pengetahuan') {
            $data['reverse_scored'] = false;
        } else {
            $data['jawaban_benar'] = null;
            $data['reverse_scored'] = $request->boolean('reverse_scored');
        }

        return $data;
    }
}
