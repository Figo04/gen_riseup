<?php

namespace App\Http\Controllers;

use App\Models\Modul;
use App\Models\SubBagian;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RefleksiController extends Controller
{
    public function show(Modul $modul, SubBagian $subBagian): View|RedirectResponse
    {
        $lembar = $this->lembar($modul, $subBagian);

        if (! $this->materiSelesai($subBagian)) {
            return redirect()->route('modul.sub-bagian.show', [$modul, $subBagian])
                ->with('status', 'Selesaikan materi terlebih dahulu untuk mengakses refleksi.');
        }

        $refleksi = Auth::user()->refleksi()->where('sub_bagian_id', $subBagian->id)->first();

        return view('modul.refleksi', compact('modul', 'subBagian', 'refleksi', 'lembar'));
    }

    public function store(Modul $modul, SubBagian $subBagian, Request $request): RedirectResponse
    {
        $lembar = $this->lembar($modul, $subBagian);

        if (! $this->materiSelesai($subBagian)) {
            return redirect()->route('modul.sub-bagian.show', [$modul, $subBagian])
                ->with('status', 'Selesaikan materi terlebih dahulu untuk mengakses refleksi.');
        }

        if ($this->sudahRefleksi($subBagian)) {
            return redirect()->route('modul.sub-bagian.refleksi', [$modul, $subBagian])
                ->with('status', 'Refleksi sudah pernah dikirim dan tidak dapat diubah.');
        }

        $request->validate([
            'pertanyaan' => 'required|array|size:'.count($lembar['pertanyaan']),
            'pertanyaan.*' => 'required|string|max:2000',
            'tabel' => 'nullable|array',
            'tabel.*' => 'array',
            'tabel.*.*' => 'nullable|string|max:255',
        ]);

        Auth::user()->refleksi()->create([
            'sub_bagian_id' => $subBagian->id,
            'jawaban' => [
                'pertanyaan' => $request->input('pertanyaan'),
                'tabel' => $this->barisTerisi($request, $lembar),
            ],
            'is_locked' => true,
            'submitted_at' => now(),
        ]);

        return redirect()->route('modul.sub-bagian.refleksi', [$modul, $subBagian])
            ->with('status', 'Refleksi berhasil dikirim.');
    }

    /**
     * Lembar refleksi modul ini; 404 kalau sub-bagian bukan yang terakhir.
     *
     * @return array<string, mixed>
     */
    private function lembar(Modul $modul, SubBagian $subBagian): array
    {
        abort_if($subBagian->modul_id !== $modul->id, 404);
        abort_if(! $subBagian->adalahTerakhir(), 404);

        return config("refleksi.{$modul->slug}") ?? abort(404);
    }

    /**
     * Buang baris worksheet yang seluruh kolomnya dikosongkan siswa.
     *
     * @param  array<string, mixed>  $lembar
     * @return array<int, array<int, string>>
     */
    private function barisTerisi(Request $request, array $lembar): array
    {
        if (! isset($lembar['tabel'])) {
            return [];
        }

        return collect($request->input('tabel', []))
            ->map(fn (array $baris) => array_map(fn ($sel) => trim((string) $sel), $baris))
            ->reject(fn (array $baris) => implode('', $baris) === '')
            ->values()
            ->all();
    }

    private function materiSelesai(SubBagian $subBagian): bool
    {
        return Auth::user()->progressModul()
            ->where('sub_bagian_id', $subBagian->id)
            ->where('materi_selesai', true)
            ->exists();
    }

    private function sudahRefleksi(SubBagian $subBagian): bool
    {
        return Auth::user()->refleksi()->where('sub_bagian_id', $subBagian->id)->exists();
    }
}
