import React from "react";
import {
    Dumbbell,
    Target,
    Gauge,
    FileText,
    ListChecks,
} from "lucide-react";
import { getImageUrl } from "../../../utils/imageUrl";

export default function CustomExerciseDetails({ exercise }) {
    if (!exercise) return null;

    const Row = ({ icon: Icon, label, value }) => (
        <div
            className="
                rounded-xl border border-gray-100
                bg-gray-50 p-4
                transition-colors
                hover:border-[#DCE9D8]
                hover:bg-[#F7FAF6]
            "
        >
            <div className="mb-2 flex items-center gap-2 text-gray-400">
                <Icon size={16} />
                <span className="text-xs font-medium">{label}</span>
            </div>

            <p className="text-sm font-semibold text-gray-800">
                {value || "—"}
            </p>
        </div>
    );

    const difficultyStyles = {
        مبتدئ: "bg-[#EEF5EC] text-[#407437]",
        متوسط: "bg-amber-50 text-amber-700",
        متقدم: "bg-red-50 text-red-700",
        beginner: "bg-[#EEF5EC] text-[#407437]",
        intermediate: "bg-amber-50 text-amber-700",
        advanced: "bg-red-50 text-red-700",
    };

    const difficultyClass =
        difficultyStyles[
            String(exercise.difficulty || "").toLowerCase()
        ] || "bg-gray-100 text-gray-600";

     const imageUrl = getImageUrl(exercise.image);

    return (
        <div dir="rtl" className="flex flex-col gap-6">

            {/* Exercise Image */}
            <div
                className="
                    relative h-64 w-full overflow-hidden
                    rounded-2xl border border-gray-100
                    bg-[#F4F8F2]
                "
            >
                {imageUrl ? (
                    <img
                        src={imageUrl}
                        alt={exercise.name || "صورة التمرين"}
                        className="h-full w-full object-cover"
                    />
                ) : (
                    <div
                        className="
                            flex h-full w-full items-center justify-center
                        "
                    >
                        <div className="flex flex-col items-center gap-3">
                            <div
                                className="
                                    flex h-16 w-16 items-center justify-center
                                    rounded-2xl bg-[#EEF5EC]
                                    text-[#407437]
                                    ring-1 ring-[#DCE9D8]
                                "
                            >
                                <Dumbbell size={30} />
                            </div>

                            <span className="text-sm text-gray-400">
                                لا توجد صورة للتمرين
                            </span>
                        </div>
                    </div>
                )}

                <span
                    className={`
                        absolute top-4 right-4
                        rounded-lg px-3 py-1.5
                        text-xs font-semibold
                        shadow-sm
                        ${difficultyClass}
                    `}
                >
                    {exercise.difficulty || "غير محدد"}
                </span>
            </div>

            {/* Header */}
            <div className="flex items-start gap-4">
                <div
                    className="
                        flex h-12 w-12 shrink-0 items-center
                        justify-center rounded-2xl
                        bg-[#EEF5EC] text-[#407437]
                        ring-1 ring-[#DCE9D8]
                    "
                >
                    <Dumbbell size={23} />
                </div>

                <div className="min-w-0 flex-1">
                    <p className="mb-1 text-xs font-medium text-gray-400">
                        تمرين مخصص
                    </p>

                    <h3 className="text-xl font-bold text-gray-900">
                        {exercise.name || "تمرين بدون اسم"}
                    </h3>
                </div>
            </div>

            {/* Basic Information */}
            <div>
                <h4 className="mb-3 text-sm font-bold text-gray-800">
                    معلومات التمرين
                </h4>

                <div className="grid grid-cols-1 gap-3 sm:grid-cols-3">
                    <Row
                        icon={Target}
                        label="العضلة المستهدفة"
                        value={exercise.muscle_group}
                    />

                    <Row
                        icon={Dumbbell}
                        label="المعدات"
                        value={exercise.equipment || "بدون معدات"}
                    />

                    <Row
                        icon={Gauge}
                        label="مستوى الصعوبة"
                        value={exercise.difficulty}
                    />
                </div>
            </div>

            {/* Description */}
            <div
                className="
                    rounded-2xl border border-gray-100
                    bg-white p-4
                "
            >
                <div className="mb-3 flex items-center gap-2">
                    <div
                        className="
                            flex h-8 w-8 items-center justify-center
                            rounded-lg bg-[#EEF5EC]
                        "
                    >
                        <FileText
                            size={16}
                            className="text-[#407437]"
                        />
                    </div>

                    <h4 className="text-sm font-bold text-gray-800">
                        وصف التمرين
                    </h4>
                </div>

                <p className="whitespace-pre-line text-sm leading-7 text-gray-600">
                    {exercise.description ||
                        "لا يوجد وصف مضاف لهذا التمرين."}
                </p>
            </div>

            {/* Instructions */}
            <div
                className="
                    rounded-2xl border border-[#E4ECE1]
                    bg-[#F7FAF6] p-4
                "
            >
                <div className="mb-3 flex items-center gap-2">
                    <div
                        className="
                            flex h-8 w-8 items-center justify-center
                            rounded-lg bg-[#E8F1E5]
                        "
                    >
                        <ListChecks
                            size={16}
                            className="text-[#407437]"
                        />
                    </div>

                    <h4 className="text-sm font-bold text-gray-800">
                        تعليمات الأداء
                    </h4>
                </div>

                <p className="whitespace-pre-line text-sm leading-7 text-gray-600">
                    {exercise.instructions ||
                        "لا توجد تعليمات مضافة لهذا التمرين."}
                </p>
            </div>
        </div>
    );
}