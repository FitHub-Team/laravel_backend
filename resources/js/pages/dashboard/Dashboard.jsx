import { useEffect, useState } from "react";
import { useOutletContext } from "react-router-dom";
import api from "../../services/api";
import {
    Users,
    FileText,
    CirclePlus,
    UserCheck,
    TriangleAlert,
    FileEdit,
    ChevronLeft,
    Clock,
} from "lucide-react";

import Button from "../../components/common/Button";
import StatCard from "../../components/StatCard";
import ClientCard from "../../components/ClientCard";
import ActivityCient from "../../components/ActivityCient";

const Dashboard = () => {
    const { dashboardData, loading, error } = useOutletContext();

    return (
        <div className="min-h-screen bg-[#F8FAFC] px-6 lg:px-10 py-8 text-right">
            {/* ===== Welcome + Actions ===== */}
            <div className="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-6">
                <div>
                    <h1 className="text-2xl lg:text-3xl font-bold text-[#1E293B] mb-2 tracking-tight">
                        مرحباً، كابتن {dashboardData?.coach?.full_name} 👋
                    </h1>

                    <p className="text-[#94A3B8] text-sm leading-relaxed">
                        إليك ملخص أداء مشتركينك اليوم
                    </p>
                </div>

                <div className="flex gap-3">
                    <Button
                        title="إضافة خطة"
                        Icon={CirclePlus}
                        color=" bg-[#407437] text-white  hover:bg-green-600  shadow-sm shadow-emerald-200/50 rounded-xl transition-all"
                    />

                    <Button
                        title="إضافة مشترك"
                        Icon={Users}
                        color="bg-white hover:bg-gray-50 text-[#184159] border border-gray-200 rounded-xl transition-all"
                    />
                </div>
            </div>

            {/* ===== Statistics ===== */}
            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-10">
                <StatCard
                    title="عدد المشتركين"
                    value={dashboardData?.subscriptions_count ?? 0}
                    icon={<Users size={18} />}
                    color="bg-slate-50 text-slate-500"
                    textColor="text-slate-500"
                    note="+4 هذا الاسبوع"
                    NoteColor="bg-emerald-50 text-emerald-600"
                    subtitle="السعة المتبقية: 10 مقاعد"
                />

                <StatCard
                    title="المشتركون النشطون"
                    value="5"
                    icon={<UserCheck size={18} />}
                    color="bg-sky-50 text-sky-500"
                    textColor="text-slate-500"
                    note="63% التزام ممتاز"
                    NoteColor="bg-emerald-50 text-emerald-600"
                    subtitle="سجلوا نشاطاً آخر 48 ساعة"
                />

                <StatCard
                    title="يحتاجون إلى متابعة"
                    value="5"
                    icon={<TriangleAlert size={18} />}
                    color="bg-amber-50 text-amber-500"
                    textColor="text-amber-500"
                    note="يتطلب تدخلاً"
                    NoteColor="bg-amber-50 text-amber-600"
                    subtitle="غياب عن تمارين أو انخفاض الالتزام"
                />

                <StatCard
                    title="خطط تحتاج إلى تحديث"
                    value="5"
                    icon={<FileEdit size={18} />}
                    color="bg-blue-50 text-blue-500"
                    textColor="text-slate-500"
                    note="مراجعة أسبوعية"
                    NoteColor="bg-emerald-50 text-emerald-600"
                    subtitle="مشتركون أكملوا مراحلهم الحالية"
                />
            </div>

            {/* ===== Bottom Sections ===== */}
            <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {/* ----- Clients Needing Attention ----- */}
                <section className="bg-white rounded-2xl border border-slate-100 shadow-sm shadow-slate-100/60 p-5">
                    {/* Header */}
                    <div className="flex items-center justify-between mb-5">
                        <div className="flex items-center gap-2.5">
                            <span className="bg-amber-400 w-2.5 h-2.5 rounded-full ring-4 ring-amber-100" />

                            <h2 className="text-[#334155] font-bold text-base">
                                يحتاج إلى انتباهك
                            </h2>

                            <span className="bg-amber-50 text-amber-700 font-semibold rounded-full px-2.5 py-0.5 text-xs">
                                5
                            </span>
                        </div>

                        <a
                            href="#"
                            className="flex items-center gap-1 text-xs text-emerald-600 hover:text-emerald-700 font-medium transition-colors"
                        >
                            عرض كل المشتركين
                            <ChevronLeft size={15} />
                        </a>
                    </div>

                    {/* Body */}
                    <div className="flex flex-col gap-3">
                        <ClientCard
                            name="محمد العتيبي"
                            image="/images/client.jpg"
                            progress={30}
                            status="سجل وزنه أمس (88.2 كجم)"
                            action="سجل وزنه أمس (88.2 كجم)"
                            statusColor="bg-emerald-50 text-emerald-600"
                            reason="تغيب عن 3 تمارين متتالية وانخفض معدل استهلاك الماء"
                            timeAgo="منذ ساعتين"
                        />

                        <ClientCard
                            name="محمد العتيبي"
                            image="/images/client.jpg"
                            progress={30}
                            status="سجل وزنه أمس (88.2 كجم)"
                            action="سجل وزنه أمس (88.2 كجم)"
                            statusColor="bg-emerald-50 text-emerald-600"
                            reason="تغيب عن 3 تمارين متتالية وانخفض معدل استهلاك الماء"
                            timeAgo="منذ ساعتين"
                        />

                        <ClientCard
                            name="محمد العتيبي"
                            image="/images/client.jpg"
                            progress={30}
                            status="سجل وزنه أمس (88.2 كجم)"
                            action="سجل وزنه أمس (88.2 كجم)"
                            statusColor="bg-emerald-50 text-emerald-600"
                            reason="تغيب عن 3 تمارين متتالية وانخفض معدل استهلاك الماء"
                            timeAgo="منذ ساعتين"
                        />

                        <ClientCard
                            name="محمد العتيبي"
                            image="/images/client.jpg"
                            progress={30}
                            status="سجل وزنه أمس (88.2 كجم)"
                            action="سجل وزنه أمس (88.2 كجم)"
                            statusColor="bg-emerald-50 text-emerald-600"
                            reason="تغيب عن 3 تمارين متتالية وانخفض معدل استهلاك الماء"
                            timeAgo="منذ ساعتين"
                        />
                    </div>
                </section>

                {/* ----- Recent Activities ----- */}
                <section className="bg-white rounded-2xl border border-slate-100 shadow-sm shadow-slate-100/60 p-5">
                    {/* Header */}
                    <div className="flex items-center justify-between mb-5">
                        <div className="flex items-center gap-2.5">
                            <Clock size={16} className="text-slate-400" />

                            <h3 className="text-[#334155] font-semibold text-base">
                                آخر النشاطات
                            </h3>
                        </div>

                        <a
                            href="#"
                            className="flex items-center gap-1 text-xs text-emerald-600 hover:text-emerald-700 font-medium transition-colors"
                        >
                            تحديث تلقائي
                        </a>
                    </div>

                    {/* Body */}
                    <div className="flex flex-col gap-3">
                        <ActivityCient
                            image="/images/client.jpg"
                            status="تمرين مكتمل"
                            title="أكمل أحمد تمرينه اليوم (تمرين الأرجل المكثف والديدليفت الروماني)"
                            timeAgo="منذ ساعتين"
                        />

                        <ActivityCient
                            image="/images/client.jpg"
                            status="تمرين مكتمل"
                            title="أكمل أحمد تمرينه اليوم (تمرين الأرجل المكثف والديدليفت الروماني)"
                            timeAgo="منذ ساعتين"
                        />

                        <ActivityCient
                            image="/images/client.jpg"
                            status="تمرين مكتمل"
                            title="أكمل أحمد تمرينه اليوم (تمرين الأرجل المكثف والديدليفت الروماني)"
                            timeAgo="منذ ساعتين"
                        />
                    </div>
                </section>
            </div>
        </div>
    );
};

export default Dashboard;
