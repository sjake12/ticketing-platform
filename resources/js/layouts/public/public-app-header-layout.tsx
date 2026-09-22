import { Link, usePage } from "@inertiajs/react";
import { dashboard, login, register } from "@/routes";
import { events, venues } from '@/routes/customer';
import AvatarDropdown from "@/components/avatar-dropdown";

export default function PublicLayoutHeader() {

    const { auth } = usePage().props;

    return (
        <header className="sticky top-0 z-50 flex w-full items-center justify-between bg-[#FDFDFC] p-6 text-[#1b1b18] dark:bg-[#0a0a0a] lg:p-8 shadow">
            <nav className="flex items-center justify-end gap-4">
                <Link
                    href={events()}
                    className="hover:underline text-lg font-bold"
                >
                    Events
                </Link>
                <Link
                    href={venues()}
                    className="hover:underline text-lg font-bold"
                >
                    Venues
                </Link>
            </nav>
            <nav className="flex items-center justify-end gap-4">
                {auth.user ? (
                    <AvatarDropdown/>
                ) : (
                    <>
                        <Link
                            href={login()}
                            className="inline-block rounded-sm border border-transparent px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#19140035] dark:text-[#EDEDEC] dark:hover:border-[#3E3E3A]"
                        >
                            Log in
                        </Link>
                        <Link
                            href={register()}
                            className="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                        >
                            Register
                        </Link>
                    </>
                )}
            </nav>
        </header>
    )
}
