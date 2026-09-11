<?php

namespace App\Http\Controllers;

use App\Models\KalenderHaid;
use App\Models\TrackerGizi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TrackerController extends Controller
{
    private const HARI = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

    private const KEBIASAAN = ['sayur_buah', 'protein', 'air_putih', 'camilan_sehat'];

    public function showGizi(): View
    {
        $tracker = Auth::user()->trackerGizi;

        return view('tracker.gizi', [
            'tracker' => $tracker,
            'hari' => self::HARI,
            'kebiasaan' => self::KEBIASAAN,
        ]);
    }

    public function storeGizi(Request $request): RedirectResponse
    {
        if (Auth::user()->trackerGizi()->where('is_locked', true)->exists()) {
            return redirect()->route('tracker-gizi.show')
                ->with('status', 'Tracker gizi sudah pernah dikirim dan tidak dapat diubah.');
        }

        $data = [];
        foreach (self::HARI as $hari) {
            foreach (self::KEBIASAAN as $item) {
                $data[$hari][$item] = $request->boolean("data.$hari.$item");
            }
        }

        TrackerGizi::create([
            'user_id' => Auth::id(),
            'data' => $data,
            'is_locked' => true,
            'submitted_at' => now(),
        ]);

        return redirect()->route('tracker-gizi.show')
            ->with('status', 'Tracker gizi berhasil dikirim.');
    }

    public function indexHaid(): View
    {
        $entri = Auth::user()->kalenderHaid()->orderByDesc('tanggal_mulai')->get();

        return view('tracker.kalender-haid.index', compact('entri'));
    }

    public function storeHaid(Request $request): RedirectResponse
    {
        $validated = $this->validateHaid($request);

        Auth::user()->kalenderHaid()->create($validated);

        return redirect()->route('kalender-haid.index')->with('status', 'Entri kalender haid ditambahkan.');
    }

    public function editHaid(KalenderHaid $kalenderHaid): View
    {
        abort_if($kalenderHaid->user_id !== Auth::id(), 403);

        return view('tracker.kalender-haid.edit', ['entri' => $kalenderHaid]);
    }

    public function updateHaid(Request $request, KalenderHaid $kalenderHaid): RedirectResponse
    {
        abort_if($kalenderHaid->user_id !== Auth::id(), 403);

        $kalenderHaid->update($this->validateHaid($request));

        return redirect()->route('kalender-haid.index')->with('status', 'Entri kalender haid diperbarui.');
    }

    public function destroyHaid(KalenderHaid $kalenderHaid): RedirectResponse
    {
        abort_if($kalenderHaid->user_id !== Auth::id(), 403);

        $kalenderHaid->delete();

        return redirect()->route('kalender-haid.index')->with('status', 'Entri kalender haid dihapus.');
    }

    private function validateHaid(Request $request): array
    {
        return $request->validate([
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'catatan' => 'nullable|string',
        ]);
    }
}
