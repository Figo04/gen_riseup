<?php

namespace Tests\Feature;

use App\Models\HasilKuesioner;
use App\Models\KalenderHaid;
use App\Models\ProgressModul;
use App\Models\Refleksi;
use App\Models\TrackerGizi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MassAssignmentUserIdTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_id_tidak_bisa_diisi_lewat_mass_assignment(): void
    {
        // Menirukan kode berbahaya yang mungkin ditulis nanti:
        // Model::create($request->all()) dengan user_id titipan penyerang.
        $model = [Refleksi::class, TrackerGizi::class, ProgressModul::class, KalenderHaid::class, HasilKuesioner::class];

        foreach ($model as $kelas) {
            $instance = new $kelas(['user_id' => 99]);

            $this->assertNull(
                $instance->user_id,
                "$kelas masih mengizinkan user_id diisi lewat mass assignment."
            );
        }
    }

    public function test_relasi_tetap_memaksa_kepemilikan_ke_pemiliknya(): void
    {
        $korban = User::factory()->create();
        $penyerang = User::factory()->create();

        $entri = $penyerang->kalenderHaid()->create([
            'user_id' => $korban->id,   // diabaikan: tidak fillable
            'tanggal_mulai' => '2026-09-01',
        ]);

        $this->assertSame($penyerang->id, $entri->user_id);
        $this->assertDatabaseMissing('kalender_haid', ['user_id' => $korban->id]);
    }
}
