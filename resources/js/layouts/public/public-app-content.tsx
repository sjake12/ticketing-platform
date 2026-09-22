import { PropsWithChildren } from "react";
export default function PublicAppContent({children}: PropsWithChildren ) {
    return (
        <main
            className=""
        >
            { children }
        </main>
    )
}
