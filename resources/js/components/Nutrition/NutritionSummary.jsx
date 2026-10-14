import { Flame, Beef, Wheat, Droplet } from "lucide-react";

const NutritionSummary = ({
  totalCalories = 0,
  protein = 0,
  carbs = 0,
  fat = 0,
  savedCalories = 0,
}) => {
  const macros = [
    {
      label: "السعرات اليومية المستهدفة",
      value: totalCalories,
      unit: "سعرة",
      icon: Flame,
      color: "text-red-500",
      bg: "bg-red-50",
    },
    {
      label: "البروتين المستهدف",
      value: protein,
      unit: "جرام / يوم",
      icon: Beef,
      color: "text-blue-500",
      bg: "bg-blue-50",
    },
    {
      label: "الكربوهيدرات المستهدفة",
      value: carbs,
      unit: "جرام / يوم",
      icon: Wheat,
      color: "text-amber-500",
      bg: "bg-amber-50",
    },
    {
      label: "الدهون المستهدفة",
      value: fat,
      unit: "جرام / يوم",
      icon: Droplet,
      color: "text-purple-500",
      bg: "bg-purple-50",
    },
  ];

  return (
    <div className="space-y-3">

      {/* العنوان + إجمالي السعرات المحفوظة */}
      <div className="flex flex-wrap items-center justify-between gap-3">
        <h3 className="text-sm font-bold text-slate-800">
          الأهداف اليومية للماكروز والسعرات
        </h3>

        <div className="flex items-center gap-2 text-xs text-slate-500">
          <span>إجمالي السعرات المحفوظة حالياً</span>
          <span className="text-sm font-bold text-slate-800">
            {savedCalories}
          </span>
          <span className="text-slate-300">/</span>
          <span className="text-emerald-600 font-semibold">
            {totalCalories}
          </span>
        </div>
      </div>

      {/* شبكة الماكروز */}
      <div className="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        {macros.map((macro) => {
          const Icon = macro.icon;
          return (
            <div
              key={macro.label}
              className="rounded-2xl border border-slate-200 bg-white p-4"
            >
              <p className="text-[11px] text-slate-500 mb-3">
                {macro.label}
              </p>

              <div className="flex items-center justify-between">

                {/* الأيقونة */}
                <div
                  className={`w-9 h-9 rounded-xl ${macro.bg} flex items-center justify-center`}
                >
                  <Icon size={16} className={macro.color} />
                </div>

                {/* الرقم */}
                <div className="text-left">
                  <p className="text-2xl font-bold text-slate-800 leading-none">
                    {macro.value}
                  </p>
                  <p className="text-[10px] text-slate-400 mt-1">
                    {macro.unit}
                  </p>
                </div>

              </div>
            </div>
          );
        })}
      </div>

    </div>
  );
};

export default NutritionSummary;