import { useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";
import {
    ArrowUpLeft,
    CalendarDays,
    History,
    RefreshCw,
    UserRound,
    
} from "lucide-react";

import PlansTabs from "../../../components/workout/plan/PlansTabs";
import { getAllWorkoutPlanVersions } from "../../../services/coachPlansService";

function formatDate(value) {
    if (!value) return "—";

    const date = new Date(value);

    if (Number.isNaN(date.getTime())) return "—";

    return new Intl.DateTimeFormat("ar", {
        day: "numeric",
        month: "short",
        year: "numeric",
    }).format(date);
}

function TraineeInfo({ trainee }) {
    return (
        <div className="flex min-w-0 items-center gap-3">
            {trainee?.profile_image ? (
                <img
                    src={trainee.profile_image}
                    alt={trainee.full_name || "المتدرب"}
                    className="h-11 w-11 shrink-0 rounded-full object-cover"
                />
            ) : (
                <div className="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-[#EEF5EC] text-[#407437]">
                    <UserRound size={20} />
                </div>
            )}

            <div className="min-w-0">
                <p className="truncate text-sm font-semibold text-gray-900">
                    {trainee?.full_name || "متدرب"}
                </p>

                {trainee?.email && (
                    <p className="mt-1 truncate text-xs text-gray-500">
                        {trainee.email}
                    </p>
                )}
            </div>
        </div>
    );
}

function VersionsCard({ plan, onOpen }) {
    return (
        <article className="rounded-xl border border-gray-200 bg-white p-4">
            <div className="flex items-start justify-between gap-3">
                <TraineeInfo trainee={plan.trainee} />

                <span className="shrink-0 rounded-lg bg-[#EEF5EC] px-2.5 py-1 text-xs font-semibold text-[#407437]">
                    V{plan.version ?? "—"}
                </span>
            </div>

            <div className="mt-4 grid grid-cols-2 gap-4 border-t border-gray-100 pt-4">
                <div>
                    <p className="text-xs text-gray-500">عدد الإصدارات</p>
                    <p className="mt-1 text-sm font-semibold text-gray-900">
                        {plan.versions_count ?? 1}
                    </p>
                </div>

                <div>
                    <p className="text-xs text-gray-500">آخر تحديث</p>
                    <p className="mt-1 text-sm font-semibold text-gray-900">
                        {formatDate(plan.updated_at)}
                    </p>
                </div>
            </div>

            <button
                type="button"
                onClick={() => onOpen(plan)}
                className="mt-4 flex min-h-11 w-full items-center justify-center gap-2 rounded-lg bg-[#407437] px-4 text-sm font-semibold text-white transition-colors hover:bg-[#345F2D] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#407437]/40"
            >
                عرض الإصدارات
                <ArrowUpLeft size={17} />
            </button>
        </article>
    );
}

export default function WorkoutPlanVersions() {
    const navigate = useNavigate();

    const [versions, setVersions] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState("");

    useEffect(() => {
        let mounted = true;

        async function fetchVersions() {
            try {
                setLoading(true);
                setError("");

                const data = await getAllWorkoutPlanVersions();

                if (mounted) {
                    setVersions(Array.isArray(data) ? data : []);
                }
            } catch (err) {
                if (mounted) {
                    setError(
                        err.response?.data?.message ||
                            "تعذر تحميل إصدارات الخطط",
                    );
                }
            } finally {
                if (mounted) setLoading(false);
            }
        }

        fetchVersions();

        return () => {
            mounted = false;
        };
    }, []);

    const handleOpenVersions = (plan) => {
        navigate(`/dashboard/versions/${plan.trainee_id}`);
    };

    return (
        <main
            dir="rtl"
            className="mx-auto w-full max-w-7xl px-4 py-6 sm:px-6 lg:px-8"
        >
            <PlansTabs />

            {/* Page header */}
            <div className="mb-6 flex flex-wrap items-center justify-between gap-4">
                <div>
                    <h1 className="text-xl font-bold text-gray-900 sm:text-2xl">
                        إصدارات خطط التمارين
                    </h1>

                    <p className="mt-1.5 text-sm text-gray-500">
                        استعرض الإصدارات السابقة لخطط المتدربين.
                    </p>
                </div>

                {!loading && !error && (
                    <div className="inline-flex items-center gap-2 rounded-lg bg-gray-100 px-3 py-2 text-xs font-medium text-gray-600">
                        <History size={16} />
                        {versions.length} متدرب
                    </div>
                )}
            </div>

            {/* Loading */}
            {loading && (
                <div
                    role="status"
                    className="flex min-h-64 flex-col items-center justify-center gap-3 rounded-xl border border-gray-200 bg-white"
                >
                    <RefreshCw
                        size={23}
                        className="animate-spin text-[#407437]"
                    />
                    <p className="text-sm text-gray-500">
                        جاري تحميل الإصدارات...
                    </p>
                </div>
            )}

            {/* Error */}
            {!loading && error && (
                <div
                    role="alert"
                    className="rounded-xl border border-red-100 bg-red-50 px-5 py-8 text-center text-sm text-red-700"
                >
                    {error}
                </div>
            )}

            {/* Empty state */}
            {!loading && !error && versions.length === 0 && (
                <div className="flex min-h-64 flex-col items-center justify-center rounded-xl border border-dashed border-gray-200 bg-white px-5 text-center">
                    <div className="mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 text-gray-400">
                        <History size={23} />
                    </div>

                    <h2 className="text-sm font-semibold text-gray-900">
                        لا توجد إصدارات سابقة
                    </h2>

                    <p className="mt-2 max-w-sm text-sm leading-6 text-gray-500">
                        ستظهر هنا إصدارات خطط التمارين عند توفرها.
                    </p>
                </div>
            )}

            {!loading && !error && versions.length > 0 && (
                <>
                    {/* Desktop table */}
                    <div className="hidden overflow-hidden rounded-xl border border-gray-200 bg-white md:block">
                        <table className="w-full table-fixed text-right">
                            <thead className="border-b border-gray-200 bg-gray-50/70">
                                <tr className="text-xs font-medium text-gray-500">
                                    <th className="w-[32%] px-5 py-4">
                                        المتدرب
                                    </th>
                                    <th className="w-[17%] px-4 py-4">
                                        عدد الإصدارات
                                    </th>
                                    <th className="w-[15%] px-4 py-4">
                                        آخر إصدار
                                    </th>
                                    <th className="w-[20%] px-4 py-4">
                                        آخر تحديث
                                    </th>
                                    <th className="w-[16%] px-4 py-4">
                                        الإجراء
                                    </th>
                                </tr>
                            </thead>

                            <tbody className="divide-y divide-gray-100">
                                {versions.map((plan) => (
                                    <tr
                                        key={plan.id}
                                        className="transition-colors hover:bg-gray-50/70"
                                    >
                                        <td className="px-5 py-4">
                                            <TraineeInfo
                                                trainee={plan.trainee}
                                            />
                                        </td>

                                        <td className="px-4 py-4">
                                            <div className="inline-flex items-center gap-2.5">
                                                

                                                <div className="flex flex-col gap-0.5">
                                                    <span className="text-sm font-semibold text-gray-900">
                                                        {plan.versions_count ??
                                                            "—"}
                                                    </span>
                                                    <span className="text-[11px] text-gray-500">
                                                        إصدارات
                                                    </span>
                                                </div>
                                            </div>
                                        </td>

                                        <td className="px-4 py-4">
                                            <span className="inline-flex rounded-md bg-[#EEF5EC] px-2.5 py-1 text-xs font-semibold text-[#407437]">
                                                V{plan.version ?? "—"}
                                            </span>
                                        </td>

                                        <td className="px-4 py-4">
                                            <div className="flex items-center gap-2 text-sm text-gray-600">
                                                <CalendarDays
                                                    size={15}
                                                    className="shrink-0 text-gray-400"
                                                />
                                                {formatDate(plan.updated_at)}
                                            </div>
                                        </td>

                                        <td className="px-4 py-4">
                                            <button
                                                type="button"
                                                onClick={() =>
                                                    handleOpenVersions(plan)
                                                }
                                                className="inline-flex min-h-10 items-center gap-2 rounded-lg border border-gray-200 bg-white px-3 text-xs font-semibold text-gray-700 transition-colors hover:border-[#407437]/40 hover:bg-[#F5F9F3] hover:text-[#407437]"
                                            >
                                                عرض الإصدارات
                                                <ArrowUpLeft size={15} />
                                            </button>
                                        </td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>

                    {/* Mobile cards */}
                    <div className="grid grid-cols-1 gap-3 md:hidden">
                        {versions.map((plan) => (
                            <VersionsCard
                                key={plan.id}
                                plan={plan}
                                onOpen={handleOpenVersions}
                            />
                        ))}
                    </div>
                </>
            )}
        </main>
    );
}
