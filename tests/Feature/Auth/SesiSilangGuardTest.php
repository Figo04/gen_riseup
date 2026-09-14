<?php

namespace Tests\Feature\Auth;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Sengaja login lewat endpoint asli, bukan actingAs(): actingAs($x, 'admin')
 * memanggil Auth::shouldUse('admin') sehingga default guard ikut bergeser dan
 * middleware `guest` di POST /login jadi salah sasaran — artefak test yang
 * tidak pernah terjadi di browser sungguhan.
 */
class SesiSilangGuardTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_siswa_mengakhiri_sesi_admin_di_browser_yang_sama(): void
    {
        $admin = Admin::factory()->create();
        $user = User::factory()->create();

        $this->post('/admin/login', ['email' => $admin->email, 'password' => 'password']);
        $this->assertAuthenticatedAs($admin, 'admin');

        $this->post('/login', ['email' => $user->email, 'password' => 'password']);

        $this->assertAuthenticatedAs($user, 'web');
        $this->assertGuest('admin');
        $this->get('/admin')->assertRedirect(route('admin.login'));
    }

    public function test_login_admin_mengakhiri_sesi_siswa_di_browser_yang_sama(): void
    {
        $user = User::factory()->create();
        $admin = Admin::factory()->create();

        $this->post('/login', ['email' => $user->email, 'password' => 'password']);
        $this->assertAuthenticatedAs($user, 'web');

        $this->post('/admin/login', ['email' => $admin->email, 'password' => 'password']);

        $this->assertAuthenticatedAs($admin, 'admin');
        $this->assertGuest('web');
        $this->get('/dashboard')->assertRedirect(route('login'));
    }
}
