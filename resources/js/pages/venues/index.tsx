import { Head, Link } from '@inertiajs/react';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import { Button } from '@/components/ui/button';
import { SquarePen, Trash } from 'lucide-react';
import { VenuesPageProps } from '@/types';
import { create, edit, destroy } from '@/routes/venues';
import { buttonVariants } from '@/components/ui/button';

interface Venues {
    id: number;
    name: string;
    city: string;
}

export default function Venues({ venues }: VenuesPageProps) {
    const handleDelete = (id: number) => {
        if (window.confirm('Are you sure you want to delete this venue?')) {
            destroy(id);
        }
    };

    return (
        <>
            <Head title={'Venues'} />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <Link
                    href={create()}
                    className={buttonVariants({ className: 'w-40' })}
                >
                    Create
                </Link>
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead className="w-[100px]">ID</TableHead>
                            <TableHead>Name</TableHead>
                            <TableHead>City</TableHead>
                            <TableHead>Actions</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        {venues.map((venue: Venues) => (
                            <TableRow key={venue.id}>
                                <TableCell>{venue.id}</TableCell>
                                <TableCell>{venue.name}</TableCell>
                                <TableCell>{venue.city}</TableCell>
                                <TableCell>
                                    <Link
                                        href={edit(venue.id)}
                                        className={buttonVariants({
                                            size: 'icon',
                                        })}
                                    >
                                        <SquarePen />
                                    </Link>
                                    <Button
                                        size={'icon'}
                                        variant={'destructive'}
                                        onClick={() => handleDelete(venue.id)}
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
