<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TimeEntry>
 */
class TimeEntryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $start = $this->faker->dateTimeBetween('-15 days', 'now');
        $end = (clone $start)->modify('+'.$this->faker->numberBetween(2, 6).' hours');

        return [
            'client_id' => 1,
            'project_id' => 1,
            'user_id' => 1,
            'work_type_id' => 1,
            'start' => $start,
            'end' => $end,
            'description' => $this->faker->sentence(),
            'link' => $this->faker->url(),
            'billable' => $this->faker->boolean(),
        ];
    }
}
