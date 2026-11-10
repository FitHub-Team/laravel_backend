import { useEffect, useState } from "react";
import {
    useNavigate,
    useParams,
    useSearchParams,
} from "react-router-dom";
import {
    ArrowRight,
    Dumbbell,
    User,
    CalendarDays,
    Timer,
    Repeat2,
    Layers3,
} from "lucide-react";

import api from "../../../services/api";

const DAY_LABELS = {
    saturday: "السبت",
    sunday: "الأحد",
    monday: "الاثنين",
    tuesday: "الثلاثاء",
    wednesday: "الأربعاء",
    thursday: "الخميس",
    friday: "الجمعة",
};

const STATUS_LABELS = {
    draft: {
        label: "مسودة",
        className: "bg-amber-50 text-amber-700",
    },
    active: {
        label: "نشطة",
        className: "bg-[#EEF5EC] text-[#407437]",
    },
    completed: {
        label: "مكتملة",
        className: "bg-blue-50 text-blue-600",
    },
    cancelled: {
        label: "ملغاة",
        className: "bg-gray-100 text-gray-600",
    },
};

function formatDate(value) {
    if (!value) return "غير محدد";

    // عرض تاريخ الخطة كما ورد، دون تغييره بسبب المنطقة الزمنية.
    const datePart = String(value).split("T")[0];
    const parts = datePart.split("-");

    if (parts.length !== 3) return "غير محدد";

    return `${parts[2]}/${parts[1]}/${parts[0]}`;
}

