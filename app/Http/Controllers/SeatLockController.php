<?php

namespace App\Http\Controllers;

use App\Models\Seat;
use App\Services\SeatLockService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SeatLockController extends Controller
{
    public function __construct(private readonly SeatLockService $lockService) {}

    public function lock(Request $request, Seat $seat): RedirectResponse
    {
        if ($seat->status !== 'available') {
            return back()->withErrors([
                'seat' => 'This seat is no longer available.',
            ]);
        }

        $userId = (string) $request->user()->id;

        $acquired = $this->lockService->lock($seat, $userId);

        if (! $acquired) {
            return back()->withErrors([
                'seat' => 'This seat was just taken by someone else.',
            ]);
        }

        return back()->with(['locked_seat_id' => $seat->id]);
    }

    public function release(Request $request, Seat $seat): RedirectResponse
    {
        $userId = (string) $request->user()->id;

        $this->lockService->release($seat, $userId);

        return back();
    }
}
