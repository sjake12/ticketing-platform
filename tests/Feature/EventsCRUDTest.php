<?php

use App\Models\Venue;
use Carbon\Carbon;
use App\Models\Seat;

test('add seats automatically when event is added', function () {
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

    $response = $this->post('/events', $event);

    $response->assertValid();
    $response->assertRedirect('/events');

    expect(Seat::count())->toBe(150);
});
