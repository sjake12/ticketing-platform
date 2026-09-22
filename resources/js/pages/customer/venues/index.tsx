import PublicLayout from "@/layouts/public-app-layout";

export default function CustomerVenue() {
    return (
        <div>
            Customer Venue
        </div>
    )
}

CustomerVenue.layout = (props: React.ReactNode) => <PublicLayout>{props}</PublicLayout>;
