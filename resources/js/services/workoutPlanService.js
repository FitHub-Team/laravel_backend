import api from "./api";

const getTrainees = async () => {
  const response = await api.get("/coach/trainees");

  return response.data?.data ?? [];
};

const getWorkoutPlan = async (traineeId) => {
  const response = await api.get(
    `/coach/trainees/${traineeId}/workout-plan`
  );

  return response.data?.data ?? null;
};

const saveWorkoutPlan = async (traineeId, data) => {
  const response = await api.post(
    `/coach/trainees/${traineeId}/workout-plan`,
    data
  );

  return response.data;
};

// توليد خطة تمارين بالـ AI
const generateAIWorkoutPlan = async (traineeId) => {
  const response = await api.post(
    `/coach/ai/generate/workout-plan/${traineeId}`
  );

  return response.data?.data ?? null;
};

export default {
  getTrainees,
  getWorkoutPlan,
  saveWorkoutPlan,
  generateAIWorkoutPlan,
};