<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminLayoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_shows_sidebar_with_data_and_konten_groups(): void
    {
        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin, 'admin')->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertSeeText('Data');
        $response->assertSeeText('Konten');
        $response->assertSeeText('Dashboard');
        $response->assertSeeText('Responden');
        $response->assertSeeText('Hasil Test');
        $response->assertSeeText('Kelola Soal');
        $response->assertSeeText('Kelola Materi');
        $response->assertSeeText('Data Kalender Haid');
        $response->assertSeeText($admin->nama);
    }

    public function test_sidebar_links_every_menu(): void
    {
        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin, 'admin')->get(route('admin.dashboard'));

        foreach (['dashboard', 'responden.index', 'hasil-test.index', 'soal.index', 'materi.index', 'kalender-haid.index'] as $name) {
            $response->assertSee(route('admin.'.$name), false);
        }
    }
}
