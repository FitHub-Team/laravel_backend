import { useEffect, useState } from "react";
import {
    CalendarDays,
    CheckCircle2,
    ChevronDown,
    Clock3,
    Dumbbell,
    Layers3,
    UserRound,
    ArrowRight,
    XCircle,
} from "lucide-react";

import workoutPlanService from "../../../services/workoutPlanService";

// ------------------------------------
// Constants
// ------------------------------------

const DAY_NAMES = {
    saturday: "السبت",
    sunday: "الأحد",
    monday: "الإثنين",
    tuesday: "الثلاثاء",
    wednesday: "الأربعاء",
    thursday: "الخميس",
    friday: "الجمعة",
};

const STATUS_STYLES = {
    active: {
        label: "نشطة",
        className: "bg-[#EEF5EC] text-[#407437]",
        Icon: CheckCircle2,
    },
    completed: {
        label: "مكتملة",
        className: "bg-blue-50 text-blue-600",
        Icon: CheckCircle2,
    },
    cancelled: {
        label: "ملغاة",
        className: "bg-red-50 text-red-600",
        Icon: XCircle,
    },
};

// ------------------------------------
// Helpers
// ------------------------------------

function formatDate(value) {
    if (!value) return "—";

    const date = new Date(value);

    if (Number.isNaN(date.getTime())) return "—";

    return new Intl.DateTimeFormat("ar", {
        day: "numeric",
        month: "long",
        year: "numeric",
        timeZone: "UTC",
    }).format(date);
}

function getDayName(day, index) {
    const name = day.day || day.day_name || day.name;

    if (!name) return `اليوم ${index + 1}`;

    return DAY_NAMES[String(name).toLowerCase()] || name;
}

function getExerciseName(item) {
    return (
        item.exercise?.name_ar ||
        item.exercise?.name ||
        item.custom_exercise?.name_ar ||
        item.custom_exercise?.name ||
        item.customExercise?.name_ar ||
        item.customExercise?.name ||
        item.name ||
        `تمرين #${item.id}`
    );
}

function getExerciseDayId(item) {
    return item.workout_plan_id ?? null;
}

// ------------------------------------
// Sidebar information row
// ------------------------------------

function DetailRow({ icon: Icon, label, value }) {
    return (
        <div className="flex items-center gap-3 py-3">
            <div className="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-gray-50 text-[#407437]">
                <Icon size={17} strokeWidth={1.8} />
            </div>

            <div className="min-w-0 flex-1">
                <p className="text-xs text-gray-500">{label}</p>

                <p className="mt-1 break-words text-sm font-semibold text-gray-900">
                    {value ?? "—"}
                </p>
            </div>
        </div>
    );
}

// ------------------------------------
// Exercise row
// ------------------------------------

function ExerciseItem({ item, index }) {
    const details = [
        { label: "مجموعات", value: item.sets },
        { label: "تكرارات", value: item.reps },
        { label: "وزن", value: item.weight },
        { label: "مدة", value: item.duration },
    ].filter((detail) => detail.value != null);

    return (
        <div className="flex items-start gap-3 px-4 py-4 sm:px-5">
            <div className="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-[#EEF5EC] text-xs font-bold text-[#407437]">
                {index + 1}
            </div>

            <div className="min-w-0 flex-1">
                <p className="text-sm font-semibold text-gray-900">
                    {getExerciseName(item)}
                </p>

                {item.notes && (
                    <p className="mt-1 text-xs leading-5 text-gray-500">
                        {item.notes}
                    </p>
                )}

                {details.length > 0 && (
                    <div className="mt-3 flex flex-wrap gap-2">
                        {details.map((detail) => (
                            <span
                                key={detail.label}
                                className="inline-flex items-center gap-1.5 rounded-md bg-gray-50 px-2.5 py-1.5 text-xs text-gray-600"
                            >
                                <span className="text-gray-400">
                                    {detail.label}
                                </span>

                                <span className="font-semibold text-gray-800">
                                    {detail.value}
                                </span>
                            </span>
                        ))}
                    </div>
                )}
            </div>
        </div>
    );
}

// ------------------------------------
// Workout day accordion
// ------------------------------------

