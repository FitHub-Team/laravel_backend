import { useState } from "react";

import {
    Plus,
    Search,
    Utensils,
} from "lucide-react";

import useRecipes from "../../../hooks/useRecipes";

import RecipeCard from "../../../components/recipes/RecipeCard";
import RecipeFormModal from "../../../components/recipes/RecipeFormModal";
import RecipeDetailsModal from "../../../components/recipes/RecipeDetailsModal";

const filters = [
    {
        value: "",
        label: "الكل",
    },
    {
        value: "breakfast",
        label: "الإفطار",
    },
    {
        value: "lunch",
        label: "الغداء",
    },
    {
        value: "dinner",
        label: "العشاء",
    },
    {
        value: "snack",
        label: "السناك",
    },
];

const getRecipeImageUrl = (recipe) => {
    if (!recipe?.image_url && !recipe?.image) {
        return null;
    }

    const image =
        recipe.image_url || recipe.image;

    if (
        image.startsWith("http://") ||
        image.startsWith("https://")
    ) {
        return image;
    }

    if (image.startsWith("/storage/")) {
        return `http://localhost${image}`;
    }

    return `http://localhost/storage/${image.replace(
        /^\/+/,
        "",
    )}`;
};

const RecipeLibrary = () => {
    const [openForm, setOpenForm] =
        useState(false);

    const [
        selectedRecipe,
        setSelectedRecipe,
    ] = useState(null);

    const {
        recipes,

        search,
        setSearch,

        mealType,
        setMealType,

        loading,
        saving,
        deletingId,
        error,

        addRecipe,
        removeRecipe,
    } = useRecipes();

    const handleDelete = async (id) => {
        const confirmed =
            window.confirm(
                "هل أنت متأكد من حذف هذه الوصفة؟",
            );

        if (!confirmed) {
            return;
        }

        await removeRecipe(id);
    };

    return (
        <div
            dir="rtl"
            className="
                min-h-screen
                bg-[#F8FAF8]
                px-5
                md:px-7
                lg:px-10
                py-7
            "
        >
            <div className="max-w-[1500px] mx-auto">
                {/* =========================
                    Header
                ========================== */}

                <div
                    className="
                        flex
                        flex-col
                        md:flex-row
                        md:items-center
                        justify-between
                        gap-5
                        mb-7
                    "
                >
                    <div>
                        <p className="text-sm font-medium text-[#407437] mb-2">
                            التغذية
                        </p>

                        <h1 className="text-2xl font-bold text-gray-900">
                            مكتبة الوصفات
                        </h1>

                        <p className="text-sm text-gray-500 mt-2">
                            أنشئ وصفاتك واحفظها لاستخدامها في خطط التغذية.
                        </p>
                    </div>

                    <button
                        type="button"
                        onClick={() =>
                            setOpenForm(true)
                        }
                        className="
                            flex
                            items-center
                            justify-center
                            gap-2
                            bg-[#407437]
                            hover:bg-green-800
                            text-white
                            px-4
                            py-2.5
                            rounded-xl
                            text-sm
                            font-semibold
                            transition-colors
                        "
                    >
                        <Plus size={17} />

                        إضافة وصفة
                    </button>
                </div>

                {/* =========================
                    Error
                ========================== */}

                {error && (
                    <div
                        className="
                            bg-red-50
                            text-red-600
                            border
                            border-red-100
                            rounded-xl
                            px-4
                            py-3
                            text-sm
                            mb-5
                        "
                    >
                        {error}
                    </div>
                )}

                {/* =========================
                    Search + Filters
                ========================== */}

                <div
                    className="
                        bg-white
                        border
                        border-gray-100
                        rounded-2xl
                        p-4
                        shadow-sm
                        mb-6
                    "
                >
                    <div
                        className="
                            flex
                            flex-col
                            lg:flex-row
                            gap-4
                            lg:items-center
                        "
                    >
                        {/* Search */}

                        <div className="relative flex-1">
                            <Search
                                size={17}
                                className="
                                    absolute
                                    right-3
                                    top-1/2
                                    -translate-y-1/2
                                    text-gray-400
                                "
                            />

                            <input
                                type="text"
                                value={search}
                                onChange={(event) =>
                                    setSearch(
                                        event.target
                                            .value,
                                    )
                                }
                                placeholder="ابحث عن وصفة..."
                                className="
                                    w-full
                                    bg-gray-50
                                    border
                                    border-gray-200
                                    rounded-xl
                                    pr-10
                                    pl-4
                                    py-2.5
                                    text-sm
                                    outline-none
                                    focus:border-green-500
                                "
                            />
                        </div>

                        {/* Filters */}

                        <div className="flex gap-2 overflow-x-auto">
                            {filters.map(
                                (filter) => (
                                    <button
                                        key={
                                            filter.value
                                        }
                                        type="button"
                                        onClick={() =>
                                            setMealType(
                                                filter.value,
                                            )
                                        }
                                        className={`
                                            whitespace-nowrap
                                            px-4
                                            py-2
                                            rounded-xl
                                            text-xs
                                            font-semibold
                                            transition-colors
                                            ${
                                                mealType ===
                                                filter.value
                                                    ? "bg-[#407437] text-white"
                                                    : "bg-gray-50 text-gray-500 hover:bg-gray-100"
                                            }
                                        `}
                                    >
                                        {
                                            filter.label
                                        }
                                    </button>
                                ),
                            )}
                        </div>
                    </div>
                </div>

                {/* =========================
                    Loading / Content
                ========================== */}

                {loading ? (
                    <div className="min-h-[350px] flex items-center justify-center">
                        <p className="text-sm text-gray-400">
                            جاري تحميل الوصفات...
                        </p>
                    </div>
                ) : recipes.length === 0 ? (
                    <div
                        className="
                            bg-white
                            border
                            border-gray-100
                            rounded-2xl
                            min-h-[350px]
                            flex
                            items-center
                            justify-center
                        "
                    >
                        <div className="text-center">
                            <div
                                className="
                                    w-14
                                    h-14
                                    mx-auto
                                    bg-green-50
                                    rounded-2xl
                                    flex
                                    items-center
                                    justify-center
                                    text-[#407437]
                                "
                            >
                                <Utensils
                                    size={24}
                                />
                            </div>

                            <h3 className="font-bold text-gray-800 mt-4">
                                لا توجد وصفات
                            </h3>

                            <p className="text-xs text-gray-400 mt-2">
                                ابدأ بإضافة أول وصفة إلى مكتبتك.
                            </p>

                            <button
                                type="button"
                                onClick={() =>
                                    setOpenForm(true)
                                }
                                className="
                                    mt-4
                                    bg-[#407437]
                                    hover:bg-green-800
                                    text-white
                                    px-4
                                    py-2
                                    rounded-xl
                                    text-xs
                                    font-semibold
                                    transition-colors
                                "
                            >
                                إضافة وصفة
                            </button>
                        </div>
                    </div>
                ) : (
                    <>
                        {/* Recipes Header */}

                        <div className="flex items-center justify-between mb-4">
                            <p className="text-sm font-semibold text-gray-700">
                                الوصفات
                            </p>

                            <span className="text-xs text-gray-400">
                                {recipes.length} وصفة
                            </span>
                        </div>

                        {/* Recipes Grid */}

                        <div
                            className="
                                grid
                                grid-cols-[repeat(auto-fit,minmax(380px,400px))]
                                gap-6
                                justify-start
                            "
                        >
                            {recipes.map(
                                (recipe) => {
                                    const imageUrl =
                                        getRecipeImageUrl(
                                            recipe,
                                        );

                                    const recipeWithImage =
                                        {
                                            ...recipe,
                                            image_url:
                                                imageUrl,
                                        };

                                    return (
                                        <RecipeCard
                                            key={
                                                recipe.id
                                            }
                                            recipe={
                                                recipeWithImage
                                            }
                                            deleting={
                                                deletingId ===
                                                recipe.id
                                            }
                                            onDelete={
                                                handleDelete
                                            }
                                            onView={() =>
                                                setSelectedRecipe(
                                                    recipeWithImage,
                                                )
                                            }
                                        />
                                    );
                                },
                            )}
                        </div>
                    </>
                )}
            </div>

            {/* =========================
                Add Recipe Modal
            ========================== */}

            <RecipeFormModal
                open={openForm}
                onClose={() =>
                    setOpenForm(false)
                }
                onSubmit={addRecipe}
                saving={saving}
            />

            {/* =========================
                Recipe Details Modal
            ========================== */}

            <RecipeDetailsModal
                open={Boolean(
                    selectedRecipe,
                )}
                recipe={selectedRecipe}
                deleting={
                    deletingId ===
                    selectedRecipe?.id
                }
                onClose={() =>
                    setSelectedRecipe(null)
                }
                onDelete={async (id) => {
                    const confirmed =
                        window.confirm(
                            "هل أنت متأكد من حذف هذه الوصفة؟",
                        );

                    if (!confirmed) {
                        return;
                    }

                    const success =
                        await removeRecipe(id);

                    if (success) {
                        setSelectedRecipe(
                            null,
                        );
                    }
                }}
                onEdit={(recipe) => {
                    console.log(
                        "Edit recipe:",
                        recipe,
                    );
                }}
                onAddToPlan={(recipe) => {
                    console.log(
                        "Add recipe to nutrition plan:",
                        recipe,
                    );
                }}
            />
        </div>
    );
};

export default RecipeLibrary;