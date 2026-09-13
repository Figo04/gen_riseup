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

    public function test_form_merender_28_checkbox_dan_terkunci_setelah_dikirim(): void
    {
        $user = $this->siswaSudahPretest();

        // 7 hari x 4 kebiasaan. Checkbox disembunyikan (peer sr-only), jadi
        // name-nya gampang rusak tanpa ketahuan.
        // Regex, bukan substr: class "peer-disabled:..." juga mengandung kata "disabled".
        $inputTerkunci = '/<input type="checkbox" name="data\[[^\]]+\]\[[^\]]+\]"[^>]*\sdisabled/';

        $sebelum = $this->actingAs($user)->get(route('tracker-gizi.show'))->assertOk();
        $this->assertSame(28, substr_count($sebelum->getContent(), 'type="checkbox" name="data['));
        $this->assertSame(0, preg_match_all($inputTerkunci, $sebelum->getContent()));

        $this->actingAs($user)->post(route('tracker-gizi.store'), ['data' => ['Senin' => ['protein' => '1']]]);

        // ->fresh(): actingAs memakai ulang instance User yang sama, dan relasi
        // trackerGizi sudah ter-cache null dari GET pertama (artefak test, bukan bug app).
        $sesudah = $this->actingAs($user->fresh())->get(route('tracker-gizi.show'))->assertOk();
        $this->assertSame(28, substr_count($sesudah->getContent(), 'type="checkbox" name="data['));
        $this->assertSame(28, preg_match_all($inputTerkunci, $sesudah->getContent()));
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
