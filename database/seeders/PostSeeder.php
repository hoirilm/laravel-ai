<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Seed the posts table.
     */
    public function run(): void
    {
        // Ambil semua user yang sudah ada
        $users = User::all();

        if ($users->isEmpty()) {
            $this->command->warn('Tidak ada user ditemukan. Jalankan UserSeeder terlebih dahulu.');
            return;
        }

        // Setiap user mendapat 3–5 post biasa
        $users->each(function (User $user) {
            $count = rand(3, 5);

            Post::factory($count)->create([
                'user_id' => $user->id,
            ]);
        });

        // Tambah 5 post dengan gambar (milik user random)
        Post::factory(5)
            ->withImages(rand(1, 3))
            ->create([
                'user_id' => $users->random()->id,
            ]);
    }
}
