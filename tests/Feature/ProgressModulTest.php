<?php

namespace Tests\Feature;

use App\Models\HasilKuesioner;
use App\Models\Modul;
use App\Models\SubBagian;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProgressModulTest extends TestCase
{
    use RefreshDatabase;

    public function test_modul_route_redirects_to_pretest_when_not_completed(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/modul');

        $response->assertRedirect(route('kuesioner.pretest.create'));
    }

    public function test_menandai_materi_selesai_membuat_progress_dan_tampil_di_daftar_sub_bagian(): void
    {
        $user = User::factory()->create();
        HasilKuesioner::create(['user_id' => $user->id, 'tipe_sesi' => 'pre', 'skor_pengetahuan' => 80, 'kategori_pengetahuan' => 'Baik', 'skor_sikap' => 50, 'submitted_at' => now()]);
        $modul = Modul::create(['nama' => 'Kejar Mimpi', 'slug' => 'kejar-mimpi', 'urutan' => 1]);
        $sub = SubBagian::create(['modul_id' => $modul->id, 'judul' => 'Bab 1', 'konten_view' => 'modul.kejar-mimpi.ingat-lagi-mimpimu', 'urutan' => 1]);

        $response = $this->actingAs($user)->post(route('modul.sub-bagian.selesai', [$modul, $sub]));

        $response->assertRedirect(route('modul.show', $modul));
        $this->assertDatabaseHas('progress_modul', [
            'user_id' => $user->id,
            'sub_bagian_id' => $sub->id,
            'materi_selesai' => true,
        ]);

        $this->actingAs($user)->get(route('modul.show', $modul))
            ->assertSee('✓ Selesai');
    }

    public function test_materi_selesai_semua_hanya_true_setelah_semua_sub_bagian_di_semua_modul_selesai(): void
    {
        $user = User::factory()->create();
        $modulA = Modul::create(['nama' => 'A', 'slug' => 'a', 'urutan' => 1]);
        $modulB = Modul::create(['nama' => 'B', 'slug' => 'b', 'urutan' => 2]);
        $subA = SubBagian::create(['modul_id' => $modulA->id, 'judul' => 'A1', 'konten_view' => 'x', 'urutan' => 1]);
        $subB = SubBagian::create(['modul_id' => $modulB->id, 'judul' => 'B1', 'konten_view' => 'x', 'urutan' => 1]);

        $user->progressModul()->create(['sub_bagian_id' => $subA->id, 'materi_selesai' => true, 'materi_selesai_at' => now()]);
        $this->assertFalse($user->fresh()->materiSelesaiSemua());

        $user->progressModul()->create(['sub_bagian_id' => $subB->id, 'materi_selesai' => true, 'materi_selesai_at' => now()]);
        $this->assertTrue($user->fresh()->materiSelesaiSemua());
    }

    public function test_sub_bagian_yang_bukan_milik_modul_di_url_mengembalikan_404(): void
    {
        $user = User::factory()->create();
        HasilKuesioner::create(['user_id' => $user->id, 'tipe_sesi' => 'pre', 'skor_pengetahuan' => 80, 'kategori_pengetahuan' => 'Baik', 'skor_sikap' => 50, 'submitted_at' => now()]);
        $modulA = Modul::create(['nama' => 'A', 'slug' => 'a', 'urutan' => 1]);
        $modulB = Modul::create(['nama' => 'B', 'slug' => 'b', 'urutan' => 2]);
        $subB = SubBagian::create(['modul_id' => $modulB->id, 'judul' => 'B1', 'konten_view' => 'x', 'urutan' => 1]);

        $this->actingAs($user)->get(route('modul.sub-bagian.show', [$modulA, $subB]))
            ->assertNotFound();
    }
}
