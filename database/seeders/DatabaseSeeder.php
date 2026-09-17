<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Seat;
use App\Models\User;
use App\Models\Venue;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        //        User::factory()->create([
        //            'name' => 'Test User',
        //            'email' => 'test@example.com',
        //        ]);

        $venue = Venue::factory()->create([
            'name' => 'Downtown Arena',
            'city' => 'Davao City',
        ]);

        $event = Event::factory()->create([
            'venue_id' => $venue->id,
            'title' => 'Opening Night Concert',
        ]);

        foreach (['A', 'B', 'C'] as $section) {
            foreach (range(1, 5) as $row) {
                foreach (range(1, 10) as $number) {
                    $event->seats()->create([
                        'section' => $section,
                        'row' => $row,
                        'number' => $number,
                        'status' => 'available',
                    ]);
                }
            }
        }

        Event::factory()
            ->count(5)
            ->has(Seat::factory()->count(20))
            ->create();
    }
}
