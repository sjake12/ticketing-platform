import { Head, Link, router } from '@inertiajs/react';
import { useState } from 'react';
import PublicLayout from '@/layouts/public-app-layout';
import { ReactNode } from 'react';

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
    events: Event[];
    filters: {
        search?: string;
        category?: string;
        city?: string;
    };
};

const CATEGORIES = ['Concert', 'Sports', 'Theatre', 'Comedy'];

export default function CustomerEvents({ events, filters }: Props) {
    const [search, setSearch] = useState(filters.search ?? '');
    const [category, setCategory] = useState(filters.category ?? '');
    const [city, setCity] = useState(filters.city ?? '');

    const applyFilters = (overrides: Partial<Props['filters']> = {}) => {
        router.get(
            '/events',
            {
                search,
                category,
                city,
                ...overrides,
            },
            { preserveState: true, replace: true },
        );
    };

    const clearFilters = () => {
        setSearch('');
        setCategory('');
        setCity('');
        router.get('/events', {}, { preserveState: true, replace: true });
    };

    return (
        <>
            <Head title="Browse Events" />

            <div className="mx-auto max-w-6xl px-4 py-8">
                <h1 className="mb-6 text-3xl font-bold">Upcoming Events</h1>

                {/* Filters */}
                <div className="mb-8 flex flex-wrap gap-3 rounded-lg bg-gray-50 p-4">
                    <input
                        type="text"
                        placeholder="Search events..."
                        value={search}
                        onChange={(e) => setSearch(e.target.value)}
                        onKeyDown={(e) => e.key === 'Enter' && applyFilters()}
                        className="min-w-[200px] flex-1 rounded border px-3 py-2"
                    />

                    <select
                        value={category}
                        onChange={(e) => {
                            setCategory(e.target.value);
                            applyFilters({ category: e.target.value });
                        }}
                        className="rounded border px-3 py-2"
                    >
                        <option value="">All categories</option>
                        {CATEGORIES.map((c) => (
                            <option key={c} value={c}>
                                {c}
                            </option>
                        ))}
                    </select>

                    <input
                        type="text"
                        placeholder="City"
                        value={city}
                        onChange={(e) => setCity(e.target.value)}
                        onKeyDown={(e) => e.key === 'Enter' && applyFilters()}
                        className="w-40 rounded border px-3 py-2"
                    />

                    <button
                        onClick={() => applyFilters()}
                        className="rounded bg-black px-4 py-2 text-white hover:bg-gray-800"
                    >
                        Search
                    </button>

                    {(search || category || city) && (
                        <button
                            onClick={clearFilters}
                            className="px-4 py-2 text-gray-600 hover:underline"
                        >
                            Clear
                        </button>
                    )}
                </div>

                {/* Results */}
                {events.length === 0 ? (
                    <div className="py-16 text-center text-gray-500">
                        <p className="text-lg">No events match your filters.</p>
                        <button
                            onClick={clearFilters}
                            className="mt-2 underline"
                        >
                            Clear filters and try again
                        </button>
                    </div>
                ) : (
                    <div className="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        {events.map((event) => (
                            <Link
                                key={event.id}
                                href={`/events/${event.id}`}
                                className="block overflow-hidden rounded-lg border transition-shadow hover:shadow-lg"
                            >
                                <div className="p-5">
                                    <span className="text-xs font-semibold tracking-wide text-gray-500 uppercase">
                                        {event.category}
                                    </span>
                                    <h2 className="mt-1 text-xl font-semibold">
                                        {event.title}
                                    </h2>
                                    <p className="mt-1 text-gray-600">
                                        {event.venue.name} — {event.venue.city}
                                    </p>
                                    <p className="mt-1 text-sm text-gray-500">
                                        {new Date(
                                            event.starts_at,
                                        ).toLocaleDateString(undefined, {
                                            weekday: 'short',
                                            month: 'short',
                                            day: 'numeric',
                                            year: 'numeric',
                                        })}
                                    </p>
                                    <p className="mt-3 font-semibold">
                                        From $
                                        {Number(event.base_price).toFixed(2)}
                                    </p>
                                </div>
                            </Link>
                        ))}
                    </div>
                )}
            </div>
        </>
    );
}

CustomerEvents.layout = (page: ReactNode) => (
    <PublicLayout>{page}</PublicLayout>
);
