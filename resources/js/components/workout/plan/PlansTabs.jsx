
import { NavLink } from "react-router-dom";
import { ClipboardList, History } from "lucide-react";

const DEFAULT_TABS = [
    {
        label: "الخطط الحالية",
        to: "/dashboard/current-plans",
        icon: ClipboardList,
    },
    {
        label: "الإصدارات السابقة",
        to: "/dashboard/versions",
        icon: History,
    },
];

export default function PlansTabs({
    tabs = DEFAULT_TABS,
    className = "",
}) {
    return (
        <nav
            dir="rtl"
            aria-label="التنقل بين أقسام الخطط"
            className={`mb-6 w-full ${className}`}
        >
            <div
                className="
                    inline-flex w-full items-center gap-1
                    rounded-xl bg-gray-100 p-1
                    sm:w-auto
                "
            >
                {tabs.map(({ label, to, icon: Icon }) => (
                    <NavLink
                        key={to}
                        to={to}
                        className={({ isActive }) => `
                            flex min-h-11 min-w-0 flex-1
                            items-center justify-center gap-2
                            rounded-lg px-4 py-2.5
                            text-sm font-medium
                            transition-colors duration-200
                            sm:flex-none sm:min-w-40
                            focus-visible:outline-none
                            focus-visible:ring-2
                            focus-visible:ring-[#407437]/40
                            ${
                                isActive
                                    ? "bg-white font-semibold text-[#407437] shadow-sm"
                                    : "text-gray-500 hover:bg-white/60 hover:text-gray-800"
                            }
                        `}
                    >
                        {({ isActive }) => (
                            <>
                                <Icon
                                    size={18}
                                    strokeWidth={isActive ? 2 : 1.75}
                                    className="shrink-0"
                                    aria-hidden="true"
                                />

                                <span className="truncate">
                                    {label}
                                </span>
                            </>
                        )}
                    </NavLink>
                ))}
            </div>
        </nav>
    );
}
