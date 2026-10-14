
import { useState } from "react";
import { Plus, Search, Utensils } from "lucide-react";

import useRecipes from "../../../hooks/useRecipes";
import { getImageUrl } from "../../../utils/imageUrl";

import RecipeCard from "../../../components/recipes/RecipeCard";
import RecipeFormModal from "../../../components/recipes/RecipeFormModal";
import RecipeDetailsModal from "../../../components/recipes/RecipeDetailsModal";

const filters = [
  { value: "", label: "الكل" },
  { value: "breakfast", label: "الإفطار" },
  { value: "lunch", label: "الغداء" },
  { value: "dinner", label: "العشاء" },
  { value: "snack", label: "السناك" },
];

export default function RecipeLibrary() {
  const [openForm, setOpenForm] = useState(false);
  const [selectedRecipe, setSelectedRecipe] = useState(null);

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

  // Prepare image URLs for cards and details modal
  const normalizedRecipes = recipes.map((recipe) => ({
    ...recipe,
    image_url: getImageUrl(
      recipe.image_url || recipe.image
    ),
  }));

  const handleDelete = async (id) => {
    if (!window.confirm("هل أنت متأكد من حذف هذه الوصفة؟")) {
      return false;
    }

    try {
      return await removeRecipe(id);
    } catch (err) {
      console.error("Delete recipe error:", err);
      return false;
    }
  };

  const handleDeleteFromDetails = async (id) => {
    const success = await handleDelete(id);

    if (success) {
      setSelectedRecipe(null);
    }
  };

  return (
    <div
      dir="rtl"
      className="min-h-screen bg-[#F8FAF8] px-5 py-7 md:px-7 lg:px-10"
    >
      <div className="mx-auto max-w-[1500px] space-y-6">

        {/* Header */}
        <div className="flex flex-col justify-between gap-5 md:flex-row md:items-center">
          <div>
            <p className="mb-2 text-sm font-medium text-[#407437]">
              التغذية
            </p>

            <h1 className="text-2xl font-bold text-gray-900">
              مكتبة الوصفات
            </h1>

            <p className="mt-2 text-sm text-gray-500">
              أنشئ وصفاتك واحفظها لاستخدامها في خطط التغذية.
            </p>
          </div>

          <button
            type="button"
            onClick={() => setOpenForm(true)}
            className="flex items-center justify-center gap-2 rounded-xl bg-[#407437] px-4 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-green-800"
          >
            <Plus size={17} />
            إضافة وصفة
          </button>
        </div>

        {/* Error */}
        {error && (
          <div
            role="alert"
            className="rounded-xl border border-red-100 bg-red-50 px-4 py-3 text-sm text-red-600"
          >
            {error}
          </div>
        )}

        {/* Search and Filters */}
        <div className="rounded-2xl border border-gray-100 bg-white p-4 shadow-sm">
          <div className="flex flex-col gap-4 lg:flex-row lg:items-center">

            <div className="relative flex-1">
              <Search
                size={17}
                className="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400"
              />

              <input
                type="search"
                value={search}
                onChange={(e) => setSearch(e.target.value)}
                placeholder="ابحث عن وصفة..."
                className="w-full rounded-xl border border-gray-200 bg-gray-50 py-2.5 pl-4 pr-10 text-sm outline-none focus:border-green-500"
              />
            </div>

            <div className="flex gap-2 overflow-x-auto">
              {filters.map((filter) => (
                <button
                  key={filter.value}
                  type="button"
                  onClick={() => setMealType(filter.value)}
                  className={`whitespace-nowrap rounded-xl px-4 py-2 text-xs font-semibold transition-colors ${
                    mealType === filter.value
                      ? "bg-[#407437] text-white"
                      : "bg-gray-50 text-gray-500 hover:bg-gray-100"
                  }`}
                >
                  {filter.label}
                </button>
              ))}
            </div>
          </div>
        </div>

        {/* Loading */}
        {loading ? (
          <div className="flex min-h-[350px] items-center justify-center">
            <p className="text-sm text-gray-400">
              جاري تحميل الوصفات...
            </p>
          </div>
        ) : normalizedRecipes.length === 0 ? (

          /* Empty State */
          <div className="flex min-h-[350px] items-center justify-center rounded-2xl border border-gray-100 bg-white">
            <div className="text-center">
              <div className="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-green-50 text-[#407437]">
                <Utensils size={24} />
              </div>

              <h3 className="mt-4 font-bold text-gray-800">
                لا توجد وصفات
              </h3>

              <p className="mt-2 text-xs text-gray-400">
                ابدأ بإضافة أول وصفة إلى مكتبتك.
              </p>

              <button
                type="button"
                onClick={() => setOpenForm(true)}
                className="mt-4 rounded-xl bg-[#407437] px-4 py-2 text-xs font-semibold text-white hover:bg-green-800"
              >
                إضافة وصفة
              </button>
            </div>
          </div>
        ) : (

          /* Recipes */
          <section>
            <div className="mb-4 flex items-center justify-between">
              <h2 className="text-sm font-semibold text-gray-700">
                الوصفات
              </h2>

              <span className="text-xs text-gray-400">
                {normalizedRecipes.length} وصفة
              </span>
            </div>

            <div className="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4">
              {normalizedRecipes.map((recipe) => (
                <RecipeCard
                  key={recipe.id}
                  recipe={recipe}
                  deleting={deletingId === recipe.id}
                  onDelete={handleDelete}
                  onView={() => setSelectedRecipe(recipe)}
                />
              ))}
            </div>
          </section>
        )}
      </div>

      {/* Add Recipe Modal */}
      <RecipeFormModal
        open={openForm}
        onClose={() => setOpenForm(false)}
        onSubmit={addRecipe}
        saving={saving}
      />

      {/* Recipe Details Modal */}
      <RecipeDetailsModal
        open={Boolean(selectedRecipe)}
        recipe={selectedRecipe}
        deleting={deletingId === selectedRecipe?.id}
        onClose={() => setSelectedRecipe(null)}
        onDelete={handleDeleteFromDetails}
        onEdit={(recipe) => {
          console.log("Edit recipe:", recipe);
        }}
        onAddToPlan={(recipe) => {
          console.log("Add recipe to nutrition plan:", recipe);
        }}
      />
    </div>
  );
}
