import { Head, Link, router, usePage } from '@inertiajs/react';
import { useEffect, useMemo, useState } from 'react';
import PublicLayout from '@/layouts/public-app-layout';

type SeatStatus = 'available' | 'held_by_you' | 'locked' | 'sold';

type Seat = {
    id: string;
    section: string;
    row: number;
    number: number;
    status: 'available' | 'sold';
    display_status?: SeatStatus;
};

type Event = {
    id: string;
    title: string;
    category: string;
    starts_at: string;
    base_price: number;
    venue: {
        name: string;
        city: string;
    };
};

type Props = {
    event: Event;
    seats: Seat[];
};

const LOCK_TTL_SECONDS = 300; // must match SeatLockService::LOCK_TTL_SECONDS

export default function ShowCustomerEvents({ event, seats }: Props) {
    const { errors } = usePage().props as { errors: Record<string, string> };

    const heldSeat = seats.find((s) => s.display_status === 'held_by_you');
    const [secondsLeft, setSecondsLeft] = useState(LOCK_TTL_SECONDS);

    // Countdown for the currently held seat. Resets whenever the held seat changes.
    useEffect(() => {
        if (!heldSeat) return;

        setSecondsLeft(LOCK_TTL_SECONDS);
        const interval = setInterval(() => {
            setSecondsLeft((s) => {
                if (s <= 1) {
                    clearInterval(interval);
                    router.reload({ only: ['seats'] });
                    return 0;
                }
                return s - 1;
            });
        }, 1000);

        return () => clearInterval(interval);
    }, [heldSeat?.id]);

    const sections = useMemo(() => {
        const bySection: Record<string, Record<number, Seat[]>> = {};

        for (const seat of seats) {
            bySection[seat.section] ??= {};
            bySection[seat.section][seat.row] ??= [];
            bySection[seat.section][seat.row].push(seat);
        }

        return Object.entries(bySection).map(([sectionName, rows]) => ({
            name: sectionName,
            rows: Object.entries(rows)
                .sort(([a], [b]) => Number(a) - Number(b))
                .map(([rowNumber, rowSeats]) => ({
                    row: Number(rowNumber),
                    seats: rowSeats.sort((a, b) => a.number - b.number),
                })),
        }));
    }, [seats]);

    const availableCount = seats.filter((s) => s.status === 'available').length;

    const statusOf = (seat: Seat): SeatStatus => {
        if (seat.status === 'sold') return 'sold';
        return seat.display_status ?? 'available';
    };

    const seatClasses = (status: SeatStatus) => {
        switch (status) {
            case 'available':
                return 'bg-white border-gray-300 hover:border-black hover:bg-gray-50 cursor-pointer';
            case 'held_by_you':
                return 'bg-green-100 border-green-500 cursor-pointer';
            case 'locked':
                return 'bg-yellow-100 border-yellow-400 cursor-not-allowed opacity-70';
            case 'sold':
                return 'bg-gray-300 border-gray-300 cursor-not-allowed opacity-50';
        }
    };

    const handleSeatClick = (seat: Seat) => {
        const status = statusOf(seat);

        if (status === 'available') {
            // Releasing any other held seat first isn't handled server-side yet —
            // for now we only allow one held seat at a time in the UI.
            if (heldSeat && heldSeat.id !== seat.id) {
                return;
            }
            router.post(`/seats/${seat.id}/lock`, {}, { preserveScroll: true });
            return;
        }

        if (status === 'held_by_you') {
            router.post(
                `/seats/${seat.id}/release`,
                {},
                { preserveScroll: true },
            );
        }
    };

    const formatCountdown = (totalSeconds: number) => {
        const m = Math.floor(totalSeconds / 60);
        const s = totalSeconds % 60;
        return `${m}:${s.toString().padStart(2, '0')}`;
    };

    return (
        <>
            <Head title={event.title} />

            <div className="mx-auto max-w-5xl px-4 py-8">
                <Link
                    href="/events"
                    className="text-sm text-gray-500 hover:underline"
                >
                    ← Back to events
                </Link>

                <div className="mt-4 mb-6">
                    <span className="text-xs font-semibold tracking-wide text-gray-500 uppercase">
                        {event.category}
                    </span>
                    <h1 className="mt-1 text-3xl font-bold">{event.title}</h1>
                    <p className="mt-1 text-gray-600">
                        {event.venue.name} — {event.venue.city}
                    </p>
                    <p className="mt-1 text-gray-500">
                        {new Date(event.starts_at).toLocaleDateString(
                            undefined,
                            {
                                weekday: 'long',
                                month: 'long',
                                day: 'numeric',
                                year: 'numeric',
                                hour: 'numeric',
                                minute: '2-digit',
                            },
                        )}
                    </p>
                    <p className="mt-2 font-semibold">
                        From ${Number(event.base_price).toFixed(2)} ·{' '}
                        {availableCount} seats available
                    </p>
                </div>

                {errors?.seat && (
                    <div className="mb-4 rounded border border-red-300 bg-red-50 px-4 py-2 text-red-700">
                        {errors.seat}
                    </div>
                )}

                {heldSeat && (
                    <div className="mb-6 flex items-center justify-between rounded-lg border border-green-400 bg-green-50 p-4">
                        <div>
                            <p className="font-semibold">
                                Holding Section {heldSeat.section}, Row{' '}
                                {heldSeat.row}, Seat {heldSeat.number}
                            </p>
                            <p className="text-sm text-gray-600">
                                Reservation expires in{' '}
                                {formatCountdown(secondsLeft)}
                            </p>
                        </div>
                        <div className="flex gap-2">
                            <button
                                onClick={() =>
                                    router.post(`/seats/${heldSeat.id}/release`)
                                }
                                className="rounded border px-4 py-2 hover:bg-gray-50"
                            >
                                Release
                            </button>
                            <Link
                                href={`/reservations/${heldSeat.id}/checkout`}
                                className="rounded bg-black px-4 py-2 text-white hover:bg-gray-800"
                            >
                                Proceed to checkout
                            </Link>
                        </div>
                    </div>
                )}

                <div className="mb-6 flex gap-4 text-sm">
                    <div className="flex items-center gap-2">
                        <span className="h-4 w-4 rounded border border-gray-300 bg-white" />
                        Available
                    </div>
                    <div className="flex items-center gap-2">
                        <span className="h-4 w-4 rounded border border-green-500 bg-green-100" />
                        Held by you
                    </div>
                    <div className="flex items-center gap-2">
                        <span className="h-4 w-4 rounded border border-yellow-400 bg-yellow-100" />
                        Held by another user
                    </div>
                    <div className="flex items-center gap-2">
                        <span className="h-4 w-4 rounded border border-gray-300 bg-gray-300" />
                        Sold
                    </div>
                </div>

                <div className="space-y-8">
                    {sections.map((section) => (
                        <div key={section.name}>
                            <h2 className="mb-3 text-lg font-semibold">
                                Section {section.name}
                            </h2>
                            <div className="space-y-2 overflow-x-auto pb-2">
                                {section.rows.map((row) => (
                                    <div
                                        key={row.row}
                                        className="flex items-center gap-2"
                                    >
                                        <span className="w-8 shrink-0 text-xs text-gray-400">
                                            Row {row.row}
                                        </span>
                                        <div className="flex gap-1">
                                            {row.seats.map((seat) => {
                                                const status = statusOf(seat);
                                                const clickable =
                                                    status === 'available' ||
                                                    status === 'held_by_you';

                                                return (
                                                    <button
                                                        key={seat.id}
                                                        type="button"
                                                        disabled={!clickable}
                                                        onClick={() =>
                                                            handleSeatClick(
                                                                seat,
                                                            )
                                                        }
                                                        title={`Section ${seat.section}, Row ${seat.row}, Seat ${seat.number}`}
                                                        className={`flex h-8 w-8 items-center justify-center rounded border text-xs transition-colors ${seatClasses(
                                                            status,
                                                        )}`}
                                                    >
                                                        {seat.number}
                                                    </button>
                                                );
                                            })}
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </div>
                    ))}
                </div>
            </div>
        </>
    );
}

ShowCustomerEvents.layout = (props: React.ReactNode) => (
    <PublicLayout>{props}</PublicLayout>
);
