<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\Venue;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'venue_id' => Venue::factory(),
            'title' => rtrim(fake()->sentence(3), '. ').'Live',
            'category' => fake()->randomElement(['Concert', 'Sports', 'Theatre', 'Comedy']),
            'starts_at' => fake()->dateTimeBetween('+1 week', '+3 months'),
            'base_price' => fake()->randomFloat(2, 25, 250),
        ];
    }
}
