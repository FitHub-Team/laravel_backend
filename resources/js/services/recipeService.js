import api from "./api";

export const getRecipes = async ({
  search = "",
  mealType = "",
} = {}) => {
  const response = await api.get("/coach/recipes", {
    params: {
      search: search || undefined,
      meal_type: mealType || undefined,
    },
  });

  return response.data;
};

export const createRecipe = async (data) => {
  const response = await api.post(
    "/coach/recipes",
    data,
    {
      headers: {
        "Content-Type": undefined,
      },
    },
  );

  return response.data;
};

export const getRecipe = async (id) => {
  const response = await api.get(`/coach/recipes/${id}`);

  return response.data;
};

export const updateRecipe = async (id, data) => {
  const response = await api.put(
    `/coach/recipes/${id}`,
    data,
  );

  return response.data;
};

export const deleteRecipe = async (id) => {
  const response = await api.delete(
    `/coach/recipes/${id}`,
  );

  return response.data;
};