<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class ServiceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'provider_id'  => User::factory(),
            'name'         => $this->faker->words(2, true),
            'description'  => $this->faker->sentence(),
            'category_id'  => 1,
            'duration'     => $this->faker->randomElement([30,60,90]),
            'price'        => $this->faker->randomFloat(2, 10, 100),
            'is_published' => true,
        ];
    }

    public function unpublished(): self
    {
        return $this->state(fn() => ['is_published' => false]);
    }
}
