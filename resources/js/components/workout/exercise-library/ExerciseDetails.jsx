import {
  Dumbbell,
  Plus,
  FileText,
  Info,
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

const ExerciseDetails = ({ exercise, onAdd, onBack }) => {
  // ألوان دلالية لمستوى الصعوبة
  const difficultyStyles = {
    beginner:     "bg-blue-50 text-blue-700 ring-blue-200",
    intermediate: "bg-amber-50 text-amber-700 ring-amber-200",
    advanced:     "bg-orange-50 text-orange-700 ring-orange-200",
    expert:       "bg-red-50 text-red-700 ring-red-200",
  };

  const difficultyStyle =
    difficultyStyles[exercise.difficulty_level] ||
    "bg-slate-50 text-slate-600 ring-slate-200";

  return (
    <div className="flex-1 flex flex-col min-h-0 overflow-hidden">

      {/* Content */}
      <div className="flex-1 overflow-y-auto modal-scroll">
        <div className="p-6 space-y-5">

          {/* ===== Header ===== */}
          <div className="flex items-center gap-4">

            {/* الأيقونة الرئيسية - فُضّل تفضل خضراء لأنها هوية */}
            <div className="flex-shrink-0 w-14 h-14 rounded-2xl bg-green-50 flex items-center justify-center">
              <Dumbbell
                size={26}
                className="text-green-700"
                strokeWidth={1.8}
              />
            </div>

            <div className="flex-1 min-w-0">
              <h3 className="text-xl font-bold text-slate-800 leading-tight truncate">
                {exercise.name}
              </h3>

              <p className="text-sm text-slate-400 mt-0.5 truncate">
                {translateMuscleGroup(exercise.muscle_group)}
              </p>
            </div>

            {/* شارة الصعوبة - ألوان دلالية */}
            <span
              className={`
                flex-shrink-0
                text-[11px]
                font-semibold
                px-3
                py-1
                rounded-full
                ring-1
                ring-inset
                ${difficultyStyle}
              `}
            >
              {translateDifficulty(exercise.difficulty_level)}
            </span>
          </div>

          {/* ===== Description ===== */}
          {exercise.description && (
            <div className="rounded-2xl bg-slate-50 border border-slate-100 p-4">

              <div className="flex items-center gap-2 mb-2">
                <FileText
                  size={13}
                  className="text-slate-400"
                />

                <h4 className="text-xs font-bold text-slate-700">
                  وصف التمرين
                </h4>
              </div>

              <p className="text-[13px] text-slate-600 leading-6">
                {exercise.description}
              </p>
            </div>
          )}

          {/* ===== Info Section ===== */}
          <div>

            <div className="flex items-center gap-2 mb-3">

              <div className="w-6 h-6 rounded-lg bg-slate-100 flex items-center justify-center">
                <Info
                  size={13}
                  className="text-slate-500"
                />
              </div>

              <h4 className="text-sm font-bold text-slate-700">
                معلومات التمرين
              </h4>
            </div>

            <div className="grid grid-cols-1 sm:grid-cols-2 gap-2.5">

              <InfoRow
                icon={Target}
                label="العضلات المستهدفة"
                value={translateMuscleGroup(exercise.muscle_group)}
              />

              <InfoRow
                icon={TrendingUp}
                label="مستوى الصعوبة"
                value={translateDifficulty(exercise.difficulty_level)}
                badgeStyle={difficultyStyle}
              />

              <InfoRow
                icon={Dumbbell}
                label="المعدات"
                value={translateEquipment(exercise.equipment)}
              />

              <InfoRow
                icon={Layers}
                label="التصنيف"
                value={translateCategory(exercise.category)}
              />

              <InfoRow
                icon={Activity}
                label="نوع الحركة"
                value={translateMechanics(exercise.mechanics)}
              />

              <InfoRow
                icon={Zap}
                label="نوع القوة"
                value={translateForceType(exercise.force_type)}
              />

            </div>
          </div>
        </div>
      </div>

      {/* ===== Footer ===== */}
      <div className="flex items-center gap-3 px-6 py-4 border-t border-slate-100 bg-white">

        {/* الزر الرئيسي - يفضل أخضر لأنه CTA */}
        <button
          type="button"
          onClick={() => onAdd(exercise)}
          className="
            group
            flex-1
            h-[46px]
            rounded-full
            bg-green-700
            text-white
            text-sm
            font-bold
            hover:bg-green-800
            transition-all
            flex
            items-center
            justify-center
            gap-2
            shadow-[0_8px_20px_-8px_rgba(21,128,61,0.45)]
          "
        >
          <Plus
            size={17}
            className="transition-transform group-hover:rotate-90 duration-300"
          />

          إضافة إلى الخطة
        </button>

        {/* زر الرجوع - رمادي محايد */}
        <button
          type="button"
          onClick={onBack}
          className="
            px-6
            h-[46px]
            rounded-full
            border
            border-slate-200
            bg-white
            text-slate-600
            text-sm
            font-semibold
            hover:bg-slate-50
            hover:text-slate-800
            hover:border-slate-300
            transition
          "
        >
          رجوع
        </button>

      </div>
    </div>
  );
};

/* ===== InfoRow ===== */

const InfoRow = ({ icon: Icon, label, value, badgeStyle }) => {
  return (
    <div
      className="
        flex
        items-center
        gap-3
        p-3
        rounded-xl
        bg-white
        border
        border-slate-100
        hover:border-slate-200
        hover:bg-slate-50/60
        transition-all
      "
    >

      <div
        className="
          w-9
          h-9
          rounded-lg
          flex
          items-center
          justify-center
          flex-shrink-0
          bg-slate-50
          text-slate-500
        "
      >
        <Icon size={16} />
      </div>

      <div className="flex-1 min-w-0">

        <p className="text-[11px] text-slate-400">
          {label}
        </p>

        {/* شارة الصعوبة بتظهر بألوانها الدلالية */}
        {badgeStyle ? (
          <span
            className={`
              inline-block
              mt-0.5
              text-[11px]
              font-semibold
              px-2
              py-0.5
              rounded-full
              ring-1
              ring-inset
              ${badgeStyle}
            `}
          >
            {value || "غير محدد"}
          </span>
        ) : (
          <p className="text-sm font-bold text-slate-800 truncate">
            {value || "غير محدد"}
          </p>
        )}

      </div>
    </div>
  );
};

export default ExerciseDetails;