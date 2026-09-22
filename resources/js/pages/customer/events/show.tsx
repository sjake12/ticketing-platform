import { Head, Link } from '@inertiajs/react';
import { useMemo } from 'react';
import PublicLayout from '@/layouts/public-app-layout';

type Seat = {
    id: string;
    section: string;
    row: number;
    number: number;
    status: 'available' | 'locked' | 'sold';
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
    seats: Seat[];
};

type Props = {
    event: Event;
};

export default function ShowCustomerEvents({ event }: Props) {
    // Group seats by section, then by row, for rendering the grid
    const sections = useMemo(() => {
        const bySection: Record<string, Record<number, Seat[]>> = {};

        for (const seat of event.seats) {
            bySection[seat.section] ??= {};
            bySection[seat.section][seat.row] ??= [];
            bySection[seat.section][seat.row].push(seat);
        }

        // Sort rows and seats within each section for a clean grid
        return Object.entries(bySection).map(([sectionName, rows]) => ({
            name: sectionName,
            rows: Object.entries(rows)
                .sort(([a], [b]) => Number(a) - Number(b))
                .map(([rowNumber, seats]) => ({
                    row: Number(rowNumber),
                    seats: seats.sort((a, b) => a.number - b.number),
                })),
        }));
    }, [event.seats]);

    const availableCount = event.seats.filter(
        (s) => s.status === 'available',
    ).length;

    const seatClasses = (status: Seat['status']) => {
        switch (status) {
            case 'available':
                return 'bg-white border-gray-300 hover:border-black hover:bg-gray-50 cursor-pointer';
            case 'locked':
                return 'bg-yellow-100 border-yellow-400 cursor-not-allowed opacity-70';
            case 'sold':
                return 'bg-gray-300 border-gray-300 cursor-not-allowed opacity-50';
        }
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

                {/* Event header */}
                <div className="mt-4 mb-8">
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

                {/* Legend */}
                <div className="mb-6 flex gap-4 text-sm">
                    <div className="flex items-center gap-2">
                        <span className="h-4 w-4 rounded border border-gray-300 bg-white" />
                        Available
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

                {/* Seat map */}
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
                                            {row.seats.map((seat) => (
                                                <button
                                                    key={seat.id}
                                                    type="button"
                                                    disabled={
                                                        seat.status !==
                                                        'available'
                                                    }
                                                    title={`Section ${seat.section}, Row ${seat.row}, Seat ${seat.number}`}
                                                    className={`flex h-8 w-8 items-center justify-center rounded border text-xs transition-colors ${seatClasses(
                                                        seat.status,
                                                    )}`}
                                                >
                                                    {seat.number}
                                                </button>
                                            ))}
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
