import { NavLink } from "react-router-dom";

export default function PlansTabs() {
    const baseClass =
        "px-4 py-2.5 text-sm font-semibold rounded-xl transition";

    return (
        <div
            dir="rtl"
            className="
                mb-6
                flex w-fit items-center gap-2
                rounded-2xl
                border border-gray-200
                bg-white
                p-1.5
            "
        >
            <NavLink
                to="/dashboard/current-plans"
                className={({ isActive }) =>
                    `${baseClass} ${
                        isActive
                            ? "bg-[#407437] text-white"
                            : "text-gray-500 hover:bg-gray-50 hover:text-[#407437]"
                    }`
                }
            >
                الخطط الحالية
            </NavLink>

            <NavLink
                to="/dashboard/versions"
                className={({ isActive }) =>
                    `${baseClass} ${
                        isActive
                            ? "bg-[#407437] text-white"
                            : "text-gray-500 hover:bg-gray-50 hover:text-[#407437]"
                    }`
                }
            >
                الإصدارات السابقة
            </NavLink>
        </div>
    );
}