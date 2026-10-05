import React from "react";
import {
    Dumbbell,
    Eye,
    Pencil,
    Trash2,
    CircleDot,
} from "lucide-react";

export default function CustomExerciseCard({
    exercise,
    onView,
    onEdit,
    onDelete,
}) {
    return (
        <div className="group bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200 overflow-hidden">
            {/* Top */}
            <div className="p-5">
                <div className="flex items-start justify-between gap-4">
                    {/* Icon + Name */}
                    <div className="flex items-center gap-3 min-w-0">
                        <div className="w-11 h-11 rounded-xl bg-green-50 flex items-center justify-center shrink-0">
                            <Dumbbell
                                size={22}
                                className="text-green-600"
                            />
                        </div>

                        <div className="min-w-0">
                            <h3 className="font-bold text-base text-gray-800 truncate">
                                {exercise.name}
                            </h3>

                            <p className="text-xs text-gray-400 mt-1">
                                تمرين خاص
                            </p>
                        </div>
                    </div>

                    {/* Difficulty */}
                    <span className="shrink-0 text-xs font-medium px-3 py-1.5 rounded-full bg-green-50 text-green-700">
                        {exercise.difficulty}
                    </span>
                </div>

                {/* Details */}
                <div className="grid grid-cols-2 gap-3 mt-5">
                    <div className="bg-gray-50 rounded-xl px-3 py-2.5">
                        <div className="flex items-center gap-1.5 text-xs text-gray-400 mb-1">
                            <CircleDot size={13} />
                            العضلة
                        </div>

                        <p className="text-sm font-medium text-gray-700 truncate">
                            {exercise.muscle_group}
                        </p>
                    </div>

                    <div className="bg-gray-50 rounded-xl px-3 py-2.5">
                        <div className="flex items-center gap-1.5 text-xs text-gray-400 mb-1">
                            <Dumbbell size={13} />
                            المعدات
                        </div>

                        <p className="text-sm font-medium text-gray-700 truncate">
                            {exercise.equipment || "بدون معدات"}
                        </p>
                    </div>
                </div>

                {/* Description */}
                {exercise.description && (
                    <p className="text-sm text-gray-500 leading-6 mt-4 line-clamp-2">
                        {exercise.description}
                    </p>
                )}
            </div>

            {/* Actions */}
            <div className="px-5 py-3 bg-gray-50/70 border-t border-gray-100">
                <div className="flex items-center gap-2">
                    <button
                        onClick={() => onView(exercise)}
                        className="flex-1 flex items-center justify-center gap-1.5
                        text-sm font-medium text-green-700
                        bg-green-50 hover:bg-green-100
                        py-2 rounded-lg transition"
                    >
                        <Eye size={16} />
                        عرض
                    </button>

                    <button
                        onClick={() => onEdit(exercise)}
                        className="flex-1 flex items-center justify-center gap-1.5
                        text-sm font-medium text-gray-600
                        bg-white border border-gray-200
                        hover:bg-gray-100
                        py-2 rounded-lg transition"
                    >
                        <Pencil size={16} />
                        تعديل
                    </button>

                    <button
                        onClick={() => onDelete(exercise)}
                        className="w-10 h-9 flex items-center justify-center
                        rounded-lg text-red-500
                        bg-white border border-gray-200
                        hover:bg-red-50 hover:border-red-100
                        transition"
                        title="حذف"
                    >
                        <Trash2 size={16} />
                    </button>
                </div>
            </div>
        </div>
    );
}
