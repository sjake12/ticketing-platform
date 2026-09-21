<?php

use App\Models\Seat;
use App\Models\User;
use App\Models\Venue;
use Carbon\Carbon;

use function Pest\Laravel\actingAs;

test('add seats automatically when event is added', function () {

    $admin = User::factory()->create([
        'name' => 'Admin',
        'email' => 'admin@admin.com',
        'password' => bcrypt('secret'),
        'role' => 'admin',
    ]);

    $venue = Venue::create([
        'name' => 'MOA Arena',
        'city' => 'Pasig City',
        'layout' => [
            'sections' => [
                ['name' => 'A', 'rows' => '5', 'seats_per_row' => 10],
                ['name' => 'B', 'rows' => '5', 'seats_per_row' => 10],
                ['name' => 'C', 'rows' => '5', 'seats_per_row' => 10],
            ],
        ],
    ]);

    $event = [
        'venue_id' => $venue->id,
        'title' => 'BINI Signals World Tour',
        'category' => 'Concert',
        'starts_at' => Carbon::parse('2027-07-12 21:44:00'),
        'base_price' => 120.22,
    ];

    actingAs($admin)
        ->post('/events', $event)
        ->assertValid()
        ->assertRedirect('/events');

    expect(Seat::count())->toBe(150);
});
