import { useForm } from '@inertiajs/react';
import {Input} from "@/components/ui/input";
import {Button} from "@/components/ui/button";

type Section = {
    name: string;
    rows: number;
    seats_per_row: number;
};

export default function CreateVenue() {
    const { data, setData, post, processing, errors } = useForm({
        name: '',
        city: '',
        layout: {
            sections: [{ name: 'A', rows: 5, seats_per_row: 10 }] as Section[],
        },
    });

    const updateSection = (index: number, field: keyof Section, value: string | number) => {
        const sections = [...data.layout.sections];
        sections[index] = { ...sections[index], [field]: value };
        setData('layout', { sections });
    };

    const addSection = () => {
        setData('layout', {
            sections: [...data.layout.sections, { name: '', rows: 5, seats_per_row: 10 }],
        });
    };

    const removeSection = (index: number) => {
        setData('layout', {
            sections: data.layout.sections.filter((_, i) => i !== index),
        });
    };

    const totalSeats = data.layout.sections.reduce(
        (sum, s) => sum + s.rows * s.seats_per_row,
        0
    );

    const submit = (e: React.FormEvent) => {
        e.preventDefault();
        post('/venues');
    };

    return (
        <form onSubmit={submit} className="space-y-6 w-[600px] flex flex-col p-4">
            <div>
                <label>Venue Name</label>
                <Input value={data.name} onChange={(e) => setData('name', e.target.value)} />
                {errors.name && <p className="text-red-600">{errors.name}</p>}
            </div>

            <div>
                <label>City</label>
                <Input value={data.city} onChange={(e) => setData('city', e.target.value)} />
                {errors.city && <p className="text-red-600">{errors.city}</p>}
            </div>

            <div>
                <h3>Seating Sections</h3>
                {data.layout.sections.map((section, i) => (
                    <div key={i} className="flex gap-2 items-center border p-3 rounded mb-2">
                        <Input
                            placeholder="Section name (e.g. VIP, A)"
                            value={section.name}
                            onChange={(e) => updateSection(i, 'name', e.target.value)}
                            className="w-32"
                        />
                        <Input
                            type="number"
                            placeholder="Rows"
                            value={section.rows}
                            onChange={(e) => updateSection(i, 'rows', Number(e.target.value))}
                            className="w-20"
                        />
                        <span>rows ×</span>
                        <Input
                            type="number"
                            placeholder="Seats per row"
                            value={section.seats_per_row}
                            onChange={(e) => updateSection(i, 'seats_per_row', Number(e.target.value))}
                            className="w-20"
                        />
                        <span>seats</span>
                        <span className="text-gray-500">
              = {section.rows * section.seats_per_row} seats
            </span>
                        {data.layout.sections.length > 1 && (
                            <button type="button" onClick={() => removeSection(i)} className="text-red-600">
                                Remove
                            </button>
                        )}
                        {errors[`layout.sections.${i}.name`] && (
                            <p className="text-red-600 text-sm">{errors[`layout.sections.${i}.name`]}</p>
                        )}
                    </div>
                ))}
                <Button type="button" onClick={addSection}>+ Add Section</Button>
            </div>

            <p className="font-semibold">Total seats: {totalSeats}</p>

            <Button type="submit" disabled={processing}>Create Venue</Button>
        </form>
    );
}