export default function WorkoutPlanDetails() {
    const { planId } = useParams();
    const [searchParams] = useSearchParams();
    const traineeId = searchParams.get("trainee");
    const navigate = useNavigate();

    const [plan, setPlan] = useState(null);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState("");
    const [retryCount, setRetryCount] = useState(0);

    useEffect(() => {
        const controller = new AbortController();

        const fetchPlan = async () => {
            setLoading(true);
            setError("");
            setPlan(null);

            try {
                if (
                    !/^[1-9]\d*$/.test(traineeId || "") ||
                    !/^[1-9]\d*$/.test(planId || "")
                ) {
                    throw new Error(
                        "رابط الخطة غير مكتمل. افتحيها من صفحة الخطط الحالية.",
                    );
                }

                const response = await api.get(
                    `/coach/trainees/${traineeId}/workout-plan`,
                    { signal: controller.signal },
                );

                const payload = response.data;

                if (payload?.status === false) {
                    throw new Error(
                        payload.message || "تعذر تحميل الخطة.",
                    );
                }

                const fetchedPlan = payload?.data;

                if (!fetchedPlan) {
                    throw new Error("لا توجد خطة متاحة لهذا المشترك.");
                }

                // هذا الطلب يعيد خطة المشترك؛ نتحقق أنها الخطة المطلوبة.
                if (String(fetchedPlan.id) !== String(planId)) {
                    throw new Error(
                        "الخطة التي أعادها الخادم تختلف عن الخطة المطلوبة. ارجعي للخطط الحالية وحدّثي القائمة.",
                    );
                }

                if (
                    String(fetchedPlan.trainee_id) !== String(traineeId)
                ) {
                    throw new Error(
                        "بيانات المشترك في الخطة لا تطابق الرابط.",
                    );
                }

                if (!controller.signal.aborted) {
                    setPlan(fetchedPlan);
                }
            } catch (err) {
                if (controller.signal.aborted) return;

                const status = err.response?.status;

                setError(
                    status === 401
                        ? "انتهت جلسة الدخول. سجّلي الدخول من جديد."
                        : status === 403
                          ? "ليس لديك صلاحية لعرض هذه الخطة."
                          : status === 404
                            ? "الخطة المطلوبة غير موجودة."
                            : err.response?.data?.message ||
                              err.message ||
                              "حدث خطأ أثناء تحميل الخطة.",
                );
            } finally {
                if (!controller.signal.aborted) {
                    setLoading(false);
                }
            }
        };

        fetchPlan();

        return () => controller.abort();
    }, [planId, traineeId, retryCount]);

    const planStatus = STATUS_LABELS[plan?.status] || {
        label: plan?.status || "غير محددة",
        className: "bg-gray-100 text-gray-600",
    };

    const days = Array.isArray(plan?.days) ? plan.days : [];

    return (
        <div
            dir="rtl"
            className="min-h-screen bg-gray-50/60 p-4 sm:p-6"
        >
            <div className="mx-auto max-w-7xl space-y-6">
                <div className="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                    <div className="flex items-start gap-3">
                        <button
                            type="button"
                            aria-label="العودة للخطط الحالية"
                            onClick={() =>
                                navigate("/dashboard/current-plans")
                            }
                            className="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-gray-200 bg-white text-gray-500 transition hover:border-[#C8DCC3] hover:text-[#407437]"
                        >
                            <ArrowRight size={18} />
                        </button>

                        <div>
                            <p className="text-xs font-semibold text-[#407437]">
                                عرض الخطة
                            </p>
                            <h1 className="mt-1 text-2xl font-bold text-gray-900">
                                خطة التمارين
                            </h1>
                            <p className="mt-1 text-sm text-gray-500">
                                تفاصيل الخطة والتمارين الخاصة بالمشترك.
                            </p>
                        </div>
                    </div>

                    {plan && !loading && !error && (
                        <button
                            type="button"
                            onClick={() =>
                                navigate(
                                    `/dashboard/workout-plan/${plan.id}/edit?trainee=${plan.trainee_id}`,
                                )
                            }
                            className="rounded-xl bg-[#407437] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#355F2C]"
                        >
                            تعديل الخطة
                        </button>
                    )}
                </div>

                {loading ? (
                    <div
                        role="status"
                        className="rounded-2xl border border-gray-200 bg-white p-10 text-center text-sm text-gray-500"
                    >
                        جاري تحميل تفاصيل الخطة...
                    </div>
                ) : error ? (
                    <div
                        role="alert"
                        className="rounded-2xl border border-red-100 bg-red-50 p-6"
                    >
                        <p className="text-sm text-red-600">
                            {error}
                        </p>
                        <button
                            type="button"
                            onClick={() =>
                                setRetryCount((count) => count + 1)
                            }
                            className="mt-4 rounded-lg bg-white px-4 py-2 text-sm font-semibold text-red-700"
                        >
                            إعادة المحاولة
                        </button>
                    </div>
                ) : plan ? (
                    <>
                        <div className="grid grid-cols-1 gap-3 sm:grid-cols-3">
                            <InfoCard
                                icon={User}
                                label="المشترك"
                                value={
                                    plan.trainee?.full_name ||
                                    "غير متوفر"
                                }
                            />

                            <InfoCard
                                icon={Layers3}
                                label="الإصدار"
                                value={
                                    plan.version != null
                                        ? `v${plan.version}`
                                        : "غير محدد"
                                }
                            />

                            <div className="rounded-2xl border border-gray-200 bg-white p-4">
                                <p className="text-xs text-gray-400">
                                    الحالة
                                </p>
                                <span
                                    className={`mt-2 inline-flex items-center gap-2 rounded-full px-3 py-1 text-sm font-bold ${planStatus.className}`}
                                >
                                    <span className="h-2 w-2 rounded-full bg-current" />
                                    {planStatus.label}
                                </span>
                            </div>
                        </div>

                        <div className="flex flex-wrap gap-3">
                            <DateBadge
                                label="تاريخ البداية"
                                value={formatDate(plan.start_date)}
                            />
                            <DateBadge
                                label="تاريخ النهاية"
                                value={formatDate(plan.end_date)}
                            />
                        </div>

                        {days.length === 0 ? (
                            <div className="rounded-2xl border border-dashed border-gray-200 bg-white p-10 text-center text-sm text-gray-500">
                                لا توجد أيام مضافة لهذه الخطة.
                            </div>
                        ) : (
                            <div className="space-y-5">
                                {days.map((day, dayIndex) => {
                                    const exercises = Array.isArray(
                                        day.exercises,
                                    )
                                        ? day.exercises
                                        : [];

                                    return (
                                        <div
                                            key={day.id ?? dayIndex}
                                            className="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm"
                                        >
                                            <div className="flex flex-col gap-3 border-b border-gray-100 bg-[#FAFCF9] px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                                                <div>
                                                    <div className="flex items-center gap-2">
                                                        <CalendarDays
                                                            size={17}
                                                            className="text-[#407437]"
                                                        />
                                                        <h2 className="font-bold text-gray-900">
                                                            اليوم{" "}
                                                            {dayIndex + 1}
                                                            {" — "}
                                                            {DAY_LABELS[
                                                                day.day
                                                            ] ||
                                                                day.day ||
                                                                "غير محدد"}
                                                        </h2>
                                                    </div>

                                                    {day.focus && (
                                                        <p className="mt-1 text-xs text-gray-500">
                                                            {day.focus}
                                                        </p>
                                                    )}

                                                    {day.date && (
                                                        <p className="mt-1 text-xs text-gray-400">
                                                            {formatDate(
                                                                day.date,
                                                            )}
                                                        </p>
                                                    )}
                                                </div>

                                                <span className="rounded-full bg-[#EEF5EC] px-3 py-1.5 text-xs font-semibold text-[#407437]">
                                                    {exercises.length} تمارين
                                                </span>
                                            </div>

                                            {exercises.length === 0 ? (
                                                <p className="px-5 py-6 text-sm text-gray-400">
                                                    لا توجد تمارين لهذا اليوم.
                                                </p>
                                            ) : (
                                                <div className="divide-y divide-gray-100">
                                                    {exercises.map(
                                                        (exercise, index) => (
                                                            <div
                                                                key={
                                                                    exercise.workout_exercise_id ??
                                                                    `${day.id}-${index}`
                                                                }
                                                                className="grid grid-cols-1 gap-4 px-5 py-4 transition hover:bg-gray-50/60 md:grid-cols-[2fr_1fr_1fr_1fr] md:items-center"
                                                            >
                                                                <div className="flex min-w-0 items-center gap-3">
                                                                    <div className="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#EEF5EC] text-[#407437]">
                                                                        <Dumbbell
                                                                            size={18}
                                                                        />
                                                                    </div>

                                                                    <div className="min-w-0">
                                                                        <p className="text-sm font-bold text-gray-900">
                                                                            {index + 1}.{" "}
                                                                            {exercise.name ||
                                                                                "اسم التمرين غير متوفر"}
                                                                        </p>
                                                                        <p className="mt-1 text-xs text-gray-400">
                                                                            {exercise.muscle_group ||
                                                                                "العضلة غير محددة"}
                                                                        </p>
                                                                        {exercise.notes && (
                                                                            <p className="mt-2 whitespace-pre-wrap text-xs text-gray-500">
                                                                                {exercise.notes}
                                                                            </p>
                                                                        )}
                                                                    </div>
                                                                </div>

                                                                <ExerciseValue
                                                                    icon={Layers3}
                                                                    label="المجموعات"
                                                                    value={exercise.sets}
                                                                />
                                                                <ExerciseValue
                                                                    icon={Repeat2}
                                                                    label="التكرارات"
                                                                    value={exercise.reps}
                                                                />
                                                                <ExerciseValue
                                                                    icon={Timer}
                                                                    label="الراحة"
                                                                    value={
                                                                        exercise.rest_time != null &&
                                                                        exercise.rest_time !== ""
                                                                            ? `${exercise.rest_time} ثانية`
                                                                            : "غير محددة"
                                                                    }
                                                                />
                                                            </div>
                                                        ),
                                                    )}
                                                </div>
                                            )}
                                        </div>
                                    );
                                })}
                            </div>
                        )}
                    </>
                ) : null}
            </div>
        </div>
    );
}

function InfoCard({ icon: Icon, label, value }) {
    return (
        <div className="rounded-2xl border border-gray-200 bg-white p-4">
            <div className="flex items-center gap-2 text-gray-400">
                <Icon size={15} />
                <span className="text-xs">{label}</span>
            </div>
            <p className="mt-2 text-sm font-bold text-gray-900">
                {value ?? "غير متوفر"}
            </p>
        </div>
    );
}

function DateBadge({ label, value }) {
    return (
        <div className="rounded-xl border border-gray-200 bg-white px-4 py-3">
            <p className="text-[11px] text-gray-400">{label}</p>
            <p className="mt-1 text-sm font-semibold text-gray-700">
                {value}
            </p>
        </div>
    );
}

function ExerciseValue({ icon: Icon, label, value }) {
    return (
        <div>
            <p className="flex items-center gap-1.5 text-xs text-gray-400">
                <Icon size={13} />
                {label}
            </p>
            <p className="mt-1 text-sm font-semibold text-gray-800">
                {value ?? "غير محدد"}
            </p>
        </div>
    );
}