function WorkoutDay({ day, index, exercises }) {
    const [isOpen, setIsOpen] = useState(index === 0);

    return (
        <div className="overflow-hidden rounded-xl border border-gray-200 bg-white">
            <button
                type="button"
                aria-expanded={isOpen}
                onClick={() => setIsOpen((previous) => !previous)}
                className="flex w-full items-center justify-between gap-3 px-4 py-4 text-right transition-colors hover:bg-gray-50 sm:px-5"
            >
                <div className="flex min-w-0 items-center gap-3">
                    <div className="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-[#EEF5EC] text-[#407437]">
                        <CalendarDays size={19} />
                    </div>

                    <div className="min-w-0">
                        <h3 className="text-sm font-bold text-gray-900">
                            {getDayName(day, index)}
                        </h3>

                        {day.focus && (
                            <p className="mt-1 text-xs text-gray-500">
                                {day.focus}
                            </p>
                        )}
                    </div>
                </div>

                <div className="flex shrink-0 items-center gap-3">
                    <span className="text-xs font-medium text-gray-500">
                        {exercises.length} تمرين
                    </span>

                    <ChevronDown
                        size={18}
                        className={`text-gray-400 transition-transform duration-200 ${
                            isOpen ? "rotate-180" : ""
                        }`}
                    />
                </div>
            </button>

            {isOpen && (
                <div className="divide-y divide-gray-100 border-t border-gray-100">
                    {exercises.length > 0 ? (
                        exercises.map((item, itemIndex) => (
                            <ExerciseItem
                                key={item.id ?? itemIndex}
                                item={item}
                                index={itemIndex}
                            />
                        ))
                    ) : (
                        <p className="px-5 py-6 text-center text-sm text-gray-400">
                            لا توجد تمارين مرتبطة بهذا اليوم.
                        </p>
                    )}
                </div>
            )}
        </div>
    );
}

// ------------------------------------
// Main component
// ------------------------------------

