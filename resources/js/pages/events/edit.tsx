import { Head, router, useForm } from '@inertiajs/react';
import {Button} from "@/components/ui/button";
import {Field, FieldLabel} from "@/components/ui/field";
import {Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue} from "@/components/ui/select";
import {Input} from "@/components/ui/input";
import React, { SubmitEvent } from "react";

interface Event {
    id: number;
    venue_id: number;
    title: string;
    category: string;
    starts_at: string;
    base_price: number;
}

interface EventForm {
    venue_id: number;
    title: string;
    category: string;
    starts_at: string;
    base_price: number;
}

interface Venue {
    id: number;
    name: string;
}

interface PageProps {
    event: Event;
    venues: Venue[];
}

export default function EditEvent({event, venues}: PageProps) {

    const { data, setData, patch, processing } = useForm<EventForm>({
        venue_id: event.venue_id,
        title: event.title,
        category: event.category,
        starts_at: event.starts_at,
        base_price: event.base_price,
    });

    const handleSubmit = (e: SubmitEvent<HTMLFormElement>)=> {
        e.preventDefault();

        patch(`/events/${event.id}`);
    };

    return (
        <>
            <Head title="Edit Event" />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <Button className={'w-40'} onClick={() => router.visit('/events')}>Back to Events</Button>
                <form className="w-[500px] flex flex-col gap-4" onSubmit={handleSubmit}>
                    <Field>
                        <FieldLabel htmlFor="event-venue">Venue</FieldLabel>
                        <Select
                            value={data.venue_id ? data.venue_id.toString() : ''}
                            onValueChange={(value) => setData('venue_id', Number(value))}
                        >
                            <SelectTrigger>
                                <SelectValue placeholder="Select Venue"/>
                            </SelectTrigger>
                            <SelectContent>
                                <SelectGroup>
                                    {venues.map((venue) => (
                                        <SelectItem key={venue.id} value={`${venue.id}`}>{venue.name}</SelectItem>
                                    ))}
                                </SelectGroup>
                            </SelectContent>
                        </Select>
                    </Field>
                    <Field>
                        <FieldLabel htmlFor="Title">Title</FieldLabel>
                        <Input
                            id="title"
                            type="text"
                            value={data.title}
                            onChange={(e) => setData('title', e.target.value)}
                            placeholder="Event Title"
                        />
                    </Field>
                    <Field>
                        <FieldLabel htmlFor="Category">Category</FieldLabel>
                        <Input
                            id="category"
                            type="text"
                            value={data.category}
                            onChange={(e) => setData('category', e.target.value)}
                            placeholder="Category"
                        />
                    </Field>
                    <Field>
                        <FieldLabel htmlFor="Starts at">Starts at</FieldLabel>
                        <Input
                            id="starts_at"
                            type="datetime-local"
                            value={data.starts_at}
                            onChange={(e) => setData('starts_at', e.target.value.replace('T', ' '))}
                            placeholder="Time and Date"
                        />
                    </Field>
                    <Field>
                        <FieldLabel htmlFor="Base Price">Base Price</FieldLabel>
                        <Input
                            id="base_price"
                            type="number"
                            value={data.base_price}
                            step={"0.01"}
                            onChange={(e) => setData('base_price', e.target.valueAsNumber)}
                        />
                    </Field>
                    <Button type="submit">Update Event</Button>
                </form>
            </div>
        </>
    )
}
