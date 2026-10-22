import { useEffect, useState } from "react";
import {
    Trash2,
    Plus,
    Dumbbell,
    Clock3,
    Layers3,
    RotateCcw,
    Info,
    FolderHeart,
} from "lucide-react";
import ExerciseLibraryModal from "./ExerciseLibraryModal";
import CoachExercisesModal from "./custom-exercises/CoachExercisesModal";
import useCustomExercises from "../../hooks/useCustomExercises";

const exerciseId = (item) => item.exercise_id ?? item.exercise?.id ?? item.id;
const display = (v) =>
    typeof v === "string" || typeof v === "number" ? v : "";

function Field({
    label,
    icon: Icon,
    value,
    onChange,
    type = "text",
    min,
    placeholder = "",
}) {
    return (
        <label className="flex min-w-0 flex-col gap-2 rounded-xl border border-gray-200 bg-gray-50 px-3 py-2.5 focus-within:border-green-400">
            <span className="flex items-center gap-1.5 text-[11px] text-gray-500">
                {Icon && <Icon size={13} />} {label}
            </span>
            <input
                type={type}
                min={min}
                value={display(value)}
                onChange={(event) => onChange(event.target.value)}
                placeholder={placeholder}
                className="w-full min-w-0 bg-transparent text-right text-sm font-semibold text-gray-800 outline-none"
            />
        </label>
    );
}

function ExerciseRow({ item, index, onDelete, onUpdateExercise }) {
    const id = exerciseId(item);
    const details =
        item.exercise && typeof item.exercise === "object"
            ? item.exercise
            : item;
    const [draft, setDraft] = useState({
        sets: item.sets ?? 3,
        reps: item.reps ?? "10-12",
        weight: item.weight ?? "",
        rest_seconds: item.rest_seconds ?? item.rest_time ?? item.rest ?? 60,
        notes: item.notes ?? "",
    });
    useEffect(() => {
        setDraft({
            sets: item.sets ?? 3,
            reps: item.reps ?? "10-12",
            weight: item.weight ?? "",
            rest_seconds:
                item.rest_seconds ?? item.rest_time ?? item.rest ?? 60,
            notes: item.notes ?? "",
        });
    }, [
        item.sets,
        item.reps,
        item.weight,
        item.rest_seconds,
        item.rest_time,
        item.rest,
        item.notes,
        id,
    ]);

    const change = (field, value) => {
        setDraft((p) => ({ ...p, [field]: value }));
        onUpdateExercise?.(id, { [field]: value }, index);
    };
    const title = display(details.name) || "تمرين غير معروف";
    return (
        <article className="overflow-hidden rounded-2xl border border-gray-200 bg-white hover:border-green-200">
            <div className="flex items-start justify-between gap-3 border-b border-gray-100 px-4 py-4">
                <div className="flex min-w-0 items-start gap-3">
                    <div className="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-green-50 text-[#407437]">
                        <Dumbbell size={18} />
                    </div>
                    <div className="min-w-0">
                        <h3 className="text-sm font-bold text-gray-900">
                            {title}{" "}
                            <span className="text-xs text-gray-400">
                                #{index + 1}
                            </span>
                        </h3>
                        <div className="mt-2 flex flex-wrap gap-2 text-[11px] text-gray-500">
                            {details.muscle_group && (
                                <span className="rounded-full bg-green-50 px-2 py-1">
                                    {display(details.muscle_group)}
                                </span>
                            )}
                            {details.equipment && (
                                <span className="rounded-full bg-gray-100 px-2 py-1">
                                    {display(details.equipment)}
                                </span>
                            )}
                            {details.difficulty_level && (
                                <span className="rounded-full bg-blue-50 px-2 py-1">
                                    {display(details.difficulty_level)}
                                </span>
                            )}
                        </div>
                    </div>
                </div>
                <button
                    type="button"
                    onClick={() => onDelete?.(id, index)}
                    aria-label={`حذف ${title}`}
                    className="rounded-xl bg-red-50 p-2 text-red-500 hover:bg-red-100"
                >
                    <Trash2 size={17} />
                </button>
            </div>
            {details.description && (
                <div className="flex items-start gap-2 border-b border-gray-100 bg-[#FAFCFA] px-4 py-3">
                    <Info size={15} className="mt-1 shrink-0 text-[#407437]" />
                    <p className="line-clamp-2 text-xs leading-6 text-gray-500">
                        {display(details.description)}
                    </p>
                </div>
            )}
            <div className="grid grid-cols-2 gap-3 p-4 sm:grid-cols-3 lg:grid-cols-5">
                <Field
                    label="المجموعات"
                    icon={Layers3}
                    type="number"
                    min="1"
                    value={draft.sets}
                    onChange={(v) => change("sets", v)}
                />
                <Field
                    label="التكرارات"
                    icon={RotateCcw}
                    value={draft.reps}
                    placeholder="10-12"
                    onChange={(v) => change("reps", v)}
                />
                <Field
                    label="الوزن (kg)"
                    icon={Dumbbell}
                    value={draft.weight}
                    placeholder="اختياري"
                    onChange={(v) => change("weight", v)}
                />
                <Field
                    label="الراحة (ثانية)"
                    icon={Clock3}
                    type="number"
                    min="0"
                    value={draft.rest_seconds}
                    onChange={(v) => change("rest_seconds", v)}
                />
                <Field
                    label="ملاحظات المدرب"
                    value={draft.notes}
                    placeholder="ملاحظة"
                    onChange={(v) => change("notes", v)}
                />
            </div>
        </article>
    );
}

