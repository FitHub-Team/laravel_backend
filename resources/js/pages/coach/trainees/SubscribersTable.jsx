import { useNavigate } from "react-router-dom";
import { Plus } from "lucide-react";

import Button from "../../../components/common/Button";
import FilterTabs from "../../../components/filter/FilterTabs";
import SubscribersList from "../../../components/subscribers/SubscribersList";

import useTrainees from "../../../hooks/useTrainees";

const SubscribersTable = () => {
  const navigate = useNavigate();

  const {
    trainees,
    loading,
    error,
    search,
    setSearch,
    submitSearch,
  } = useTrainees();

  if (error) {
    return (
      <div className="w-full bg-white rounded-2xl border border-red-100 p-8 text-center">
        <p className="text-red-500 font-medium">{error}</p>
      </div>
    );
  }

  return (
    <div className="flex flex-col gap-6 w-full py-5 px-6">
      {/* Page Header */}

      <div className="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
          <h1 className="text-2xl font-bold text-gray-800">
            المشتركون
          </h1>

          <p className="text-sm text-gray-500 mt-1">
            إدارة المشتركين، متابعة البرنامج اليومي، ومراجعة الخطط
            التدريبية والغذائية.
          </p>
        </div>

        <Button
          title="إضافة خطة مشتركة"
          Icon={Plus}
          color="bg-[#407437] text-white"
          onClick={() => navigate("/dashboard/workout-plan")}
        />
      </div>

      {/* Search */}

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
                submitSearch();
              }
            }}
          />
        </div>

        <div className="flex-1 flex md:justify-end overflow-x-auto [&::-webkit-scrollbar]:hidden">
          <FilterTabs />
        </div>
      </div>

      <SubscribersList
        trainees={trainees}
        loading={loading}
      />
    </div>
  );
};

export default SubscribersTable;