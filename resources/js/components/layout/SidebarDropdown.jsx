
import { useState } from "react";
import { NavLink, useLocation } from "react-router-dom";
import { ChevronDown } from "lucide-react";

export default function SidebarDropdown({
    icon: Icon,
    label,
    items = [],
    matchPaths = [],
}) {
    const location = useLocation();

    const isPathActive = (path) =>
        location.pathname === path ||
        location.pathname.startsWith(`${path}/`);

    const isGroupActive =
        items.some((item) => isPathActive(item.to)) ||
        matchPaths.some(isPathActive);

    const [manualOpen, setManualOpen] = useState(null);

    // Open automatically on active routes,
    // but allow manual closing.
    const expanded = manualOpen ?? isGroupActive;

    return (
        <div dir="rtl" className="min-w-0 space-y-1">
            {/* Dropdown trigger */}
            <button
                type="button"
                onClick={() => setManualOpen(!expanded)}
                aria-expanded={expanded}
                className={`
                    flex w-full items-center justify-between
                    rounded-xl px-3 py-3 text-sm
                    transition-colors duration-200
                    ${
                        isGroupActive
                            ? "bg-[#407437] font-semibold text-[#EEF5EC]"
                            : "text-gray-600 hover:bg-gray-50"
                    }
                `}
            >
                <div className="flex min-w-0 items-center gap-3">
                    <Icon
                        size={20}
                        strokeWidth={1.8}
                        className="shrink-0"
                    />
                    <span className="truncate">{label}</span>
                </div>

                <ChevronDown
                    size={17}
                    className={`shrink-0 transition-transform duration-200 ${
                        expanded ? "rotate-180" : ""
                    }`}
                />
            </button>

            {/* Scrollable dropdown content */}
            {expanded && (
                <div
                    className="
                         max-h-48
                        overflow-y-auto overscroll-contain
                        border-r border-gray-200
                        pr-3 pl-1
                        sidebar-scroll
                    "
                >
                    <div className="space-y-1 py-1">
                        {items.map(
                            ({ label, to, icon: ItemIcon }) => (
                                <NavLink
                                    key={to}
                                    to={to}
                                    className={({ isActive }) => `
                                        flex min-h-10 items-center
                                        gap-2.5 rounded-lg
                                        px-3 py-2.5 text-[13px]
                                        transition-colors duration-200
                                        ${
                                            isActive
                                                ? "bg-[#EEF5EC] font-semibold text-[#407437]"
                                                : "text-gray-500 hover:bg-gray-50 hover:text-gray-800"
                                        }
                                    `}
                                >
                                    <ItemIcon
                                        size={17}
                                        strokeWidth={1.8}
                                        className="shrink-0"
                                    />

                                    <span className="truncate">
                                        {label}
                                    </span>
                                </NavLink>
                            )
                        )}
                    </div>
                </div>
            )}
        </div>
    );
}
