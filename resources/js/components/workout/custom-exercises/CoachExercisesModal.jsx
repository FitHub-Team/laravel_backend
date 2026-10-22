import { useMemo, useState } from "react";
import {
    X,
    Search,
    Dumbbell,
    Plus,
} from "lucide-react";

import { getImageUrl } from "../../../utils/imageUrl";

export default function CoachExercisesModal({
    isOpen,
    onClose,
    exercises = [],
    isLoading = false,
    onSelectExercise,
}) {
    const [search, setSearch] = useState("");

    const filteredExercises = useMemo(() => {
        return exercises.filter((exercise) => {
            const name = exercise.name_ar || exercise.name || "";

            return name
                .toLowerCase()
                .includes(search.trim().toLowerCase());
        });
    }, [exercises, search]);

    if (!isOpen) return null;

    const handleSelect = (exercise) => {
        onSelectExercise(exercise);
        onClose();
        setSearch("");
    };

    return (
        <div
            dir="rtl"
            className="
                fixed inset-0 z-[110]
                flex items-center justify-center
                bg-black/50 p-4
            "
            onMouseDown={(event) => {
                if (event.target === event.currentTarget) {
                    onClose();
                }
            }}
        >
            <div
                role="dialog"
                aria-modal="true"
                aria-label="تمارين المدرب الخاصة"
                className="
                    flex max-h-[85vh] w-full max-w-3xl
                    flex-col overflow-hidden
                    rounded-2xl bg-white shadow-xl
                "
            >
                {/* Header */}
                <div className="flex items-center justify-between border-b p-5">
                    <div>
                        <h2 className="flex items-center gap-2 text-lg font-bold text-[#1A3D27]">
                            <Dumbbell
                                size={22}
                                className="text-[#407437]"
                            />

                            تماريني الخاصة
                        </h2>

                        <p className="mt-1 text-sm text-gray-500">
                            اختر تمرينًا من مكتبتك لإضافته إلى الخطة
                        </p>
                    </div>

                    <button
                        type="button"
                        onClick={onClose}
                        className="
                            rounded-lg p-2
                            text-gray-500
                            transition
                            hover:bg-gray-100
                            hover:text-gray-700
                        "
                        aria-label="إغلاق"
                    >
                        <X size={20} />
                    </button>
                </div>

                {/* Search */}
                <div className="border-b p-4">
                    <div
                        className="
                            flex items-center gap-2
                            rounded-xl border border-gray-200
                            px-3
                            transition
                            focus-within:border-[#407437]
                            focus-within:ring-2
                            focus-within:ring-[#407437]/10
                        "
                    >
                        <Search
                            size={18}
                            className="text-gray-400"
                        />

                        <input
                            type="text"
                            value={search}
                            onChange={(event) =>
                                setSearch(event.target.value)
                            }
                            placeholder="ابحث عن تمرين خاص..."
                            className="
                                w-full bg-transparent
                                py-3 text-sm
                                outline-none
                            "
                        />
                    </div>
                </div>

                {/* Exercises */}
                <div className="flex-1 overflow-y-auto p-4">
                    {isLoading ? (
                        <p className="py-10 text-center text-sm text-gray-500">
                            جاري تحميل التمارين...
                        </p>
                    ) : filteredExercises.length === 0 ? (
                        <div className="py-12 text-center">
                            <Dumbbell
                                size={32}
                                className="mx-auto mb-3 text-gray-300"
                            />

                            <p className="text-sm text-gray-500">
                                لا توجد تمارين خاصة مطابقة للبحث.
                            </p>
                        </div>
                    ) : (
                        <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            {filteredExercises.map((exercise) => {
                                const imageUrl = getImageUrl(
                                    exercise.image
                                );

                                return (
                                    <div
                                        key={exercise.id}
                                        className="
                                            group overflow-hidden
                                            rounded-2xl
                                            border border-gray-200
                                            bg-white
                                            transition-all
                                            hover:border-[#C8DCC3]
                                            hover:shadow-md
                                        "
                                    >
                                        {/* Image */}
                                        <div className="h-40 w-full overflow-hidden bg-[#F4F8F2]">
                                            {imageUrl ? (
                                                <img
                                                    src={imageUrl}
                                                    alt={
                                                        exercise.name ||
                                                        "صورة التمرين"
                                                    }
                                                    className="
                                                        h-full w-full
                                                        object-cover
                                                        transition-transform
                                                        duration-300
                                                        group-hover:scale-[1.03]
                                                    "
                                                />
                                            ) : (
                                                <div
                                                    className="
                                                        flex h-full
                                                        items-center
                                                        justify-center
                                                    "
                                                >
                                                    <div className="flex flex-col items-center gap-2">
                                                        <div
                                                            className="
                                                                flex h-12 w-12
                                                                items-center
                                                                justify-center
                                                                rounded-xl
                                                                bg-[#EEF5EC]
                                                                text-[#407437]
                                                            "
                                                        >
                                                            <Dumbbell
                                                                size={24}
                                                            />
                                                        </div>

                                                        <span className="text-xs text-gray-400">
                                                            لا توجد صورة
                                                        </span>
                                                    </div>
                                                </div>
                                            )}
                                        </div>

                                        {/* Content */}
                                        <div className="p-4">
                                            <div className="flex items-start justify-between gap-3">
                                                <div className="min-w-0 flex-1">
                                                    <h3
                                                        className="
                                                            truncate
                                                            font-semibold
                                                            text-gray-800
                                                        "
                                                    >
                                                        {exercise.name_ar ||
                                                            exercise.name ||
                                                            "تمرين بدون اسم"}
                                                    </h3>

                                                    <p className="mt-1 text-xs text-gray-500">
                                                        {exercise.target_muscle ||
                                                            exercise.muscle_group ||
                                                            "غير محدد"}
                                                    </p>
                                                </div>

                                                {exercise.difficulty && (
                                                    <span
                                                        className="
                                                            shrink-0
                                                            rounded-lg
                                                            bg-[#EEF5EC]
                                                            px-2.5 py-1
                                                            text-[11px]
                                                            font-semibold
                                                            text-[#407437]
                                                        "
                                                    >
                                                        {
                                                            exercise.difficulty
                                                        }
                                                    </span>
                                                )}
                                            </div>

                                            {/* Equipment */}
                                            <div className="mt-3 flex items-center gap-2 text-xs text-gray-500">
                                                <Dumbbell
                                                    size={14}
                                                    className="text-gray-400"
                                                />

                                                <span>
                                                    {exercise.equipment ||
                                                        "بدون معدات"}
                                                </span>
                                            </div>

                                            {/* Action */}
                                            <button
                                                type="button"
                                                onClick={() =>
                                                    handleSelect(exercise)
                                                }
                                                className="
                                                    mt-4 flex w-full
                                                    items-center
                                                    justify-center gap-2
                                                    rounded-xl
                                                    bg-[#407437]
                                                    px-3 py-2.5
                                                    text-sm font-semibold
                                                    text-white
                                                    transition
                                                    hover:bg-[#32632C]
                                                "
                                            >
                                                <Plus size={16} />

                                                إضافة إلى الخطة
                                            </button>
                                        </div>
                                    </div>
                                );
                            })}
                        </div>
                    )}
                </div>
            </div>
        </div>
    );
}
