<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WorkType>
 */
class WorkTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->randomElement(['Dev - frontend', 'Dev - backend', 'Webdesign', 'Graphisme', 'Création logo', 'Motion', 'Photo', 'Gestion de projet', 'Illustration', 'Meeting']),
        ];
    }
}
