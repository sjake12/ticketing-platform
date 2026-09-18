<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class VenueController extends Controller
{
    public function index()
    {
        $venues = Venue::all();

        return Inertia::render('venues/index', compact('venues'));
    }

    public function create()
    {
        return Inertia::render('venues/create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'city' => 'required',
        ]);

        $newVenue = [
            'name' => $validated['name'],
            'city' => $validated['city'],
            'layout' => [
                'sections' => ['A', 'B', 'C'],
                'seats_per_row' => 10,
                'rows_per_section' => 5,
            ],
        ];

        Venue::create($newVenue);

        return Redirect::route('venues.index');
    }

    public function show(Venue $venue) {}

    public function edit(Venue $venue)
    {
        return Inertia::render('venues/edit', compact('venue'));
    }

    public function update(Request $request, Venue $venue)
    {
        $validated = $request->validate([
            'name' => 'required',
            'city' => 'required',
        ]);

        $updatedVenue = [
            'name' => $validated['name'],
            'city' => $validated['city'],
            'layout' => [
                'sections' => ['A', 'B', 'C'],
                'seats_per_row' => 10,
                'rows_per_section' => 5,
            ],
        ];

        $venue->update($updatedVenue);

        return Redirect::route('venues.index');
    }

    public function destroy(Venue $venue)
    {
        $venue->delete();

        return Redirect::route('venues.index')->with('success', 'Venue has been deleted');
    }
}
