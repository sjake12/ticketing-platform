<?php

namespace Database\Factories;

use App\Models\Venue;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Venue>
 */
class VenueFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company().' Arena',
            'city' => fake()->city(),
            'layout' => [
                'sections' => ['A', 'B', 'C'],
                'rows_per_section' => 5,
                'seats_per_row' => 10,
            ],
        ];
    }
}
