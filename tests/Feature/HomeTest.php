<?php

namespace Tests\Feature;

use App\Models\HasilKuesioner;
use App\Models\Modul;
use App\Models\ProgressModul;
use App\Models\SubBagian;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeTest extends TestCase
{
    use RefreshDatabase;

    private function seedDuaModul(): array
    {
        $a = Modul::create(['nama' => 'Kejar Mimpi', 'slug' => 'kejar-mimpi', 'urutan' => 1]);
        $b = Modul::create(['nama' => 'Investasi Gizi', 'slug' => 'investasi-gizi', 'urutan' => 2]);

        return [
            $a,
            SubBagian::create(['modul_id' => $a->id, 'judul' => 'Bab 1', 'konten_view' => 'modul.kejar-mimpi.ingat-lagi-mimpimu', 'urutan' => 1]),
            $b,
            SubBagian::create(['modul_id' => $b->id, 'judul' => 'Bab 1', 'konten_view' => 'modul.investasi-gizi.jangan-lupa-cairan-tubuhmu', 'urutan' => 1]),
        ];
    }

    public function test_home_menampilkan_ajakan_pretest_sebelum_pretest_diisi(): void
    {
        $this->seedDuaModul();
        $user = User::factory()->create(['name' => 'Nadia']);

        $this->actingAs($user)->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Hai Nadia')
            ->assertSee('Kamu sudah menyelesaikan 0 dari 2 modul.')
            ->assertSee('Santai, ini bukan ujian')
            ->assertSee('0/1');
    }

    public function test_home_menampilkan_progress_dan_sisa_modul_setelah_sebagian_selesai(): void
    {
        [, $subA, $modulB] = $this->seedDuaModul();
        $user = User::factory()->create();
        HasilKuesioner::create(['user_id' => $user->id, 'tipe_sesi' => 'pre', 'skor_pengetahuan' => 80, 'kategori_pengetahuan' => 'Baik', 'skor_sikap' => 50, 'submitted_at' => now()]);
        ProgressModul::create(['user_id' => $user->id, 'sub_bagian_id' => $subA->id, 'materi_selesai' => true]);

        $this->actingAs($user)->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Kamu sudah menyelesaikan 1 dari 2 modul.')
            ->assertSee('Sudah kamu isi, makasih ya')
            // hanya modul yang belum tuntas masuk "Lanjut dari sini"
            ->assertSee($modulB->subtitle)
            ->assertDontSee('Kenali mimpimu, susun langkah kecilnya');
    }
}
