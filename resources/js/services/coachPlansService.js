import api from "./api";

export const getCoachPlans = async () => {
  const response = await api.get("/coach/workout-plans");

  return response.data;
};
export const sendExistingPlan = async (planId) => {
  const response = await api.patch(
    `/coach/workout-plans/${planId}/send`
  );

  return response.data;
};
// Delete saved draft
export const deleteDraftPlan = async (planId) => {
  const response = await api.delete(
    `/coach/workout-plans/${planId}`
  );

  return response.data;
};

// Cancel active plan
export const cancelActivePlan = async (planId) => {
  const response = await api.patch(
    `/coach/workout-plans/${planId}/cancel`
  );

  return response.data;
};


