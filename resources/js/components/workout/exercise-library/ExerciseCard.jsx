import {  Plus } from "lucide-react";

import {
  translateDifficulty,
  translateEquipment,
  translateMuscleGroup,
  translateCategory,
} from "../../../utils/exerciseTranslations";

const ExerciseCard = ({ exercise, onSelect }) => {
  // ألوان دلالية لمستوى الصعوبة (مختلفة عن هوية البطاقة الخضراء)
  const difficultyStyles = {
    beginner:     "bg-blue-50 text-blue-700 ring-blue-200",       // أزرق - سهل
    intermediate: "bg-amber-50 text-amber-700 ring-amber-200",    // عنبري - متوسط
    advanced:     "bg-orange-50 text-orange-700 ring-orange-200", // برتقالي - متقدم
    expert:       "bg-red-50 text-red-700 ring-red-200",          // أحمر - خبير
  };

  const difficultyStyle =
    difficultyStyles[exercise.difficulty_level] ||
    "bg-slate-50 text-slate-700 ring-slate-200";

  return (
    <button
      type="button"
      onClick={onSelect}
      className="
        group
        relative
        w-full
        text-right
        rounded-2xl
        bg-white
        border
        border-green-100
        hover:border-green-300
        hover:shadow-[0_12px_28px_-14px_rgba(21,128,61,0.4)]
        hover:-translate-y-0.5
        transition-all
        duration-300
        p-4
      "
    >
      <div className="flex items-start gap-3">

    

        {/* Content */}
        <div className="flex-1 min-w-0">

          {/* Name + Difficulty */}
          <div className="flex items-center justify-between gap-2">

            <h3
              className="
                text-sm
                font-bold
                text-green-900
                truncate
                group-hover:text-green-700
                transition-colors
              "
            >
              {exercise.name}
            </h3>

            {/* شارة الصعوبة - ألوان دلالية مختلفة */}
            <span
              className={`
                flex-shrink-0
                text-[10px]
                font-semibold
                px-2
                py-0.5
                rounded-full
                ring-1
                ring-inset
                ${difficultyStyle}
              `}
            >
              {translateDifficulty(exercise.difficulty_level)}
            </span>

          </div>

          {/* Muscle group */}
          <p className="text-[11px] text-[#45556C] truncate mt-0.5">
            {translateMuscleGroup(exercise.muscle_group)}
          </p>

          {/* Tags */}
          <div className="flex items-center gap-1.5 mt-2.5 flex-wrap">

            <span
              className="
                text-[10px]
                font-medium
                px-2
                py-0.5
                rounded-md
                bg-green-100
                text-green-800
              "
            >
              {translateCategory(exercise.category)}
            </span>

            <span
              className="
                text-[10px]
                font-medium
                px-2
                py-0.5
                rounded-md
                bg-green-50
                text-green-700
              "
            >
              {translateEquipment(exercise.equipment)}
            </span>

          </div>
        </div>

        {/* Add icon */}
        <div
          className="
            flex-shrink-0
            w-7
            h-7
            rounded-full
            bg-green-100
            flex
            items-center
            justify-center
            opacity-0
            -translate-x-1
            group-hover:opacity-100
            group-hover:translate-x-0
            transition-all
            duration-300
          "
        >
          <Plus
            size={14}
            className="text-green-800"
            strokeWidth={2.5}
          />
        </div>

      </div>
    </button>
  );
};

export default ExerciseCard;