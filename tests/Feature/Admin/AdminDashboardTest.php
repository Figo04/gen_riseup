<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\HasilKuesioner;
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

        HasilKuesioner::create([
            'user_id' => $prePostUser->id, 'tipe_sesi' => 'pre',
            'skor_pengetahuan' => 80, 'kategori_pengetahuan' => 'Baik', 'skor_sikap' => 50, 'submitted_at' => now(),
        ]);
        HasilKuesioner::create([
            'user_id' => $prePostUser->id, 'tipe_sesi' => 'post',
            'skor_pengetahuan' => 90, 'kategori_pengetahuan' => 'Baik', 'skor_sikap' => 55, 'submitted_at' => now(),
        ]);
        HasilKuesioner::create([
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
            HasilKuesioner::create([
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
}
