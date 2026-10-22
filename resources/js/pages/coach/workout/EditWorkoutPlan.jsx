import { useState } from "react";
import { useParams, useNavigate } from "react-router-dom";
import {
    ArrowRight,
    Save,
    Send,
    User,
    Target,
    Layers3,
    FilePenLine,
    Dumbbell,
    Trash2,
    Plus,
    CalendarDays,
} from "lucide-react";

export default function EditWorkoutPlan() {
    const { planId } = useParams();
    const navigate = useNavigate();

    const mockPlan = {
        id: planId,
        trainee_name: "Test User",
        goal: "خسارة الدهون",
        status: "draft",
        version: 3,
    };

    const [days, setDays] = useState([
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
                    rest_seconds: 60,
                    notes: "",
                },

                {
                    id: 2,
                    name: "Incline Dumbbell Press",
                    muscle_group: "Chest",
                    sets: 3,
                    reps: "12",
                    rest_seconds: 60,
                    notes: "تحكم بالحركة",
                },

                {
                    id: 3,
                    name: "Triceps Pushdown",
                    muscle_group: "Triceps",
                    sets: 3,
                    reps: "12",
                    rest_seconds: 45,
                    notes: "",
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
                    rest_seconds: 60,
                    notes: "",
                },

                {
                    id: 5,
                    name: "Seated Cable Row",
                    muscle_group: "Back",
                    sets: 3,
                    reps: "12",
                    rest_seconds: 60,
                    notes: "",
                },

                {
                    id: 6,
                    name: "Biceps Curl",
                    muscle_group: "Biceps",
                    sets: 3,
                    reps: "12",
                    rest_seconds: 45,
                    notes: "",
                },
            ],
        },
    ]);

    const handleExerciseChange = (
        dayId,
        exerciseId,
        field,
        value
    ) => {
        setDays((previousDays) =>
            previousDays.map((day) => {
                if (day.id !== dayId) {
                    return day;
                }

                return {
                    ...day,

                    exercises: day.exercises.map(
                        (exercise) => {
                            if (
                                exercise.id !==
                                exerciseId
                            ) {
                                return exercise;
                            }

                            return {
                                ...exercise,
                                [field]: value,
                            };
                        }
                    ),
                };
            })
        );
    };

    const handleDeleteExercise = (
        dayId,
        exerciseId
    ) => {
        setDays((previousDays) =>
            previousDays.map((day) => {
                if (day.id !== dayId) {
                    return day;
                }

                return {
                    ...day,

                    exercises: day.exercises.filter(
                        (exercise) =>
                            exercise.id !==
                            exerciseId
                    ),
                };
            })
        );
    };

    const handleAddExercise = (dayId) => {
        setDays((previousDays) =>
            previousDays.map((day) => {
                if (day.id !== dayId) {
                    return day;
                }

                const newExercise = {
                    id: Date.now(),
                    name: "تمرين جديد",
                    muscle_group: "غير محدد",
                    sets: "",
                    reps: "",
                    rest_seconds: "",
                    notes: "",
                };

                return {
                    ...day,

                    exercises: [
                        ...day.exercises,
                        newExercise,
                    ],
                };
            })
        );
    };

    const handleSaveDraft = () => {
        console.log("Save draft:", {
            planId,
            days,
        });
    };

    const handleSendPlan = () => {
        console.log("Send plan:", {
            planId,
            days,
        });
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
                            onClick={() =>
                                navigate(-1)
                            }
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
                            <div className="mb-1 flex items-center gap-2">
                                <FilePenLine
                                    size={19}
                                    className="text-[#407437]"
                                />

                                <span className="text-xs font-semibold text-[#407437]">
                                    محرر الخطة
                                </span>
                            </div>

                            <h1 className="text-2xl font-bold text-gray-900">
                                تعديل خطة التمارين
                            </h1>

                            <p className="mt-1 text-sm text-gray-500">
                                عدّل تمارين الخطة
                                والمجموعات والتكرارات
                                وفترات الراحة.
                            </p>
                        </div>
                    </div>

                    {/* Actions */}
                    <div className="flex flex-wrap items-center gap-3">
                        <button
                            type="button"
                            onClick={
                                handleSaveDraft
                            }
                            className="
                                flex items-center gap-2
                                rounded-xl
                                border border-[#C8DCC3]
                                bg-white
                                px-4 py-2.5
                                text-sm font-semibold
                                text-[#407437]
                                transition
                                hover:bg-[#F7FAF6]
                            "
                        >
                            <Save size={17} />
                            حفظ كمسودة
                        </button>

                        <button
                            type="button"
                            onClick={
                                handleSendPlan
                            }
                            className="
                                flex items-center gap-2
                                rounded-xl
                                bg-[#407437]
                                px-4 py-2.5
                                text-sm font-semibold
                                text-white
                                transition
                                hover:bg-[#355F2C]
                            "
                        >
                            <Send size={17} />
                            إرسال الخطة
                        </button>
                    </div>
                </div>

                {/* Plan Info */}
                <div
                    className="
                        grid grid-cols-1 gap-3
                        rounded-2xl
                        border border-gray-200
                        bg-white p-4
                        shadow-sm
                        sm:grid-cols-2
                        lg:grid-cols-4
                    "
                >
                    <InfoItem
                        icon={User}
                        label="المشترك"
                        value={
                            mockPlan.trainee_name
                        }
                    />

                    <InfoItem
                        icon={Target}
                        label="الهدف"
                        value={mockPlan.goal}
                    />

                    <InfoItem
                        icon={Layers3}
                        label="الإصدار"
                        value={`v${mockPlan.version}`}
                    />

                    <div className="rounded-xl bg-amber-50 p-3">
                        <p className="text-xs text-amber-600">
                            الحالة
                        </p>

                        <div className="mt-1 flex items-center gap-2">
                            <span className="h-2 w-2 rounded-full bg-amber-500" />

                            <span className="text-sm font-bold text-amber-700">
                                مسودة
                            </span>
                        </div>
                    </div>
                </div>

                {/* Days */}
                <div className="space-y-5">
                    {days.map((day) => (
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
                                    flex flex-col
                                    gap-3
                                    border-b
                                    border-gray-100
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
                                            {
                                                day.name
                                            }
                                        </h2>
                                    </div>

                                    <p className="mt-1 text-xs text-gray-400">
                                        {
                                            day.day_of_week
                                        }
                                    </p>
                                </div>

                                <div className="flex items-center gap-3">
                                    <span
                                        className="
                                            rounded-full
                                            bg-[#EEF5EC]
                                            px-3 py-1.5
                                            text-xs
                                            font-semibold
                                            text-[#407437]
                                        "
                                    >
                                        {
                                            day
                                                .exercises
                                                .length
                                        }{" "}
                                        تمارين
                                    </span>

                                    <button
                                        type="button"
                                        onClick={() =>
                                            handleAddExercise(
                                                day.id
                                            )
                                        }
                                        className="
                                            flex items-center gap-1.5
                                            rounded-lg
                                            bg-[#407437]
                                            px-3 py-2
                                            text-xs
                                            font-semibold
                                            text-white
                                            transition
                                            hover:bg-[#355F2C]
                                        "
                                    >
                                        <Plus
                                            size={
                                                14
                                            }
                                        />
                                        إضافة تمرين
                                    </button>
                                </div>
                            </div>

                            {/* Exercises */}
                            <div className="divide-y divide-gray-100">
                                {day.exercises.map(
                                    (
                                        exercise,
                                        index
                                    ) => (
                                        <div
                                            key={
                                                exercise.id
                                            }
                                            className="
                                                p-5
                                                transition
                                                hover:bg-gray-50/50
                                            "
                                        >
                                            <div
                                                className="
                                                    grid
                                                    grid-cols-1
                                                    gap-4
                                                    xl:grid-cols-[2fr_110px_110px_130px_45px]
                                                    xl:items-end
                                                "
                                            >
                                                {/* Exercise */}
                                                <div>
                                                    <div className="flex items-center gap-3">
                                                        <div
                                                            className="
                                                                flex
                                                                h-10
                                                                w-10
                                                                items-center
                                                                justify-center
                                                                rounded-xl
                                                                bg-[#EEF5EC]
                                                                text-[#407437]
                                                            "
                                                        >
                                                            <Dumbbell
                                                                size={
                                                                    18
                                                                }
                                                            />
                                                        </div>

                                                        <div>
                                                            <p className="text-sm font-bold text-gray-900">
                                                                {index +
                                                                    1}
                                                                .{" "}
                                                                {
                                                                    exercise.name
                                                                }
                                                            </p>

                                                            <p className="mt-1 text-xs text-gray-400">
                                                                {
                                                                    exercise.muscle_group
                                                                }
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>

                                                {/* Sets */}
                                                <InputField
                                                    label="المجموعات"
                                                    type="number"
                                                    value={
                                                        exercise.sets
                                                    }
                                                    onChange={(
                                                        e
                                                    ) =>
                                                        handleExerciseChange(
                                                            day.id,
                                                            exercise.id,
                                                            "sets",
                                                            e
                                                                .target
                                                                .value
                                                        )
                                                    }
                                                />

                                                {/* Reps */}
                                                <InputField
                                                    label="التكرارات"
                                                    value={
                                                        exercise.reps
                                                    }
                                                    onChange={(
                                                        e
                                                    ) =>
                                                        handleExerciseChange(
                                                            day.id,
                                                            exercise.id,
                                                            "reps",
                                                            e
                                                                .target
                                                                .value
                                                        )
                                                    }
                                                />

                                                {/* Rest */}
                                                <InputField
                                                    label="الراحة / ثانية"
                                                    type="number"
                                                    value={
                                                        exercise.rest_seconds
                                                    }
                                                    onChange={(
                                                        e
                                                    ) =>
                                                        handleExerciseChange(
                                                            day.id,
                                                            exercise.id,
                                                            "rest_seconds",
                                                            e
                                                                .target
                                                                .value
                                                        )
                                                    }
                                                />

                                                {/* Delete */}
                                                <button
                                                    type="button"
                                                    onClick={() =>
                                                        handleDeleteExercise(
                                                            day.id,
                                                            exercise.id
                                                        )
                                                    }
                                                    className="
                                                        flex h-10 w-10
                                                        items-center
                                                        justify-center
                                                        rounded-xl
                                                        text-red-400
                                                        transition
                                                        hover:bg-red-50
                                                        hover:text-red-600
                                                    "
                                                >
                                                    <Trash2
                                                        size={
                                                            17
                                                        }
                                                    />
                                                </button>
                                            </div>

                                            {/* Notes */}
                                            <div className="mt-4">
                                                <label className="mb-1.5 block text-xs font-medium text-gray-500">
                                                    ملاحظات
                                                </label>

                                                <input
                                                    type="text"
                                                    value={
                                                        exercise.notes
                                                    }
                                                    onChange={(
                                                        e
                                                    ) =>
                                                        handleExerciseChange(
                                                            day.id,
                                                            exercise.id,
                                                            "notes",
                                                            e
                                                                .target
                                                                .value
                                                        )
                                                    }
                                                    placeholder="أضف ملاحظة للتمرين..."
                                                    className="
                                                        w-full
                                                        rounded-xl
                                                        border
                                                        border-gray-200
                                                        bg-white
                                                        px-3 py-2.5
                                                        text-sm
                                                        text-gray-700
                                                        outline-none
                                                        transition
                                                        focus:border-[#407437]
                                                        focus:ring-2
                                                        focus:ring-[#407437]/10
                                                    "
                                                />
                                            </div>
                                        </div>
                                    )
                                )}
                            </div>
                        </div>
                    ))}
                </div>
            </div>
        </div>
    );
}

function InfoItem({
    icon: Icon,
    label,
    value,
}) {
    return (
        <div className="rounded-xl bg-gray-50 p-3">
            <div className="flex items-center gap-2 text-gray-400">
                <Icon size={15} />

                <span className="text-xs">
                    {label}
                </span>
            </div>

            <p className="mt-1.5 text-sm font-bold text-gray-800">
                {value}
            </p>
        </div>
    );
}

function InputField({
    label,
    value,
    onChange,
    type = "text",
}) {
    return (
        <div>
            <label className="mb-1.5 block text-xs font-medium text-gray-500">
                {label}
            </label>

            <input
                type={type}
                value={value}
                onChange={onChange}
                className="
                    w-full
                    rounded-xl
                    border border-gray-200
                    bg-white
                    px-3 py-2.5
                    text-center
                    text-sm
                    font-semibold
                    text-gray-700
                    outline-none
                    transition
                    focus:border-[#407437]
                    focus:ring-2
                    focus:ring-[#407437]/10
                "
            />
        </div>
    );
}