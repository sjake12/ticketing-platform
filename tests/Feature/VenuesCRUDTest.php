<?php

use App\Models\Venue;

test('enter venues page', function () {
    $response = $this->get('/venues');

    $response->assertStatus(200);
});

test('create venues', function () {
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

    $response = $this->post('/venues', $newVenue);

    $response->assertValid();

    $response->assertRedirect('/venues');

    $venue = Venue::first();

    expect($venue)
        ->not->toBeNull()
        ->name->toBe($newVenue['name'])
        ->city->toBe($newVenue['city'])
        ->layout->toBe($newVenue['layout']);
});

test('update venues', function () {
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

    $response = $this->patch('/venues/'.$venue->id, $updatedVenue);

    $response->assertValid();

    $response->assertRedirect('/venues');

    $venue->refresh();

    expect($venue)
        ->name->toBe($updatedVenue['name'])
        ->city->toBe($updatedVenue['city'])
        ->layout->toBe($updatedVenue['layout']);
});

test('delete venues', function () {
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

    $response = $this->delete('/venues/'.$venue->id);
    $response->assertValid();

    $response->assertRedirect('/venues');
});
