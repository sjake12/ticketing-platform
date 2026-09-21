<?php

namespace App\Services;

use App\Models\Event;
use App\Models\Seat;
use App\Models\Venue;

class SeatGeneratorService
{
    /**
     * Create a new class instance.
     */
    public function generateForEvent(Event $event, Venue $venue): void
    {
        $seats = [];
        $now = now();

        foreach ($venue->layout['sections'] as $section) {
            foreach (range(1, $section['rows']) as $row) {
                foreach (range(1, $section['seats_per_row']) as $number) {
                    $seats[] = [
                        'event_id' => $event->id,
                        'section' => $section['name'],
                        'row' => $row,
                        'number' => $number,
                        'status' => 'available',
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }
        }

        foreach (array_chunk($seats, 1000) as $chunk) {
            Seat::insert($chunk);
        }
    }
}
