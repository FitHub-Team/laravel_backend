import NavItem from "./NavItem";
import { useNavigate, useLocation } from "react-router-dom";

import {
    Home,
    Users,
    FileText,
    Bookmark,
    MessageSquare,
    Bell,
    LogOut,
    User,
    Settings,Dumbbell
} from "lucide-react";

import logo from "../../assets/logo-dashboard.png";
import profileImage from "../../assets/profile-avatar.jpg";

function Sidebar({ dashboardData }) {
    const navigate = useNavigate();
    const location = useLocation();

    const handleLogout = () => {
        localStorage.removeItem("token");
        localStorage.removeItem("user");
        localStorage.setItem("flashMessage", "تم تسجيل الخروج بنجاح ..");
        navigate("/login", { replace: true });
    };

    return (
        <aside className="fixed top-0 right-0 w-64 h-screen bg-white border-l border-gray-200 flex flex-col hidden lg:flex rtl">
            <div className="flex-1 overflow-y-auto sidebar-scroll">
                {/* Header */}
                <div className="w-full flex items-center justify-between px-5 py-4 border-b border-[#F1F5F9] shrink-0">
                    {/* اللوجو + النص */}
                    <div className="flex items-center gap-3">
                        <img
                            src={logo}
                            alt="Logo"
                            className="w-[45px] h-[45px] object-contain"
                        />

                        <div className="flex flex-col items-start gap-1">
                            <span className="text-[10px] font-bold text-green-600 bg-green-50 px-2 py-0.5 rounded-full">
                                COACH
                            </span>

                            <h3 className="font-bold text-[#1A4762] text-[13px] leading-tight whitespace-nowrap">
                                لوحة تحكم المدرب
                            </h3>
                        </div>
                    </div>
                </div>

                {/* Navigation */}
                <nav className="p-4 space-y-2">
                    {/* Profile Card */}
                    <div className="p-4 flex items-center justify-between rounded-xl border border-gray-100 bg-[#F8F9FA]">
                        <div className="flex items-center gap-3 w-full">
                            <div className="w-12 h-12 shrink-0 rounded-full border-2 border-green-700 overflow-hidden">
                                <img
                                    src={
                                        dashboardData?.coach?.profile_photo ??
                                        profileImage
                                    }
                                    alt="User"
                                    className="w-full h-full object-cover"
                                />
                            </div>

                            <div className="flex items-center gap-2 flex-1 min-w-0">
                                <h3 className="font-bold text-[#343A40] text-sm flex-1 ">
                                    كابتن {dashboardData?.coach?.full_name}
                                </h3>

                                <span className="shrink-0 text-[10px] text-green-600 bg-green-50 px-2 py-0.5 rounded-full">
                                    نشط
                                </span>
                            </div>
                        </div>
                    </div>

                    <NavItem
                        icon={<Home size={20} />}
                        label="الرئيسية"
                        to="/dashboard"
                        active={location.pathname === "/dashboard"}
                    />

                    <NavItem
                        icon={<Users size={20} />}
                        label="المشتركون"
                        to="/dashboard/Subscribers"
                        active={location.pathname === "/dashboard/Subscribers"}
                    />

                    <NavItem
                        icon={<Users size={20} />}
                        label="طلبات الاشتراك"
                        to="/dashboard/Subscribers-Request"
                        active={
                            location.pathname ===
                            "/dashboard/Subscribers-Request"
                        }
                    />

                    <NavItem
                        icon={<FileText size={20} />}
                        label="الخطط"
                        to="/dashboard/workoutPlan"
                        active={location.pathname === "/dashboard/workoutPlan"}
                    />
                    <NavItem
                        to="/dashboard/exercises"
                        icon={<Dumbbell size={20} />} // استخدم نفس نوع الأيقونات المستخدمة في باقي العناصر
                        label="تماريني الخاصة"
                        active={location.pathname === "/dashboard/exercises"}
                    />
                   

                    {/* <NavItem icon={<Bookmark size={20} />} label="الحزم" /> */}

                    <NavItem
                        icon={<Bell size={20} />}
                        label="الإشعارات"
                        badge="1"
                        badgeColor="bg-red-500"
                    />

                    <NavItem
                        icon={<User size={20} />}
                        to="/dashboard/coach/profile"
                        label="الملف الشخصي"
                        active={
                            location.pathname === "/dashboard/coach/profile"
                        }
                    />

                    <NavItem icon={<Settings size={20} />} label="الإعدادات" />

                    <NavItem
                        icon={<LogOut size={20} />}
                        label="تسجيل الخروج"
                        logoutColor="text-red-500"
                        onClick={handleLogout}
                    />
                </nav>
            </div>

            {/* Footer Progress */}
            <div className="p-4 border border-[#7FA279] rounded-lg m-4">
                <div className="flex justify-between text-xs text-gray-500 mb-2">
                    <span className="font-bold text-[#184159] text-[12px] leading-[16px] text-right">
                        طاقة المشتركين
                    </span>
                    <span>28 / 35</span>
                </div>

                <div className="w-full bg-gray-200 rounded-full h-2">
                    <div
                        className="bg-[#407437] h-2 rounded-full"
                        style={{ width: "80%" }}
                    />
                </div>

                <p className="text-xs text-gray-400 mt-2 text-center">
                    متبقي 7 مقاعد لاستقبال مشتركين جدد
                </p>
            </div>
        </aside>
    );
}

export default Sidebar;
