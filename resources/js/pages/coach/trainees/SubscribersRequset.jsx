import { useEffect, useState } from "react";
import api from "../../../services/api";
import { Check, X } from "lucide-react";

const SubscribersRequset = () => {
  // =========================
  // State
  // =========================
  const [traineesRequest, setTraineesRequest] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState("");
  // search
  const [search, setSearch] = useState("");
  const [submittedSearch, setSubmittedSearch] = useState("");

  // =========================
  // Get pending requests
  // =========================
  useEffect(() => {
    const getTraineesRequest = async () => {
      try {
        const response = await api.get("/coach/subscriptions/pending");

        console.log("Pending requests:", response.data);

        setTraineesRequest(response.data?.data ?? []);
      } catch (error) {
        console.error("Error loading requests:", error);

        setError("حدث خطأ أثناء تحميل طلبات الاشتراك");
      } finally {
        setLoading(false);
      }
    };

    getTraineesRequest();
  }, [setSubmittedSearch]);

  // =========================
  // Error
  // =========================
  if (error) {
    return (
      <div className="w-full bg-white rounded-2xl border border-red-100 p-8 text-center">
        <p className="text-red-500 font-medium">{error}</p>
      </div>
    );
  }
  // قبول الاشتراك
  const handleAccept = async (subscriptionId) => {
    try {
      await api.put(`/coach/subscriptions/${subscriptionId}/accept`);

      // حذف الطلب من القائمة بعد قبوله
      setTraineesRequest((prev) =>
        prev.filter((subscription) => subscription.id !== subscriptionId),
      );
    } catch (error) {
      console.error("Accept error:", error);
      setError("حدث خطأ أثناء قبول طلب الاشتراك");
    }
  };
  // رفض الاشتراك
  const handleReject = async (subscriptionId) => {
    try {
      await api.put(`/coach/subscriptions/${subscriptionId}/reject`);

      // حذف الطلب من القائمة بعد رفضه
      setTraineesRequest((prev) =>
        prev.filter((subscription) => subscription.id !== subscriptionId),
      );
    } catch (error) {
      console.error("Reject error:", error);
      setError("حدث خطأ أثناء رفض طلب الاشتراك");
    }
  };

  return (
    <div className="flex flex-col gap-6 w-full py-5 px-6">
      {/* =========================
          Page Header
      ========================= */}
      <div>
        <h1 className="text-2xl font-bold text-gray-800">طلبات الاشتراك</h1>

        <p className="text-sm text-gray-500 mt-1">
          مراجعة طلبات الاشتراك الواردة من المتدربين وقبولها أو رفضها.
        </p>
      </div>

      {/* =========================
          Search
      ========================= */}
      <div className="flex items-center gap-3 bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
        <div className="flex-1 relative">
          <input
            type="text"
            placeholder="بحث باسم المشترك أو بريده الإلكتروني..."
            className="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 text-sm text-right focus:outline-none focus:border-emerald-500 transition-colors"
            value={search}
            onChange={(e) => setSearch(e.target.value)}
            onKeyDown={(e) => {
              if (e.key === "Enter") {
                setSubmittedSearch(search);
              }
            }}
          />
        </div>
      </div>

      {/* =========================
          Table
      ========================= */}
      <div className="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 overflow-x-auto">
        <table className="w-full text-right border-collapse">
          {/* Header */}
          <thead>
            <tr className="border-b border-gray-100 text-gray-400 text-xs font-semibold">
              <th className="py-3 px-4">اسم المتدرب</th>

              <th className="py-3 px-4">البريد الإلكتروني</th>

              <th className="py-3 px-4">الهدف الرياضي</th>

              <th className="py-3 px-4">حالة الطلب</th>

              <th className="py-3 px-4 text-center">الإجراءات</th>
            </tr>
          </thead>

          {/* Body */}
          <tbody className="divide-y divide-gray-50 text-sm text-gray-600">
            {/* Loading */}
            {loading ? (
              <tr>
                <td colSpan="5" className="text-center py-8 text-gray-500">
                  جاري تحميل طلبات الاشتراك...
                </td>
              </tr>
            ) : traineesRequest.length === 0 ? (
              /* Empty */
              <tr>
                <td colSpan="5" className="text-center py-8 text-gray-500">
                  لا يوجد طلبات اشتراك حاليًا
                </td>
              </tr>
            ) : (
              /* Requests */
              traineesRequest.map((subscription) => {
                const trainee = subscription.trainee ?? {};

                return (
                  <tr
                    key={subscription.id}
                    className="hover:bg-gray-50/50 transition-colors"
                  >
                    {/* =========================
                        Trainee Name
                    ========================= */}
                    <td className="py-4 px-4">
                      <div className="flex items-center gap-3">
                        <span className="w-9 h-9 rounded-full bg-amber-100 text-amber-700 font-bold flex items-center justify-center text-sm">
                          {trainee.full_name
                            ? trainee.full_name.charAt(0)
                            : "؟"}
                        </span>

                        <div>
                          <div className="font-semibold text-gray-800">
                            {trainee.full_name ?? "غير معروف"}
                          </div>
                        </div>
                      </div>
                    </td>

                    {/* =========================
                        Email
                    ========================= */}
                    <td className="py-4 px-4">
                      <span className="text-gray-500">
                        {trainee.email ?? "لا يوجد بريد"}
                      </span>
                    </td>

                    {/* =========================
                        Goal
                    ========================= */}
                    <td className="py-4 px-4">
                      <span className="bg-emerald-50 text-emerald-600 px-3 py-1 rounded-full text-xs font-medium">
                        {trainee.user_profile?.goal?.title ?? "غير محدد"}
                      </span>
                    </td>

                    {/* =========================
                        Status
                    ========================= */}
                    <td className="py-4 px-4">
                      <span className="px-3 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-600">
                        قيد الانتظار
                      </span>
                    </td>

                    {/* =========================
                        Actions
                    ========================= */}
                    <td className="py-4 px-4">
                      <div className="flex items-center justify-center gap-2">
                        {/* Accept */}
                        <button
                          type="button"
                          onClick={() => handleAccept(subscription.id)}
                          className="flex items-center gap-1 px-3 py-2 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-100 transition-colors"
                        >
                          <Check size={16} />
                          قبول
                        </button>

                        {/* Reject */}
                        <button
                          type="button"
                          onClick={() => handleReject(subscription.id)}
                          className="flex items-center gap-1 px-3 py-2 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 transition-colors"
                        >
                          <X size={16} />
                          رفض
                        </button>
                      </div>
                    </td>
                  </tr>
                );
              })
            )}
          </tbody>
        </table>
      </div>
    </div>
  );
};

export default SubscribersRequset;
