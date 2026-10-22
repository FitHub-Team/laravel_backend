import {
    X,
    ArrowRight,
    History,
    Dumbbell,
    CalendarDays,
    Loader2,
    AlertCircle,
    ChevronLeft,
} from "lucide-react";

const dayNames = {
    sunday: "الأحد",
    monday: "الإثنين",
    tuesday: "الثلاثاء",
    wednesday: "الأربعاء",
    thursday: "الخميس",
    friday: "الجمعة",
    saturday: "السبت",
};

const statusNames = {
    active: "نشطة",
    completed: "مكتملة",
    cancelled: "ملغاة",
};

const formatDate = (value) => {
    if (!value) return "غير محدد";

    const date = new Date(value);

    return Number.isNaN(date.getTime())
        ? "غير محدد"
        : date.toLocaleDateString("ar-EG");
};

export default function WorkoutVersionsModal({
    isOpen,
    onClose,
    traineeName,
    versions = [],
    selectedVersion,
    selectedPlanId,
    onSelectVersion,
    onBack,
    isLoading = false,
    isDetailsLoading = false,
    error = null,
}) {
    if (!isOpen) return null;

    const showingDetails = selectedPlanId !== null;

    const planExercises =
        selectedVersion?.workout_exercises ?? [];

    const planDays = selectedVersion?.days ?? [];

    // Include days even if no record exists in workout_plan_days.
    const allDayKeys = [
        ...new Set([
            ...planDays.map((day) => day.day),
            ...planExercises.map((exercise) => exercise.day_of_week),
        ]),
    ].filter(Boolean);

    return (
        <div
            dir="rtl"
            className="fixed inset-0 z-[110] flex items-center justify-center bg-black/50 p-4"
            onMouseDown={(event) => {
                if (event.target === event.currentTarget) onClose();
            }}
        >
            <div
                role="dialog"
                aria-modal="true"
                aria-label="إصدارات خطط التمارين"
                className="flex max-h-[90vh] w-full max-w-3xl flex-col overflow-hidden rounded-2xl bg-white shadow-xl"
            >
                <header className="flex items-center justify-between border-b border-gray-100 p-5">
                    <div className="flex items-center gap-3">
                        {showingDetails && (
                            <button
                                type="button"
                                onClick={onBack}
                                aria-label="العودة لقائمة الإصدارات"
                                className="rounded-lg p-2 hover:bg-gray-100"
                            >
                                <ArrowRight size={20} />
                            </button>
                        )}

                        <div className="rounded-xl bg-green-50 p-3 text-[#407437]">
                            <History size={22} />
                        </div>

                        <div>
                            <h2 className="font-bold text-[#1A3D27]">
                                {showingDetails
                                    ? selectedVersion
                                        ? `خطة التمارين V${selectedVersion.version}`
                                        : "تفاصيل الإصدار"
                                    : "إصدارات خطط التمارين"}
                            </h2>

                            <p className="mt-1 text-xs text-gray-500">
                                المشترك: {traineeName}
                            </p>
                        </div>
                    </div>

                    <button
                        type="button"
                        onClick={onClose}
                        aria-label="إغلاق"
                        className="rounded-lg p-2 text-gray-500 hover:bg-gray-100"
                    >
                        <X size={20} />
                    </button>
                </header>

                <div className="flex-1 space-y-4 overflow-y-auto p-5">
                    {error && (
                        <div className="flex items-center gap-2 rounded-lg bg-red-50 p-4 text-sm text-red-600">
                            <AlertCircle size={18} />
                            <span>{error}</span>
                        </div>
                    )}

                    {(isLoading || isDetailsLoading) && (
                        <div className="flex min-h-48 items-center justify-center gap-2 text-gray-500">
                            <Loader2
                                size={22}
                                className="animate-spin"
                            />
                            جاري تحميل البيانات...
                        </div>
                    )}

                    {!showingDetails && !isLoading && (
                        <>
                            <div className="flex items-center justify-between">
                                <h3 className="font-semibold text-gray-800">
                                    الإصدارات المحفوظة
                                </h3>

                                <span className="rounded-lg bg-green-50 px-3 py-1 text-xs text-[#407437]">
                                    {versions.length} إصدار
                                </span>
                            </div>

                            {versions.length === 0 && !error && (
                                <div className="py-14 text-center">
                                    <Dumbbell
                                        size={30}
                                        className="mx-auto mb-3 text-gray-300"
                                    />
                                    <p className="text-sm text-gray-500">
                                        لا توجد خطط مرسلة سابقًا لهذا المشترك.
                                    </p>
                                </div>
                            )}

                            {versions.map((version) => (
                                <button
                                    key={version.id}
                                    type="button"
                                    onClick={() =>
                                        onSelectVersion(version.id)
                                    }
                                    className="flex w-full items-center justify-between gap-4 rounded-xl border border-gray-200 p-4 text-right transition hover:border-[#407437] hover:bg-green-50/30"
                                >
                                    <div className="flex items-center gap-3">
                                        <div className="rounded-xl bg-green-50 p-3 text-[#407437]">
                                            <Dumbbell size={21} />
                                        </div>

                                        <div>
                                            <h4 className="font-bold text-gray-800">
                                                الإصدار V{version.version}
                                            </h4>

                                            <div className="mt-2 flex flex-wrap items-center gap-3 text-xs text-gray-500">
                                                <span className="flex items-center gap-1">
                                                    <CalendarDays size={13} />
                                                    {formatDate(
                                                        version.created_at
                                                    )}
                                                </span>

                                                <span>
                                                    {version.workout_exercises_count ?? 0}{" "}
                                                    تمرين
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div className="flex items-center gap-2">
                                        <span className="rounded-lg bg-gray-100 px-3 py-1.5 text-xs text-gray-600">
                                            {statusNames[version.status] ??
                                                version.status}
                                        </span>

                                        <ChevronLeft
                                            size={18}
                                            className="text-gray-400"
                                        />
                                    </div>
                                </button>
                            ))}
                        </>
                    )}

                    {showingDetails &&
                        !isDetailsLoading &&
                        selectedVersion && (
                            <div className="space-y-5">
                                <div className="rounded-xl bg-[#F4F9F2] p-4">
                                    <h3 className="font-bold text-[#1A3D27]">
                                        تفاصيل الإصدار V
                                        {selectedVersion.version}
                                    </h3>

                                    <div className="mt-3 flex flex-wrap gap-4 text-xs text-gray-600">
                                        <span>
                                            الحالة:{" "}
                                            {statusNames[
                                                selectedVersion.status
                                            ] ?? selectedVersion.status}
                                        </span>

                                        <span>
                                            تاريخ الإنشاء:{" "}
                                            {formatDate(
                                                selectedVersion.created_at
                                            )}
                                        </span>

                                        <span>
                                            عدد التمارين: {planExercises.length}
                                        </span>
                                    </div>
                                </div>

                                {allDayKeys.length === 0 && (
                                    <p className="py-8 text-center text-sm text-gray-500">
                                        لا توجد أيام مسجلة في هذا الإصدار.
                                    </p>
                                )}

                                {allDayKeys.map((dayKey) => {
                                    const dayInfo = planDays.find(
                                        (day) => day.day === dayKey
                                    );

                                    const exercises = planExercises.filter(
                                        (item) =>
                                            item.day_of_week === dayKey
                                    );

                                    return (
                                        <section
                                            key={dayKey}
                                            className="overflow-hidden rounded-xl border border-gray-200"
                                        >
                                            <div className="flex flex-wrap items-center justify-between gap-2 bg-gray-50 p-4">
                                                <h4 className="font-bold text-gray-800">
                                                    {dayNames[dayKey] ??
                                                        dayKey}
                                                </h4>

                                                <span className="text-xs text-gray-500">
                                                    {dayInfo?.focus ||
                                                        "بدون تركيز محدد"}
                                                </span>
                                            </div>

                                            <div className="space-y-3 p-4">
                                                {exercises.length === 0 && (
                                                    <p className="text-sm text-gray-400">
                                                        يوم راحة / لا توجد تمارين
                                                    </p>
                                                )}

                                                {exercises.map(
                                                    (exercise) => (
                                                        <div
                                                            key={exercise.id}
                                                            className="rounded-lg border border-gray-100 p-3"
                                                        >
                                                            <h5 className="font-semibold text-gray-800">
                                                                {exercise.exercise
                                                                    ?.name_ar ||
                                                                    exercise.exercise
                                                                        ?.name ||
                                                                    exercise.exercise_name ||
                                                                    exercise.custom_exercise
                                                                        ?.name ||
                                                                    "تمرين غير محدد"}
                                                            </h5>

                                                            <div className="mt-3 flex flex-wrap gap-4 text-xs text-gray-600">
                                                                <span>
                                                                    المجموعات:{" "}
                                                                    {exercise.sets ??
                                                                        "-"}
                                                                </span>

                                                                <span>
                                                                    التكرارات:{" "}
                                                                    {exercise.reps ??
                                                                        "-"}
                                                                </span>

                                                                <span>
                                                                    الراحة:{" "}
                                                                    {exercise.rest_time ??
                                                                        "-"}{" "}
                                                                    ثانية
                                                                </span>
                                                            </div>

                                                            {exercise.notes && (
                                                                <p className="mt-3 text-xs leading-6 text-gray-500">
                                                                    ملاحظات:{" "}
                                                                    {exercise.notes}
                                                                </p>
                                                            )}
                                                        </div>
                                                    )
                                                )}
                                            </div>
                                        </section>
                                    );
                                })}
                            </div>
                        )}
                </div>

                <footer className="border-t border-gray-100 px-5 py-3 text-center text-xs text-gray-400">
                    الإصدارات السابقة للعرض فقط ولا يمكن تعديلها.
                </footer>
            </div>
        </div>
    );
}