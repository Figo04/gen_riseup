<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Seed the default admin account.
     */
    public function run(): void
    {
        Admin::firstOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@genresilup.test')],
            [
                'nama' => 'Admin',
                'password' => env('ADMIN_PASSWORD', 'password'),
            ]
        );
    }
}
