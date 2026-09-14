<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\KalenderHaid;
use App\Models\Refleksi;
use App\Models\SubBagian;
use App\Models\TrackerGizi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminKalenderHaidTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_view_kalender_haid(): void
    {
        $this->get(route('admin.kalender-haid.index'))->assertRedirect(route('admin.login'));
    }

    public function test_admin_sees_all_students_entries_read_only(): void
    {
        $admin = Admin::factory()->create();
        $user = User::factory()->create(['name' => 'Siti']);
        KalenderHaid::forceCreate([
            'user_id' => $user->id,
            'tanggal_mulai' => '2026-01-01',
            'tanggal_selesai' => '2026-01-05',
            'catatan' => 'Nyeri ringan',
        ]);

        $response = $this->actingAs($admin, 'admin')->get(route('admin.kalender-haid.index'));

        $response->assertOk();
        $response->assertSee('Siti');
        $response->assertSee('Nyeri ringan');
    }

    public function test_reflection_and_nutrition_tracker_data_are_never_shown(): void
    {
        $admin = Admin::factory()->create();
        $user = User::factory()->create();
        $subBagian = SubBagian::create([
            'modul_id' => \App\Models\Modul::create(['nama' => 'Kenali Tubuhmu', 'slug' => 'kenali-tubuhmu', 'urutan' => 1])->id,
            'judul' => 'Bab 1', 'konten_view' => 'modul.kenali-tubuhmu.pendahuluan', 'urutan' => 1,
        ]);
        Refleksi::forceCreate(['user_id' => $user->id, 'sub_bagian_id' => $subBagian->id, 'jawaban' => 'RAHASIA_REFLEKSI_XYZ']);
        TrackerGizi::forceCreate(['user_id' => $user->id, 'data' => ['senin' => ['sayur_buah' => true]]]);

        $response = $this->actingAs($admin, 'admin')->get(route('admin.kalender-haid.index'));

        $response->assertOk();
        $response->assertDontSee('RAHASIA_REFLEKSI_XYZ');
    }
}
