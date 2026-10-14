import NutritionSummary from "./NutritionSummary";
import MealCard from "./MealCard";

const NutritionPlan = ({
  summary = {
    totalCalories: 2106,
    protein: 180,
    carbs: 230,
    fat: 60,
    savedCalories: 2050,
  },
  meals = [],
  onAddFood = () => {},
  onDeleteFood = () => {},
}) => {
  return (
    <div className="space-y-5">

      {/* ====== الملخص العلوي ====== */}
      <NutritionSummary {...summary} />

      {/* ====== شبكة الوجبات ====== */}
      <div className="grid grid-cols-1 lg:grid-cols-2 gap-4">
        {meals.map((meal) => (
          <MealCard
            key={meal.id}
            meal={meal}
            foods={meal.foods}
            onAddFood={() => onAddFood(meal.id)}
            onDeleteFood={onDeleteFood}
          />
        ))}
      </div>

    </div>
  );
};

export default NutritionPlan;