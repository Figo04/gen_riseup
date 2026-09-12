<?php

namespace Tests\Feature;

use App\Models\KuesionerSoal;
use App\Models\Modul;
use App\Models\ProgressModul;
use App\Models\SubBagian;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PosttestTest extends TestCase
{
    use RefreshDatabase;

    /** Sama seperti PretestTest::seedSoal — 4 soal pengetahuan + 4 soal sikap. */
    private function seedSoal(): void
    {
        foreach (['B', 'S', 'B', 'S'] as $i => $kunci) {
            KuesionerSoal::create(['tipe' => 'pengetahuan', 'pertanyaan' => "Soal pengetahuan $i", 'jawaban_benar' => $kunci, 'urutan' => $i + 1]);
        }

        foreach ([false, false, true, true] as $i => $reverse) {
            KuesionerSoal::create(['tipe' => 'sikap', 'pertanyaan' => "Soal sikap $i", 'reverse_scored' => $reverse, 'urutan' => $i + 1]);
        }
    }

    /** @param  array<string>  $isian */
    private function kirimPretest(User $user, array $isian): void
    {
        $jawaban = KuesionerSoal::orderBy('id')->pluck('id')->combine($isian)->all();

        $this->actingAs($user)->post(route('kuesioner.pretest.store'), ['jawaban' => $jawaban]);
    }

    /** @param  array<string>  $isian */
    private function kirimPosttest(User $user, array $isian): \Illuminate\Testing\TestResponse
    {
        $jawaban = KuesionerSoal::orderBy('id')->pluck('id')->combine($isian)->all();

        return $this->actingAs($user)->post(route('kuesioner.posttest.store'), ['jawaban' => $jawaban]);
    }

    private function seedSatuSubBagian(): SubBagian
    {
        $modul = Modul::create(['nama' => 'Modul Tes', 'slug' => 'modul-tes', 'urutan' => 1]);

        return SubBagian::create(['modul_id' => $modul->id, 'judul' => 'Bab 1', 'konten_view' => 'modul.kejar-mimpi.mimpi-itu-apa', 'urutan' => 1]);
    }

    private function tandaiSemuaMateriSelesai(User $user): void
    {
        $subBagian = $this->seedSatuSubBagian();

        ProgressModul::create(['user_id' => $user->id, 'sub_bagian_id' => $subBagian->id, 'materi_selesai' => true, 'materi_selesai_at' => now()]);
    }

    public function test_posttest_terkunci_sebelum_semua_materi_selesai(): void
    {
        $this->seedSoal();
        $user = User::factory()->create();
        $this->kirimPretest($user, ['B', 'S', 'B', 'S', 'SS', 'SS', 'SS', 'SS']);
        $this->seedSatuSubBagian(); // ada sub-bagian, tapi belum ditandai selesai oleh user

        $response = $this->actingAs($user)->get(route('kuesioner.posttest.create'));

        $response->assertRedirect(route('dashboard'));
        $this->assertDatabaseMissing('hasil_kuesioner', ['user_id' => $user->id, 'tipe_sesi' => 'post']);
    }

    public function test_posttest_bisa_diakses_dan_diskor_setelah_semua_materi_selesai(): void
    {
        $this->seedSoal();
        $user = User::factory()->create();
        $this->kirimPretest($user, ['B', 'S', 'B', 'S', 'SS', 'SS', 'SS', 'SS']);
        $this->tandaiSemuaMateriSelesai($user);

        $this->actingAs($user)->get(route('kuesioner.posttest.create'))->assertOk();

        $this->kirimPosttest($user, ['B', 'S', 'B', 'S', 'SS', 'SS', 'SS', 'SS']);

        $this->assertDatabaseHas('hasil_kuesioner', [
            'user_id' => $user->id,
            'tipe_sesi' => 'post',
            'skor_pengetahuan' => 100.00,
            'kategori_pengetahuan' => 'Baik',
        ]);
    }

    public function test_posttest_submit_kedua_ditolak_backend(): void
    {
        $this->seedSoal();
        $user = User::factory()->create();
        $this->kirimPretest($user, ['B', 'S', 'B', 'S', 'SS', 'SS', 'SS', 'SS']);
        $this->tandaiSemuaMateriSelesai($user);
        $this->kirimPosttest($user, ['B', 'S', 'B', 'S', 'SS', 'SS', 'SS', 'SS']);

        $response = $this->kirimPosttest($user, ['S', 'B', 'S', 'B', 'STS', 'STS', 'STS', 'STS']);

        $response->assertRedirect(route('dashboard'));
        $this->assertDatabaseCount('hasil_kuesioner', 2); // 1 pre + 1 post, submit post kedua ditolak
        $this->assertDatabaseHas('hasil_kuesioner', ['user_id' => $user->id, 'tipe_sesi' => 'post', 'skor_pengetahuan' => 100.00]);
    }

    public function test_pretest_dan_posttest_tidak_saling_menghalangi_karena_tipe_sesi_beda(): void
    {
        $this->seedSoal();
        $user = User::factory()->create();
        $this->kirimPretest($user, ['B', 'S', 'B', 'S', 'SS', 'SS', 'SS', 'SS']);
        $this->tandaiSemuaMateriSelesai($user);

        $this->kirimPosttest($user, ['B', 'S', 'B', 'S', 'SS', 'SS', 'SS', 'SS']);

        $this->assertDatabaseCount('hasil_kuesioner', 2);
        $this->assertDatabaseHas('hasil_kuesioner', ['user_id' => $user->id, 'tipe_sesi' => 'pre']);
        $this->assertDatabaseHas('hasil_kuesioner', ['user_id' => $user->id, 'tipe_sesi' => 'post']);
    }
}
