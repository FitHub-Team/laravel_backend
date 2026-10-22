
import api from "./api";

export const getExercises = async (params = {}) => {
  const response = await api.get("/coach/exercises", {
    params: {
      page: params.page ?? 1,
      search: params.search || undefined,
      muscle_group: params.muscle_group || undefined,
      difficulty_level: params.difficulty_level || undefined,
    },
  });

  return response.data;
};