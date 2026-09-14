<?php

namespace Tests\Feature;

use App\Models\KuesionerSoal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PretestTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 4 soal pengetahuan (kunci B,S,B,S) + 4 soal sikap (2 favorable, 2 reverse).
     * Sengaja bukan 22+20 supaya angka harapannya bisa dihitung tangan di test.
     */
    private function seedSoal(): void
    {
        foreach (['B', 'S', 'B', 'S'] as $i => $kunci) {
            KuesionerSoal::create(['tipe' => 'pengetahuan', 'pertanyaan' => "Soal pengetahuan $i", 'jawaban_benar' => $kunci, 'urutan' => $i + 1]);
        }

        foreach ([false, false, true, true] as $i => $reverse) {
            KuesionerSoal::create(['tipe' => 'sikap', 'pertanyaan' => "Soal sikap $i", 'reverse_scored' => $reverse, 'urutan' => $i + 1]);
        }
    }

    /** @param  array<string>  $isian  jawaban per soal, urut sesuai id */
    private function kirimPretest(User $user, array $isian): \Illuminate\Testing\TestResponse
    {
        $jawaban = KuesionerSoal::orderBy('id')->pluck('id')->combine($isian)->all();

        return $this->actingAs($user)->post(route('kuesioner.pretest.store'), ['jawaban' => $jawaban]);
    }

    public function test_materi_terkunci_sebelum_pretest_disubmit(): void
    {
        $response = $this->actingAs(User::factory()->create())->get(route('modul.index'));

        $response->assertRedirect(route('kuesioner.pretest.create'));
        $this->assertDatabaseCount('hasil_kuesioner', 0);
    }

    public function test_form_pretest_merender_semua_opsi_sebagai_radio_wajib_isi(): void
    {
        $this->seedSoal();
        $soalPengetahuan = KuesionerSoal::where('tipe', 'pengetahuan')->first();
        $soalSikap = KuesionerSoal::where('tipe', 'sikap')->first();

        $response = $this->actingAs(User::factory()->create())->get(route('kuesioner.pretest.create'));

        // Pil bergaya tombol tetap radio asli: value & required tidak boleh hilang.
        foreach (['B', 'S'] as $value) {
            $response->assertSee('name="jawaban['.$soalPengetahuan->id.']" value="'.$value.'" required', false);
        }

        foreach (['STS', 'TS', 'S', 'SS'] as $value) {
            $response->assertSee('name="jawaban['.$soalSikap->id.']" value="'.$value.'" required', false);
        }
    }

    public function test_skor_pengetahuan_dan_kategori_sesuai_kunci_jawaban(): void
    {
        $this->seedSoal();
        $user = User::factory()->create();

        // 3 dari 4 benar (soal ke-4 dijawab salah) = 75% → Cukup (56-75%).
        $this->kirimPretest($user, ['B', 'S', 'B', 'B', 'SS', 'SS', 'SS', 'SS']);

        $this->assertDatabaseHas('hasil_kuesioner', [
            'user_id' => $user->id,
            'tipe_sesi' => 'pre',
            'skor_pengetahuan' => 75.00,
            'kategori_pengetahuan' => 'Cukup',
        ]);
        $this->assertDatabaseCount('hasil_kuesioner_detail', 8);
    }

    public function test_skor_pengetahuan_penuh_masuk_kategori_baik(): void
    {
        $this->seedSoal();
        $user = User::factory()->create();

        $this->kirimPretest($user, ['B', 'S', 'B', 'S', 'SS', 'SS', 'SS', 'SS']);

        $this->assertDatabaseHas('hasil_kuesioner', ['skor_pengetahuan' => 100.00, 'kategori_pengetahuan' => 'Baik']);
    }

    public function test_skor_sikap_membalik_item_reverse_scored(): void
    {
        $this->seedSoal();
        $user = User::factory()->create();

        // Semua dijawab SS: 2 favorable = 4+4, 2 reverse = 1+1 → total 10.
        // Kalau reverse_scored diabaikan, hasilnya jadi 16.
        $this->kirimPretest($user, ['B', 'B', 'B', 'B', 'SS', 'SS', 'SS', 'SS']);

        $this->assertDatabaseHas('hasil_kuesioner', ['user_id' => $user->id, 'skor_sikap' => 10.00]);
    }

    public function test_submit_kedua_ditolak_backend_dan_tidak_mengubah_hasil_pertama(): void
    {
        $this->seedSoal();
        $user = User::factory()->create();
        $this->kirimPretest($user, ['B', 'S', 'B', 'S', 'SS', 'SS', 'SS', 'SS']);

        // Semua jawaban pengetahuan sengaja salah: kalau tembus, skor jadi 0/Kurang.
        $response = $this->kirimPretest($user, ['S', 'B', 'S', 'B', 'STS', 'STS', 'STS', 'STS']);

        $response->assertRedirect(route('dashboard'));
        $this->assertDatabaseCount('hasil_kuesioner', 1);
        $this->assertDatabaseCount('hasil_kuesioner_detail', 8);
        $this->assertDatabaseHas('hasil_kuesioner', ['skor_pengetahuan' => 100.00, 'kategori_pengetahuan' => 'Baik']);
    }

    public function test_hasil_pretest_ganda_ditolak_di_level_database(): void
    {
        $user = User::factory()->create();
        $baris = ['user_id' => $user->id, 'tipe_sesi' => 'pre', 'skor_pengetahuan' => 80, 'kategori_pengetahuan' => 'Baik', 'skor_sikap' => 50, 'submitted_at' => now()];
        \App\Models\HasilKuesioner::forceCreate($baris);

        $this->expectException(\Illuminate\Database\UniqueConstraintViolationException::class);
        \App\Models\HasilKuesioner::forceCreate($baris);
    }

    public function test_jawaban_tidak_lengkap_ditolak_validasi(): void
    {
        $this->seedSoal();
        $user = User::factory()->create();
        $soalPertama = KuesionerSoal::orderBy('id')->first();

        $response = $this->actingAs($user)->post(route('kuesioner.pretest.store'), ['jawaban' => [$soalPertama->id => 'B']]);

        $response->assertSessionHasErrors();
        $this->assertDatabaseCount('hasil_kuesioner', 0);
    }
}
