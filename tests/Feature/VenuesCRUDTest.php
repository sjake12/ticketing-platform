<?php

use App\Models\User;
use App\Models\Venue;

use function Pest\Laravel\actingAs;

test('enter venues page', function () {
    $admin = User::factory()->create([
        'name' => 'Admin',
        'email' => 'admin@admin.com',
        'password' => bcrypt('secret'),
        'role' => 'admin',
    ]);

    actingAs($admin)
        ->get('/admin/venues')
        ->assertStatus(200);
});

test('create venues', function () {

    $admin = User::factory()->create([
        'name' => 'Admin',
        'email' => 'admin@admin.com',
        'password' => bcrypt('secret'),
        'role' => 'admin',
    ]);

    $newVenue = [
        'name' => 'Venue 1',
        'city' => 'Venue 1',
        'layout' => [
            'sections' => [
                ['name' => 'A', 'rows' => '5', 'seats_per_row' => 10],
                ['name' => 'B', 'rows' => '5', 'seats_per_row' => 10],
                ['name' => 'C', 'rows' => '5', 'seats_per_row' => 10],
            ],
        ],
    ];

    actingAs($admin)
        ->post('/admin/venues', $newVenue)
        ->assertValid()
        ->assertRedirect('/admin/venues');

    $venue = Venue::first();

    expect($venue)
        ->not->toBeNull()
        ->name->toBe($newVenue['name'])
        ->city->toBe($newVenue['city'])
        ->layout->toBe($newVenue['layout']);
});

test('update venues', function () {

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

    $updatedVenue = [
        'name' => 'Venue 1',
        'city' => 'City',
        'layout' => [
            'sections' => [
                ['name' => 'VIP', 'rows' => '3', 'seats_per_row' => 5],
                ['name' => 'Front', 'rows' => '3', 'seats_per_row' => 5],
                ['name' => 'Back', 'rows' => '3', 'seats_per_row' => 5],
            ],
        ],
    ];

    actingAs($admin)
        ->patch('/admin/venues/'.$venue->id, $updatedVenue)
        ->assertValid()
        ->assertRedirect('/admin/venues');

    $venue->refresh();

    expect($venue)
        ->name->toBe($updatedVenue['name'])
        ->city->toBe($updatedVenue['city'])
        ->layout->toBe($updatedVenue['layout']);
});

test('delete venues', function () {

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

    actingAs($admin)
        ->delete('/admin/venues/'.$venue->id)
        ->assertValid()
        ->assertRedirect('/admin/venues');
});
