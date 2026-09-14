<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\HasilKuesioner;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AdminHasilTestTest extends TestCase
{
    use RefreshDatabase;

    private function hasil(User $user, string $tipe, float $pengetahuan, string $kategori, float $sikap): void
    {
        HasilKuesioner::forceCreate([
            'user_id' => $user->id,
            'tipe_sesi' => $tipe,
            'skor_pengetahuan' => $pengetahuan,
            'kategori_pengetahuan' => $kategori,
            'skor_sikap' => $sikap,
            'submitted_at' => now(),
        ]);
    }

    public function test_guest_cannot_access_hasil_test(): void
    {
        $this->get(route('admin.hasil-test.index'))->assertRedirect(route('admin.login'));
    }

    public function test_student_cannot_access_hasil_test(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('admin.hasil-test.index'))
            ->assertRedirect(route('admin.login'));
    }

    public function test_listing_shows_pre_post_scores_and_delta(): void
    {
        $admin = Admin::factory()->create();
        $siswa = User::factory()->create(['name' => 'Ani Lestari']);
        $this->hasil($siswa, 'pre', 60, 'Cukup', 40);
        $this->hasil($siswa, 'post', 85, 'Baik', 55);

        $response = $this->actingAs($admin, 'admin')->get(route('admin.hasil-test.index'));

        $response->assertOk();
        $response->assertSeeText('Ani Lestari');
        $response->assertSeeText('Cukup');
        $response->assertSeeText('Baik');
        $response->assertSeeText('+25');  // selisih pengetahuan
        $response->assertSeeText('+15');  // selisih sikap
    }

    public function test_student_without_post_test_shows_dash_instead_of_delta(): void
    {
        $admin = Admin::factory()->create();
        $siswa = User::factory()->create(['name' => 'Budi Santoso']);
        $this->hasil($siswa, 'pre', 60, 'Cukup', 40);

        $response = $this->actingAs($admin, 'admin')->get(route('admin.hasil-test.index'));

        $response->assertOk();
        $response->assertSeeText('Budi Santoso');
        // Tidak ada sel selisih yang terwarnai → selisih tidak dihitung.
        $response->assertDontSee('text-green-600', false);
        $response->assertDontSee('text-red-600', false);
    }

    public function test_students_who_never_submitted_are_excluded(): void
    {
        $admin = Admin::factory()->create();
        User::factory()->create(['name' => 'Belum Ngerjain']);
        $sudah = User::factory()->create(['name' => 'Sudah Ngerjain']);
        $this->hasil($sudah, 'pre', 60, 'Cukup', 40);

        $response = $this->actingAs($admin, 'admin')->get(route('admin.hasil-test.index'));

        $response->assertSeeText('Sudah Ngerjain');
        $response->assertDontSeeText('Belum Ngerjain');
    }

    public function test_search_filters_by_name_email_or_school(): void
    {
        $admin = Admin::factory()->create();
        $ani = User::factory()->create(['name' => 'Ani Lestari', 'sekolah' => 'SMAN 1']);
        $budi = User::factory()->create(['name' => 'Budi Santoso', 'sekolah' => 'SMPN 4']);
        $this->hasil($ani, 'pre', 60, 'Cukup', 40);
        $this->hasil($budi, 'pre', 70, 'Cukup', 45);

        $response = $this->actingAs($admin, 'admin')
            ->get(route('admin.hasil-test.index', ['cari' => 'SMPN']));

        $response->assertSeeText('Budi Santoso');
        $response->assertDontSeeText('Ani Lestari');
    }

    public function test_reflection_and_nutrition_data_are_never_queried(): void
    {
        $admin = Admin::factory()->create();
        $siswa = User::factory()->create();
        $this->hasil($siswa, 'pre', 60, 'Cukup', 40);

        $queries = [];
        DB::listen(function ($q) use (&$queries) {
            $queries[] = $q->sql;
        });

        $this->actingAs($admin, 'admin')->get(route('admin.hasil-test.index'))->assertOk();

        foreach ($queries as $sql) {
            $this->assertStringNotContainsString('refleksi', $sql);
            $this->assertStringNotContainsString('tracker_gizi', $sql);
        }
    }
}
