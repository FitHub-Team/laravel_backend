import { useState, useEffect } from "react";
import {
  X,
  User,
  Mail,
  MapPin,
  Briefcase,
  GraduationCap,
  Calendar,
  CreditCard,
  DollarSign,
  Star,
  Award,
  FileText,
  Plus,
  Trash2,
  Save,
} from "lucide-react";
import api from "../../services/api";

const EditProfileModal = ({
  isOpen,
  onClose,
  coach,
  profile,
  certifications,
  onSaved,
}) => {
  const [activeTab, setActiveTab] = useState("info");
  const [formData, setFormData] = useState({
    // بيانات الحساب
    full_name: "",
    email: "",
    // المعلومات الشخصية
    location: "",
    birth_year: "",
    specialization: "",
    experience: "",
    national_id: "",
    price: "",
    // المهارات
    skills: [],
    // الشهادات
    certifications: [],
    // النبذة
    bio: "",
  });
  const [saving, setSaving] = useState(false);
  const [editError, setEditError] = useState("");
  const [errors, setErrors] = useState({});

  const [newSkill, setNewSkill] = useState("");
  const [newCert, setNewCert] = useState({
    title: "",
    issuer: "",
    year: "",
  });

  // تحميل البيانات الأولية
  useEffect(() => {
    if (isOpen) {
      setFormData({
        full_name: coach?.user?.full_name ?? "",
        email: coach?.user?.email ?? "",
        location: profile?.location ?? "",
        birth_year: profile?.birth_year ?? "",
        specialization: profile?.specialization ?? "",
        experience: profile?.experience ?? "",
        national_id: profile?.national_id ?? "",
        price: profile?.price ?? "",
        skills: Array.isArray(profile?.skills) ? profile.skills : [],
        certifications: Array.isArray(certifications) ? certifications : [],
        bio: profile?.bio ?? "",
      });
    }
  }, [isOpen, coach, profile, certifications]);

  if (!isOpen) return null;

  // تحديث حقل
  const handleChange = (field, value) => {
    setFormData((prev) => ({ ...prev, [field]: value }));
  };

  // إضافة مهارة
  const handleAddSkill = () => {
    const skill = newSkill.trim();
    if (!skill) return;
    setFormData((prev) => ({
      ...prev,
      skills: [...prev.skills, { name: skill }],
    }));
    setNewSkill("");
  };

  // حذف مهارة
  const handleRemoveSkill = (id) => {
    setFormData((prev) => ({
      ...prev,
      skills: prev.skills.filter((s) => s.id !== id),
    }));
  };

  // إضافة شهادة
  const handleAddCert = () => {
    if (!newCert.title.trim()) return;
    setFormData((prev) => ({
      ...prev,
      certifications: [...prev.certifications, { ...newCert, id: Date.now() }],
    }));
    setNewCert({ title: "", issuer: "", year: "" });
  };

  // حذف شهادة
  const handleRemoveCert = (id) => {
    setFormData((prev) => ({
      ...prev,
      certifications: prev.certifications.filter((c) => c.id !== id),
    }));
  };

  // حفظ التعديلات
  const handleSave = async () => {
    try {
      setErrors({});

      const { email, ...updateData } = formData;

      console.log("DATA SENT:", updateData);
     
      console.log("SKILLS SENT:", updateData.skills);

      const response = await api.post(
        "/coach/profile/setting/update",
        updateData,
      );

      console.log("API RESPONSE:", response.data);

      onSaved(response.data.data);

      onClose();
    } catch (error) {
      console.log("STATUS:", error.response?.status);
      console.log("RESPONSE:", error.response?.data);

      if (error.response?.status === 422) {
        setErrors(error.response.data.errors || {});
      }
    }
  };

  const tabs = [
    { key: "info", label: "المعلومات الشخصية", icon: <User size={16} /> },
    { key: "skills", label: "المهارات", icon: <Star size={16} /> },
    { key: "certs", label: "الشهادات", icon: <Award size={16} /> },
    { key: "bio", label: "نبذة", icon: <FileText size={16} /> },
  ];

  const inputClass =
    "w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-700 outline-none transition-colors focus:border-[#7FA279] focus:ring-2 focus:ring-[#7FA279]/20";
  const labelClass = "block mb-2 text-sm font-medium text-gray-700";

  return (
    <div
      className="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4 rtl"
      dir="rtl"
      onClick={onClose}
    >
      <div
        className="w-full max-w-3xl max-h-[90vh] overflow-hidden rounded-3xl bg-white shadow-2xl flex flex-col"
        onClick={(e) => e.stopPropagation()}
      >
        {/* ============ Header ============ */}
        <div className="flex items-center justify-between border-b border-gray-100 px-6 py-5 shrink-0">
          <div className="flex items-center gap-3">
            <div className="w-10 h-10 rounded-xl bg-green-50 flex items-center justify-center">
              <User size={20} className="text-[#7FA279]" />
            </div>
            <div>
              <h2 className="text-lg font-bold text-[#1A4762]">
                تعديل الملف الشخصي
              </h2>
              <p className="text-xs text-gray-500 mt-0.5">
                قم بتحديث بياناتك الشخصية والمهنية
              </p>
            </div>
          </div>

          <button
            type="button"
            onClick={onClose}
            className="flex h-9 w-9 items-center justify-center rounded-xl text-gray-400 transition-colors hover:bg-gray-50 hover:text-gray-600"
          >
            <X size={20} />
          </button>
        </div>

        {/* ============ Tabs ============ */}
        <div className="flex border-b border-gray-100 overflow-x-auto shrink-0">
          {tabs.map((tab) => (
            <button
              key={tab.key}
              type="button"
              onClick={() => setActiveTab(tab.key)}
              className={`flex items-center gap-2 px-4 lg:px-6 py-3 text-sm font-medium whitespace-nowrap transition-colors border-b-2 ${
                activeTab === tab.key
                  ? "text-[#1A4762] border-[#7FA279] bg-green-50/30"
                  : "text-gray-500 border-transparent hover:text-[#1A4762]"
              }`}
            >
              {tab.icon}
              {tab.label}
            </button>
          ))}
        </div>

        {/* ============ Content ============ */}
        <div className="flex-1 overflow-y-auto p-4 lg:p-6">
          {/* ---------- Info Tab ---------- */}
          {activeTab === "info" && (
            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label className={labelClass}>الاسم الكامل</label>
                <input
                  type="text"
                  value={formData.full_name}
                  onChange={(e) => handleChange("full_name", e.target.value)}
                  className={inputClass}
                  placeholder="أدخل الاسم الكامل"
                />
                {errors.full_name && (
                  <p className="mt-1 text-sm text-red-500">
                    {errors.full_name[0]}
                  </p>
                )}
              </div>

              <div>
                <label className={labelClass}>البريد الإلكتروني</label>
                <input
                  type="email"
                  value={formData.email}
                  onChange={(e) => handleChange("email", e.target.value)}
                  className={inputClass}
                  disabled
                  placeholder="example@email.com"
                />
              </div>

              <div>
                <label className={labelClass}>مكان الإقامة</label>
                <input
                  type="text"
                  value={formData.location}
                  onChange={(e) => handleChange("location", e.target.value)}
                  className={inputClass}
                  placeholder="المدينة، الدولة"
                />
                {errors.location && (
                  <p className="mt-1 text-sm text-red-500">
                    {errors.location[0]}
                  </p>
                )}
              </div>

              <div>
                <label className={labelClass}>سنة الميلاد</label>
                <input
                  type="number"
                  value={formData.birth_year}
                  onChange={(e) => handleChange("birth_year", e.target.value)}
                  className={inputClass}
                  placeholder="1990"
                />
                {errors.birth_year && (
                  <p className="mt-1 text-sm text-red-500">
                    {errors.birth_year[0]}
                  </p>
                )}
              </div>

              <div>
                <label className={labelClass}>التخصص</label>
                <input
                  type="text"
                  value={formData.specialization}
                  onChange={(e) =>
                    handleChange("specialization", e.target.value)
                  }
                  className={inputClass}
                  placeholder="مثال: تدريب كمال الأجسام"
                />
                {errors.specialization && (
                  <p className="mt-1 text-sm text-red-500">
                    {errors.specialization[0]}
                  </p>
                )}
              </div>

              <div>
                <label className={labelClass}>سنوات الخبرة</label>
                <input
                  type="number"
                  value={formData.experience}
                  onChange={(e) => handleChange("experience", e.target.value)}
                  className={inputClass}
                  placeholder="5"
                />
                {errors.specialization && (
                  <p className="mt-1 text-sm text-red-500">
                    {errors.specialization[0]}
                  </p>
                )}
              </div>

              <div>
                <label className={labelClass}>رقم الهوية</label>
                <input
                  type="text"
                  value={formData.national_id}
                  onChange={(e) => handleChange("national_id", e.target.value)}
                  className={inputClass}
                  placeholder="1234567890"
                />
                {errors.specialization && (
                  <p className="mt-1 text-sm text-red-500">
                    {errors.specialization[0]}
                  </p>
                )}
              </div>

              <div>
                <label className={labelClass}>سعر الجلسة (ر.س)</label>
                <input
                  type="number"
                  value={formData.price}
                  onChange={(e) => handleChange("price", e.target.value)}
                  className={inputClass}
                  placeholder="150"
                />
                {errors.specialization && (
                  <p className="mt-1 text-sm text-red-500">
                    {errors.specialization[0]}
                  </p>
                )}
              </div>
            </div>
          )}

          {/* ---------- Skills Tab ---------- */}
          {activeTab === "skills" && (
            <div className="space-y-4">
              {/* إضافة مهارة */}
              <div className="flex gap-2">
                <input
                  type="text"
                  value={newSkill}
                  onChange={(e) => setNewSkill(e.target.value)}
                  onKeyDown={(e) => e.key === "Enter" && handleAddSkill()}
                  className={inputClass}
                  placeholder="أدخل مهارة جديدة..."
                />
                {errors.specialization && (
                  <p className="mt-1 text-sm text-red-500">
                    {errors.specialization[0]}
                  </p>
                )}
                <button
                  type="button"
                  onClick={handleAddSkill}
                  className="flex items-center gap-2 rounded-xl bg-[#1A4762] px-4 py-3 text-sm font-medium text-white transition-colors hover:bg-[#184159] shrink-0"
                >
                  <Plus size={16} />
                  إضافة
                </button>
              </div>

              {/* قائمة المهارات */}
              <div className="flex flex-wrap gap-2">
                {formData.skills.length ? (
                  formData.skills.map((skill) => (
                    <span
                      key={skill.id}
                      className="flex items-center gap-2 px-3 py-1.5 rounded-full bg-green-50 text-green-700 text-sm font-medium border border-green-100"
                    >
                      <Star size={14} />
                      {skill.name ?? skill.title}
                      <button
                        type="button"
                        onClick={() => handleRemoveSkill(skill.id)}
                        className="text-green-600/60 hover:text-red-500 transition-colors"
                      >
                        <X size={14} />
                      </button>
                    </span>
                  ))
                ) : (
                  <p className="text-sm text-gray-400 py-4 text-center w-full">
                    لا توجد مهارات مسجلة حالياً
                  </p>
                )}
              </div>
            </div>
          )}

          {/* ---------- Certifications Tab ---------- */}
          {activeTab === "certs" && (
            <div className="space-y-4">
              {/* إضافة شهادة */}
              <div className="grid grid-cols-1 md:grid-cols-3 gap-3 p-4 rounded-xl bg-[#F8F9FA] border border-gray-100">
                <div>
                  <label className={labelClass}>اسم الشهادة</label>
                  <input
                    type="text"
                    value={newCert.title}
                    onChange={(e) =>
                      setNewCert((prev) => ({ ...prev, title: e.target.value }))
                    }
                    className={inputClass}
                    placeholder="مثال: شهادة تدريب"
                  />
                </div>

                <div>
                  <label className={labelClass}>الجهة المانحة</label>
                  <input
                    type="text"
                    value={newCert.issuer}
                    onChange={(e) =>
                      setNewCert((prev) => ({
                        ...prev,
                        issuer: e.target.value,
                      }))
                    }
                    className={inputClass}
                    placeholder="مثال: الاتحاد السعودي"
                  />
                </div>

                <div>
                  <label className={labelClass}>السنة</label>
                  <div className="flex gap-2">
                    <input
                      type="text"
                      value={newCert.year}
                      onChange={(e) =>
                        setNewCert((prev) => ({
                          ...prev,
                          year: e.target.value,
                        }))
                      }
                      className={inputClass}
                      placeholder="2023"
                    />
                    <button
                      type="button"
                      onClick={handleAddCert}
                      className="flex items-center justify-center rounded-xl bg-[#1A4762] px-4 text-white transition-colors hover:bg-[#184159] shrink-0"
                    >
                      <Plus size={16} />
                    </button>
                  </div>
                </div>
              </div>

              {/* قائمة الشهادات */}
              <div className="grid grid-cols-1 md:grid-cols-2 gap-3">
                {formData.certifications.length ? (
                  formData.certifications.map((cert) => (
                    <div
                      key={cert.id}
                      className="flex items-start gap-3 p-4 rounded-xl border border-gray-100 bg-[#F8F9FA] hover:border-[#7FA279] transition-colors"
                    >
                      <div className="w-10 h-10 rounded-lg bg-green-50 flex items-center justify-center shrink-0">
                        <Award size={20} className="text-[#7FA279]" />
                      </div>
                      <div className="flex-1 min-w-0">
                        <h4 className="font-bold text-[#1A4762] text-sm truncate">
                          {cert.title ?? cert.name}
                        </h4>
                        <p className="text-xs text-gray-500 mt-0.5">
                          {cert.issuer ?? cert.organization ?? ""}
                          {cert.year ? ` • ${cert.year}` : ""}
                        </p>
                      </div>
                      <button
                        type="button"
                        onClick={() => handleRemoveCert(cert.id)}
                        className="text-gray-300 hover:text-red-500 transition-colors shrink-0"
                      >
                        <Trash2 size={16} />
                      </button>
                    </div>
                  ))
                ) : (
                  <p className="text-sm text-gray-400 py-4 text-center w-full md:col-span-2">
                    لا توجد شهادات مسجلة حالياً
                  </p>
                )}
              </div>
            </div>
          )}

          {/* ---------- Bio Tab ---------- */}
          {activeTab === "bio" && (
            <div>
              <label className={labelClass}>النبذة التعريفية</label>
              <textarea
                value={formData.bio}
                onChange={(e) => handleChange("bio", e.target.value)}
                rows={6}
                className={`${inputClass} resize-none`}
                placeholder="اكتب نبذة تعريفية عنك وعن خبراتك..."
              />
              {errors.bio && (
                <p className="mt-1 text-sm text-red-500">{errors.bio[0]}</p>
              )}
            </div>
          )}
        </div>

        {/* ============ Footer ============ */}
        <div className="flex justify-end gap-3 border-t border-gray-100 px-6 py-4 shrink-0 bg-white">
          <button
            type="button"
            onClick={onClose}
            className="rounded-xl border border-gray-200 px-5 py-2.5 text-sm font-medium text-gray-600 transition-colors hover:bg-gray-50"
          >
            إلغاء
          </button>

          <button
            type="button"
            onClick={handleSave}
            className="flex items-center gap-2 rounded-xl bg-[#1A4762] px-5 py-2.5 text-sm font-medium text-white transition-colors hover:bg-[#184159]"
          >
            <Save size={16} />
            حفظ التعديلات
          </button>
        </div>
      </div>
    </div>
  );
};

export default EditProfileModal;
