<?php

namespace Tests\Feature;

use App\Models\HasilKuesioner;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TrackerGiziTest extends TestCase
{
    use RefreshDatabase;

    private function siswaSudahPretest(): User
    {
        $user = User::factory()->create();
        HasilKuesioner::create(['user_id' => $user->id, 'tipe_sesi' => 'pre', 'skor_pengetahuan' => 80, 'kategori_pengetahuan' => 'Baik', 'skor_sikap' => 50, 'submitted_at' => now()]);

        return $user;
    }

    public function test_redirect_ke_pretest_kalau_belum_pretest(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('tracker-gizi.show'));

        $response->assertRedirect(route('kuesioner.pretest.create'));
    }

    public function test_submit_tracker_sukses_dan_data_ternormalisasi(): void
    {
        $user = $this->siswaSudahPretest();

        $response = $this->actingAs($user)->post(route('tracker-gizi.store'), [
            'data' => ['Senin' => ['sayur_buah' => '1', 'protein' => '1']],
        ]);

        $response->assertRedirect(route('tracker-gizi.show'));
        $this->assertDatabaseHas('tracker_gizi', ['user_id' => $user->id, 'is_locked' => true]);
        $tracker = $user->trackerGizi;
        $this->assertTrue($tracker->data['Senin']['sayur_buah']);
        $this->assertFalse($tracker->data['Senin']['air_putih']);
        $this->assertFalse($tracker->data['Minggu']['camilan_sehat']);
    }

    public function test_submit_kedua_kali_ditolak_backend_tidak_mengubah_data(): void
    {
        $user = $this->siswaSudahPretest();
        $this->actingAs($user)->post(route('tracker-gizi.store'), ['data' => ['Senin' => ['sayur_buah' => '1']]]);

        $this->actingAs($user)->post(route('tracker-gizi.store'), ['data' => ['Senin' => ['sayur_buah' => '0']]]);

        $this->assertDatabaseCount('tracker_gizi', 1);
        $this->assertTrue($user->trackerGizi->fresh()->data['Senin']['sayur_buah']);
    }
}
