<?php

namespace Tests\Feature;

use App\Models\HasilKuesioner;
use App\Models\Modul;
use App\Models\ProgressModul;
use App\Models\SubBagian;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RefleksiTest extends TestCase
{
    use RefreshDatabase;

    private function siswaSiapMateri(): array
    {
        $user = User::factory()->create();
        HasilKuesioner::create(['user_id' => $user->id, 'tipe_sesi' => 'pre', 'skor_pengetahuan' => 80, 'kategori_pengetahuan' => 'Baik', 'skor_sikap' => 50, 'submitted_at' => now()]);
        $modul = Modul::create(['nama' => 'Kejar Mimpi', 'slug' => 'kejar-mimpi', 'urutan' => 1]);
        $sub = SubBagian::create(['modul_id' => $modul->id, 'judul' => 'Bab 1', 'konten_view' => 'modul.kejar-mimpi.ingat-lagi-mimpimu', 'urutan' => 1]);

        return [$user, $modul, $sub];
    }

    public function test_refleksi_redirect_ke_materi_kalau_materi_belum_selesai(): void
    {
        [$user, $modul, $sub] = $this->siswaSiapMateri();

        $response = $this->actingAs($user)->get(route('modul.sub-bagian.refleksi', [$modul, $sub]));

        $response->assertRedirect(route('modul.sub-bagian.show', [$modul, $sub]));
    }

    public function test_submit_refleksi_sukses_setelah_materi_selesai(): void
    {
        [$user, $modul, $sub] = $this->siswaSiapMateri();
        ProgressModul::create(['user_id' => $user->id, 'sub_bagian_id' => $sub->id, 'materi_selesai' => true, 'materi_selesai_at' => now()]);

        $response = $this->actingAs($user)->post(route('modul.sub-bagian.refleksi.store', [$modul, $sub]), [
            'jawaban' => 'Aku belajar untuk lebih percaya diri.',
        ]);

        $response->assertRedirect(route('modul.sub-bagian.refleksi', [$modul, $sub]));
        $this->assertDatabaseHas('refleksi', [
            'user_id' => $user->id,
            'sub_bagian_id' => $sub->id,
            'is_locked' => true,
        ]);
    }

    public function test_halaman_refleksi_tampil_read_only_setelah_submit(): void
    {
        [$user, $modul, $sub] = $this->siswaSiapMateri();
        ProgressModul::create(['user_id' => $user->id, 'sub_bagian_id' => $sub->id, 'materi_selesai' => true, 'materi_selesai_at' => now()]);
        $this->actingAs($user)->post(route('modul.sub-bagian.refleksi.store', [$modul, $sub]), ['jawaban' => 'Jawaban pertama.']);

        $response = $this->actingAs($user)->get(route('modul.sub-bagian.refleksi', [$modul, $sub]));

        $response->assertSee('Jawaban pertama.');
        $response->assertSee('disabled', false);
    }

    public function test_submit_kedua_kali_ditolak_backend_tidak_mengubah_jawaban(): void
    {
        [$user, $modul, $sub] = $this->siswaSiapMateri();
        ProgressModul::create(['user_id' => $user->id, 'sub_bagian_id' => $sub->id, 'materi_selesai' => true, 'materi_selesai_at' => now()]);
        $this->actingAs($user)->post(route('modul.sub-bagian.refleksi.store', [$modul, $sub]), ['jawaban' => 'Jawaban pertama.']);

        $this->actingAs($user)->post(route('modul.sub-bagian.refleksi.store', [$modul, $sub]), ['jawaban' => 'Coba ubah jawaban.']);

        $this->assertDatabaseHas('refleksi', ['user_id' => $user->id, 'sub_bagian_id' => $sub->id, 'jawaban' => 'Jawaban pertama.']);
        $this->assertDatabaseMissing('refleksi', ['jawaban' => 'Coba ubah jawaban.']);
        $this->assertDatabaseCount('refleksi', 1);
    }

    public function test_submit_refleksi_kosong_ditolak_validasi(): void
    {
        [$user, $modul, $sub] = $this->siswaSiapMateri();
        ProgressModul::create(['user_id' => $user->id, 'sub_bagian_id' => $sub->id, 'materi_selesai' => true, 'materi_selesai_at' => now()]);

        $response = $this->actingAs($user)->post(route('modul.sub-bagian.refleksi.store', [$modul, $sub]), ['jawaban' => '']);

        $response->assertSessionHasErrors('jawaban');
        $this->assertDatabaseCount('refleksi', 0);
    }
}
