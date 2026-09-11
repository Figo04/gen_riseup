<?php

namespace App\Http\Controllers;

use App\Models\Modul;
use App\Models\Refleksi;
use App\Models\SubBagian;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RefleksiController extends Controller
{
    public function show(Modul $modul, SubBagian $subBagian): View|RedirectResponse
    {
        abort_if($subBagian->modul_id !== $modul->id, 404);

        if (! $this->materiSelesai($subBagian)) {
            return redirect()->route('modul.sub-bagian.show', [$modul, $subBagian])
                ->with('status', 'Selesaikan materi terlebih dahulu untuk mengakses refleksi.');
        }

        $refleksi = Auth::user()->refleksi()->where('sub_bagian_id', $subBagian->id)->first();

        return view('modul.refleksi', compact('modul', 'subBagian', 'refleksi'));
    }

    public function store(Modul $modul, SubBagian $subBagian, Request $request): RedirectResponse
    {
        abort_if($subBagian->modul_id !== $modul->id, 404);

        if (! $this->materiSelesai($subBagian)) {
            return redirect()->route('modul.sub-bagian.show', [$modul, $subBagian])
                ->with('status', 'Selesaikan materi terlebih dahulu untuk mengakses refleksi.');
        }

        if ($this->sudahRefleksi($subBagian)) {
            return redirect()->route('modul.sub-bagian.refleksi', [$modul, $subBagian])
                ->with('status', 'Refleksi sudah pernah dikirim dan tidak dapat diubah.');
        }

        $request->validate(['jawaban' => 'required|string']);

        Refleksi::create([
            'user_id' => Auth::id(),
            'sub_bagian_id' => $subBagian->id,
            'jawaban' => $request->input('jawaban'),
            'is_locked' => true,
            'submitted_at' => now(),
        ]);

        return redirect()->route('modul.sub-bagian.refleksi', [$modul, $subBagian])
            ->with('status', 'Refleksi berhasil dikirim.');
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
