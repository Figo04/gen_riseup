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

    public function test_satu_kelas_bisa_mendaftar_berbarengan_dari_ip_yang_sama(): void
    {
        // Alasan utama throttle registrasi dipasang longgar. Kalau angkanya
        // pernah diturunkan, test ini yang jatuh duluan — bukan siswa di lab.
        for ($i = 1; $i <= 40; $i++) {
            $this->post('/register', [
                'name' => "Siswa $i",
                'email' => "siswa$i@example.com",
                'password' => 'password',
                'password_confirmation' => 'password',
                'usia' => 15,
                'jenis_kelamin' => 'P',
            ])->assertRedirect(route('dashboard', absolute: false));

            // flushSession() saja tidak cukup: guard web masih memegang user
            // di memori lintas request, jadi pendaftar berikutnya akan kena
            // middleware `guest` dan di-redirect tanpa pernah tersimpan.
            $this->post('/logout');
        }

        $this->assertDatabaseCount('users', 40);
    }

    public function test_registrasi_ditolak_saat_dibanjiri_dari_satu_ip(): void
    {
        // Payload sengaja kosong: request tetap menyentuh throttle tapi gagal
        // validasi, jadi tidak ada user yang login dan middleware `guest`
        // tidak ikut me-redirect request berikutnya.
        for ($i = 0; $i < 60; $i++) {
            $this->post('/register', [])->assertStatus(302);
        }

        $this->post('/register', [])->assertStatus(429);
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
