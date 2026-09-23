import { Button } from '@/components/ui/button';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import { Head, Link } from '@inertiajs/react';
import { SquarePen, Trash } from 'lucide-react';
import { create, edit, destroy } from '@/routes/events';

interface Event {
    id: number;
    venueId: number;
    title: string;
    category: string;
    starts_at: string;
    base_price: number;
    venue: {
        id: number;
        name: string;
        city: string;
    };
}

interface Props {
    events: Event[];
}

export default function Events({ events }: Props) {
    const handleDelete = (id: number) => {
        if (window.confirm('Are you sure you want to delete this venue?')) {
            destroy(id);
        }
    };

    return (
        <>
            <Head title={'Events'} />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <Button asChild>
                    <Link className={'w-40'} href={create()}>
                        Create
                    </Link>
                </Button>
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead className="w-[100px]">ID</TableHead>
                            <TableHead>Venue</TableHead>
                            <TableHead>City</TableHead>
                            <TableHead>Title</TableHead>
                            <TableHead>Category</TableHead>
                            <TableHead>Start at</TableHead>
                            <TableHead>Base Price</TableHead>
                            <TableHead>Actions</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        {events.map((event: Event) => (
                            <TableRow key={event.id}>
                                <TableCell>{event.id}</TableCell>
                                <TableCell>{event.venue.name}</TableCell>
                                <TableCell>{event.venue.city}</TableCell>
                                <TableCell>{event.title}</TableCell>
                                <TableCell>{event.category}</TableCell>
                                <TableCell>
                                    {new Date(event.starts_at).toLocaleString(
                                        'en-US',
                                        {
                                            dateStyle: 'medium',
                                            timeStyle: 'short',
                                        },
                                    )}
                                </TableCell>
                                <TableCell>{event.base_price}</TableCell>
                                <TableCell>
                                    <Button size={'icon'} asChild>
                                        <Link href={edit(event.id)}>
                                            <SquarePen />
                                        </Link>
                                    </Button>
                                    <Button
                                        onClick={() => handleDelete(event.id)}
                                        size={'icon'}
                                        variant={'destructive'}
                                    >
                                        <Trash />
                                    </Button>
                                </TableCell>
                            </TableRow>
                        ))}
                    </TableBody>
                </Table>
            </div>
        </>
    );
}
