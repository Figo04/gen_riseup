<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use RuntimeException;

class AdminSeeder extends Seeder
{
    /**
     * Seed the default admin account.
     *
     * Tidak ada password default: seeding sengaja gagal keras kalau
     * ADMIN_PASSWORD belum diset, supaya deploy tidak diam-diam memasang
     * kredensial yang tertulis di source code. updateOrCreate (bukan
     * firstOrCreate) supaya seed ulang me-rotate password akun yang ada.
     */
    public function run(): void
    {
        $password = env('ADMIN_PASSWORD') ?: throw new RuntimeException(
            'ADMIN_PASSWORD wajib diset di .env sebelum menjalankan seeder.'
        );

        Admin::updateOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@genresilup.test')],
            [
                'nama' => 'Admin',
                'password' => $password,
            ]
        );
    }
}
