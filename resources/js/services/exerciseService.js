import api from "./api";

export const getExercises = async () => {
  const response = await api.get("/coach/exercises");

  return response.data.data.data;
};