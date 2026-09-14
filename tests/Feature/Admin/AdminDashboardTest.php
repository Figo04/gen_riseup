<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\HasilKuesioner;
use App\Models\Modul;
use App\Models\ProgressModul;
use App\Models\SubBagian;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_stat_cards_show_correct_counts(): void
    {
        $admin = Admin::factory()->create();

        // 3 respondents total.
        $prePostUser = User::factory()->create();
        $preOnlyUser = User::factory()->create();
        User::factory()->create(); // registered, no kuesioner yet

        HasilKuesioner::forceCreate([
            'user_id' => $prePostUser->id, 'tipe_sesi' => 'pre',
            'skor_pengetahuan' => 80, 'kategori_pengetahuan' => 'Baik', 'skor_sikap' => 50, 'submitted_at' => now(),
        ]);
        HasilKuesioner::forceCreate([
            'user_id' => $prePostUser->id, 'tipe_sesi' => 'post',
            'skor_pengetahuan' => 90, 'kategori_pengetahuan' => 'Baik', 'skor_sikap' => 55, 'submitted_at' => now(),
        ]);
        HasilKuesioner::forceCreate([
            'user_id' => $preOnlyUser->id, 'tipe_sesi' => 'pre',
            'skor_pengetahuan' => 60, 'kategori_pengetahuan' => 'Cukup', 'skor_sikap' => 40, 'submitted_at' => now(),
        ]);

        $response = $this->actingAs($admin, 'admin')->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertViewHas('totalRespondents', 3);
        $response->assertViewHas('preCompleted', 2);
        $response->assertViewHas('postCompleted', 1);
        $response->assertViewHas('prePostComplete', 1);
    }

    public function test_recent_activity_shows_latest_10_submissions_ordered_desc(): void
    {
        $admin = Admin::factory()->create();
        $users = User::factory()->count(12)->create();

        foreach ($users as $i => $user) {
            HasilKuesioner::forceCreate([
                'user_id' => $user->id, 'tipe_sesi' => 'pre',
                'skor_pengetahuan' => 70, 'kategori_pengetahuan' => 'Cukup', 'skor_sikap' => 45,
                'submitted_at' => now()->subMinutes(12 - $i),
            ]);
        }

        $response = $this->actingAs($admin, 'admin')->get(route('admin.dashboard'));

        $response->assertViewHas('recentActivity', function ($recentActivity) use ($users) {
            return $recentActivity->count() === 10
                && $recentActivity->first()->user_id === $users->last()->id;
        });
    }

    public function test_charts_show_correct_aggregates(): void
    {
        $admin = Admin::factory()->create();

        $modul = Modul::create(['nama' => 'Modul Test', 'slug' => 'modul-test', 'urutan' => 1]);
        $subBagian1 = SubBagian::create(['modul_id' => $modul->id, 'judul' => 'Bab 1', 'konten_view' => 'x', 'urutan' => 1]);
        $subBagian2 = SubBagian::create(['modul_id' => $modul->id, 'judul' => 'Bab 2', 'konten_view' => 'x', 'urutan' => 2]);

        // User A: pre+post selesai, materi lengkap.
        $userA = User::factory()->create(['jenis_kelamin' => 'L', 'usia' => 15]);
        HasilKuesioner::forceCreate(['user_id' => $userA->id, 'tipe_sesi' => 'pre', 'skor_pengetahuan' => 80, 'kategori_pengetahuan' => 'Baik', 'skor_sikap' => 50, 'submitted_at' => now()]);
        HasilKuesioner::forceCreate(['user_id' => $userA->id, 'tipe_sesi' => 'post', 'skor_pengetahuan' => 90, 'kategori_pengetahuan' => 'Baik', 'skor_sikap' => 55, 'submitted_at' => now()]);
        ProgressModul::forceCreate(['user_id' => $userA->id, 'sub_bagian_id' => $subBagian1->id, 'materi_selesai' => true]);
        ProgressModul::forceCreate(['user_id' => $userA->id, 'sub_bagian_id' => $subBagian2->id, 'materi_selesai' => true]);

        // User B: pretest saja, materi belum lengkap.
        $userB = User::factory()->create(['jenis_kelamin' => 'P', 'usia' => 15]);
        HasilKuesioner::forceCreate(['user_id' => $userB->id, 'tipe_sesi' => 'pre', 'skor_pengetahuan' => 60, 'kategori_pengetahuan' => 'Cukup', 'skor_sikap' => 40, 'submitted_at' => now()]);
        ProgressModul::forceCreate(['user_id' => $userB->id, 'sub_bagian_id' => $subBagian1->id, 'materi_selesai' => true]);

        // User C: belum pretest sama sekali.
        $userC = User::factory()->create(['jenis_kelamin' => 'P', 'usia' => 17]);

        $response = $this->actingAs($admin, 'admin')->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertViewHas('testStatus', [
            'Belum Pre-Test' => 1,
            'Pre-Test Saja' => 1,
            'Pre+Post Selesai' => 1,
        ]);
        $response->assertViewHas('materialStatus', [
            'Selesai Semua Materi' => 1,
            'Belum Selesai' => 2,
        ]);
        $response->assertViewHas('genderDistribution', function ($genderDistribution) {
            return $genderDistribution['L'] === 1 && $genderDistribution['P'] === 2;
        });
        $response->assertViewHas('ageDistribution', function ($ageDistribution) {
            return $ageDistribution[15] === 2 && $ageDistribution[17] === 1;
        });
    }
}
