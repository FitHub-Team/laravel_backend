import { useState, useEffect } from "react";
import { useNavigate } from "react-router-dom";
import {
  MapPin,
  Briefcase,
  DollarSign,
  Edit3,
  CheckCircle2,
  XCircle,
  Settings,
  Calendar,
} from "lucide-react";

import api from "../../../services/api";

import profileImage from "../../../assets/profile-avatar.jpg";
import TabsCard from "../../../components/Coach profile/TabsCard";
import EditProfileModal from "../../../components/Coach profile/EditProfileModal";

function CoachProfile() {
  const [profile, setProfile] = useState({});
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  const [isEditModalOpen, setIsEditModalOpen] = useState(false);
  const [activeTab, setActiveTab] = useState("info");

  const [isDeletePhotoOpen, setIsDeletePhotoOpen] = useState(false);
  const [isDeletingPhoto, setIsDeletingPhoto] = useState(false);

  const navigate = useNavigate();

  // =========================
  // Fetch Coach Profile
  // =========================
  useEffect(() => {
    const fetchProfile = async () => {
      try {
        setLoading(true);

        const response = await api.get("/coach/profile/setting/show");

        console.log("Profile API:", response.data);

        setProfile(response.data.data);
      } catch (err) {
        console.error("Profile Error:", err);

        setError(
          err.response?.data?.message || "فشل في جلب بيانات الملف الشخصي",
        );
      } finally {
        setLoading(false);
      }
    };

    fetchProfile();
  }, []);

  // =========================
  // Change Avatar
  // =========================
  const handleAvatarChange = async (e) => {
    const file = e.target.files?.[0];

    if (!file) return;

    try {
      const formData = new FormData();

      formData.append("profile_photo", file);

      console.log("File:", file);
      console.log("FormData:", [...formData.entries()]);

      const response = await api.post(
        "/coach/profile/setting/update/avatar",
        formData,
      );

      console.log("Avatar API:", response.data);

      setProfile((prev) => ({
        ...prev,
        profile_photo: response.data.data.profile_photo,
      }));
    } catch (error) {
      console.error("Avatar upload error:", error.response?.data || error);
    } finally {
      e.target.value = "";
    }
  };
  // delete avatar
  const handleDeleteAvatar = async () => {
    try {
      setIsDeletingPhoto(true);

      const response = await api.delete("/coach/profile/setting/delete/avatar");

      console.log("Delete Avatar API:", response.data);

      setProfile((prev) => ({
        ...prev,
        profile_photo: null,
      }));

      setIsDeletePhotoOpen(false);
    } catch (error) {
      console.error("Delete avatar error:", error.response?.data || error);

      alert(error.response?.data?.message || "حدث خطأ أثناء حذف الصورة");
    } finally {
      setIsDeletingPhoto(false);
    }
  };

  // =========================
  // Loading
  // =========================
  // if (loading) {
  //   return (
  //     <div className="min-h-screen bg-[#F8F9FA] p-6 rtl" dir="rtl">
  //       <div className="flex min-h-[400px] items-center justify-center">
  //         <p className="text-sm text-gray-500">جاري تحميل الملف الشخصي...</p>
  //       </div>
  //     </div>
  //   );
  // }

  // =========================
  // Error
  // =========================
  if (error) {
    return (
      <div className="min-h-screen bg-[#F8F9FA] p-6 rtl" dir="rtl">
        <div className="rounded-2xl border border-red-100 bg-red-50 p-5 text-sm text-red-600">
          {error}
        </div>
      </div>
    );
  }

  // =========================
  // Certifications
  // =========================
  const certifications = (() => {
    try {
      return typeof profile?.certifications === "string"
        ? JSON.parse(profile.certifications)
        : (profile?.certifications ?? []);
    } catch {
      return [];
    }
  })();

  // =========================
  // Calculate Age
  // =========================
  const age = profile?.birth_year
    ? new Date().getFullYear() - Number(profile.birth_year)
    : null;

  return (
    <div className="min-h-screen bg-[#F8F9FA] p-4 lg:p-6 rtl" dir="rtl">
      {/* =====================================================
          Header Card
      ====================================================== */}
      <div className="relative mb-5 overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-[0_4px_20px_-4px_rgba(26,71,98,0.08)]">
        {/* ================= Banner ================= */}
        <div className="relative h-36 overflow-hidden bg-gradient-to-br from-green-600 via-green-700 to-green-800 lg:h-35">
          <div className="absolute -left-10 -top-24 h-64 w-64 rounded-full bg-white/10 blur-3xl" />

          <div className="absolute -bottom-32 right-1/3 h-72 w-72 rounded-full bg-[#C5E1B5]/20 blur-3xl" />

          <div className="absolute -right-16 -top-28 h-72 w-72 rounded-full border border-white/15" />

          <div className="absolute -right-8 -top-20 h-56 w-56 rounded-full border border-white/10" />

          <div className="absolute inset-x-0 bottom-0 h-px bg-gradient-to-r from-transparent via-white/40 to-transparent" />

          {/* Certified Badge */}
          <div className="absolute right-5 top-5 flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-3 py-1.5 backdrop-blur-md lg:right-8">
            <span className="h-2 w-2 rounded-full bg-[#C5E1B5]" />

            <span className="text-xs font-medium text-white">مدرب معتمد</span>
          </div>

          <div className="absolute left-0 top-0 h-14 w-14 rounded-tl-3xl border-l border-t border-white/20" />
        </div>

        {/* =====================================================
            Profile Content
        ====================================================== */}
        <div className="relative px-5 pb-6 pt-6 lg:px-8">
          <div className="flex flex-col gap-5 lg:flex-row lg:items-end lg:gap-6">
            {/* =================================================
                Avatar
            ================================================== */}
            <div className="relative w-fit shrink-0">
              <input
                type="file"
                accept="image/*"
                id="profile-photo-input"
                className="hidden"
                onChange={handleAvatarChange}
              />

              <label
                htmlFor="profile-photo-input"
                className="group relative block h-28 w-28 cursor-pointer overflow-hidden rounded-full border-4 border-white bg-gray-100 shadow-lg lg:h-36 lg:w-36"
                title="تغيير صورة البروفايل"
              >
                <img
                  src={profile?.profile_photo || profileImage}
                  alt={profile?.user?.full_name || "صورة المدرب"}
                  className="h-full w-full object-cover"
                />

                {/* Hover overlay */}
                <div className="absolute inset-0 flex items-center justify-center bg-black/0 transition-all duration-200 group-hover:bg-black/40">
                  <div className="scale-75 rounded-full bg-white/90 p-2 opacity-0 shadow transition-all duration-200 group-hover:scale-100 group-hover:opacity-100">
                    <Edit3 size={18} className="text-[#1A4762]" />
                  </div>
                </div>

                {/* Delete Button - Overlay فوق يمين */}
                {profile?.profile_photo && (
                  <button
                    type="button"
                    onClick={(e) => {
                      e.preventDefault();
                      e.stopPropagation();
                      setIsDeletePhotoOpen(true);
                    }}
                    className="absolute top-1 right-1 z-10 flex h-8 w-8 items-center justify-center rounded-full bg-red-500 text-white shadow-md opacity-0 transition-all duration-200 hover:bg-red-600 hover:scale-110 group-hover:opacity-100 lg:top-2 lg:right-2 lg:h-9 lg:w-9"
                    title="حذف الصورة"
                  >
                    <XCircle size={16} />
                  </button>
                )}
              </label>

              {/* Status */}
              <span
                className={`absolute bottom-2 left-2 h-5 w-5 rounded-full border-[3px] border-white ${
                  profile?.status === "active" ? "bg-green-500" : "bg-gray-400"
                }`}
              />
            </div>

            {/* =================================================
                Name & Details
            ================================================== */}
            <div className="min-w-0 flex-1 lg:pb-2">
              <div className="mb-2 flex flex-wrap items-center gap-2">
                {/* Name */}
                <h1 className="text-xl font-bold text-[#1A4762] lg:text-2xl">
                  كابتن {profile?.user?.full_name || "غير محدد"}
                </h1>

                {/* Role */}
                <span className="rounded-full bg-[#1A4762]/10 px-3 py-1 text-[10px] font-bold text-[#1A4762]">
                  COACH
                </span>

                {/* Approval Status */}
                {profile?.is_approved ? (
                  <span className="flex items-center gap-1 rounded-full bg-green-50 px-3 py-1 text-[10px] font-bold text-green-700">
                    <CheckCircle2 size={13} />
                    موثّق
                  </span>
                ) : (
                  <span className="flex items-center gap-1 rounded-full bg-yellow-50 px-3 py-1 text-[10px] font-bold text-yellow-700">
                    <XCircle size={13} />
                    قيد المراجعة
                  </span>
                )}
              </div>

              {/* Specialization */}
              <p className="text-sm text-gray-500">
                {profile?.specialization || "مدرب لياقة بدنية"}
              </p>

              {/* ================= Statistics ================= */}
              <div className="mt-4 flex flex-wrap gap-2">
                {/* Location */}
                {profile?.location && (
                  <div className="flex items-center gap-2 rounded-xl border border-gray-100 bg-gray-50 px-3 py-2 text-xs text-gray-600">
                    <MapPin size={15} className="text-[#7FA279]" />

                    <span>{profile.location}</span>
                  </div>
                )}

                {/* Experience */}
                {profile?.experience != null && (
                  <div className="flex items-center gap-2 rounded-xl border border-gray-100 bg-gray-50 px-3 py-2 text-xs text-gray-600">
                    <Briefcase size={15} className="text-[#7FA279]" />

                    <span>{profile.experience} سنوات خبرة</span>
                  </div>
                )}

                {/* Age */}
                {age != null && (
                  <div className="flex items-center gap-2 rounded-xl border border-gray-100 bg-gray-50 px-3 py-2 text-xs text-gray-600">
                    <Calendar size={15} className="text-[#7FA279]" />

                    <span>{age} سنة</span>
                  </div>
                )}

                {/* Price */}
                {profile?.price != null && (
                  <div className="flex items-center gap-2 rounded-xl border border-gray-100 bg-gray-50 px-3 py-2 text-xs text-gray-600">
                    <DollarSign size={15} className="text-[#7FA279]" />

                    <span>{profile.price} ر.س / شهريا</span>
                  </div>
                )}
              </div>
            </div>

            {/* =================================================
                Actions
            ================================================== */}
            <div className="flex w-full gap-2 lg:w-auto lg:pb-2">
              {/* Edit Profile */}
              <button
                type="button"
                onClick={() => setIsEditModalOpen(true)}
                className="flex flex-1 items-center justify-center gap-2 rounded-xl bg-[#1A4762] px-5 py-2.5 text-sm font-medium text-white transition-colors hover:bg-[#184159] lg:flex-none"
              >
                <Edit3 size={16} />
                تعديل البروفايل
              </button>

              {/* Settings */}
              <button
                type="button"
                onClick={() => navigate("/coach/settings")}
                title="الإعدادات"
                aria-label="الإعدادات"
                className="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-gray-200 bg-white text-gray-500 transition-colors hover:border-[#7FA279] hover:bg-gray-50 hover:text-[#1A4762]"
              >
                <Settings size={18} />
              </button>
            </div>
          </div>
        </div>
      </div>

      {/* =====================================================
          Tabs Card
      ====================================================== */}
      <TabsCard
        activeTab={activeTab}
        setActiveTab={setActiveTab}
        coach={profile}
        profile={profile}
        certifications={certifications}
      />

      {/* =====================================================
          Rejection Reason
      ====================================================== */}
      {profile?.rejection_reason && (
        <div className="mt-5 rounded-xl border border-red-100 bg-red-50 p-4">
          <div className="flex items-start gap-3">
            <XCircle size={20} className="mt-0.5 shrink-0 text-red-500" />

            <div>
              <h4 className="text-sm font-bold text-red-700">سبب الرفض</h4>

              <p className="mt-1 text-xs leading-relaxed text-red-600">
                {profile.rejection_reason}
              </p>
            </div>
          </div>
        </div>
      )}

      {/* =====================================================
          Edit Profile Modal
      ====================================================== */}
      {isEditModalOpen && (
        <EditProfileModal
          isOpen={isEditModalOpen}
          onClose={() => setIsEditModalOpen(false)}
          coach={profile}
          profile={profile}
          certifications={certifications}
          onSaved={(updatedProfile) => {
            setProfile(updatedProfile);
          }}
        />
      )}
      {isDeletePhotoOpen && (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4 backdrop-blur-sm">
          <div className="w-full max-w-sm rounded-2xl bg-white p-6 shadow-2xl">
            <div className="mb-4 flex items-center justify-center">
              <div className="flex h-14 w-14 items-center justify-center rounded-full bg-red-50">
                <XCircle size={28} className="text-red-500" />
              </div>
            </div>

            <h3 className="text-center text-lg font-bold text-[#1A4762]">
              حذف صورة الملف الشخصي؟
            </h3>

            <p className="mt-2 text-center text-sm leading-6 text-gray-500">
              هل أنت متأكد من حذف صورة الملف الشخصي؟
              <br />
              يمكنك رفع صورة جديدة لاحقًا.
            </p>

            <div className="mt-6 flex gap-3">
              <button
                type="button"
                onClick={() => setIsDeletePhotoOpen(false)}
                disabled={isDeletingPhoto}
                className="flex-1 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-medium text-gray-600 transition hover:bg-gray-50 disabled:opacity-50"
              >
                إلغاء
              </button>

              <button
                type="button"
                onClick={handleDeleteAvatar}
                disabled={isDeletingPhoto}
                className="flex-1 rounded-xl bg-red-500 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-red-600 disabled:cursor-not-allowed disabled:opacity-50"
              >
                {isDeletingPhoto ? "جاري الحذف..." : "نعم، حذف الصورة"}
              </button>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}

export default CoachProfile;
