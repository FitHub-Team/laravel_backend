import NutritionPlan from "../../../components/Nutrition/NutritionPlan";
import PlanHeader from "../../../components/workout/PlanTopSection";

export default function NutritionPlanPage() {
    const summary = {
        totalCalories: 2100,
        protein: 170,
        carbs: 220,
        fat: 65,
        savedCalories: 2100,
    };

    const meals = [
        {
            id: 1,
            meal_name: "الإفطار",
            components: "3 بيضات + خبز أسمر + خضار",
            calories: 500,
            time_to_eat: "08:00",
            foods: [],
        },
        {
            id: 2,
            meal_name: "سناك",
            components: "زبادي + موز + لوز",
            calories: 300,
            time_to_eat: "11:00",
            foods: [],
        },
        {
            id: 3,
            meal_name: "الغداء",
            components: "صدر دجاج + أرز + سلطة",
            calories: 750,
            time_to_eat: "14:00",
            foods: [],
        },
        {
            id: 4,
            meal_name: "العشاء",
            components: "تونة + خبز أسمر + خضار",
            calories: 550,
            time_to_eat: "19:00",
            foods: [],
        },
    ];

    return (
        <div dir="rtl" className="mx-auto max-w-6xl p-6">
            <PlanHeader />

            <h1 className="mb-6 text-2xl font-bold text-[#1A3D27]">
                خطة التغذية
            </h1>

            <NutritionPlan
                summary={summary}
                meals={meals}
                onAddFood={(mealId) => {
                    console.log("Add food:", mealId);
                }}
                onDeleteFood={(foodId) => {
                    console.log("Delete food:", foodId);
                }}
            />
        </div>
    );
}
