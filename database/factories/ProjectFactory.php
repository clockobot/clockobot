<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->domainName(),
            'client_id' => 1,
            'description' => $this->faker->sentence(),
            'deadline' => $this->faker->dateTimeBetween(now(), now()->addYear()),
            'hour_estimate' => $this->faker->randomFloat(1, 0.5, 200),
        ];
    }
}