export default function WorkoutTable({
    selectedDayData,
    exercises = [],
    handleDelete,
    onAddExercise,
    selectedDayFocus = "",
    onFocusChange,
    onUpdateExercise,
}) {
    const [isModalOpen, setIsModalOpen] = useState(false);
    const [isCustomModalOpen, setIsCustomModalOpen] = useState(false);
    const {
        exercises: coachExercises,
        loading: isCoachExercisesLoading,
        error: coachExercisesError,
    } = useCustomExercises();

    const name =
        selectedDayData?.day_name_ar ?? selectedDayData?.name ?? "غير محدد";
    const isRest = exercises.length === 0;
    return (
        <div
            dir="rtl"
            className="space-y-6 rounded-2xl border border-gray-100 bg-white p-4 shadow-sm sm:p-6"
        >
            <div className="flex flex-col justify-between gap-4 border-b pb-5 sm:flex-row sm:items-start">
                <div className="min-w-0 flex-1">
                    <div className="flex flex-wrap items-center gap-3">
                        <h2 className="text-lg font-bold text-[#1A3D27]">
                            جدول يوم {name}
                        </h2>
                        <span
                            className={`rounded-full px-3 py-1 text-xs ${isRest ? "bg-amber-50 text-amber-700" : "bg-green-50 text-green-700"}`}
                        >
                            {isRest ? "راحة / بلا تمارين" : "يوم تدريب"}
                        </span>
                    </div>
                    <label className="mt-4 block max-w-xl">
                        <span className="mb-2 block text-xs font-semibold text-gray-600">
                            التركيز التدريبي
                        </span>
                        <input
                            type="text"
                            value={display(selectedDayFocus)}
                            onChange={(e) => onFocusChange?.(e.target.value)}
                            placeholder="مثال: دفع (صدر، كتف، ترايسبس)"
                            className="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm outline-none focus:border-green-500"
                        />
                    </label>
                </div>
                <div className="flex flex-wrap items-center gap-3">
                    {/* General exercise library */}
                    <button
                        type="button"
                        onClick={() => setIsModalOpen(true)}
                        className="flex items-center justify-center gap-2 rounded-xl bg-[#407437] px-4 py-3 text-sm font-semibold text-white transition hover:bg-[#32632C]"
                    >
                        <Plus size={17} />
                        إضافة تمرين
                    </button>

                    {/* Coach's custom exercise library */}
                    <button
                        type="button"
                        onClick={() => setIsCustomModalOpen(true)}
                        className="flex items-center justify-center gap-2 rounded-xl border border-[#407437] bg-white px-4 py-3 text-sm font-semibold text-[#407437] transition hover:bg-[#F1F7EF]"
                    >
                        <FolderHeart size={17} />
                        تماريني الخاصة
                    </button>
                </div>
            </div>
            <div className="flex items-center justify-between">
                <h3 className="flex items-center gap-2 text-sm font-bold">
                    <Dumbbell size={18} className="text-[#407437]" /> تمارين
                    اليوم
                </h3>
                <span className="rounded-lg bg-gray-100 px-3 py-1.5 text-xs">
                    {exercises.length} تمرين
                </span>
            </div>
            {exercises.length ? (
                <div className="space-y-4">
                    {exercises.map((item, index) => (
                        <ExerciseRow
                            key={`${exerciseId(item)}-${index}`}
                            item={item}
                            index={index}
                            onDelete={handleDelete}
                            onUpdateExercise={onUpdateExercise}
                        />
                    ))}
                </div>
            ) : (
                <div className="rounded-2xl border border-dashed border-gray-200 bg-gray-50 p-10 text-center text-sm text-gray-500">
                    لا توجد تمارين لهذا اليوم. يمكنك إضافتها من المكتبة.
                </div>
            )}
            <ExerciseLibraryModal
                isOpen={isModalOpen}
                onClose={() => setIsModalOpen(false)}
                onSelectExercise={(exercise) => {
                    onAddExercise?.(exercise);
                    setIsModalOpen(false);
                }}
            />
            {isCustomModalOpen && coachExercisesError && (
                <p role="alert" className="text-sm text-red-600">
                    {coachExercisesError}
                </p>
            )}
            <CoachExercisesModal
                isOpen={isCustomModalOpen}
                onClose={() => setIsCustomModalOpen(false)}
                exercises={coachExercises}
                isLoading={isCoachExercisesLoading}
                onSelectExercise={(exercise) => {
                    onAddExercise?.({
                        ...exercise,
                        id: undefined,
                        exercise_id: null,
                        custom_exercise_id: exercise.id,
                        source: "custom",
                        name: exercise.name_ar || exercise.name,
                        sets: 3,
                        reps: "10-12",
                        rest_seconds: 60,
                        notes: "",
                    });

                    setIsCustomModalOpen(false);
                }}
            />
        </div>
    );
}
