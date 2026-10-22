import { useNavigate, useParams } from "react-router-dom";
import {
    ArrowRight,
    Dumbbell,
    User,
    Target,
    CalendarDays,
    Timer,
    Repeat2,
    Layers3,
} from "lucide-react";

export default function WorkoutPlanDetails() {
    const { planId } = useParams();
    const navigate = useNavigate();

    // بيانات تجريبية مؤقتة
    const mockPlan = {
        id: planId,
        trainee_name: "Test User",
        goal: "خسارة الدهون",
        version: 3,
        status: "active",
        start_date: "2026-10-18",
        end_date: "2026-11-18",

        days: [
            {
                id: 1,
                name: "اليوم الأول",
                day_of_week: "السبت",
                exercises: [
                    {
                        id: 1,
                        name: "Bench Press",
                        muscle_group: "Chest",
                        sets: 4,
                        reps: "10",
                        rest: 60,
                    },
                    {
                        id: 2,
                        name: "Incline Dumbbell Press",
                        muscle_group: "Chest",
                        sets: 3,
                        reps: "12",
                        rest: 60,
                    },
                    {
                        id: 3,
                        name: "Triceps Pushdown",
                        muscle_group: "Triceps",
                        sets: 3,
                        reps: "12",
                        rest: 45,
                    },
                ],
            },

            {
                id: 2,
                name: "اليوم الثاني",
                day_of_week: "الاثنين",
                exercises: [
                    {
                        id: 4,
                        name: "Lat Pulldown",
                        muscle_group: "Back",
                        sets: 4,
                        reps: "10",
                        rest: 60,
                    },
                    {
                        id: 5,
                        name: "Seated Cable Row",
                        muscle_group: "Back",
                        sets: 3,
                        reps: "12",
                        rest: 60,
                    },
                    {
                        id: 6,
                        name: "Biceps Curl",
                        muscle_group: "Biceps",
                        sets: 3,
                        reps: "12",
                        rest: 45,
                    },
                ],
            },

            {
                id: 3,
                name: "اليوم الثالث",
                day_of_week: "الأربعاء",
                exercises: [
                    {
                        id: 7,
                        name: "Squat",
                        muscle_group: "Legs",
                        sets: 4,
                        reps: "8",
                        rest: 90,
                    },
                    {
                        id: 8,
                        name: "Leg Press",
                        muscle_group: "Legs",
                        sets: 3,
                        reps: "12",
                        rest: 60,
                    },
                    {
                        id: 9,
                        name: "Leg Curl",
                        muscle_group: "Hamstrings",
                        sets: 3,
                        reps: "12",
                        rest: 60,
                    },
                ],
            },
        ],
    };

    return (
        <div
            dir="rtl"
            className="min-h-screen bg-gray-50/60 p-4 sm:p-6"
        >
            <div className="mx-auto max-w-7xl space-y-6">
                {/* Header */}
                <div className="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                    <div className="flex items-start gap-3">
                        <button
                            type="button"
                            onClick={() => navigate(-1)}
                            className="
                                flex h-10 w-10 shrink-0
                                items-center justify-center
                                rounded-xl
                                border border-gray-200
                                bg-white
                                text-gray-500
                                transition
                                hover:border-[#C8DCC3]
                                hover:text-[#407437]
                            "
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

                    <button
                        type="button"
                        onClick={() =>
                            navigate(
                                `/dashboard/workout-plan/${planId}/edit`
                            )
                        }
                        className="
                            rounded-xl
                            bg-[#407437]
                            px-5 py-2.5
                            text-sm font-semibold
                            text-white
                            transition
                            hover:bg-[#355F2C]
                        "
                    >
                        تعديل الخطة
                    </button>
                </div>

                {/* Plan Info */}
                <div className="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
                    <InfoCard
                        icon={User}
                        label="المشترك"
                        value={mockPlan.trainee_name}
                    />

                    <InfoCard
                        icon={Target}
                        label="الهدف"
                        value={mockPlan.goal}
                    />

                    <InfoCard
                        icon={Layers3}
                        label="الإصدار"
                        value={`v${mockPlan.version}`}
                    />

                    <div className="rounded-2xl border border-gray-200 bg-white p-4">
                        <p className="text-xs text-gray-400">
                            الحالة
                        </p>

                        <div className="mt-2 flex items-center gap-2">
                            <span className="h-2 w-2 rounded-full bg-[#407437]" />

                            <span className="text-sm font-bold text-[#407437]">
                                نشطة
                            </span>
                        </div>
                    </div>
                </div>

                {/* Dates */}
                <div className="flex flex-wrap gap-3">
                    <DateBadge
                        label="تاريخ البداية"
                        value={mockPlan.start_date}
                    />

                    <DateBadge
                        label="تاريخ النهاية"
                        value={mockPlan.end_date}
                    />
                </div>

                {/* Days */}
                <div className="space-y-5">
                    {mockPlan.days.map((day) => (
                        <div
                            key={day.id}
                            className="
                                overflow-hidden
                                rounded-2xl
                                border border-gray-200
                                bg-white
                                shadow-sm
                            "
                        >
                            {/* Day Header */}
                            <div
                                className="
                                    flex flex-col gap-3
                                    border-b border-gray-100
                                    bg-[#FAFCF9]
                                    px-5 py-4
                                    sm:flex-row
                                    sm:items-center
                                    sm:justify-between
                                "
                            >
                                <div>
                                    <div className="flex items-center gap-2">
                                        <CalendarDays
                                            size={17}
                                            className="text-[#407437]"
                                        />

                                        <h2 className="font-bold text-gray-900">
                                            {day.name}
                                        </h2>
                                    </div>

                                    <p className="mt-1 text-xs text-gray-400">
                                        {day.day_of_week}
                                    </p>
                                </div>

                                <span
                                    className="
                                        rounded-full
                                        bg-[#EEF5EC]
                                        px-3 py-1.5
                                        text-xs font-semibold
                                        text-[#407437]
                                    "
                                >
                                    {day.exercises.length} تمارين
                                </span>
                            </div>

                            {/* Exercises */}
                            <div className="divide-y divide-gray-100">
                                {day.exercises.map((exercise, index) => (
                                    <div
                                        key={exercise.id}
                                        className="
                                            grid grid-cols-1 gap-4
                                            px-5 py-4
                                            transition
                                            hover:bg-gray-50/60
                                            md:grid-cols-[2fr_1fr_1fr_1fr]
                                            md:items-center
                                        "
                                    >
                                        {/* Exercise */}
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
                                                <Dumbbell size={18} />
                                            </div>

                                            <div>
                                                <p className="text-sm font-bold text-gray-900">
                                                    {index + 1}.{" "}
                                                    {exercise.name}
                                                </p>

                                                <p className="mt-1 text-xs text-gray-400">
                                                    {exercise.muscle_group}
                                                </p>
                                            </div>
                                        </div>

                                        {/* Sets */}
                                        <ExerciseValue
                                            icon={Layers3}
                                            label="المجموعات"
                                            value={exercise.sets}
                                        />

                                        {/* Reps */}
                                        <ExerciseValue
                                            icon={Repeat2}
                                            label="التكرارات"
                                            value={exercise.reps}
                                        />

                                        {/* Rest */}
                                        <ExerciseValue
                                            icon={Timer}
                                            label="الراحة"
                                            value={`${exercise.rest} ثانية`}
                                        />
                                    </div>
                                ))}
                            </div>
                        </div>
                    ))}
                </div>
            </div>
        </div>
    );
}

function InfoCard({ icon: Icon, label, value }) {
    return (
        <div className="rounded-2xl border border-gray-200 bg-white p-4">
            <div className="flex items-center gap-2 text-gray-400">
                <Icon size={15} />

                <span className="text-xs">
                    {label}
                </span>
            </div>

            <p className="mt-2 text-sm font-bold text-gray-900">
                {value}
            </p>
        </div>
    );
}

function DateBadge({ label, value }) {
    return (
        <div
            className="
                rounded-xl
                border border-gray-200
                bg-white
                px-4 py-3
            "
        >
            <p className="text-[11px] text-gray-400">
                {label}
            </p>

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
                {value}
            </p>
        </div>
    );
}