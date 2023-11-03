<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->realText,
            'commentable_id' => function () {
                return Post::factory()->create()->id;
            },
            'commentable_type' => 'App\Models\Post',
            'user_id' => function () {
                return User::factory()->create()->id;
            }
        ];
    }
}
