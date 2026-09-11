<?php

namespace App\Http\Controllers;

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
}
