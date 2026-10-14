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