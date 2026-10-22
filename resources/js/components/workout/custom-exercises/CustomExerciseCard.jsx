import React from "react";
import {
    Dumbbell,
    Eye,
    Pencil,
    Trash2,
    Target,
    Layers3,
    ArrowUpLeft,
} from "lucide-react";
import { getImageUrl } from "../../../utils/imageUrl";
export default function CustomExerciseCard({
    exercise,
    onView,
    onEdit,
    onDelete,
}) {
    const difficultyStyles = {
        مبتدئ: "bg-[#EEF5EC] text-[#407437] ring-[#DCE9D8]",
        متوسط: "bg-amber-50 text-amber-700 ring-amber-100",
        متقدم: "bg-red-50 text-red-700 ring-red-100",
        beginner: "bg-[#EEF5EC] text-[#407437] ring-[#DCE9D8]",
        intermediate: "bg-amber-50 text-amber-700 ring-amber-100",
        advanced: "bg-red-50 text-red-700 ring-red-100",
    };

    const difficultyClass =
        difficultyStyles[
            String(exercise.difficulty || "").toLowerCase()
        ] || "bg-gray-100 text-gray-600 ring-gray-200";


    const imageUrl = getImageUrl(exercise.image);

    return (
        <article
            dir="rtl"
            className="
                group flex h-full flex-col overflow-hidden
                rounded-2xl border border-gray-200/80
                bg-white shadow-sm
                transition-all duration-300
                hover:-translate-y-1
                hover:border-[#C8DCC3]
                hover:shadow-lg hover:shadow-[#407437]/10
            "
        >
            {/* Image */}
            <div className="relative h-48 w-full overflow-hidden bg-[#F4F8F2]">
                {imageUrl ? (
                    <img
                        src={imageUrl}
                        alt={exercise.name || "صورة التمرين"}
                        className="
                            h-full w-full object-cover
                            transition-transform duration-300
                            group-hover:scale-[1.03]
                        "
                    />
                ) : (
                    <div
                        className="
                            flex h-full w-full items-center
                            justify-center text-[#407437]
                        "
                    >
                        <div className="flex flex-col items-center gap-2">
                            <div
                                className="
                                    flex h-14 w-14 items-center justify-center
                                    rounded-2xl bg-[#EEF5EC]
                                    ring-1 ring-[#DCE9D8]
                                "
                            >
                                <Dumbbell size={26} />
                            </div>

                            <span className="text-xs text-gray-400">
                                لا توجد صورة
                            </span>
                        </div>
                    </div>
                )}

                {/* Difficulty Badge */}
                <span
                    className={`
                        absolute top-3 right-3
                        inline-flex items-center gap-1.5
                        rounded-lg px-3 py-1.5
                        text-xs font-semibold ring-1
                        backdrop-blur-sm
                        ${difficultyClass}
                    `}
                >
                    <span className="h-1.5 w-1.5 rounded-full bg-current" />
                    {exercise.difficulty || "غير محدد"}
                </span>
            </div>

            {/* Header */}
            <div className="flex items-start gap-3 p-5 pb-4">
                <div
                    className="
                        flex h-11 w-11 shrink-0 items-center
                        justify-center rounded-xl
                        bg-[#EEF5EC] text-[#407437]
                        ring-1 ring-[#DCE9D8]
                    "
                >
                    <Dumbbell size={21} strokeWidth={1.8} />
                </div>

                <div className="min-w-0 flex-1">
                    <div className="mb-1 flex items-center gap-2">
                        <span className="text-[11px] font-medium text-gray-400">
                            تمرين مخصص
                        </span>

                        <span className="h-1 w-1 rounded-full bg-gray-300" />

                        <span className="text-[11px] text-[#407437]">
                            مكتبة المدرب
                        </span>
                    </div>

                    <h3
                        className="truncate text-[16px] font-bold leading-6 text-gray-900"
                        title={exercise.name}
                    >
                        {exercise.name || "تمرين بدون اسم"}
                    </h3>
                </div>
            </div>

            {/* Details */}
            <div className="grid grid-cols-2 gap-3 px-5">
                <div className="min-w-0 rounded-xl bg-gray-50 p-3">
                    <div className="mb-2 flex items-center gap-1.5 text-gray-400">
                        <Target size={15} />
                        <span className="text-xs">العضلة المستهدفة</span>
                    </div>

                    <p
                        className="truncate text-sm font-semibold text-gray-800"
                        title={exercise.muscle_group}
                    >
                        {exercise.muscle_group || "غير محددة"}
                    </p>
                </div>

                <div className="min-w-0 rounded-xl bg-gray-50 p-3">
                    <div className="mb-2 flex items-center gap-1.5 text-gray-400">
                        <Layers3 size={15} />
                        <span className="text-xs">المعدات</span>
                    </div>

                    <p
                        className="truncate text-sm font-semibold text-gray-800"
                        title={exercise.equipment}
                    >
                        {exercise.equipment || "بدون معدات"}
                    </p>
                </div>
            </div>

            {/* Description */}
            <div className="flex-1 px-5 pb-5 pt-4">
                <p className="mb-1.5 text-xs font-semibold text-gray-700">
                    وصف التمرين
                </p>

                <p className="line-clamp-2 min-h-10 text-sm leading-5 text-gray-500">
                    {exercise.description ||
                        "لا يوجد وصف مضاف لهذا التمرين حتى الآن."}
                </p>
            </div>

            {/* Actions */}
            <div className="border-t border-gray-100 bg-gray-50/60 p-3">
                <div className="flex items-center gap-2">
                    <button
                        type="button"
                        onClick={() => onView?.(exercise)}
                        className="
                            flex min-w-0 flex-1 items-center
                            justify-center gap-2 rounded-xl
                            bg-[#407437] px-3 py-2.5
                            text-sm font-semibold text-white
                            transition-colors
                            hover:bg-[#35612F]
                        "
                    >
                        <Eye size={17} />
                        <span>عرض التفاصيل</span>
                        <ArrowUpLeft size={14} className="opacity-70" />
                    </button>

                    <button
                        type="button"
                        onClick={() => onEdit?.(exercise)}
                        aria-label={`تعديل ${exercise.name || "التمرين"}`}
                        title="تعديل التمرين"
                        className="
                            flex h-10 w-10 shrink-0 items-center
                            justify-center rounded-xl border
                            border-gray-200 bg-white text-gray-600
                            transition-colors
                            hover:border-[#C8DCC3]
                            hover:bg-[#EEF5EC]
                            hover:text-[#407437]
                        "
                    >
                        <Pencil size={17} />
                    </button>

                    <button
                        type="button"
                        onClick={() => onDelete?.(exercise)}
                        aria-label={`حذف ${exercise.name || "التمرين"}`}
                        title="حذف التمرين"
                        className="
                            flex h-10 w-10 shrink-0 items-center
                            justify-center rounded-xl border
                            border-gray-200 bg-white text-gray-400
                            transition-colors
                            hover:border-red-200
                            hover:bg-red-50
                            hover:text-red-600
                        "
                    >
                        <Trash2 size={17} />
                    </button>
                </div>
            </div>
        </article>
    );
}