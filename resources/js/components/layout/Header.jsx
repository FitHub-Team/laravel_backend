import { Plus, Bell, Search, Calendar } from "lucide-react";
import Button from "../common/Button";
import NotificationBell from "./NotificationBell";
import profileImage from "../../assets/profile-avatar.jpg";

function Header({ dashboardData ,navigate }) {
  return (
    <header className="flex flex-col m-5 md:flex-row justify-between items-start md:items-center mb-8 gap-4 lg:mr-64 px-7">
      <div className="flex items-center gap-4 w-full md:w-auto">
        <div className="relative w-full md:w-96">
          <Search
            className="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400"
            size={18}
          />
          <input
            type="text"
            placeholder="ابحث عن مشترك، هدفك، أو بريد..."
            className="w-full bg-gray-100 border-none rounded-xl py-2.5 pr-10 pl-4 text-sm focus:ring-2 focus:ring-green-500 outline-none"
          />
        </div>
      </div>

      <div className="flex items-center gap-3 w-full md:w-auto justify-end">
        <div
          className="hidden md:flex items-center gap-2 text-gray-500 text-sm
         bg-white px-3 py-2 rounded-lg border border-gray-200 whitespace-nowrap"
        >
          <Calendar size={16} />
          <span>
            {new Date().toLocaleDateString("ar-EG", {
              weekday: "long",
              day: "numeric",
              month: "long",
              year: "numeric",
            })}
          </span>
        </div>
        <Button
          title="خطة جديدة"
          Icon={Plus}
          color="bg-[#407437] text-white"
          onClick={() => navigate("/dashboard/WorkoutPlan")}
        />
      

        <NotificationBell />

        <div className="w-12 h-12 shrink-0 rounded-full border-2 border-green-500 overflow-hidden">
          <img
            src={dashboardData?.coach?.profile_photo ?? profileImage}
            alt="User"
            className="w-full h-full object-cover"
          />
        </div>
      </div>
    </header>
  );
}

export default Header;
