import {Head, useForm, router} from "@inertiajs/react";
import {Input} from "@/components/ui/input";
import {Button} from "@/components/ui/button";
import React, { SubmitEvent } from 'react';
import {VenueFormData} from "@/types";


export default function CreatVenue(){

    const { data, setData, post, processing, reset } = useForm<VenueFormData>({
       name: '',
       city: '',
    });

    const handleSubmit = (e: SubmitEvent<HTMLFormElement>) => {
        e.preventDefault();

        post('/venues', {
            onSuccess: () => reset()
        })
    }

    return (
        <>
            <Head title={'Create Venue'}/>
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <Button className={'w-40'} onClick={() => router.visit('/venues')}>Back to Venues</Button>
                <form className={'w-[500px] flex flex-col gap-4'} onSubmit={handleSubmit}>
                    <Input
                        id={'name'}
                        type={'text'}
                        value={data.name}
                        onChange={(e) => setData('name', e.target.value)}
                        placeholder={'Enter venue name'}
                        //error handling will be implemented later
                    />
                    <Input
                        id={'city'}
                        type={'text'}
                        value={data.city}
                        onChange={(e) => setData('city', e.target.value)}
                        placeholder={'Enter venue location(city)'}
                        //error handling will be implemented later
                    />
                    <Button type="submit" disabled={processing}>{processing ? 'Submitting' : 'Create'}</Button>
                </form>
            </div>
        </>
    )
}
