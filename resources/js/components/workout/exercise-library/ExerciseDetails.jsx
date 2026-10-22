
import {
  ArrowRight,
  Dumbbell,
  Plus,
  FileText,
  Target,
  TrendingUp,
  Layers,
  Activity,
  Zap,
} from "lucide-react";

import {
  translateDifficulty,
  translateEquipment,
  translateMuscleGroup,
  translateCategory,
  translateMechanics,
  translateForceType,
} from "../../../utils/exerciseTranslations";

// Arabic value first, English fallback
const localizedValue = (arabic, english, translateFn) => {
  const ar = typeof arabic === "string" ? arabic.trim() : "";

  if (ar) return ar;

  const en =
    typeof english === "string" ? english.trim() : "";

  if (!en) return "غير محدد";

  return translateFn?.(en) || en;
};

const ExerciseDetails = ({ exercise, onAdd, onBack }) => {
  if (!exercise) return null;

  const name =
    exercise.name_ar?.trim() ||
    exercise.name ||
    "تمرين بدون اسم";

  const description =
    exercise.description_ar?.trim() ||
    exercise.description?.trim() ||
    "";

  const muscleGroup = localizedValue(
    exercise.muscle_group_ar,
    exercise.muscle_group,
    translateMuscleGroup
  );

  const equipment = localizedValue(
    exercise.equipment_ar,
    exercise.equipment,
    translateEquipment
  );

  const category = localizedValue(
    exercise.type_ar,
    exercise.type ?? exercise.category,
    translateCategory
  );

  const difficulty = localizedValue(
    exercise.difficulty_level_ar,
    exercise.difficulty_level,
    translateDifficulty
  );

  const difficultyKey = String(
    exercise.difficulty_level || ""
  )
    .trim()
    .toLowerCase();

  const difficultyStyles = {
    beginner:
      "bg-emerald-50 text-emerald-700 border-emerald-200",
    intermediate:
      "bg-amber-50 text-amber-700 border-amber-200",
    advanced:
      "bg-orange-50 text-orange-700 border-orange-200",
    expert:
      "bg-red-50 text-red-700 border-red-200",
  };

  const difficultyStyle =
    difficultyStyles[difficultyKey] ||
    "bg-slate-100 text-slate-600 border-slate-200";

  const mechanics = exercise.mechanics
    ? translateMechanics(exercise.mechanics)
    : null;

  const forceType = exercise.force_type
    ? translateForceType(exercise.force_type)
    : null;

  return (
    <div
      dir="rtl"
      className="flex min-h-0 flex-1 flex-col overflow-hidden bg-white"
    >
      {/* Scrollable content */}
      <div className="modal-scroll min-h-0 flex-1 overflow-y-auto">
        <div className="space-y-6 p-4 sm:p-6">

          {/* Exercise heading */}
          <div className="flex items-start gap-3 sm:gap-4">
            <div
              className="
                flex h-12 w-12 shrink-0 items-center
                justify-center rounded-2xl
                bg-[#407437]/10 text-[#407437]
                sm:h-14 sm:w-14
              "
            >
              <Dumbbell size={25} strokeWidth={1.8} />
            </div>

            <div className="min-w-0 flex-1">
              <h3 className="text-lg font-bold leading-7 text-slate-800 sm:text-xl">
                {name}
              </h3>

              <p className="mt-1 text-sm text-slate-500">
                {muscleGroup}
              </p>

              {exercise.name_ar && exercise.name && (
                <p
                  dir="ltr"
                  className="mt-1 text-left text-xs text-slate-400"
                >
                  {exercise.name}
                </p>
              )}
            </div>
          </div>

          {/* Summary badges */}
          <div className="flex flex-wrap items-center gap-2">
            <span
              className={`
                inline-flex items-center rounded-full
                border px-3 py-1.5
                text-xs font-semibold
                ${difficultyStyle}
              `}
            >
              {difficulty}
            </span>

            <span
              className="
                rounded-full bg-[#407437]/10
                px-3 py-1.5 text-xs font-medium
                text-[#407437]
              "
            >
              {category}
            </span>
          </div>

          {/* Description */}
          {description && (
            <section
              className="
                rounded-2xl border border-slate-100
                bg-slate-50/70 p-4 sm:p-5
              "
            >
              <div className="mb-3 flex items-center gap-2">
                <FileText
                  size={17}
                  className="text-[#407437]"
                />

                <h4 className="text-sm font-bold text-slate-800">
                  وصف التمرين
                </h4>
              </div>

              <p
                dir={exercise.description_ar?.trim() ? "rtl" : "auto"}
                className="whitespace-pre-line text-sm leading-7 text-slate-600"
              >
                {description}
              </p>
            </section>
          )}

          {/* Information */}
          <section>
            <div className="mb-3 flex items-center gap-2">
              <div
                className="
                  flex h-8 w-8 items-center
                  justify-center rounded-lg
                  bg-[#407437]/10 text-[#407437]
                "
              >
                <Dumbbell size={16} />
              </div>

              <h4 className="text-sm font-bold text-slate-800">
                معلومات التمرين
              </h4>
            </div>

            <div className="grid grid-cols-1 gap-3 sm:grid-cols-2">
              <InfoRow
                icon={Target}
                label="العضلات المستهدفة"
                value={muscleGroup}
              />

              <InfoRow
                icon={TrendingUp}
                label="مستوى الصعوبة"
                value={difficulty}
                badgeStyle={difficultyStyle}
              />

              <InfoRow
                icon={Dumbbell}
                label="المعدات المستخدمة"
                value={equipment}
              />

              <InfoRow
                icon={Layers}
                label="تصنيف التمرين"
                value={category}
              />

              {mechanics && (
                <InfoRow
                  icon={Activity}
                  label="نوع الحركة"
                  value={mechanics}
                />
              )}

              {forceType && (
                <InfoRow
                  icon={Zap}
                  label="نوع القوة"
                  value={forceType}
                />
              )}
            </div>
          </section>
        </div>
      </div>

      {/* Sticky actions */}
      <div
        className="
          shrink-0 border-t border-slate-100
          bg-white px-4 py-4 sm:px-6
        "
      >
        <div className="flex flex-col-reverse gap-3 sm:flex-row">
          <button
            type="button"
            onClick={() => onAdd?.(exercise)}
            className="
              flex h-11 flex-1 items-center
              justify-center gap-2 rounded-xl
              bg-[#407437] px-5 text-sm font-bold
              text-white shadow-sm
              transition-colors hover:bg-[#335e2c]
              focus-visible:outline-none
              focus-visible:ring-2
              focus-visible:ring-[#407437]
              focus-visible:ring-offset-2
            "
          >
            <Plus size={18} />
            إضافة إلى الخطة
          </button>

          <button
            type="button"
            onClick={onBack}
            className="
              flex h-11 items-center justify-center
              gap-2 rounded-xl border border-slate-200
              bg-white px-5 text-sm font-semibold
              text-slate-600 transition-colors
              hover:bg-slate-50 hover:text-slate-900
              focus-visible:outline-none
              focus-visible:ring-2
              focus-visible:ring-slate-300
            "
          >
            <ArrowRight size={17} />
            رجوع للمكتبة
          </button>
        </div>
      </div>
    </div>
  );
};

// Reusable information card
const InfoRow = ({ icon: Icon, label, value, badgeStyle }) => {
  return (
    <div
      className="
        flex min-w-0 items-center gap-3
        rounded-xl border border-slate-200/80
        bg-white p-3.5
      "
    >
      <div
        className="
          flex h-10 w-10 shrink-0 items-center
          justify-center rounded-xl
          bg-slate-50 text-slate-500
        "
      >
        <Icon size={18} strokeWidth={1.8} />
      </div>

      <div className="min-w-0 flex-1">
        <p className="mb-1 text-xs text-slate-500">
          {label}
        </p>

        {badgeStyle ? (
          <span
            className={`
              inline-flex max-w-full rounded-full
              border px-2.5 py-1
              text-xs font-semibold
              ${badgeStyle}
            `}
          >
            {value || "غير محدد"}
          </span>
        ) : (
          <p className="break-words text-sm font-semibold leading-6 text-slate-800">
            {value || "غير محدد"}
          </p>
        )}
      </div>
    </div>
  );
};

export default ExerciseDetails;
