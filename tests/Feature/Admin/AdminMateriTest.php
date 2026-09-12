<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Modul;
use App\Models\SubBagian;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminMateriTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_view_materi(): void
    {
        $this->get(route('admin.materi.index'))->assertRedirect(route('admin.login'));
    }

    public function test_admin_sees_modul_list_with_sub_bagian_count(): void
    {
        $admin = Admin::factory()->create();
        $modul = Modul::create(['nama' => 'Kejar Mimpi', 'slug' => 'kejar-mimpi', 'urutan' => 1]);
        SubBagian::create(['modul_id' => $modul->id, 'judul' => 'Bab 1', 'konten_view' => 'modul.kejar-mimpi.ingat-lagi-mimpimu', 'urutan' => 1]);

        $response = $this->actingAs($admin, 'admin')->get(route('admin.materi.index'));

        $response->assertOk();
        $response->assertSee('Kejar Mimpi');
        $response->assertSee('1'); // jumlah sub-bagian
    }

    public function test_admin_can_view_sub_bagian_content_read_only(): void
    {
        $admin = Admin::factory()->create();
        $modul = Modul::create(['nama' => 'Kejar Mimpi', 'slug' => 'kejar-mimpi', 'urutan' => 1]);
        $subBagian = SubBagian::create(['modul_id' => $modul->id, 'judul' => 'Ingat Lagi Mimpimu', 'konten_view' => 'modul.kejar-mimpi.ingat-lagi-mimpimu', 'urutan' => 1]);

        $response = $this->actingAs($admin, 'admin')->get(route('admin.materi.sub-bagian', [$modul, $subBagian]));

        $response->assertOk();
        $response->assertSee('Ingat Lagi Mimpimu');
        $response->assertDontSee('Tandai Materi Selesai');
    }

    public function test_sub_bagian_not_belonging_to_modul_in_url_is_404(): void
    {
        $admin = Admin::factory()->create();
        $modulA = Modul::create(['nama' => 'Kejar Mimpi', 'slug' => 'kejar-mimpi', 'urutan' => 1]);
        $modulB = Modul::create(['nama' => 'Investasi Gizi', 'slug' => 'investasi-gizi', 'urutan' => 2]);
        $subBagian = SubBagian::create(['modul_id' => $modulB->id, 'judul' => 'Bab 1', 'konten_view' => 'modul.investasi-gizi.gizi-itu-investasi-bukan-sekadar-makan', 'urutan' => 1]);

        $this->actingAs($admin, 'admin')
            ->get(route('admin.materi.sub-bagian', [$modulA, $subBagian]))
            ->assertNotFound();
    }
}
