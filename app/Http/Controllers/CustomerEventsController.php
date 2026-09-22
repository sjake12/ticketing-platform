<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CustomerEventsController extends Controller
{
    public function index(Request $request): Response
    {
        $events = Event::with('venue')
            ->when($request->search, fn ($q, $s) => $q->where('title', 'ilike', "%{$s}%"))
            ->when($request->category, fn ($q, $c) => $q->where('category', $c))
            ->when($request->city, fn ($q, $city) => $q->whereHas('venue', fn ($q) => $q->where('city', 'ilike', "%{$city}%")))
            ->orderBy('starts_at')
            ->get();

        return Inertia::render('customer/events/index', [
            'events' => $events,
            'filters' => $request->only(['search', 'category', 'city']),
        ]);
    }

    public function show(Event $event): Response
    {
        return Inertia::render('customer/events/show', [
            'event' => $event->load('venue', 'seats'),
        ]);
    }
}
