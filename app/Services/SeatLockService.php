<?php

namespace App\Services;

use App\Models\Seat;
use Illuminate\Support\Facades\Redis;
use Throwable;

class SeatLockService
{
    private const int LOCK_TTL_SECONDS = 300;

    private function lockKey(int $seatId): string
    {
        return "seat_lock:{$seatId}";
    }

    /**
     *  Attempt to lock a seat for a user. Returns true if the lock was
     *  acquired, false if the seat is already locked by someone else.
     */
    public function lock(Seat $seat, string $userId): bool
    {
        $key = $this->lockKey($seat->id);

        // SET key value NX EX seconds - if not exists(NX)
        try {
            $acquired = Redis::command('set', [
                $key,
                $userId,
                ['NX', 'EX' => self::LOCK_TTL_SECONDS],
            ]);
            return (bool) $acquired;
        } catch (Throwable $e) {
            return false;
        }
    }

    /**
     *  Release the lock - but only if the user is the one holding it
     *  Prevents user A from releasing a lock user B is holding.
     */
    public function release(Seat $seat, string $userId): bool
    {
        $key = $this->lockKey($seat->id);
        $holder = Redis::get($key);

        if ($holder !== $userId) {
            return false;
        }

        Redis::del($key);

        return true;
    }

    /**
     *  Check who currently holds the lock.
     */
    public function lockedBy(Seat $seat): ?string
    {
        return Redis::get($this->lockKey($seat->id));
    }

    public function isLockedByOther(Seat $seat, string $userId): bool
    {
        $holder = $this->lockKey($seat->id);

        return $holder !== $userId;
    }
}
