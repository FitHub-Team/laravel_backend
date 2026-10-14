import { useCallback, useEffect, useRef, useState } from "react";
import { coachProfileService } from "../services/coachProfileService";

export function useCoachProfile() {
  const [profile, setProfile] = useState({});
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [uploadingPhoto, setUploadingPhoto] = useState(false);
  const [deletingPhoto, setDeletingPhoto] = useState(false);
  const mounted = useRef(false);

  const fetchProfile = useCallback(async () => {
    setLoading(true);
    setError(null);
    try {
      const data = await coachProfileService.getProfile();
      if (mounted.current) setProfile(data || {});
    } catch (err) {
      if (mounted.current) {
        setError(err.response?.data?.message || "فشل في جلب بيانات الملف الشخصي");
      }
    } finally {
      if (mounted.current) setLoading(false);
    }
  }, []);

  useEffect(() => {
    mounted.current = true;
    fetchProfile();
    return () => { mounted.current = false; };
  }, [fetchProfile]);

  const uploadPhoto = async (file) => {
    const allowedTypes = ["image/jpeg", "image/png", "image/gif", "image/webp"];
    if (!allowedTypes.includes(file.type)) throw new Error("صيغة الصورة غير مدعومة");
    if (file.size > 2 * 1024 * 1024) throw new Error("حجم الصورة يتجاوز 2MB");
    setUploadingPhoto(true);
    try {
      const data = await coachProfileService.uploadPhoto(file);
      if (mounted.current) setProfile(prev => ({ ...prev, profile_photo: data?.profile_photo ?? prev.profile_photo }));
      return data;
    } finally {
      if (mounted.current) setUploadingPhoto(false);
    }
  };

  const deletePhoto = async () => {
    setDeletingPhoto(true);
    try {
      await coachProfileService.deletePhoto();
      if (mounted.current) setProfile(prev => ({ ...prev, profile_photo: null }));
    } finally {
      if (mounted.current) setDeletingPhoto(false);
    }
  };

  return { profile, setProfile, loading, error, uploadingPhoto, deletingPhoto,
    fetchProfile, uploadPhoto, deletePhoto };
}
