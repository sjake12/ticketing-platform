<?php

use App\Models\Seat;
use App\Models\User;
use Illuminate\Support\Facades\Redis;

use function Pest\Laravel\actingAs;

beforeEach(function () {
    Redis::flushDB();
});

it('locks a seat for an authenticated user', /** @throws JsonException */ function () {
    $user = User::factory()->create();
    $seat = Seat::factory()->create(['status' => 'available']);

    actingAs($user)
        ->from('/events/1')
        ->post("/seats/{$seat->id}/lock")
        ->assertRedirect('/events/1')
        ->assertSessionHasNoErrors()
        ->assertSessionHas('locked_seat_id', $seat->id);
});

it('rejects a lock attempt on an already-locked seat', function () {
    $userA = User::factory()->create();
    $userB = User::factory()->create();
    $seat = Seat::factory()->create(['status' => 'available']);

    actingAs($userA)->post("/seats/{$seat->id}/lock");

    actingAs($userB)
        ->post("/seats/{$seat->id}/lock")
        ->assertSessionHasErrors('seat');
});
