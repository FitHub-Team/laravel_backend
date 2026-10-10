import {
  useNavigate,
  useOutletContext,
} from "react-router-dom";

import {
  Users,
  UserPlus,
  Dumbbell,
  ClipboardList,
  Clock3,
  Check,
  X,
  Eye,
  Plus,
  ChevronLeft,
  LoaderCircle,
} from "lucide-react";

import Button from "../../components/common/Button";
import StatCard from "../../components/StatCard";

import useCoachDashboard from "../../hooks/useCoachDashboard";

const Dashboard = () => {
  const navigate = useNavigate();

  /*
   * نخلي بيانات المدرب الحالية كما هي
   * لأنها قادمة من DashboardLayout
   */
  const outletContext = useOutletContext();

  const dashboardData =
    outletContext?.dashboardData ?? null;

  const {
    pendingRequests,
    recentTrainees,
    statistics,
    loading,
    error,
    actionLoadingId,
    acceptRequest,
    rejectRequest,
  } = useCoachDashboard();

  /*
  |--------------------------------------------------------------------------
  | Helpers
  |--------------------------------------------------------------------------
  */

  const getTraineeFromSubscription = (subscription) => {
    return (
      subscription?.trainee ??
      subscription?.user ??
      null
    );
  };

  const getProfile = (trainee) => {
    return (
      trainee?.user_profile ??
      trainee?.profile ??
      {}
    );
  };

  const getGoalName = (trainee) => {
    const profile = getProfile(trainee);

    return (
      profile?.goal?.name ??
      profile?.goal?.title ??
      "غير محدد"
    );
  };

  const getPlanLabel = (workoutPlan) => {
    if (!workoutPlan) {
      return "بدون خطة";
    }

    switch (workoutPlan.status) {
      case "active":
        return "خطة نشطة";

      case "draft":
        return "مسودة";

      case "completed":
        return "مكتملة";

      case "cancelled":
        return "ملغاة";

      default:
        return "غير محدد";
    }
  };

  const getPlanClasses = (workoutPlan) => {
    if (!workoutPlan) {
      return "bg-gray-100 text-gray-500";
    }

    switch (workoutPlan.status) {
      case "active":
        return "bg-green-50 text-green-700";

      case "draft":
        return "bg-amber-50 text-amber-700";

      case "completed":
        return "bg-blue-50 text-blue-700";

      case "cancelled":
        return "bg-red-50 text-red-600";

      default:
        return "bg-gray-100 text-gray-500";
    }
  };

  /*
  |--------------------------------------------------------------------------
  | Loading
  |--------------------------------------------------------------------------
  */

  if (loading) {
    return (
      <div
        dir="rtl"
        className="min-h-[500px] flex items-center justify-center"
      >
        <div className="text-center">
          <LoaderCircle
            size={34}
            className="animate-spin text-green-700 mx-auto"
          />

          <p className="text-sm text-gray-500 mt-4">
            جاري تحميل لوحة التحكم...
          </p>
        </div>
      </div>
    );
  }

  return (
    <div
      dir="rtl"
      className="min-h-screen bg-[#F8FAF8] px-5 md:px-7 lg:px-10 py-7"
    >
      <div className="max-w-[1500px] mx-auto space-y-7">

        {/* =====================================================
            Header
        ====================================================== */}

        <section className="flex flex-col lg:flex-row lg:items-center justify-between gap-5">
          <div>
            <p className="text-sm font-medium text-green-700 mb-2">
              لوحة التحكم
            </p>

            <h1 className="text-2xl md:text-3xl font-bold text-gray-900">
              مرحباً، كابتن{" "}
              {dashboardData?.coach?.full_name ??
                "المدرب"}
            </h1>

            <p className="text-sm text-gray-500 mt-2">
              تابع طلبات الاشتراك والمشتركين والخطط
              التدريبية من مكان واحد.
            </p>
          </div>

          <Button
            title="إنشاء خطة"
            Icon={Plus}
            color="bg-[#407437] text-white hover:bg-[#35652f] rounded-xl"
            onClick={() =>
              navigate("/dashboard/WorkoutPlan")
            }
          />
        </section>

        {/* =====================================================
            Error
        ====================================================== */}

        {error && (
          <div className="bg-red-50 border border-red-100 rounded-xl px-4 py-3">
            <p className="text-sm text-red-600">
              {error}
            </p>
          </div>
        )}

        {/* =====================================================
            Statistics
        ====================================================== */}

        <section>
          <div className="mb-4">
            <h2 className="font-bold text-gray-900">
              نظرة عامة
            </h2>

            <p className="text-xs text-gray-400 mt-1">
              ملخص مباشر من بيانات المشتركين والخطط
            </p>
          </div>

          <div className="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
            <StatCard
              title="إجمالي المشتركين"
              value={statistics.totalTrainees}
              icon={Users}
              color="bg-green-50 text-green-700"
              textColor="text-gray-500"
              note="مشتركون مقبولون"
              NoteColor="bg-green-50 text-green-700"
              subtitle="إجمالي المشتركين الحاليين"
            />

            <StatCard
              title="طلبات معلقة"
              value={statistics.pendingCount}
              icon={Clock3}
              color="bg-amber-50 text-amber-600"
              textColor="text-gray-500"
              note="تحتاج مراجعة"
              NoteColor="bg-amber-50 text-amber-700"
              subtitle="طلبات اشتراك بانتظار قرارك"
            />

            <StatCard
              title="خطط نشطة"
              value={statistics.activePlans}
              icon={ClipboardList}
              color="bg-blue-50 text-blue-600"
              textColor="text-gray-500"
              note="قيد التنفيذ"
              NoteColor="bg-blue-50 text-blue-600"
              subtitle="خطط تدريب حالتها نشطة"
            />

            <StatCard
              title="بدون خطة"
              value={statistics.withoutPlan}
              icon={Dumbbell}
              color="bg-red-50 text-red-500"
              textColor="text-gray-500"
              note="تحتاج إعداد"
              NoteColor="bg-red-50 text-red-600"
              subtitle="مشتركون بدون خطة تدريب"
            />
          </div>
        </section>

        {/* =====================================================
            Main Content
        ====================================================== */}

        <div className="grid grid-cols-1 xl:grid-cols-2 gap-5">

          {/* =================================================
              Pending Requests
          ================================================== */}

          <section className="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
            <div className="flex items-center justify-between px-5 py-4 border-b border-gray-100">
              <div className="flex items-center gap-3">
                <div className="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                  <UserPlus size={19} />
                </div>

                <div>
                  <h2 className="font-bold text-gray-900">
                    طلبات الاشتراك
                  </h2>

                  <p className="text-xs text-gray-400 mt-1">
                    الطلبات التي تنتظر قبولك أو رفضك
                  </p>
                </div>
              </div>

              <span className="bg-amber-50 text-amber-700 text-xs font-bold px-2.5 py-1 rounded-full">
                {statistics.pendingCount}
              </span>
            </div>

            {pendingRequests.length === 0 ? (
              <EmptyState
                icon={UserPlus}
                title="لا توجد طلبات معلقة"
                description="ستظهر طلبات الاشتراك الجديدة هنا."
              />
            ) : (
              <div className="divide-y divide-gray-100">
                {pendingRequests
                  .slice(0, 4)
                  .map((request) => {
                    const trainee =
                      getTraineeFromSubscription(
                        request,
                      );

                    const name =
                      trainee?.full_name ??
                      "مشترك جديد";

                    const email =
                      trainee?.email ??
                      "لا يوجد بريد";

                    const goal =
                      getGoalName(trainee);

                    const isActionLoading =
                      actionLoadingId === request.id;

                    return (
                      <div
                        key={request.id}
                        className="p-4 hover:bg-gray-50/60 transition-colors"
                      >
                        <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">

                          {/* User */}

                          <div className="flex items-center gap-3 min-w-0">
                            <Avatar name={name} />

                            <div className="min-w-0">
                              <h3 className="text-sm font-bold text-gray-900 truncate">
                                {name}
                              </h3>

                              <p className="text-xs text-gray-400 mt-1 truncate">
                                {email}
                              </p>

                              <div className="mt-2">
                                <span className="text-[11px] bg-green-50 text-green-700 px-2 py-1 rounded-md">
                                  الهدف: {goal}
                                </span>
                              </div>
                            </div>
                          </div>

                          {/* Actions */}

                          <div className="flex items-center gap-2">
                            <button
                              type="button"
                              disabled={
                                isActionLoading
                              }
                              onClick={() =>
                                acceptRequest(
                                  request.id,
                                )
                              }
                              title="قبول الطلب"
                              className="
                                h-9
                                px-3
                                rounded-lg
                                bg-green-700
                                text-white
                                text-xs
                                font-semibold
                                flex
                                items-center
                                gap-1.5
                                hover:bg-green-800
                                disabled:opacity-50
                                disabled:cursor-not-allowed
                              "
                            >
                              {isActionLoading ? (
                                <LoaderCircle
                                  size={14}
                                  className="animate-spin"
                                />
                              ) : (
                                <Check size={14} />
                              )}

                              قبول
                            </button>

                            <button
                              type="button"
                              disabled={
                                isActionLoading
                              }
                              onClick={() =>
                                rejectRequest(
                                  request.id,
                                )
                              }
                              title="رفض الطلب"
                              className="
                                h-9
                                px-3
                                rounded-lg
                                bg-red-50
                                text-red-600
                                text-xs
                                font-semibold
                                flex
                                items-center
                                gap-1.5
                                hover:bg-red-100
                                disabled:opacity-50
                                disabled:cursor-not-allowed
                              "
                            >
                              <X size={14} />

                              رفض
                            </button>
                          </div>
                        </div>
                      </div>
                    );
                  })}
              </div>
            )}
          </section>

          {/* =================================================
              Recent Trainees
          ================================================== */}

          <section className="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
            <div className="flex items-center justify-between px-5 py-4 border-b border-gray-100">
              <div className="flex items-center gap-3">
                <div className="w-10 h-10 rounded-xl bg-green-50 text-green-700 flex items-center justify-center">
                  <Users size={19} />
                </div>

                <div>
                  <h2 className="font-bold text-gray-900">
                    المشتركين
                  </h2>

                  <p className="text-xs text-gray-400 mt-1">
                    وصول سريع لأحدث المشتركين
                  </p>
                </div>
              </div>

              <button
                type="button"
                onClick={() =>
                  navigate("/dashboard/trainees")
                }
                className="flex items-center gap-1 text-xs font-medium text-green-700 hover:text-green-800"
              >
                عرض الكل
                <ChevronLeft size={14} />
              </button>
            </div>

            {recentTrainees.length === 0 ? (
              <EmptyState
                icon={Users}
                title="لا يوجد مشتركون"
                description="سيظهر المشتركون المقبولون هنا."
              />
            ) : (
              <div className="divide-y divide-gray-100">
                {recentTrainees.map(
                  (subscription) => {
                    const trainee =
                      subscription?.trainee;

                    if (!trainee) {
                      return null;
                    }

                    const plan =
                      subscription?.workout_plan;

                    const goal =
                      getGoalName(trainee);

                    return (
                      <div
                        key={
                          subscription.id ??
                          trainee.id
                        }
                        className="p-4 hover:bg-gray-50/60 transition-colors"
                      >
                        <div className="flex items-center justify-between gap-4">

                          <div className="flex items-center gap-3 min-w-0">
                            <Avatar
                              name={
                                trainee.full_name
                              }
                            />

                            <div className="min-w-0">
                              <h3 className="font-bold text-sm text-gray-900 truncate">
                                {trainee.full_name ??
                                  "غير معروف"}
                              </h3>

                              <p className="text-xs text-gray-400 mt-1 truncate">
                                {trainee.email ??
                                  "لا يوجد بريد"}
                              </p>

                              <div className="flex flex-wrap items-center gap-2 mt-2">
                                <span className="text-[11px] text-gray-500">
                                  {goal}
                                </span>

                                <span
                                  className={`text-[11px] px-2 py-1 rounded-md font-medium ${getPlanClasses(
                                    plan,
                                  )}`}
                                >
                                  {getPlanLabel(
                                    plan,
                                  )}
                                </span>
                              </div>
                            </div>
                          </div>

                          <div className="flex items-center gap-2 shrink-0">
                            <button
                              type="button"
                              onClick={() =>
                                navigate(
                                  `/dashboard/trainees/${trainee.id}`,
                                )
                              }
                              title="عرض الملف"
                              className="w-9 h-9 rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50 flex items-center justify-center"
                            >
                              <Eye size={16} />
                            </button>

                            <button
                              type="button"
                              onClick={() =>
                                navigate(
                                  `/dashboard/WorkoutPlan?trainee=${trainee.id}`,
                                )
                              }
                              title="عرض الخطة"
                              className="h-9 px-3 rounded-lg bg-green-50 text-green-700 hover:bg-green-100 text-xs font-semibold"
                            >
                              الخطة
                            </button>
                          </div>
                        </div>
                      </div>
                    );
                  },
                )}
              </div>
            )}
          </section>
        </div>
      </div>
    </div>
  );
};

/*
|--------------------------------------------------------------------------
| Avatar
|--------------------------------------------------------------------------
*/

const Avatar = ({ name }) => {
  return (
    <div className="w-11 h-11 shrink-0 rounded-full bg-green-100 text-green-700 flex items-center justify-center font-bold">
      {name?.charAt(0)?.toUpperCase() ?? "؟"}
    </div>
  );
};

/*
|--------------------------------------------------------------------------
| Empty State
|--------------------------------------------------------------------------
*/

const EmptyState = ({
  icon: Icon,
  title,
  description,
}) => {
  return (
    <div className="min-h-[220px] flex items-center justify-center p-6">
      <div className="text-center">
        <div className="w-12 h-12 mx-auto rounded-xl bg-gray-50 text-gray-400 flex items-center justify-center">
          <Icon size={21} />
        </div>

        <h3 className="text-sm font-bold text-gray-800 mt-4">
          {title}
        </h3>

        <p className="text-xs text-gray-400 mt-2">
          {description}
        </p>
      </div>
    </div>
  );
};

export default Dashboard;