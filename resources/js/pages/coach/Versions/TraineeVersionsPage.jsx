import { useNavigate, useParams } from "react-router-dom";
import {
    ArrowRight,
    Eye,
    History,
} from "lucide-react";

export default function TraineeVersionsPage() {
    const { traineeId } = useParams();
    const navigate = useNavigate();

    const mockTrainee = {
        id: traineeId,
        name: "Test User",
        email: "testuser@gmail.com",
    };

    const mockVersions = [
        {
            id: 31,
            version: 3,
            status: "completed",
            created_at: "2026-10-18T10:24:00",
            workout_exercises_count: 12,
        },
        {
            id: 24,
            version: 2,
            status: "completed",
            created_at: "2026-10-10T09:10:00",
            workout_exercises_count: 10,
        },
        {
            id: 11,
            version: 1,
            status: "cancelled",
            created_at: "2026-10-01T13:40:00",
            workout_exercises_count: 8,
        },
    ];

    const getStatus = (status) => {
        const statuses = {
            active: {
                label: "نشطة",
                className:
                    "bg-[#EEF5EC] text-[#407437]",
            },

            completed: {
                label: "مكتملة",
                className:
                    "bg-blue-50 text-blue-600",
            },

            cancelled: {
                label: "ملغاة",
                className:
                    "bg-red-50 text-red-600",
            },
        };

        return (
            statuses[status] || {
                label: status || "غير محدد",
                className:
                    "bg-gray-100 text-gray-600",
            }
        );
    };

    return (
        <main
            dir="rtl"
            className="
                mx-auto
                max-w-7xl
                space-y-6
                px-4 py-7
                sm:px-6
                lg:px-8
            "
        >
            {/* Back */}
            <button
                type="button"
                onClick={() =>
                    navigate("/dashboard/versions")
                }
                className="
                    inline-flex
                    items-center gap-2
                    rounded-xl
                    border border-gray-200
                    bg-white
                    px-4 py-2.5
                    text-sm
                    text-gray-600
                    transition
                    hover:border-[#C8DCC3]
                    hover:text-[#407437]
                "
            >
                <ArrowRight size={17} />

                رجوع
            </button>

            {/* Header */}
            <div>
                <p className="mb-2 text-xs font-medium text-[#407437]">
                    SUPERFIT / WORKOUT VERSIONS
                </p>

                <h1 className="text-2xl font-bold text-gray-900 sm:text-3xl">
                    إصدارات خطة التمارين
                </h1>

                <p className="mt-2 text-sm text-gray-500">
                    جميع الإصدارات السابقة الخاصة بالمشترك.
                </p>
            </div>

            {/* Trainee */}
            <section
                className="
                    flex items-center gap-3
                    rounded-2xl
                    border border-[#DCE8D8]
                    bg-[#F4F9F2]
                    p-4
                "
            >
                <div
                    className="
                        flex h-11 w-11
                        items-center justify-center
                        rounded-xl
                        bg-white
                        text-[#407437]
                    "
                >
                    <History size={20} />
                </div>

                <div>
                    <p className="text-xs text-gray-500">
                        المشترك
                    </p>

                    <p className="mt-1 font-bold text-[#1A3D27]">
                        {mockTrainee.name}
                    </p>

                    <p className="mt-1 text-xs text-gray-400">
                        {mockTrainee.email}
                    </p>
                </div>
            </section>

            {/* Versions Table */}
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
                        grid-cols-[1fr_1fr_1.5fr_1fr_1fr]
                        gap-4
                        border-b border-gray-100
                        bg-gray-50/70
                        px-5 py-4
                        text-xs font-semibold
                        text-gray-500
                        md:grid
                    "
                >
                    <div>الإصدار</div>
                    <div>الحالة</div>
                    <div>تاريخ الإنشاء</div>
                    <div>عدد التمارين</div>
                    <div>الإجراءات</div>
                </div>

                {/* Rows */}
                <div className="divide-y divide-gray-100">
                    {mockVersions.map((version) => {
                        const status =
                            getStatus(
                                version.status
                            );

                        return (
                            <div
                                key={version.id}
                                className="
                                    grid
                                    grid-cols-1
                                    gap-4
                                    px-5 py-4
                                    transition
                                    hover:bg-[#FAFCF9]
                                    md:grid-cols-[1fr_1fr_1.5fr_1fr_1fr]
                                    md:items-center
                                "
                            >
                                {/* Version */}
                                <div>
                                    <p className="text-sm font-bold text-gray-900">
                                        V
                                        {
                                            version.version
                                        }
                                    </p>
                                </div>

                                {/* Status */}
                                <div>
                                    <span
                                        className={`
                                            inline-flex
                                            items-center
                                            gap-1.5
                                            rounded-full
                                            px-3 py-1.5
                                            text-xs
                                            font-semibold
                                            ${status.className}
                                        `}
                                    >
                                        <span className="h-1.5 w-1.5 rounded-full bg-current" />

                                        {
                                            status.label
                                        }
                                    </span>
                                </div>

                                {/* Date */}
                                <div className="text-sm text-gray-700">
                                    {new Date(
                                        version.created_at
                                    ).toLocaleDateString(
                                        "ar"
                                    )}
                                </div>

                                {/* Exercises */}
                                <div className="text-sm font-semibold text-gray-700">
                                    {
                                        version.workout_exercises_count
                                    }{" "}
                                    تمرين
                                </div>

                                {/* Action */}
                                <div>
                                    <button
                                        type="button"
                                        onClick={() =>
                                            navigate(
                                                `/dashboard/versions/${traineeId}/${version.id}`
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
                                        <Eye
                                            size={
                                                15
                                            }
                                        />

                                        عرض
                                    </button>
                                </div>
                            </div>
                        );
                    })}
                </div>
            </div>
        </main>
    );
}