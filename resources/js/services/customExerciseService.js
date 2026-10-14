import api from './api';

const BASE = '/coach/custom-exercises';

const customExerciseService = {
    async getCustomExercises(params = {}) {
        const response = await api.get(BASE, { params });
        return response.data;
    },

    async getCustomExercise(id) {
        const response = await api.get(`${BASE}/${id}`);
        return response.data;
    },

    async createCustomExercise(data) {
        const response = await api.post(BASE, data);
        return response.data;
    },

    async updateCustomExercise(id, data) {
        const response = await api.put(`${BASE}/${id}`, data);
        return response.data;
    },

    async deleteCustomExercise(id) {
        const response = await api.delete(`${BASE}/${id}`);
        return response.data;
    },
};

export default customExerciseService;