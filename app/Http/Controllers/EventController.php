<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Venue;
use Illuminate\Http\Request;
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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'venue_id' => 'required',
            'title' => 'required',
            'category' => 'required',
            'starts_at' => 'required',
            'base_price' => 'required',
        ]);

        Event::create($validated);

        return Redirect::route('events.index')->with('success', 'Event created.');
    }

    public function show($id) {}

    public function edit($id)
    {
        $event = Event::findOrFail($id);
        $venues = Venue::all();

        return Inertia::render('events/edit', compact('event', 'venues'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'venue_id' => 'required',
            'title' => 'required',
            'category' => 'required',
            'starts_at' => 'required',
            'base_price' => 'required',
        ]);

        Event::findOrFail($id)->update($validated);

        return Redirect::route('events.index')->with('success', 'Event updated.');
    }

    public function destroy($id)
    {
        Event::findOrFail($id)->delete();

        return Redirect::route('events.index')->with('success', 'Event deleted.');
    }
}
