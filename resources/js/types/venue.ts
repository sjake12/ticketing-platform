export type Venues = {
    id: number;
    name: string;
    city: string;
};

export type VenuesPageProps = {
    venues: Venues[];
};

export type VenueFormData = {
    name: string;
    city: string;
};
