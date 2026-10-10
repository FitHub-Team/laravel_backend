import api from "./api";

export const getCoachTrainees = async (search = "") => {
  const response = await api.get("/coach/trainees", {
    params: {
      search,
    },
  });

  return response.data;
};

export const getTraineeProfile = async (traineeId) => {
  const response = await api.get(`/coach/trainees/${traineeId}`);

  return response.data;
};