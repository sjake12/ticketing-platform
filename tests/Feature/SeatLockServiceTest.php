<?php

use App\Models\Seat;
use App\Services\SeatLockService;

it('prevents two users from locking the same seat', function () {
    $seat = Seat::factory()->create();
    $service = app(SeatLockService::class);

    expect($service->lock($seat, 'user-1'))->toBeTrue()
        ->and($service->lock($seat, 'user-2'))->toBeFalse();
});

it('only allows the lock holder to release it', function () {
    $seat = Seat::factory()->create();
    $service = app(SeatLockService::class);

    $service->lock($seat, 'user-1');

    expect($service->release($seat, 'user-2'))->toBeFalse()
        ->and($service->release($seat, 'user-1'))->toBeTrue();
});
