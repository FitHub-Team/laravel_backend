import api from "./api";

const PROFILE_URL = "/coach/profile/setting";

export const coachProfileService = {
  async getProfile() {
    const response = await api.get(`${PROFILE_URL}/show`);
    return response.data.data;
  },

  async uploadPhoto(file) {
    const formData = new FormData();
    formData.append("profile_photo", file);
    const response = await api.post(`${PROFILE_URL}/update/avatar`, formData, {
      headers: { Accept: "application/json" },
    });
    return response.data.data;
  },

  async deletePhoto() {
    const response = await api.delete(`${PROFILE_URL}/delete/avatar`);
    return response.data;
  },
};
