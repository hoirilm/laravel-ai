<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(rand(4, 8), true);
        $title = rtrim($title, '.');

        return [
            'user_id'  => User::factory(),
            'title'    => $title,
            'slug'     => Str::slug($title) . '-' . Str::random(5),
            'images'   => null,
            'content'  => implode("\n\n", fake()->paragraphs(rand(3, 6))),
        ];
    }

    /**
     * State: post with images.
     */
    public function withImages(int $count = 2): static
    {
        return $this->state(fn (array $attributes) => [
            'images' => collect(range(1, $count))->map(
                fn () => 'https://picsum.photos/seed/' . Str::random(6) . '/800/500'
            )->toArray(),
        ]);
    }
}
