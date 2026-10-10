import { Clock3, Trash2, Users, Utensils } from "lucide-react";

const mealLabels = {
    breakfast: "إفطار",
    lunch: "غداء",
    dinner: "عشاء",
    snack: "سناك",
};

const RecipeCard = ({ recipe, onDelete, onView, deleting = false }) => {
    return (
        <div
            className="
                bg-white
                rounded-2xl
                border
                border-gray-100
                shadow-sm
                overflow-hidden
                hover:shadow-md
                transition-all
                h-full
                flex
                flex-col
            "
        >
            {/* =========================
                Recipe Image
            ========================== */}

            <div
                className="
                    w-full
                    aspect-[4/3]
                    bg-gradient-to-br
                    from-green-50
                    to-emerald-100
                    flex
                    items-center
                    justify-center
                    overflow-hidden
                "
            >
                {recipe.image ? (
                    <img
                        src={recipe.image}
                        alt={recipe.name}
                        className="w-full h-full object-cover"
                    />
                ) : (
                    <div
                        className="
    w-full
    aspect-[4/3]
    bg-gradient-to-br
    from-green-50
    to-emerald-100
    flex
    items-center
    justify-center
    overflow-hidden
  ">
                        {recipe.image_url ? (
                            <img
                                src={recipe.image_url}
                                alt={recipe.name}
                                className="w-full h-full object-cover"
                            />
                        ) : (
                            <Utensils size={42} className="text-[#407437]" />
                        )}
                    </div>
                )}
            </div>

            {/* =========================
                Content
            ========================== */}

            <div className="p-5 flex flex-col flex-1">
                {/* Type */}

                <div className="flex items-center justify-between gap-2">
                    <span className="text-[11px] font-semibold bg-green-50 text-[#407437] px-2.5 py-1 rounded-full">
                        {mealLabels[recipe.meal_type] ?? recipe.meal_type}
                    </span>

                    {recipe.is_public && (
                        <span className="text-[11px] bg-blue-50 text-blue-600 px-2.5 py-1 rounded-full">
                            عامة
                        </span>
                    )}
                </div>

                {/* Name */}

                <h3 className="font-bold text-gray-900 text-base mt-4">
                    {recipe.name}
                </h3>

                {/* Description */}

                <p className="text-xs text-gray-400 leading-5 mt-2 line-clamp-2 min-h-[40px]">
                    {recipe.description || "لا يوجد وصف لهذه الوصفة"}
                </p>

                {/* =========================
                    Nutrition
                ========================== */}

                <div className="grid grid-cols-4 gap-2 mt-5">
                    <NutritionValue
                        label="سعرات"
                        value={recipe.calories ?? 0}
                    />

                    <NutritionValue
                        label="بروتين"
                        value={`${Number(recipe.protein ?? 0)}g`}
                    />

                    <NutritionValue
                        label="كارب"
                        value={`${Number(recipe.carbs ?? 0)}g`}
                    />

                    <NutritionValue
                        label="دهون"
                        value={`${Number(recipe.fat ?? 0)}g`}
                    />
                </div>

                {/* =========================
                    Meta
                ========================== */}

                <div className="flex flex-wrap items-center gap-4 mt-5 text-xs text-gray-400">
                    <div className="flex items-center gap-1.5">
                        <Clock3 size={14} />

                        <span>
                            {recipe.prep_time
                                ? `${recipe.prep_time} دقيقة`
                                : "غير محدد"}
                        </span>
                    </div>

                    <div className="flex items-center gap-1.5">
                        <Users size={14} />

                        <span>{recipe.servings ?? 1} حصة</span>
                    </div>
                </div>

                {/* =========================
                    Footer
                ========================== */}

                <div className="flex items-center justify-between gap-3 pt-4 mt-auto border-t border-gray-100">
                    <button
                        type="button"
                        onClick={() => onView?.(recipe)}
                        className="
                            px-4
                            h-9
                            rounded-lg
                            bg-green-50
                            text-[#407437]
                            hover:bg-green-100
                            text-xs
                            font-semibold
                            transition-colors
                        "
                    >
                        عرض التفاصيل
                    </button>

                    <button
                        type="button"
                        disabled={deleting}
                        onClick={() => onDelete?.(recipe.id)}
                        className="
                            w-9
                            h-9
                            rounded-lg
                            bg-red-50
                            text-red-500
                            hover:bg-red-100
                            flex
                            items-center
                            justify-center
                            disabled:opacity-50
                            transition-colors
                        "
                    >
                        <Trash2 size={15} />
                    </button>
                </div>
            </div>
        </div>
    );
};

const NutritionValue = ({ label, value }) => {
    return (
        <div className="bg-gray-50 rounded-xl py-2.5 px-1 text-center">
            <p className="text-xs font-bold text-gray-800">{value}</p>

            <p className="text-[10px] text-gray-400 mt-1">{label}</p>
        </div>
    );
};

export default RecipeCard;
