import NutritionPlan from "./NutritionPlan";

const mockNutritionSummary = {
    totalCalories: 2100,
    protein: 175,
    carbs: 220,
    fat: 65,
    savedCalories: 2100,
};

const mockNutritionMeals = [
    {
        id: 1,
        meal_name: "الإفطار",
        components: "3 بيضات، خبز أسمر، خضار",
        calories: 500,
        time_to_eat: "08:00",
        foods: [],
    },
    {
        id: 2,
        meal_name: "الغداء",
        components: "صدر دجاج، أرز، سلطة",
        calories: 750,
        time_to_eat: "14:00",
        foods: [],
    },
];

export default function NutritionPlanBuilder({ traineeId }) {
    if (!traineeId) return null;

    return (
        <NutritionPlan
            summary={mockNutritionSummary}
            meals={mockNutritionMeals}
            onAddFood={(mealId) => {
                console.log("Add food:", mealId);
            }}
            onDeleteFood={(foodId) => {
                console.log("Delete food:", foodId);
            }}
        />
    );
}