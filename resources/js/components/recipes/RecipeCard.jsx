
import { useEffect, useState } from "react";
import {
  ArrowUpLeft,
  Clock3,
  Trash2,
  Users,
  Utensils,
} from "lucide-react";

const mealLabels = {
  breakfast: "الإفطار",
  lunch: "الغداء",
  dinner: "العشاء",
  snack: "وجبة خفيفة",
};

const formatNumber = (value) => {
  const number = Number(value);
  if (!Number.isFinite(number)) return "0";
  return Number(number.toFixed(1)).toString();
};

function NutritionItem({ value, label, unit = "" }) {
  return (
    <div className="flex min-w-0 flex-col items-center gap-1 text-center">
      <div className="flex items-baseline gap-0.5" dir="ltr">
        <span className="text-sm font-bold text-[#213C2A]">
          {formatNumber(value)}
        </span>
        {unit && (
          <span className="text-[10px] font-medium text-[#718273]">
            {unit}
          </span>
        )}
      </div>
      <span className="text-[11px] text-[#859187]">{label}</span>
    </div>
  );
}

export default function RecipeCard({
  recipe,
  onDelete,
  onView,
  deleting = false,
}) {
  const [imageError, setImageError] = useState(false);

  const imageUrl = recipe?.image_url || "";

  useEffect(() => {
    setImageError(false);
  }, [imageUrl]);

  const hasImage = Boolean(imageUrl) && !imageError;

  return (
    <article
      dir="rtl"
      className="
        group flex h-full min-w-0 flex-col
        overflow-hidden rounded-[22px]
        border border-[#E6ECE6] bg-white
        shadow-[0_3px_16px_rgba(31,65,38,0.035)]
        transition-all duration-300
        hover:-translate-y-1
        hover:border-[#CFDECE]
        hover:shadow-[0_14px_35px_rgba(31,65,38,0.09)]
      "
    >
      {/* Image */}
      <div className="relative aspect-[16/10] w-full overflow-hidden bg-[#EDF3EC]">
        {hasImage ? (
          <img
            src={imageUrl}
            alt={recipe.name}
            loading="lazy"
            onError={() => setImageError(true)}
            className="
              h-full w-full object-cover
              transition-transform duration-500
              group-hover:scale-[1.04]
            "
          />
        ) : (
          <div className="flex h-full w-full flex-col items-center justify-center gap-3 bg-gradient-to-br from-[#EFF6EC] via-[#F5F8F2] to-[#E4EEE0]">
            <div className="flex h-16 w-16 items-center justify-center rounded-2xl border border-white/80 bg-white/70 text-[#6D9B65] shadow-sm">
              <Utensils size={29} strokeWidth={1.6} />
            </div>
            <span className="text-xs font-medium text-[#8BA488]">
              صورة الوصفة
            </span>
          </div>
        )}

        {/* Gradient for legibility */}
        {hasImage && (
          <div className="pointer-events-none absolute inset-x-0 top-0 h-24 bg-gradient-to-b from-black/25 to-transparent" />
        )}

        {/* Meal Type */}
        <div className="absolute right-4 top-4">
          <span className="inline-flex items-center rounded-full border border-white/70 bg-white/95 px-3 py-1.5 text-[11px] font-semibold text-[#326A43] shadow-sm backdrop-blur-sm">
            {mealLabels[recipe.meal_type] ||
              recipe.meal_type ||
              "وصفة"}
          </span>
        </div>

        {/* Public badge */}
        {Boolean(recipe.is_public) && (
          <div className="absolute left-4 top-4">
            <span className="rounded-full border border-white/70 bg-white/95 px-3 py-1.5 text-[11px] font-medium text-[#486C51] shadow-sm">
              عامة
            </span>
          </div>
        )}
      </div>

      {/* Content */}
      <div className="flex flex-1 flex-col p-5">
        <div className="mb-3 flex items-center gap-4 text-xs text-[#7D8B7F]">
          <span className="flex items-center gap-1.5">
            <Clock3 size={14} strokeWidth={1.8} />
            {recipe.prep_time
              ? `${recipe.prep_time} دقيقة`
              : "وقت غير محدد"}
          </span>

          <span className="h-1 w-1 rounded-full bg-[#CDD8CC]" />

          <span className="flex items-center gap-1.5">
            <Users size={14} strokeWidth={1.8} />
            {recipe.servings ?? 1} حصة
          </span>
        </div>

        <h3 className="line-clamp-1 text-[17px] font-bold leading-7 text-[#203B28]">
          {recipe.name}
        </h3>

        <p className="mt-1.5 min-h-[42px] line-clamp-2 text-[12px] leading-[21px] text-[#839084]">
          {recipe.description || "لا يوجد وصف متاح لهذه الوصفة."}
        </p>

        {/* Macros */}
        <div className="mt-5 grid grid-cols-4 gap-1 rounded-2xl border border-[#EDF1EB] bg-[#F7F9F5] px-2 py-3.5">
          <NutritionItem
            value={recipe.calories}
            label="سعرة"
          />
          <NutritionItem
            value={recipe.protein}
            label="بروتين"
            unit="g"
          />
          <NutritionItem
            value={recipe.carbs}
            label="كارب"
            unit="g"
          />
          <NutritionItem
            value={recipe.fat}
            label="دهون"
            unit="g"
          />
        </div>

        {/* Actions */}
        <div className="mt-auto flex items-center gap-2 pt-5">
          <button
            type="button"
            onClick={() => onView?.(recipe)}
            className="
              flex h-11 flex-1 items-center justify-center gap-2
              rounded-xl bg-[#326F48] px-4
              text-[13px] font-semibold text-white
              transition-colors hover:bg-[#275B39]
              focus-visible:outline focus-visible:outline-2
              focus-visible:outline-offset-2 focus-visible:outline-[#326F48]
            "
          >
            عرض التفاصيل
            <ArrowUpLeft size={16} strokeWidth={1.8} />
          </button>

          <button
            type="button"
            onClick={() => onDelete?.(recipe.id)}
            disabled={deleting}
            aria-label={`حذف الوصفة ${recipe.name}`}
            title="حذف الوصفة"
            className="
              flex h-11 w-11 shrink-0 items-center justify-center
              rounded-xl border border-[#F1DEDE] bg-[#FFF8F8]
              text-[#CD6767] transition-colors
              hover:border-[#F1CACA] hover:bg-[#FDEEEE]
              focus-visible:outline focus-visible:outline-2
              focus-visible:outline-offset-2 focus-visible:outline-red-400
              disabled:cursor-not-allowed disabled:opacity-50
            "
          >
            <Trash2 size={17} strokeWidth={1.8} />
          </button>
        </div>
      </div>
    </article>
  );
}