export default function VersionDetailsContent({ traineeId, planId, onBack }) {
    const [plan, setPlan] = useState(null);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState("");

    useEffect(() => {
        let cancelled = false;

        async function fetchVersion() {
            try {
                setLoading(true);
                setError("");
                setPlan(null);

                const data = await workoutPlanService.getWorkoutVersionDetails(
                    traineeId,
                    planId,
                );

                if (!cancelled) {
                    setPlan(data);
                }
            } catch (err) {
                if (!cancelled) {
                    setError(
                        err.response?.data?.message ||
                            "تعذر تحميل تفاصيل الإصدار",
                    );
                }
            } finally {
                if (!cancelled) {
                    setLoading(false);
                }
            }
        }

        if (traineeId && planId) {
            fetchVersion();
        } else {
            setPlan(null);
            setError("بيانات الإصدار غير مكتملة");
            setLoading(false);
        }

        return () => {
            cancelled = true;
        };
    }, [traineeId, planId]);

    // Loading state
    if (loading) {
        return (
            <div
                dir="rtl"
                role="status"
                className="flex min-h-64 items-center justify-center gap-3 rounded-2xl border border-gray-200 bg-white"
            >
                <div className="h-7 w-7 animate-spin rounded-full border-[3px] border-gray-200 border-t-[#407437]" />

                <p className="text-sm text-gray-500">
                    جاري تحميل تفاصيل الإصدار...
                </p>
            </div>
        );
    }

    // Error state
    if (error || !plan) {
        return (
            <div
                dir="rtl"
                role="alert"
                className="flex min-h-64 flex-col items-center justify-center gap-3 rounded-2xl border border-gray-200 bg-white p-6 text-center"
            >
                <XCircle size={30} className="text-red-500" />

                <p className="text-sm text-red-600">
                    {error || "الإصدار غير موجود"}
                </p>
            </div>
        );
    }

    const days = Array.isArray(plan.days) ? plan.days : [];

    const exercises = Array.isArray(plan.workout_exercises)
        ? plan.workout_exercises
        : [];
    console.log("PLAN:", plan);

    console.log("DAYS:", days);

    console.log("EXERCISES:", exercises);

    console.table(
        exercises.map((exercise) => ({
            exercise_id: exercise.id,
            workout_day_id: exercise.workout_day_id,
            day_id: exercise.day_id,
            workout_plan_day_id: exercise.workout_plan_day_id,
        })),
    );
    const status = STATUS_STYLES[plan.status] ?? {
        label: plan.status || "غير محدد",
        className: "bg-gray-100 text-gray-600",
        Icon: Clock3,
    };

    const StatusIcon = status.Icon;

    // Group exercises by day
    const getExercisesForDay = (day) => {
        return exercises.filter((exercise) => {
            const exerciseDayId = getExerciseDayId(exercise);

            return (
                exerciseDayId != null &&
                String(exerciseDayId) === String(day.id)
            );
        });
    };

    // Count exercises assigned to a day
    const assignedExercisesCount = exercises.filter((exercise) => {
        const exerciseDayId = getExerciseDayId(exercise);

        return days.some(
            (day) =>
                exerciseDayId != null &&
                String(exerciseDayId) === String(day.id),
        );
    }).length;

    return (
        <div dir="rtl" className="overflow-hidden rounded-2xl  ">
            {/* Card header */}
            <div className="border-b border-gray-100 px-5 py-5 sm:px-6">
                {/* Back button */}
                {onBack && (
                    <button
                        type="button"
                        onClick={onBack}
                        className="
                mb-5 inline-flex items-center gap-2
                rounded-lg border border-gray-200
                bg-white px-3.5 py-2
                text-sm font-medium text-gray-600
                transition-colors
                hover:border-[#407437]
                hover:bg-[#EEF5EC]
                hover:text-[#407437]
                focus-visible:outline-none
                focus-visible:ring-2
                focus-visible:ring-[#407437]/40
            "
                    >
                        <ArrowRight size={17} />
                        <span>رجوع للإصدارات</span>
                    </button>
                )}

                <div className="flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <h2 className="text-lg font-bold text-gray-900 sm:text-xl">
                            تفاصيل خطة التمارين
                        </h2>

                        <p className="mt-1 text-xs text-gray-500">
                            الإصدار V{plan.version ?? "—"} · عرض فقط
                        </p>
                    </div>

                    <span
                        className={`inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-xs font-semibold ${status.className}`}
                    >
                        <StatusIcon size={15} />
                        {status.label}
                    </span>
                </div>
            </div>

            {/* Card body */}
            <div className="grid grid-cols-1 items-start gap-6 p-4 sm:p-6 lg:grid-cols-[minmax(0,1fr)_260px] border rounded-xl border-gray-200 bg-white shadow-sm">
                {/* Main: exercises */}
                <section className="min-w-0 space-y-4">
                    <div className="flex items-center justify-between gap-3">
                        <div>
                            <h3 className="text-base font-bold text-gray-900">
                                الجدول التدريبي
                            </h3>

                            <p className="mt-1 text-xs text-gray-500">
                                تفاصيل الأيام والتمارين الخاصة بهذا الإصدار
                            </p>
                        </div>

                        <span className="shrink-0 text-xs font-medium text-gray-500">
                            {days.length} أيام
                        </span>
                    </div>

                    <div className="space-y-3">
                        {days.map((day, index) => (
                            <WorkoutDay
                                key={`${planId}-${day.id ?? index}`}
                                day={day}
                                index={index}
                                exercises={getExercisesForDay(day)}
                            />
                        ))}

                        {days.length === 0 && (
                            <div className="rounded-xl border border-dashed border-gray-200 bg-gray-50/50 px-5 py-10 text-center">
                                <Dumbbell
                                    size={25}
                                    className="mx-auto mb-3 text-gray-400"
                                />

                                <p className="text-sm text-gray-500">
                                    لا توجد أيام تدريبية لهذا الإصدار.
                                </p>
                            </div>
                        )}
                    </div>

                    {assignedExercisesCount !== exercises.length && (
                        <p className="text-xs text-amber-700">
                            يوجد {exercises.length - assignedExercisesCount}{" "}
                            تمرين لم تتم مطابقة ارتباطه بيوم تدريبي.
                        </p>
                    )}
                </section>

                {/* Sidebar: plan information */}
                <aside className="min-w-0 rounded-xl border border-gray-200 bg-gray-50/50 p-4 lg:sticky lg:top-4">
                    <h3 className="mb-3 text-sm font-bold text-gray-900">
                        معلومات الخطة
                    </h3>

                    <div className="divide-y divide-gray-200/70">
                        <DetailRow
                            icon={UserRound}
                            label="المتدرب"
                            value={
                                plan.trainee?.full_name ||
                                `المتدرب #${traineeId}`
                            }
                        />

                        <DetailRow
                            icon={Layers3}
                            label="رقم الإصدار"
                            value={`V${plan.version ?? "—"}`}
                        />

                        <DetailRow
                            icon={CalendarDays}
                            label="تاريخ البداية"
                            value={formatDate(plan.start_date)}
                        />

                        <DetailRow
                            icon={CalendarDays}
                            label="تاريخ النهاية"
                            value={formatDate(plan.end_date)}
                        />

                        <DetailRow
                            icon={Dumbbell}
                            label="عدد التمارين"
                            value={exercises.length}
                        />
                    </div>
                </aside>
            </div>
        </div>
    );
}
