<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVenueRequest;
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

    public function store(StoreVenueRequest $request)
    {
        $validated = $request->validated();

        Venue::create($validated);

        return Redirect::route('venues.index')->with('success', 'Venue created.');
    }

    public function show(Venue $venue) {}

    public function edit(Venue $venue)
    {
        return Inertia::render('venues/edit', compact('venue'));
    }

    public function update(StoreVenueRequest $request, Venue $venue)
    {
        $validated = $request->validated();

        $venue->update($validated);

        return Redirect::route('venues.index');
    }

    public function destroy(Venue $venue)
    {
        $venue->delete();

        return Redirect::route('venues.index')->with('success', 'Venue has been deleted');
    }
}
