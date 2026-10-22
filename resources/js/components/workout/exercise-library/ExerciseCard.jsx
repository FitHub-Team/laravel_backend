
import { ArrowUpLeft, Dumbbell } from "lucide-react";

import {
  translateDifficulty,
  translateEquipment,
  translateMuscleGroup,
  translateCategory,
} from "../../../utils/exerciseTranslations";

const ExerciseCard = ({ exercise, onSelect }) => {
  // استخدام الترجمة العربية مع الرجوع للإنجليزية عند غيابها
  const name = exercise.name_ar?.trim() || exercise.name || "تمرين بدون اسم";

  const muscleGroup =
    exercise.muscle_group_ar?.trim() ||
    translateMuscleGroup(exercise.muscle_group) ||
    "غير محدد";

  const equipment =
    exercise.equipment_ar?.trim() ||
    translateEquipment(exercise.equipment) ||
    "غير محدد";

  const category =
    exercise.type_ar?.trim() ||
    translateCategory(exercise.type) ||
    "تمرين";

  const difficulty =
    exercise.difficulty_level_ar?.trim() ||
    translateDifficulty(exercise.difficulty_level) ||
    "غير محدد";

  // تحديد لون الصعوبة باستخدام القيمة الإنجليزية الثابتة
  const difficultyKey = (exercise.difficulty_level || "")
    .trim()
    .toLowerCase();

  const difficultyStyles = {
    beginner: "bg-emerald-50 text-emerald-700 border-emerald-200",
    intermediate: "bg-amber-50 text-amber-700 border-amber-200",
    advanced: "bg-orange-50 text-orange-700 border-orange-200",
    expert: "bg-red-50 text-red-700 border-red-200",
  };

  const difficultyStyle =
    difficultyStyles[difficultyKey] ||
    "bg-slate-50 text-slate-600 border-slate-200";

  return (
    <button
      type="button"
      onClick={onSelect}
      dir="rtl"
      aria-label={`عرض تفاصيل تمرين ${name}`}
      className="
        group relative flex h-full w-full flex-col
        rounded-2xl border border-slate-200
        bg-white p-4 text-right
        shadow-[0_2px_8px_rgba(15,23,42,0.03)]
        transition-all duration-200
        hover:-translate-y-0.5
        hover:border-[#407437]/40
        hover:shadow-[0_10px_28px_rgba(15,23,42,0.08)]
        focus-visible:outline-none
        focus-visible:ring-2
        focus-visible:ring-[#407437]
        focus-visible:ring-offset-2
        active:scale-[0.99]
      "
    >
      {/* Header */}
      <div className="flex w-full items-start justify-between gap-3">
        <div
          className="
            flex h-10 w-10 shrink-0 items-center
            justify-center rounded-xl bg-[#407437]/10
            text-[#407437]
          "
        >
          <Dumbbell size={19} strokeWidth={1.8} />
        </div>

        <span
          className={`
            inline-flex max-w-[55%] shrink-0 items-center
            rounded-full border px-2.5 py-1
            text-[11px] font-medium
            ${difficultyStyle}
          `}
        >
          {difficulty}
        </span>
      </div>

      {/* Exercise name */}
      <div className="mt-4 min-w-0">
        <h3
          className="
            min-h-[42px] text-sm font-bold
            leading-6 text-slate-800
            line-clamp-2
            transition-colors
            group-hover:text-[#407437]
          "
          title={name}
        >
          {name}
        </h3>

        <p className="mt-1 text-xs text-slate-500">
          {muscleGroup}
        </p>
      </div>

      {/* Tags */}
      <div className="mt-4 flex flex-wrap gap-2">
        <span
          className="
            rounded-lg bg-[#407437]/10
            px-2.5 py-1 text-[11px]
            font-medium text-[#407437]
          "
        >
          {category}
        </span>

        <span
          className="
            rounded-lg bg-slate-100
            px-2.5 py-1 text-[11px]
            font-medium text-slate-600
          "
        >
          {equipment}
        </span>
      </div>

      {/* Footer */}
      <div
        className="
          mt-auto flex items-center justify-between
          gap-2 border-t border-slate-100 pt-3
        "
        style={{ marginTop: "auto" }}
      >
        <span
          className="
            text-xs font-semibold text-[#407437]
          "
        >
          عرض تفاصيل التمرين
        </span>

        <span
          className="
            flex h-8 w-8 items-center justify-center
            rounded-full bg-[#407437]/10
            text-[#407437]
            transition-colors
            group-hover:bg-[#407437]
            group-hover:text-white
          "
        >
          <ArrowUpLeft size={16} />
        </span>
      </div>
    </button>
  );
};

export default ExerciseCard;
