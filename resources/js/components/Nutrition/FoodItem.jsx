import { Trash2 } from "lucide-react";

const FoodItem = ({ food, onDelete }) => {
  // food = { id, name, details }
  // details مثال: "3 حبات • 216 سعرة • 18.9 كارب • 12.7 دهون"

  return (
    <div className="group flex items-start justify-between gap-3 py-2.5 border-b border-slate-100 last:border-0">

      {/* الأيقونة (checkbox / drag handle) */}
      <button
        type="button"
        className="
          w-5 h-5 rounded
          border border-slate-200
          flex items-center justify-center
          shrink-0 mt-0.5
          hover:border-emerald-400
          transition-colors
        "
        title="تحديد"
      >
        <span className="w-2 h-2 rounded-sm bg-transparent group-hover:bg-emerald-200 transition" />
      </button>

      {/* النص */}
      <div className="flex-1 min-w-0 text-right">
        <p className="text-sm font-bold text-slate-800 truncate">
          {food.name}
        </p>
        <p className="text-[11px] text-slate-400 mt-0.5 truncate">
          {food.details}
        </p>
      </div>

      {/* زر الحذف */}
      {onDelete && (
        <button
          type="button"
          onClick={() => onDelete(food.id)}
          className="
            w-6 h-6 rounded-lg
            flex items-center justify-center
            text-slate-300
            hover:text-red-500 hover:bg-red-50
            transition-all shrink-0
          "
          title="حذف"
        >
          <Trash2 size={13} />
        </button>
      )}

    </div>
  );
};

export default FoodItem;