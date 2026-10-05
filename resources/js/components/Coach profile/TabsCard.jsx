import {
  Mail,
  MapPin,
  Briefcase,
  GraduationCap,
  Calendar,
  CreditCard,
  Award,
  DollarSign,
  Star,
  User,
  FileText,
} from "lucide-react";
import InfoCard from "./InfoCard";
import EmptyState from "./EmptyState";

const TabsCard = ({
  activeTab,
  setActiveTab,
  coach,
  profile,
  certifications,
}) => {
  return (
    <div className="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
      {/* Tabs Header */}
      <div className="flex border-b border-gray-100 overflow-x-auto">
        {[
          {
            key: "info",
            label: "المعلومات الشخصية",
            icon: <User size={16} />,
          },
          { key: "skills", label: "المهارات", icon: <Star size={16} /> },
          { key: "certs", label: "الشهادات", icon: <Award size={16} /> },
          { key: "bio", label: "نبذة", icon: <FileText size={16} /> },
        ].map((tab) => (
          <button
            key={tab.key}
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

      {/* Tab Content */}
      <div className="p-4 lg:p-6">
        {/* ---------- Info Tab ---------- */}
        {activeTab === "info" && (
          <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
            <InfoCard
              icon={<User size={18} />}
              label="الاسم الكامل"
              value={profile?.user?.full_name}
            />
            <InfoCard
              icon={<Mail size={18} />}
              label="البريد الإلكتروني"
              value={profile?.user?.email}
            />
            <InfoCard
              icon={<MapPin size={18} />}
              label="مكان الإقامة"
              value={profile?.location}
            />
            <InfoCard
              icon={<Calendar size={18} />}
              label="سنة الميلاد"
              value={profile?.birth_year}
            />
            <InfoCard
              icon={<GraduationCap size={18} />}
              label="التخصص"
              value={profile?.specialization}
            />
            <InfoCard
              icon={<Briefcase size={18} />}
              label="سنوات الخبرة"
              value={profile?.experience ? `${profile.experience} سنوات` : null}
            />
            <InfoCard
              icon={<CreditCard size={18} />}
              label="رقم الهوية"
              value={profile?.national_id}
            />
            <InfoCard
              icon={<DollarSign size={18} />}
              label="سعر الجلسة"
              value={profile?.price ? `${profile.price} ر.س` : null}
            />
          </div>
        )}

        {/* ---------- Skills Tab ---------- */}
        {activeTab === "skills" && (
          <div className="flex flex-wrap gap-2">
            {profile?.skills?.length ? (
              profile.skills.map((skill) => (
                <span
                  key={skill.id}
                  className="flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-green-50 text-green-700 text-sm font-medium border border-green-100"
                >
                  <Star size={14} />
                  {skill.name ?? skill.title}
                </span>
              ))
            ) : (
              <EmptyState text="لا توجد مهارات مسجلة حالياً" />
            )}
          </div>
        )}

        {/* ---------- Certifications Tab ---------- */}
        {activeTab === "certs" && (
          <div className="grid grid-cols-1 md:grid-cols-2 gap-3">
            {certifications.length ? (
              certifications.map((cert, i) => (
                <div
                  key={i}
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
                </div>
              ))
            ) : (
              <EmptyState text="لا توجد شهادات مسجلة حالياً" />
            )}
          </div>
        )}

        {/* ---------- Bio Tab ---------- */}
        {activeTab === "bio" && (
          <div className="p-4 rounded-xl bg-[#F8F9FA] border border-gray-100">
            {profile?.bio ? (
              <p className="text-sm text-gray-700 leading-relaxed whitespace-pre-line">
                {profile.bio}
              </p>
            ) : (
              <EmptyState text="لا توجد نبذة تعريفية" />
            )}
          </div>
        )}
      </div>
    </div>
  );
};
export default TabsCard;
