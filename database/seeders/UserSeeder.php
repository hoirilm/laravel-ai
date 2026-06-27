<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Seed the users table.
     */
    public function run(): void
    {
        // Buat 1 akun admin tetap
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name'              => 'Admin',
                'password'          => Hash::make('password'),
                'is_admin'          => true,
                'email_verified_at' => now(),
            ]
        );

        // Buat 1 akun user biasa tetap
        User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name'              => 'Regular User',
                'password'          => Hash::make('password'),
                'is_admin'          => false,
                'email_verified_at' => now(),
            ]
        );

        // Buat 8 user random
        User::factory(8)->create([
            'is_admin' => false,
        ]);
    }
}
