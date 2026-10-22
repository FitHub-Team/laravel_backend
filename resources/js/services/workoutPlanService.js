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

const generateAIWorkoutPlan = async (traineeId) => {
    const response = await api.post(
        `/coach/ai/generate/workout-plan/${traineeId}`
    );

    return response.data?.data ?? null;
};

const getWorkoutVersions = async (traineeId) => {
    const response = await api.get(
        `/coach/trainees/${traineeId}/workout-plan/versions`
    );

    return response.data?.data ?? [];
};

const getWorkoutVersionDetails = async (traineeId, planId) => {
    const response = await api.get(
        `/coach/trainees/${traineeId}/workout-plan/versions/${planId}`
    );

    return response.data?.data ?? null;
};

const workoutPlanService = {
    getTrainees,
    getWorkoutPlan,
    saveWorkoutPlan,
    generateAIWorkoutPlan,
    getWorkoutVersions,
    getWorkoutVersionDetails,
};

export default workoutPlanService;