<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\HasilKuesioner;
use App\Models\Modul;
use App\Models\ProgressModul;
use App\Models\SubBagian;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AdminRespondenTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_responden(): void
    {
        $this->get(route('admin.responden.index'))->assertRedirect(route('admin.login'));
    }

    public function test_student_cannot_access_responden(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('admin.responden.index'))
            ->assertRedirect(route('admin.login'));
    }

    public function test_listing_shows_progress_and_test_status(): void
    {
        $admin = Admin::factory()->create();

        $modul = Modul::create(['nama' => 'Modul Test', 'slug' => 'modul-test', 'urutan' => 1]);
        $bab1 = SubBagian::create(['modul_id' => $modul->id, 'judul' => 'Bab 1', 'konten_view' => 'x', 'urutan' => 1]);
        SubBagian::create(['modul_id' => $modul->id, 'judul' => 'Bab 2', 'konten_view' => 'x', 'urutan' => 2]);

        $siswa = User::factory()->create(['name' => 'Ani Lestari', 'sekolah' => 'SMAN 1']);
        ProgressModul::forceCreate(['user_id' => $siswa->id, 'sub_bagian_id' => $bab1->id, 'materi_selesai' => true]);
        HasilKuesioner::forceCreate([
            'user_id' => $siswa->id, 'tipe_sesi' => 'pre',
            'skor_pengetahuan' => 70, 'kategori_pengetahuan' => 'Cukup', 'skor_sikap' => 45, 'submitted_at' => now(),
        ]);

        $response = $this->actingAs($admin, 'admin')->get(route('admin.responden.index'));

        $response->assertOk();
        $response->assertSeeText('Ani Lestari');
        $response->assertViewHas('totalSubBagian', 2);
        $response->assertViewHas('siswa', function ($siswa) {
            return $siswa->first()->materi_selesai_count === 1
                && $siswa->first()->hasilKuesioner->contains('tipe_sesi', 'pre')
                && ! $siswa->first()->hasilKuesioner->contains('tipe_sesi', 'post');
        });
    }

    public function test_search_filters_by_name_email_or_school(): void
    {
        $admin = Admin::factory()->create();
        User::factory()->create(['name' => 'Ani Lestari', 'sekolah' => 'SMAN 1']);
        User::factory()->create(['name' => 'Budi Santoso', 'sekolah' => 'SMPN 4']);

        $response = $this->actingAs($admin, 'admin')
            ->get(route('admin.responden.index', ['cari' => 'SMPN']));

        $response->assertSeeText('Budi Santoso');
        $response->assertDontSeeText('Ani Lestari');
    }

    public function test_reflection_and_nutrition_data_are_never_queried(): void
    {
        $admin = Admin::factory()->create();
        User::factory()->create();

        $queries = [];
        DB::listen(function ($q) use (&$queries) {
            $queries[] = $q->sql;
        });

        $this->actingAs($admin, 'admin')->get(route('admin.responden.index'))->assertOk();

        foreach ($queries as $sql) {
            $this->assertStringNotContainsString('refleksi', $sql);
            $this->assertStringNotContainsString('tracker_gizi', $sql);
        }
    }
}
