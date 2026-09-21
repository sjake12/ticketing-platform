<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVenueRequest;
use App\Models\Venue;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class VenueController extends Controller
{
    public function index(): Response
    {
        $venues = Venue::all();

        return Inertia::render('venues/index', compact('venues'));
    }

    public function create(): Response
    {
        return Inertia::render('venues/create');
    }

    public function store(StoreVenueRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        Venue::create($validated);

        return Redirect::route('venues.index')->with('success', 'Venue created.');
    }

    public function show(Venue $venue) {}

    public function edit(Venue $venue): Response
    {
        return Inertia::render('venues/edit', compact('venue'));
    }

    public function update(StoreVenueRequest $request, Venue $venue): RedirectResponse
    {
        $validated = $request->validated();

        $venue->update($validated);

        return Redirect::route('venues.index');
    }

    public function destroy(Venue $venue): RedirectResponse
    {
        $venue->delete();

        return Redirect::route('venues.index')->with('success', 'Venue has been deleted');
    }
}
