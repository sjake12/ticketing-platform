import PublicLayoutHeader from "@/layouts/public/public-app-header-layout";
import PublicAppContent from "@/layouts/public/public-app-content";
import {PropsWithChildren} from "react";

export default function PublicLayout({children}: PropsWithChildren)  {
    return (
        <>
            <PublicLayoutHeader/>
            <PublicAppContent>
                {children}
            </PublicAppContent>
        </>
    )
}
