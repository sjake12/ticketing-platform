<?php

use App\Models\Event;
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
        ->post('/admin/events', $event)
        ->assertValid()
        ->assertRedirect('/admin/events');

    expect(Seat::count())->toBe(150);
});

test('admin can enter admin/events page and customers are restricted', function () {
    $admin = User::factory()->create([
        'name' => 'Admin',
        'email' => 'admin@admin.com',
        'password' => bcrypt('secret'),
        'role' => 'admin',
    ]);

    $customer = User::factory()->create([
        'name' => 'Customer',
        'email' => 'customer@example.com',
        'password' => bcrypt('secret'),
        'role' => 'customer',
    ]);

    actingAs($admin)
        ->get('/admin/events')
        ->assertStatus(200);

    actingAs($customer)
        ->get('/admin/events')
        ->assertStatus(403);
});

test('admin can create events', function () {
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
        ->post('/admin/events', $event)
        ->assertValid()
        ->assertRedirect('/admin/events');
});

test('admin can visit and edit event', function () {
    $admin = User::factory()->create([
        'name' => 'Admin',
        'email' => 'admin@admin.com',
        'password' => bcrypt('secret'),
        'role' => 'admin',
    ]);

    $venue = Venue::create([
        'name' => 'MOA Arena',
        'city' => 'Pasig City',
    ]);

    $event = Event::create([
        'venue_id' => $venue->id,
        'title' => 'BINI Signals World Tour',
        'category' => 'Concert',
        'starts_at' => Carbon::parse('2027-07-12 21:44:00'),
        'base_price' => 120.22,
    ]);

    $editedEvent = [
        'venue_id' => $venue->id,
        'title' => 'BINI Signals World Tour',
        'category' => 'Concert',
        'starts_at' => Carbon::parse('2027-07-12 21:44:00'),
        'base_price' => 150.22,
    ];

    actingAs($admin)
        ->patch('/admin/events/'.$event->id, $editedEvent)
        ->assertValid()
        ->assertRedirect('/admin/events');
});

test('admin can delete event', function () {
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

    $event = Event::create([
        'venue_id' => $venue->id,
        'title' => 'BINI Signals World Tour',
        'category' => 'Concert',
        'starts_at' => Carbon::parse('2027-07-12 21:44:00'),
        'base_price' => 120.22,
    ]);

    actingAs($admin)
        ->delete('/admin/events/'.$event->id)
        ->assertRedirect('/admin/events');
});

test('admin cannot edit venues when event seats are generated?', function () {
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
        ->post('/admin/events', $event)
        ->assertValid()
        ->assertRedirect('/admin/events');

    expect(Seat::count())->toBe(150);

    $editedVenue = [
        'name' => 'MOA Arena',
        'city' => 'Pasig City',
        'layout' => [
            'sections' => [
                ['name' => 'VVIP', 'rows' => '3', 'seats_per_row' => 10],
                ['name' => 'VIP', 'rows' => '3', 'seats_per_row' => 10],
                ['name' => 'GenAd', 'rows' => '3', 'seats_per_row' => 10],
            ],
        ],
    ];

    actingAs($admin)
        ->patch('/admin/venues/'.$venue->id, $editedVenue)
        ->assertValid()
        ->assertRedirect('/admin/venues');

    expect(Seat::count())->toBe(150);
});
