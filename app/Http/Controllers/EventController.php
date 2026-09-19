<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventRequest;
use App\Models\Event;
use App\Models\Venue;
use App\Services\SeatGeneratorService;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::with('venue:id,name,city')->get();

        return Inertia::render('events/index', compact('events'));
    }

    public function create()
    {
        $venues = Venue::all();

        return Inertia::render('events/create', compact('venues'));
    }

    public function store(StoreEventRequest $request, SeatGeneratorService $seatGeneratorService)
    {
        $validated = $request->validated();

        $event = Event::create($validated);

        $venue = Venue::find($request->venue_id);

        $seatGeneratorService->generateForEvent($event, $venue);

        return Redirect::route('events.index')->with('success', 'Event created.');
    }

    public function show($id) {}

    public function edit($id)
    {
        $event = Event::findOrFail($id);
        $venues = Venue::all();

        return Inertia::render('events/edit', compact('event', 'venues'));
    }

    public function update(StoreEventRequest $request, $id, SeatGeneratorService $seatGeneratorService)
    {
        $validated = $request->validated();

        $event = Event::findOrFail($id);

        $event->update($validated);

        if ($event->wasChanged('venue_id')) {

            if ($event->seats()->where('status', '!=', 'available')->exists()) {
                return back()->withErrors([
                    'venue_id' => 'You cannot change the venue because tickets have already been sold or reserved.',
                ]);
            }

            $event->seats()->delete();

            $venue = Venue::find($event->venue_id);
            $seatGeneratorService->generateForEvent($event, $venue);
        }

        return Redirect::route('events.index')->with('success', 'Event updated.');
    }

    public function destroy($id)
    {
        Event::findOrFail($id)->delete();

        return Redirect::route('events.index')->with('success', 'Event deleted.');
    }
}
