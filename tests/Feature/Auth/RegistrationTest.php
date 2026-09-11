<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'usia' => 15,
            'jenis_kelamin' => 'P',
            'sekolah' => 'SMA 1',
            'kelas' => '10A',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'usia' => 15,
            'jenis_kelamin' => 'P',
        ]);
    }

    public function test_registration_requires_usia_and_jenis_kelamin(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test2@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'usia' => 12,
            'jenis_kelamin' => 'X',
        ]);

        $response->assertSessionHasErrors(['usia', 'jenis_kelamin']);
        $this->assertGuest();
    }
}
