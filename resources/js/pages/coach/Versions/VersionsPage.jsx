import { useNavigate } from "react-router-dom";
import {
    Eye,
    History,
    User,
} from "lucide-react";

import PlansTabs from "../../../components/workout/plan/PlansTabs";

export default function VersionsPage() {
    const navigate = useNavigate();

    const trainees = [
        {
            id: 1,
            name: "Test User",
            email: "testuser@gmail.com",
            versions_count: 3,
            latest_version: 3,
            updated_at: "2026-10-18T10:24:00",
        },
        {
            id: 2,
            name: "Ali",
            email: "ali@example.com",
            versions_count: 2,
            latest_version: 2,
            updated_at: "2026-10-17T18:30:00",
        },
        {
            id: 3,
            name: "Ahmad",
            email: "ahmad@example.com",
            versions_count: 5,
            latest_version: 5,
            updated_at: "2026-10-15T12:10:00",
        },
    ];

    const handleOpenVersions = (trainee) => {
        navigate(`/dashboard/versions/${trainee.id}`);
    };

    return (
        <main
            dir="rtl"
            className="mx-auto max-w-7xl space-y-6 px-4 py-7 sm:px-6 lg:px-8"
        >
            <PlansTabs />

            {/* Header */}
            <div>
                <p className="mb-2 text-xs font-medium text-[#407437]">
                    SUPERFIT / WORKOUT HISTORY
                </p>

                <h1 className="text-2xl font-bold text-[#1A3D27] sm:text-3xl">
                    إصدارات خطط التمارين
                </h1>

                <p className="mt-2 text-sm text-gray-500">
                    استعرض إصدارات خطط التمارين لكل مشترك.
                </p>
            </div>

            {/* Table */}
            <div
                className="
                    overflow-hidden
                    rounded-2xl
                    border border-gray-200
                    bg-white
                "
            >
                {/* Header */}
                <div
                    className="
                        hidden
                        grid-cols-[2fr_1fr_1fr_1.3fr_1.4fr]
                        gap-4
                        border-b
                        border-gray-100
                        bg-gray-50/70
                        px-5 py-4
                        text-xs
                        font-semibold
                        text-gray-500
                        md:grid
                    "
                >
                    <div>المشترك</div>

                    <div>عدد الإصدارات</div>

                    <div>آخر إصدار</div>

                    <div>آخر تحديث</div>

                    <div>الإجراءات</div>
                </div>

                {/* Rows */}
                <div className="divide-y divide-gray-100">
                    {trainees.map((trainee) => (
                        <div
                            key={trainee.id}
                            className="
                                grid
                                grid-cols-1
                                gap-4
                                px-5 py-4
                                transition
                                hover:bg-[#FAFCF9]
                                md:grid-cols-[2fr_1fr_1fr_1.3fr_1.4fr]
                                md:items-center
                            "
                        >
                            {/* Trainee */}
                            <div className="flex items-center gap-3">
                                <div
                                    className="
                                        flex h-10 w-10
                                        items-center justify-center
                                        rounded-xl
                                        bg-[#EEF5EC]
                                        text-[#407437]
                                    "
                                >
                                    <User size={17} />
                                </div>

                                <div className="min-w-0">
                                    <p className="truncate text-sm font-bold text-gray-900">
                                        {trainee.name}
                                    </p>

                                    <p className="mt-1 truncate text-xs text-gray-400">
                                        {trainee.email}
                                    </p>
                                </div>
                            </div>

                            {/* Versions Count */}
                            <div>
                                <span
                                    className="
                                        inline-flex items-center gap-1.5
                                        rounded-full
                                        bg-[#EEF5EC]
                                        px-3 py-1.5
                                        text-xs font-semibold
                                        text-[#407437]
                                    "
                                >
                                    <History size={13} />

                                    {trainee.versions_count} إصدارات
                                </span>
                            </div>

                            {/* Latest Version */}
                            <div className="text-sm font-bold text-gray-700">
                                V{trainee.latest_version}
                            </div>

                            {/* Updated At */}
                            <div>
                                <p className="text-sm text-gray-700">
                                    {new Date(
                                        trainee.updated_at
                                    ).toLocaleDateString("ar")}
                                </p>

                                <p className="mt-1 text-xs text-gray-400">
                                    {new Date(
                                        trainee.updated_at
                                    ).toLocaleTimeString("ar", {
                                        hour: "2-digit",
                                        minute: "2-digit",
                                    })}
                                </p>
                            </div>

                            {/* Action */}
                            <div>
                                <button
                                    type="button"
                                    onClick={() =>
                                        handleOpenVersions(
                                            trainee
                                        )
                                    }
                                    className="
                                        inline-flex
                                        items-center
                                        gap-1.5
                                        rounded-lg
                                        bg-[#EEF5EC]
                                        px-3 py-2
                                        text-xs
                                        font-semibold
                                        text-[#407437]
                                        transition
                                        hover:bg-[#E1EEDF]
                                    "
                                >
                                    <Eye size={15} />

                                    عرض الإصدارات
                                </button>
                            </div>
                        </div>
                    ))}
                </div>
            </div>
        </main>
    );
}