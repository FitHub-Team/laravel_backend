import {
    X,
    Clock3,
    Users,
    Flame,
    Beef,
    Wheat,
    Droplets,
    Utensils,
    Pencil,
    Trash2,
    Plus,
} from "lucide-react";

const mealLabels = {
    breakfast: "إفطار",
    lunch: "غداء",
    dinner: "عشاء",
    snack: "سناك",
};

const RecipeDetailsModal = ({
    open,
    recipe,
    onClose,
    onDelete,
    onEdit,
    onAddToPlan,
    deleting = false,
}) => {
    if (!open || !recipe) {
        return null;
    }

    return (
        <div
            dir="rtl"
            className="
                fixed
                inset-0
                z-50
                bg-black/40
                backdrop-blur-[2px]
                flex
                items-center
                justify-center
                p-4
            "
        >
            <div
                className="
                    bg-white
                    w-full
                    max-w-4xl
                    max-h-[92vh]
                    overflow-y-auto
                    rounded-3xl
                    shadow-2xl
                "
            >
                {/* =========================
                    Header
                ========================== */}

                <div
                    className="
                        sticky
                        top-0
                        z-20
                        bg-white/95
                        backdrop-blur
                        border-b
                        border-gray-100
                        px-5
                        md:px-7
                        py-4
                        flex
                        items-center
                        justify-between
                    "
                >
                    <div>
                        <span
                            className="
                                inline-flex
                                text-[11px]
                                font-semibold
                                bg-green-50
                                text-[#407437]
                                px-3
                                py-1
                                rounded-full
                            "
                        >
                            {mealLabels[recipe.meal_type] ??
                                recipe.meal_type}
                        </span>

                        <h2 className="text-xl md:text-2xl font-bold text-gray-900 mt-2">
                            {recipe.name}
                        </h2>
                    </div>

                    <button
                        type="button"
                        onClick={onClose}
                        className="
                            w-10
                            h-10
                            rounded-xl
                            bg-gray-50
                            text-gray-500
                            hover:bg-gray-100
                            flex
                            items-center
                            justify-center
                            transition-colors
                        "
                    >
                        <X size={19} />
                    </button>
                </div>

                {/* =========================
                    Content
                ========================== */}

                <div className="p-5 md:p-7 space-y-7">
                    {/* =========================
                        Image
                    ========================== */}

                    <section>
                        <div
                            className="
                                w-full
                                h-56
                                md:h-72
                                rounded-2xl
                                overflow-hidden
                                bg-gradient-to-br
                                from-green-50
                                to-emerald-100
                                flex
                                items-center
                                justify-center
                            "
                        >
                            {recipe.image_url ? (
                                <img
                                    src={recipe.image_url}
                                    alt={recipe.name}
                                    className="w-full h-full object-cover"
                                />
                            ) : (
                                <Utensils
                                    size={52}
                                    className="text-[#407437]"
                                />
                            )}
                        </div>
                    </section>

                    {/* =========================
                        Description
                    ========================== */}

                    <section>
                        <SectionTitle>
                            وصف الوصفة
                        </SectionTitle>

                        <p className="text-sm text-gray-500 leading-7">
                            {recipe.description ||
                                "لا يوجد وصف لهذه الوصفة."}
                        </p>
                    </section>

                    {/* =========================
                        Nutrition
                    ========================== */}

                    <section>
                        <SectionTitle>
                            القيم الغذائية
                        </SectionTitle>

                        <div className="grid grid-cols-2 md:grid-cols-4 gap-3">
                            <NutritionBox
                                icon={Flame}
                                label="السعرات"
                                value={`${recipe.calories ?? 0} kcal`}
                            />

                            <NutritionBox
                                icon={Beef}
                                label="البروتين"
                                value={`${Number(
                                    recipe.protein ?? 0,
                                )} g`}
                            />

                            <NutritionBox
                                icon={Wheat}
                                label="الكربوهيدرات"
                                value={`${Number(
                                    recipe.carbs ?? 0,
                                )} g`}
                            />

                            <NutritionBox
                                icon={Droplets}
                                label="الدهون"
                                value={`${Number(
                                    recipe.fat ?? 0,
                                )} g`}
                            />
                        </div>
                    </section>

                    {/* =========================
                        Info
                    ========================== */}

                    <section>
                        <SectionTitle>
                            معلومات الوصفة
                        </SectionTitle>

                        <div className="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <MetaBox
                                icon={Clock3}
                                label="وقت التحضير"
                                value={
                                    recipe.prep_time
                                        ? `${recipe.prep_time} دقيقة`
                                        : "غير محدد"
                                }
                            />

                            <MetaBox
                                icon={Users}
                                label="عدد الحصص"
                                value={`${recipe.servings ?? 1} حصة`}
                            />

                            <MetaBox
                                icon={Utensils}
                                label="الخصوصية"
                                value={
                                    recipe.is_public
                                        ? "وصفة عامة"
                                        : "وصفة خاصة"
                                }
                            />
                        </div>
                    </section>

                    {/* =========================
                        Ingredients
                    ========================== */}

                    <section>
                        <div className="flex items-center justify-between mb-4">
                            <SectionTitle noMargin>
                                المكونات
                            </SectionTitle>

                            <span className="text-xs text-gray-400">
                                {recipe.ingredients?.length ?? 0} مكون
                            </span>
                        </div>

                        {Array.isArray(recipe.ingredients) &&
                        recipe.ingredients.length > 0 ? (
                            <div
                                className="
                                    border
                                    border-gray-100
                                    rounded-2xl
                                    overflow-hidden
                                    bg-white
                                "
                            >
                                {recipe.ingredients.map(
                                    (ingredient, index) => (
                                        <div
                                            key={`${ingredient.name}-${index}`}
                                            className="
                                                flex
                                                items-center
                                                justify-between
                                                gap-4
                                                px-4
                                                py-3.5
                                                border-b
                                                border-gray-100
                                                last:border-b-0
                                                hover:bg-gray-50/60
                                                transition-colors
                                            "
                                        >
                                            <div className="flex items-center gap-3">
                                                <span
                                                    className="
                                                        w-8
                                                        h-8
                                                        rounded-full
                                                        bg-green-50
                                                        text-[#407437]
                                                        flex
                                                        items-center
                                                        justify-center
                                                        text-xs
                                                        font-bold
                                                        shrink-0
                                                    "
                                                >
                                                    {index + 1}
                                                </span>

                                                <span className="text-sm font-medium text-gray-700">
                                                    {ingredient.name}
                                                </span>
                                            </div>

                                            <span className="text-xs font-semibold text-gray-500">
                                                {ingredient.quantity ?? ""}

                                                {ingredient.quantity &&
                                                ingredient.unit
                                                    ? " "
                                                    : ""}

                                                {ingredient.unit ?? ""}
                                            </span>
                                        </div>
                                    ),
                                )}
                            </div>
                        ) : (
                            <EmptyText>
                                لا توجد مكونات مسجلة.
                            </EmptyText>
                        )}
                    </section>

                    {/* =========================
                        Instructions
                    ========================== */}

                    <section>
                        <SectionTitle>
                            طريقة التحضير
                        </SectionTitle>

                        {Array.isArray(recipe.instructions) &&
                        recipe.instructions.length > 0 ? (
                            <div className="space-y-3">
                                {recipe.instructions.map(
                                    (instruction, index) => (
                                        <div
                                            key={index}
                                            className="
                                                flex
                                                items-start
                                                gap-3
                                                bg-gray-50
                                                rounded-2xl
                                                p-4
                                            "
                                        >
                                            <span
                                                className="
                                                    w-8
                                                    h-8
                                                    shrink-0
                                                    rounded-full
                                                    bg-[#407437]
                                                    text-white
                                                    flex
                                                    items-center
                                                    justify-center
                                                    text-xs
                                                    font-bold
                                                "
                                            >
                                                {index + 1}
                                            </span>

                                            <p className="text-sm text-gray-600 leading-7 pt-0.5">
                                                {instruction}
                                            </p>
                                        </div>
                                    ),
                                )}
                            </div>
                        ) : (
                            <EmptyText>
                                لا توجد خطوات تحضير مسجلة.
                            </EmptyText>
                        )}
                    </section>

                    {/* =========================
                        Actions
                    ========================== */}

                    <div
                        className="
                            flex
                            flex-col
                            md:flex-row
                            md:items-center
                            md:justify-between
                            gap-3
                            pt-6
                            border-t
                            border-gray-100
                        "
                    >
                        <div className="flex flex-col sm:flex-row gap-2">
                            <button
                                type="button"
                                onClick={() => onEdit?.(recipe)}
                                className="
                                    flex
                                    items-center
                                    justify-center
                                    gap-2
                                    px-4
                                    py-2.5
                                    rounded-xl
                                    border
                                    border-gray-200
                                    text-sm
                                    font-semibold
                                    text-gray-700
                                    hover:bg-gray-50
                                    transition-colors
                                "
                            >
                                <Pencil size={15} />

                                تعديل الوصفة
                            </button>

                            <button
                                type="button"
                                disabled={deleting}
                                onClick={() =>
                                    onDelete?.(recipe.id)
                                }
                                className="
                                    flex
                                    items-center
                                    justify-center
                                    gap-2
                                    px-4
                                    py-2.5
                                    rounded-xl
                                    bg-red-50
                                    text-red-600
                                    text-sm
                                    font-semibold
                                    hover:bg-red-100
                                    disabled:opacity-50
                                    disabled:cursor-not-allowed
                                    transition-colors
                                "
                            >
                                <Trash2 size={15} />

                                {deleting
                                    ? "جاري الحذف..."
                                    : "حذف"}
                            </button>
                        </div>

                        <button
                            type="button"
                            onClick={() =>
                                onAddToPlan?.(recipe)
                            }
                            className="
                                flex
                                items-center
                                justify-center
                                gap-2
                                px-5
                                py-2.5
                                rounded-xl
                                bg-[#407437]
                                text-white
                                text-sm
                                font-semibold
                                hover:bg-[#35652f]
                                transition-colors
                            "
                        >
                            <Plus size={16} />

                            إضافة إلى خطة التغذية
                        </button>
                    </div>
                </div>
            </div>
        </div>
    );
};

/* =========================
   Section Title
========================== */

const SectionTitle = ({
    children,
    noMargin = false,
}) => {
    return (
        <h3
            className={`font-bold text-sm text-gray-800 ${
                noMargin ? "" : "mb-4"
            }`}
        >
            {children}
        </h3>
    );
};

/* =========================
   Nutrition Box
========================== */

const NutritionBox = ({
    icon: Icon,
    label,
    value,
}) => {
    return (
        <div
            className="
                border
                border-gray-100
                bg-gray-50/60
                rounded-2xl
                p-4
            "
        >
            <div
                className="
                    w-10
                    h-10
                    rounded-xl
                    bg-white
                    text-[#407437]
                    flex
                    items-center
                    justify-center
                    mb-3
                    shadow-sm
                "
            >
                <Icon size={18} />
            </div>

            <p className="text-xs text-gray-400">
                {label}
            </p>

            <p className="font-bold text-gray-800 mt-1">
                {value}
            </p>
        </div>
    );
};

/* =========================
   Meta Box
========================== */

const MetaBox = ({
    icon: Icon,
    label,
    value,
}) => {
    return (
        <div
            className="
                flex
                items-center
                gap-3
                border
                border-gray-100
                rounded-2xl
                p-4
                bg-white
            "
        >
            <div
                className="
                    w-10
                    h-10
                    rounded-xl
                    bg-green-50
                    text-[#407437]
                    flex
                    items-center
                    justify-center
                    shrink-0
                "
            >
                <Icon size={17} />
            </div>

            <div>
                <p className="text-[11px] text-gray-400">
                    {label}
                </p>

                <p className="text-sm font-semibold text-gray-700 mt-1">
                    {value}
                </p>
            </div>
        </div>
    );
};

/* =========================
   Empty Text
========================== */

const EmptyText = ({ children }) => {
    return (
        <div className="bg-gray-50 rounded-2xl px-4 py-5">
            <p className="text-sm text-gray-400">
                {children}
            </p>
        </div>
    );
};

export default RecipeDetailsModal;