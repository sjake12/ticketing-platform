<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\Seat;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Seat>
 */
class SeatFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'event_id' => Event::factory(),
            'section' => fake()->randomElement(['A', 'B', 'C']),
            'row' => fake()->randomElement(range(1, 5)),
            'number' => fake()->numberBetween(1, 10),
            'status' => 'available',
        ];
    }
}
