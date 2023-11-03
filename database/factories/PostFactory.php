<?php

namespace Database\Factories;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;


class PostFactory extends Factory
{
    public function configure()
    {
        return $this;
    }
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->realText,
            'user_id' => function () {
                return User::factory()->create()->id;
            }
        ];
    }
}
