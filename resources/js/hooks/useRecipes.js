import toast from "../services/toast";
import {
    useCallback,
    useEffect,
    useState,
} from "react";

import {
    createRecipe,
    deleteRecipe,
    getRecipes,
} from "../services/recipeService";

const useRecipes = () => {
    const [recipes, setRecipes] = useState([]);

    const [search, setSearch] = useState("");
    const [mealType, setMealType] = useState("");

    const [loading, setLoading] = useState(true);
    const [saving, setSaving] = useState(false);
    const [deletingId, setDeletingId] = useState(null);

    const [error, setError] = useState("");

    const fetchRecipes = useCallback(async () => {
        try {
            setLoading(true);
            setError("");

            const response = await getRecipes({
                search,
                mealType,
            });

            setRecipes(
                Array.isArray(response?.data)
                    ? response.data
                    : [],
            );
        } catch (error) {
            console.error(
                "Error loading recipes:",
                error.response?.data ?? error,
            );

            setError(
                error.response?.data?.message ||
                    "حدث خطأ أثناء تحميل الوصفات",
            );
        } finally {
            setLoading(false);
        }
    }, [search, mealType]);

    useEffect(() => {
        const timer = setTimeout(() => {
            fetchRecipes();
        }, 300);

        return () => clearTimeout(timer);
    }, [fetchRecipes]);

    const addRecipe = async (data) => {
        try {
            setSaving(true);
            setError("");

            const response = await createRecipe(data);

            console.log(
                "Recipe created successfully:",
                response,
            );

            await fetchRecipes();
            toast.success("تمت إضافة الوصفة بنجاح");

            return true;
        } catch (error) {
            console.error(
                "Recipe validation errors:",
                error.response?.data?.errors,
            );

            console.error(
                "Full recipe error:",
                error.response?.data ?? error,
            );

            setError(
                error.response?.data?.message ||
                    "حدث خطأ أثناء إضافة الوصفة",
            );

            toast.error(error.response?.data?.message || "تعذر حفظ التغييرات. يرجى المحاولة مرة أخرى.");
            return false;
        } finally {
            setSaving(false);
        }
    };

    const removeRecipe = async (id) => {
        try {
            setDeletingId(id);
            setError("");

            await deleteRecipe(id);

            setRecipes((current) =>
                current.filter(
                    (recipe) => recipe.id !== id,
                ),
            );

            toast.success("تم حذف الوصفة بنجاح");
            return true;
        } catch (error) {
            console.error(
                "Error deleting recipe:",
                error.response?.data ?? error,
            );

            setError(
                error.response?.data?.message ||
                    "حدث خطأ أثناء حذف الوصفة",
            );

            toast.error(error.response?.data?.message || "تعذر حفظ التغييرات. يرجى المحاولة مرة أخرى.");
            return false;
        } finally {
            setDeletingId(null);
        }
    };

    return {
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
        refreshRecipes: fetchRecipes,
    };
};

export default useRecipes;