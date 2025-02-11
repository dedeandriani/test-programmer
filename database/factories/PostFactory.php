<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(random_int(6, 10)),
			'body' => $this->faker->paragraph(random_int(3, 10)),
            'user_id' => random_int(1, User::count())
        ];
    }
}
