<?php

namespace Tests\Feature;

use App\Models\HasilKuesioner;
use App\Models\Modul;
use App\Models\SubBagian;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VideoPopupTest extends TestCase
{
    use RefreshDatabase;

    private function loginSetelahPretest(): User
    {
        $user = User::factory()->create();
        HasilKuesioner::create(['user_id' => $user->id, 'tipe_sesi' => 'pre', 'skor_pengetahuan' => 80, 'kategori_pengetahuan' => 'Baik', 'skor_sikap' => 50, 'submitted_at' => now()]);

        return $user;
    }

    public function test_tombol_video_tampil_kalau_sub_bagian_punya_video_youtube_id(): void
    {
        $user = $this->loginSetelahPretest();
        $modul = Modul::create(['nama' => 'Kejar Mimpi', 'slug' => 'kejar-mimpi', 'urutan' => 1]);
        $sub = SubBagian::create(['modul_id' => $modul->id, 'judul' => 'Bab 1', 'konten_view' => 'modul.kejar-mimpi.ingat-lagi-mimpimu', 'urutan' => 1, 'video_youtube_id' => 'jNQXAC9IVRw']);

        $this->actingAs($user)->get(route('modul.sub-bagian.show', [$modul, $sub]))
            ->assertSee('Putar langsung di halaman ini')
            ->assertSee('jNQXAC9IVRw');
    }

    public function test_tombol_video_tidak_tampil_kalau_video_youtube_id_kosong(): void
    {
        $user = $this->loginSetelahPretest();
        $modul = Modul::create(['nama' => 'Kejar Mimpi', 'slug' => 'kejar-mimpi', 'urutan' => 1]);
        $sub = SubBagian::create(['modul_id' => $modul->id, 'judul' => 'Bab 1', 'konten_view' => 'modul.kejar-mimpi.ingat-lagi-mimpimu', 'urutan' => 1]);

        $this->actingAs($user)->get(route('modul.sub-bagian.show', [$modul, $sub]))
            ->assertDontSee('Putar langsung di halaman ini');
    }
}
