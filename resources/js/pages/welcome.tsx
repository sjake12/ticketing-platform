import { Head } from '@inertiajs/react';
import PublicLayout from "@/layouts/public-app-layout";
import { ReactNode } from "react";

export default function Welcome() {

    return (
        <>
            <Head title="Welcome" />
            <div className="">
                events display here
            </div>
        </>
    );
}

Welcome.layout = (page: ReactNode)  => <PublicLayout>{page}</PublicLayout>;
