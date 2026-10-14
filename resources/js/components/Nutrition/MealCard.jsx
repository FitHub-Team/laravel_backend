import { Plus } from "lucide-react";
import FoodItem from "./FoodItem";

const MealCard = ({ meal, foods = [], onAddFood, onDeleteFood }) => {
  // meal = { id, name, calories }
  // foods = [{ id, name, details }]

  return (
    <div className="rounded-2xl border border-slate-200 bg-white p-4 sm:p-5">

      {/* Header */}
      <div className="flex items-center justify-between gap-3 mb-4 pb-3 border-b border-slate-100">

        <div className="text-right">
          <h4 className="text-sm font-bold text-slate-800">
            {meal.name}
          </h4>
          <p className="text-[11px] text-slate-400 mt-0.5">
            {meal.calories} سعرة حرارية
          </p>
        </div>

        <button
          type="button"
          onClick={onAddFood}
          className="
            flex items-center gap-1.5
            text-xs font-semibold text-emerald-700
            hover:text-emerald-800
            transition-colors
          "
        >
          <Plus size={13} strokeWidth={2.5} />
          إضافة طعام
        </button>

      </div>

      {/* Foods */}
      <div className="space-y-0">
        {foods.length === 0 ? (
          <p className="text-xs text-slate-400 text-center py-4">
            لم تتم إضافة أي طعام لهذه الوجبة بعد
          </p>
        ) : (
          foods.map((food) => (
            <FoodItem
              key={food.id}
              food={food}
              onDelete={onDeleteFood}
            />
          ))
        )}
      </div>

    </div>
  );
};

export default MealCard;