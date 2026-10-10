import { useNavigate, useParams } from "react-router-dom";
import {
  ArrowRight,
  Mail,
  User,
  Target,
  CalendarDays,
  Dumbbell,
  Ruler,
  Weight,
} from "lucide-react";

import useTraineeProfile from "../../../hooks/useTraineeProfile";

const TraineeProfile = () => {
  const { id } = useParams();
  const navigate = useNavigate();

  const {
    trainee,
    loading,
    error,
  } = useTraineeProfile(id);

  if (loading) {
    return (
      <div
        dir="rtl"
        className="flex items-center justify-center min-h-[400px]"
      >
        <p className="text-gray-500">
          جاري تحميل بيانات المشترك...
        </p>
      </div>
    );
  }

  if (error) {
    return (
      <div dir="rtl" className="p-6">
        <div className="bg-white border border-red-100 rounded-2xl p-8 text-center">
          <p className="text-red-500 font-medium">
            {error}
          </p>
        </div>
      </div>
    );
  }

  if (!trainee) {
    return (
      <div dir="rtl" className="p-6">
        <div className="bg-white rounded-2xl p-8 text-center">
          <p className="text-gray-500">
            لا توجد بيانات لهذا المشترك
          </p>
        </div>
      </div>
    );
  }

  const profile = trainee.profile ?? {};

  const goal = profile.goal;

  const getInitial = () => {
    return trainee.full_name?.charAt(0) ?? "؟";
  };

  const genderLabel = {
    female: "أنثى",
    male: "ذكر",
  }[profile.gender];

  const trainingLocationLabel = {
    home_with_equipment: "المنزل مع معدات",
    home_without_equipment: "المنزل بدون معدات",
    gym: "النادي الرياضي",
  }[profile.training_location];

  return (
    <div
      dir="rtl"
      className="max-w-6xl mx-auto px-6 py-6 space-y-6"
    >
      {/* Header */}
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-2xl font-bold text-gray-800">
            الملف الشخصي للمشترك
          </h1>

          <p className="text-sm text-gray-500 mt-1">
            عرض البيانات الشخصية والرياضية للمشترك
          </p>
        </div>

        <button
          type="button"
          onClick={() => navigate(-1)}
          className="
            flex
            items-center
            gap-2
            px-4
            py-2
            bg-white
            border
            border-gray-200
            rounded-xl
            text-sm
            text-gray-600
            hover:bg-gray-50
            transition-colors
          "
        >
          <ArrowRight size={18} />
          رجوع
        </button>
      </div>

      {/* Main Profile Card */}
      <div className="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <div className="flex flex-col md:flex-row items-center md:items-start gap-5">
          <div
            className="
              w-24
              h-24
              rounded-full
              bg-emerald-100
              text-emerald-700
              flex
              items-center
              justify-center
              text-3xl
              font-bold
              shrink-0
            "
          >
            {getInitial()}
          </div>

          <div className="flex-1 text-center md:text-right">
            <h2 className="text-xl font-bold text-gray-800">
              {trainee.full_name ?? "غير معروف"}
            </h2>

            <div className="flex items-center justify-center md:justify-start gap-2 mt-2 text-sm text-gray-500">
              <Mail size={16} />

              <span>
                {trainee.email ?? "لا يوجد بريد إلكتروني"}
              </span>
            </div>

            {goal?.title && (
              <div className="mt-4">
                <span className="inline-flex items-center gap-2 bg-emerald-50 text-emerald-700 px-3 py-1.5 rounded-full text-sm font-medium">
                  <Target size={15} />

                  {goal.title}
                </span>
              </div>
            )}
          </div>
        </div>
      </div>

      {/* Information */}
      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {/* Personal Information */}
        <div className="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
          <h3 className="font-bold text-gray-800 mb-5">
            المعلومات الشخصية
          </h3>

          <div className="space-y-4">
            <InfoRow
              icon={User}
              label="الاسم"
              value={trainee.full_name}
            />

            <InfoRow
              icon={Mail}
              label="البريد الإلكتروني"
              value={trainee.email}
            />

            <InfoRow
              icon={User}
              label="الجنس"
              value={genderLabel}
            />

            <InfoRow
              icon={CalendarDays}
              label="العمر"
              value={
                profile.age !== null &&
                profile.age !== undefined
                  ? `${profile.age} سنة`
                  : null
              }
            />

            <InfoRow
              icon={CalendarDays}
              label="تاريخ الميلاد"
              value={profile.date_of_birth}
            />
          </div>
        </div>

        {/* Fitness Information */}
        <div className="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
          <h3 className="font-bold text-gray-800 mb-5">
            المعلومات الرياضية
          </h3>

          <div className="space-y-4">
            <InfoRow
              icon={Target}
              label="الهدف الرياضي"
              value={goal?.title}
            />

            <InfoRow
              icon={Weight}
              label="الوزن"
              value={
                profile.weight
                  ? `${Number(profile.weight)} كجم`
                  : null
              }
            />

            <InfoRow
              icon={Ruler}
              label="الطول"
              value={
                profile.height
                  ? `${Number(profile.height)} سم`
                  : null
              }
            />

            <InfoRow
              icon={Dumbbell}
              label="مكان التدريب"
              value={
                trainingLocationLabel ??
                profile.training_location
              }
            />
          </div>
        </div>
      </div>

      {/* Available Days */}
      {Array.isArray(profile.available_days) &&
        profile.available_days.length > 0 && (
          <div className="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h3 className="font-bold text-gray-800 mb-4">
              أيام التدريب المتاحة
            </h3>

            <div className="flex flex-wrap gap-2">
              {profile.available_days.map((day) => (
                <span
                  key={day}
                  className="
                    bg-emerald-50
                    text-emerald-700
                    border
                    border-emerald-100
                    px-3
                    py-1.5
                    rounded-lg
                    text-sm
                  "
                >
                  {day}
                </span>
              ))}
            </div>
          </div>
        )}

      {/* Action */}
      <div className="flex justify-end">
        <button
          type="button"
          onClick={() =>
            navigate(
              `/dashboard/WorkoutPlan?trainee=${trainee.id}`,
            )
          }
          className="
            px-5
            py-3
            bg-green-700
            text-white
            rounded-xl
            text-sm
            font-medium
            hover:bg-green-800
            transition-colors
          "
        >
          عرض / تعديل الخطة
        </button>
      </div>
    </div>
  );
};

const InfoRow = ({
  icon: Icon,
  label,
  value,
}) => {
  return (
    <div className="flex items-center justify-between gap-4 border-b border-gray-50 pb-3 last:border-0">
      <div className="flex items-center gap-3">
        <div className="w-9 h-9 rounded-lg bg-gray-50 flex items-center justify-center">
          <Icon
            size={17}
            className="text-gray-500"
          />
        </div>

        <span className="text-sm text-gray-500">
          {label}
        </span>
      </div>

      <span className="text-sm font-medium text-gray-800">
        {value ?? "غير محدد"}
      </span>
    </div>
  );
};

export default TraineeProfile;