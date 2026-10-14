import { useNavigate } from "react-router-dom";
import { SquarePen, ArrowUpDown } from "lucide-react";

import Button from "../common/Button";

const SubscribersList = ({ trainees, loading }) => {
  const navigate = useNavigate();

  const goToWorkoutPlan = (traineeId) => {
    navigate(`/dashboard/WorkoutPlan?trainee=${traineeId}`);
  };

  return (
    <div className="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 overflow-x-auto">
      <table className="w-full text-right border-collapse">
        <thead>
          <tr className="border-b border-gray-100 text-gray-400 text-xs font-semibold uppercase">
            <th className="py-3 px-4">اسم المشترك</th>

            <th className="py-3 px-4">الهدف الرياضي</th>

            <th className="py-3 px-4">
              <div className="flex gap-1">
                <p>نسبة الالتزام</p>
                <ArrowUpDown size={16} />
              </div>
            </th>

            <th className="py-3 px-4">آخر نشاط</th>

            <th className="py-3 px-4">حالة الخطة</th>

            <th className="py-3 px-4 text-center">الإجراءات</th>
          </tr>
        </thead>

        <tbody className="divide-y divide-gray-50 text-sm text-gray-600">
          {loading ? (
            <tr>
              <td
                colSpan="6"
                className="text-center py-8 text-gray-500"
              >
                جاري تحميل المتدربين...
              </td>
            </tr>
          ) : trainees.length === 0 ? (
            <tr>
              <td
                colSpan="6"
                className="text-center py-8 text-gray-500"
              >
                لا يوجد متدربين حاليًا
              </td>
            </tr>
          ) : (
            trainees.map((subscription) => {
              const trainee = subscription.trainee ?? {};

              return (
                <tr
                  key={subscription.id}
                  className="hover:bg-gray-50/50 transition-colors"
                >
                  {/* Trainee */}
                  <td className="py-4 px-4">
                    <div className="flex items-center gap-3">
                      <span className="w-9 h-9 rounded-full bg-emerald-100 text-emerald-700 font-bold flex items-center justify-center text-sm">
                        {trainee.full_name
                          ? trainee.full_name.charAt(0)
                          : "؟"}
                      </span>

                      <div>
                        <div className="font-semibold text-gray-800">
                          {trainee.full_name ?? "غير معروف"}
                        </div>

                        <div className="text-xs text-gray-400">
                          {trainee.email ?? "لا يوجد بريد"}
                        </div>
                      </div>
                    </div>
                  </td>

                  {/* Goal */}
                  <td className="py-4 px-4">
                    <span className="bg-emerald-50 text-emerald-600 px-3 py-1 rounded-full text-xs font-medium">
                      {trainee.user_profile?.goal?.title ?? "غير محدد"}
                    </span>
                  </td>

                  {/* Progress */}
                  <td className="py-4 px-4">
                    <div className="flex items-center gap-2">
                      <span className="text-xs text-gray-500">
                        {subscription.commitment_percentage ?? 0}%
                      </span>

                      <div className="w-24 bg-gray-100 h-2 rounded-full overflow-hidden">
                        <div
                          className="h-full rounded-full bg-[#407437]"
                          style={{
                            width: `${
                              subscription.commitment_percentage ?? 0
                            }%`,
                          }}
                        />
                      </div>
                    </div>
                  </td>

                  {/* Last Activity */}
                  <td className="py-4 px-4">
                    <span className="text-gray-400">
                      {subscription.last_activity ?? "لا يوجد نشاط"}
                    </span>
                  </td>

                  {/* Workout Plan Status */}
                  <td className="py-4 px-4">
                    <span
                      className={`px-3 py-1 rounded-full text-xs font-medium ${
                        subscription.workout_plan?.status === "active"
                          ? "bg-emerald-50 text-emerald-600"
                          : subscription.workout_plan?.status === "draft"
                            ? "bg-amber-50 text-amber-600"
                            : subscription.workout_plan?.status ===
                                "completed"
                              ? "bg-blue-50 text-blue-600"
                              : "bg-gray-50 text-gray-500"
                      }`}
                    >
                      {subscription.workout_plan?.status === "active"
                        ? "نشطة"
                        : subscription.workout_plan?.status === "draft"
                          ? "مسودة"
                          : subscription.workout_plan?.status ===
                              "completed"
                            ? "مكتملة"
                            : "لا توجد خطة"}
                    </span>
                  </td>

                  {/* Actions */}
                  <td className="py-4 px-4 text-center">
                    <div className="flex justify-center items-center">
                      <Button
                        color="bg-green-100 text-green-600"
                        title="عرض الملف"
                       onClick={() =>
  navigate(`/dashboard/trainees/${trainee.id}`)
}
                      />

                      <Button
                        Icon={SquarePen}
                        color="text-green-700"
                        onClick={() => goToWorkoutPlan(trainee.id)}
                      />
                    </div>
                  </td>
                </tr>
              );
            })
          )}
        </tbody>
      </table>
    </div>
  );
};

export default SubscribersList;