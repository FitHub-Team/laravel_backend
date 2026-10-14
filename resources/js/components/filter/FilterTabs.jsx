import { useState } from "react";

const FilterTabs = () => {
  const [activeTab, setActiveTab] = useState("all");

  const tabs = [
    { id: "all", label: "الكل", count: 8 },
    { id: "active", label: "نشط", count: 3 },
    { id: "needsFollow", label: "يحتاج متابعة", count: 3 },
    { id: "new", label: "جديد", count: 2 },
  ];

  return (
    <div className=" bg-white rounded-3xl p-4">
      <div className="bg-slate-100/80 rounded-2xl p-1.5 flex items-center gap-1 w-fit">
        {tabs.map((tab) => {
          const isActive = activeTab === tab.id;

          return (
            <button
              key={tab.id}
              onClick={() => setActiveTab(tab.id)}
              className={`
                px-5 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 whitespace-nowrap
                ${
                  isActive
                    ? "bg-white text-slate-700 shadow-sm shadow-slate-200/70"
                    : "text-slate-500 hover:text-slate-700"
                }
              `}
            >
              {tab.label} ({tab.count})
            </button>
          );
        })}
      </div>
    </div>
  );
};

export default FilterTabs;