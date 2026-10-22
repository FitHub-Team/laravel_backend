import { useEffect, useMemo, useState } from "react";
import {
    AlertCircle,
    ChevronLeft,
    ChevronRight,
    Dumbbell,
    LoaderCircle,
    RotateCcw,
    X,
} from "lucide-react";

import useExercises from "../../hooks/useExercises";
import ExerciseFilters from "./exercise-library/ExerciseFilters";
import ExerciseCard from "./exercise-library/ExerciseCard";
import ExerciseDetails from "./exercise-library/ExerciseDetails";
import FlashMessage from "../../components/common/FlashMessage";

const ExerciseLibraryModal = ({
    isOpen,
    onClose,
    onSelectExercise,
    selectedTrainee,
}) => {
    const [search, setSearch] = useState("");
    const [categoryFilter, setCategoryFilter] = useState("");
    const [difficultyFilter, setDifficultyFilter] = useState("");
    const [flashMessage, setFlashMessage] = useState("");
    const [selectedExercise, setSelectedExercise] = useState(null);
    const [page, setPage] = useState(1);

    // Keep the enabled-first signature compatible with existing useExercises(false) calls.
    const { data, isLoading, isFetching, isError, refetch } = useExercises(
        isOpen,
        {
            page,
            search: search.trim(),
            muscle_group: categoryFilter,
            difficulty_level: difficultyFilter,
        },
    );

    // Laravel response can be {status, data: paginator} or the paginator itself.
    const paginator =
        data?.data?.current_page != null
            ? data.data
            : data?.current_page != null
              ? data
              : null;
    const exercises = Array.isArray(paginator?.data)
        ? paginator.data
        : Array.isArray(data?.data)
          ? data.data
          : Array.isArray(data)
            ? data
            : [];
    const currentPage = paginator?.current_page ?? page;
    const lastPage = Math.max(1, Number(paginator?.last_page ?? 1));
    const total = paginator?.total ?? exercises.length;

    const categories = useMemo(
        () => [
            ...new Set(
                exercises
                    .map((e) => e.muscle_group ?? e.category)
                    .filter(Boolean),
            ),
        ],
        [exercises],
    );
    const difficulties = useMemo(
        () => [
            ...new Set(
                exercises.map((e) => e.difficulty_level).filter(Boolean),
            ),
        ],
        [exercises],
    );

    // Reset pagination whenever backend filters change.
    const updateSearch = (value) => {
        setSearch(value);
        setPage(1);
    };
    const updateCategory = (value) => {
        setCategoryFilter(value);
        setPage(1);
    };
    const updateDifficulty = (value) => {
        setDifficultyFilter(value);
        setPage(1);
    };

    const handleClose = () => {
        setSearch("");
        setCategoryFilter("");
        setDifficultyFilter("");
        setFlashMessage("");
        setSelectedExercise(null);
        setPage(1);
        onClose?.();
    };

    const handleAddExercise = () => {
        if (!selectedExercise) return;
        if (typeof onSelectExercise !== "function") {
            setFlashMessage("تعذر إضافة التمرين. يرجى المحاولة مرة أخرى.");
            return;
        }
        onSelectExercise(selectedExercise);
        handleClose();
    };

    useEffect(() => {
        if (!isOpen) return undefined;
        const onKeyDown = (event) => {
            if (event.key === "Escape") handleClose();
        };
        window.addEventListener("keydown", onKeyDown);
        return () => window.removeEventListener("keydown", onKeyDown);
        // onClose is expected to be stable in the parent.
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [isOpen]);

    if (!isOpen) return null;

    return (
        <div
            dir="rtl"
            role="presentation"
            className="fixed inset-0 z-[100] flex items-center justify-center bg-slate-950/55 p-2 backdrop-blur-[3px] sm:p-5"
            onMouseDown={(e) => {
                if (e.target === e.currentTarget) handleClose();
            }}
        >
            <FlashMessage
                message={flashMessage}
                type="error"
                onClose={() => setFlashMessage("")}
            />

            <section
                role="dialog"
                aria-modal="true"
                aria-label={
                    selectedExercise ? "تفاصيل التمرين" : "مكتبة التمارين"
                }
                className="flex h-[min(92dvh,850px)] w-full max-w-6xl flex-col overflow-hidden rounded-[24px] border border-slate-200 bg-white shadow-2xl sm:rounded-[28px]"
            >
                <header className="flex shrink-0 items-center justify-between border-b border-slate-100 bg-white px-4 py-4 sm:px-7 sm:py-5">
                    <div className="flex min-w-0 items-center gap-3">
                        {selectedExercise && (
                            <button
                                type="button"
                                onClick={() => setSelectedExercise(null)}
                                className="grid h-10 w-10 shrink-0 place-items-center rounded-xl border border-slate-200 text-slate-600 transition hover:bg-slate-50"
                                aria-label="العودة إلى المكتبة"
                            >
                                <ChevronRight size={19} />
                            </button>
                        )}
                        <span className="grid h-11 w-11 shrink-0 place-items-center rounded-2xl bg-[#407437] text-white shadow-sm">
                            <Dumbbell size={21} />
                        </span>
                        <div className="min-w-0">
                            <h2 className="text-base font-bold text-slate-900 sm:text-lg">
                                {selectedExercise
                                    ? "تفاصيل التمرين"
                                    : "مكتبة التمارين"}
                            </h2>
                            <p className="mt-0.5 text-xs text-slate-500 sm:text-sm">
                                {selectedExercise
                                    ? "راجعي التفاصيل قبل إضافته للخطة"
                                    : "اختاري التمرين الأنسب لخطة المتدرب"}
                            </p>
                        </div>
                    </div>
                    <button
                        type="button"
                        onClick={handleClose}
                        aria-label="إغلاق مكتبة التمارين"
                        className="grid h-10 w-10 shrink-0 place-items-center rounded-xl text-slate-500 transition hover:bg-slate-100 hover:text-slate-900"
                    >
                        <X size={21} />
                    </button>
                </header>

                {selectedExercise ? (
                    <div className="min-h-0 flex-1 overflow-y-auto">
                        <ExerciseDetails
                            exercise={selectedExercise}
                            onAdd={handleAddExercise}
                            onBack={() => setSelectedExercise(null)}
                        />
                    </div>
                ) : (
                    <>
                        <div className="shrink-0 border-b border-slate-100 bg-slate-50/60">
                            <ExerciseFilters
                                search={search}
                                onSearchChange={updateSearch}
                                categoryFilter={categoryFilter}
                                onCategoryChange={updateCategory}
                                difficultyFilter={difficultyFilter}
                                onDifficultyChange={updateDifficulty}
                                categories={categories}
                                difficulties={difficulties}
                                resultsCount={total}
                            />
                        </div>

                        {selectedTrainee?.trainee?.user_profile
                            ?.medical_restrictions && (
                            <div className="mx-4 mt-4 flex shrink-0 items-start gap-3 rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-amber-900 sm:mx-7">
                                <AlertCircle
                                    size={20}
                                    className="mt-0.5 shrink-0"
                                />
                                <div>
                                    <p className="text-sm font-bold">
                                        تنبيه صحي
                                    </p>
                                    <p className="mt-1 text-xs leading-6">
                                        يجب مراعاة القيود الصحية للمتدرب عند
                                        اختيار التمارين.
                                    </p>
                                    <p className="mt-1 whitespace-pre-wrap text-xs font-medium leading-6">
                                        {
                                            selectedTrainee.trainee.user_profile
                                                .medical_restrictions
                                        }
                                    </p>
                                </div>
                            </div>
                        )}

                        <main className="min-h-0 flex-1 overflow-y-auto bg-white px-4 py-5 sm:px-7">
                            {isLoading ? (
                                <div className="flex h-full min-h-52 flex-col items-center justify-center gap-3 text-slate-500">
                                    <LoaderCircle
                                        size={31}
                                        className="animate-spin text-[#407437]"
                                    />
                                    <p className="text-sm">
                                        جاري تحميل التمارين...
                                    </p>
                                </div>
                            ) : isError ? (
                                <div className="flex min-h-52 flex-col items-center justify-center gap-3 text-center">
                                    <AlertCircle
                                        size={34}
                                        className="text-red-500"
                                    />
                                    <p className="text-sm font-semibold text-slate-900">
                                        تعذر تحميل التمارين
                                    </p>
                                    <p className="text-xs text-slate-500">
                                        تحققي من الاتصال وحاولي مرة أخرى.
                                    </p>
                                    <button
                                        type="button"
                                        onClick={() => refetch()}
                                        className="mt-1 inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2 text-sm hover:bg-slate-50"
                                    >
                                        <RotateCcw size={15} /> إعادة المحاولة
                                    </button>
                                </div>
                            ) : exercises.length === 0 ? (
                                <div className="flex min-h-52 flex-col items-center justify-center gap-2 text-center">
                                    <span className="grid h-14 w-14 place-items-center rounded-2xl bg-green-50 text-[#407437]">
                                        <Dumbbell />
                                    </span>
                                    <p className="mt-2 text-sm font-semibold text-slate-900">
                                        لا توجد تمارين مطابقة
                                    </p>
                                    <p className="text-xs text-slate-500">
                                        جرّبي تغيير البحث أو خيارات التصفية.
                                    </p>
                                </div>
                            ) : (
                                <div className="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                                    {exercises.map((exercise) => (
                                        <ExerciseCard
                                            key={exercise.id}
                                            exercise={exercise}
                                            onSelect={() =>
                                                setSelectedExercise(exercise)
                                            }
                                        />
                                    ))}
                                </div>
                            )}
                        </main>

                        <footer className="flex shrink-0 flex-col gap-3 border-t border-slate-100 bg-white px-4 py-3 sm:flex-row sm:items-center sm:justify-between sm:px-7">
                            <div className="text-center text-xs text-slate-500 sm:text-right">
                                {total > 0
                                    ? `${total} تمرين • الصفحة ${currentPage} من ${lastPage}`
                                    : "مكتبة التمارين"}
                                {isFetching && !isLoading && (
                                    <span className="mr-2 text-[#407437]">
                                        جاري التحديث...
                                    </span>
                                )}
                            </div>
                            <div className="flex items-center justify-center gap-2">
                                <button
                                    type="button"
                                    onClick={() =>
                                        setPage((p) => Math.max(1, p - 1))
                                    }
                                    disabled={page <= 1 || isFetching}
                                    className="inline-flex h-10 items-center gap-1 rounded-xl border border-slate-200 px-3 text-sm font-medium text-slate-700 transition hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-40"
                                >
                                    <ChevronRight size={17} /> السابق
                                </button>
                                <span className="min-w-14 rounded-lg bg-green-50 px-3 py-2 text-center text-sm font-bold text-[#407437]">
                                    {currentPage}
                                </span>
                                <button
                                    type="button"
                                    onClick={() =>
                                        setPage((p) =>
                                            Math.min(lastPage, p + 1),
                                        )
                                    }
                                    disabled={page >= lastPage || isFetching}
                                    className="inline-flex h-10 items-center gap-1 rounded-xl border border-slate-200 px-3 text-sm font-medium text-slate-700 transition hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-40"
                                >
                                    التالي <ChevronLeft size={17} />
                                </button>
                            </div>
                            <button
                                type="button"
                                onClick={handleClose}
                                className="hidden rounded-xl px-4 py-2 text-sm font-medium text-slate-500 transition hover:bg-slate-100 sm:block"
                            >
                                إغلاق
                            </button>
                        </footer>
                    </>
                )}
            </section>
        </div>
    );
};

export default ExerciseLibraryModal;
