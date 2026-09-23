import { Head, useForm, Link } from '@inertiajs/react';
import { Input } from '@/components/ui/input';
import { Button, buttonVariants } from '@/components/ui/button';
import { VenueFormData } from '@/types';
import React, { SubmitEvent } from 'react';
import { index as venues } from '@/routes/venues';

interface Props {
    venue: {
        id: number;
        name: string;
        city: string;
    };
}

export default function EditVenue({ venue }: Props) {
    console.log(venue);

    const { data, setData, patch, processing, reset } = useForm<VenueFormData>({
        name: venue.name,
        city: venue.city,
    });

    const handleSubmit = (e: SubmitEvent<HTMLFormElement>) => {
        e.preventDefault();

        patch(`/venues/${venue.id}`, {
            onSuccess: () => reset(),
        });
    };

    return (
        <>
            <Head title={'Edit Venue'} />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                    <Link
                        href={venues()}
                        className={buttonVariants({ className: 'w-40' })}
                    >
                        Back to Venues
                    </Link>
                    <h1>Edit Venue</h1>
                    <form
                        className={'flex w-[500px] flex-col gap-4'}
                        onSubmit={handleSubmit}
                    >
                        <Input
                            id={'name'}
                            type={'text'}
                            value={data.name}
                            onChange={(e) => setData('name', e.target.value)}
                            //error handling will be implemented later
                        />
                        <Input
                            id={'city'}
                            type={'text'}
                            value={data.city}
                            onChange={(e) => setData('city', e.target.value)}
                            //error handling will be implemented later
                        />
                        <Button type="submit" disabled={processing}>
                            {processing ? 'Submitting' : 'Update'}
                        </Button>
                    </form>
                </div>
            </div>
        </>
    );
}
