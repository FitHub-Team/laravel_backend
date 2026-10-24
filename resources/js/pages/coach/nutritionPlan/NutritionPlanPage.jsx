import { useState } from "react";
import { useSearchParams } from "react-router-dom";
import { Save, Send } from "lucide-react";
import NutritionPlan from "../../../components/Nutrition/NutritionPlan";
import TraineeSelector from "../../../components/common/TraineeSelector";
import useCoachTrainees from "../../../hooks/useCoachTrainees";
import Button from "../../../components/common/Button";
import Actions from "../../../components/workout/WorkoutActions";
import NutritionDays from "../../../components/Nutrition/NutritionDays";
const STORAGE_KEY = "superfit_nutrition_plans";

// Default meals (Frontend only)
const initialMeals = [
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

const defaultSummary = {
    totalCalories: 2100,
    protein: 170,
    carbs: 220,
    fat: 65,
    savedCalories: 2100,
};

// Read locally saved nutrition plans
function readPlans() {
    try {
        const stored = JSON.parse(localStorage.getItem(STORAGE_KEY) || "{}");

        return stored && typeof stored === "object" && !Array.isArray(stored)
            ? stored
            : {};
    } catch {
        return {};
    }
}

export default function NutritionPlanPage() {
    const [searchParams, setSearchParams] = useSearchParams();

    // Read selected trainee from URL
    const traineeId = searchParams.get("trainee");

    // Fetch trainees from Laravel Backend
    const {
        trainees,
        selectedTrainee,
        selectTrainee,
        isTraineesLoading,
        traineesError,
    } = useCoachTrainees(traineeId);
    const [selectedDay, setSelectedDay] = useState("saturday");
    // Nutrition plans are currently stored locally
    const [plans] = useState(readPlans);

    // Actual trainee user ID, not subscription ID
    const selectedTraineeId = selectedTrainee?.trainee?.id ?? null;

    // Get the selected trainee's nutrition plan
    const currentPlan = selectedTraineeId
        ? (plans[selectedTraineeId] ?? null)
        : null;

    // Use saved data or defaults
    const summary = currentPlan?.summary ?? defaultSummary;

    const meals = currentPlan?.meals ?? initialMeals;

    // Select trainee and update URL
    const handleSelectTrainee = (subscription) => {
        const id = subscription?.trainee?.id;

        if (!id) return;

        selectTrainee(subscription);

        setSearchParams(
            (previous) => {
                const params = new URLSearchParams(previous);
                params.set("trainee", String(id));
                return params;
            },
            { replace: true },
        );
    };

    return (
        <div dir="rtl" className="mx-auto max-w-6xl space-y-6 p-6">
            {/* Trainee selection */}
            <section className="rounded-xl bg-white p-4 shadow-sm">
                <div className="flex flex-col items-start justify-between gap-4 md:flex-row md:items-center">
                    <TraineeSelector
                        trainees={trainees}
                        selectedTrainee={selectedTrainee}
                        onSelectTrainee={handleSelectTrainee}
                        isLoading={isTraineesLoading}
                    />

                    {traineesError && (
                        <p className="mt-3 text-sm text-red-600">
                            {traineesError}
                        </p>
                    )}
                    {selectedTrainee && (
                        <div className="mt-4 border-t  border-gray-100 pt-4 text-sm text-gray-600">
                            <p>
                                الهدف:{" "}
                                <span className="font-medium text-[#407437]">
                                    {selectedTrainee.trainee?.user_profile?.goal
                                        ?.title ?? "غير محدد"}
                                </span>
                            </p>

                            <p className="mt-2 text-xs">
                                الحالة:{" "}
                                {currentPlan?.status === "active"
                                    ? "نشطة محليًا"
                                    : currentPlan?.status === "draft"
                                      ? "مسودة محلية"
                                      : "لم يتم حفظ خطة بعد"}
                            </p>
                        </div>
                    )}

                    {/* { Save and send buttons} */}
                    {/* {selectedTrainee && activeSection === "workout" && ( */}
                    <div className="flex flex-wrap gap-3">
                        <Button
                            title="حفظ كمسودة"
                            Icon={Save}
                            onClick={() => save("draft")}
                            // disabled={isSaving || isLoading || !hasExercises}
                            color="bg-[#407437] text-white"
                        />

                        <Button
                            title="إرسال الخطة للمشترك"
                            Icon={Send}
                            onClick={() => save("active")}
                            // disabled={isSaving || isLoading || !hasExercises}
                            color="bg-[#407437] text-white"
                        />
                    </div>
                    {/* // )} } */}
                </div>
            </section>

            <Actions activeSection="nutrition" traineeId={selectedTraineeId} />
           
         
            <NutritionDays
                selectedDay={selectedDay}
                onSelectDay={setSelectedDay}
            />
            {/* No trainee selected */}
            {!selectedTrainee ? (
                <section
                    className="
                        flex min-h-[350px] items-center
                        justify-center rounded-xl
                        border border-gray-200 bg-white
                    "
                >
                    <div className="px-6 text-center">
                        <h2 className="text-lg font-bold text-gray-800">
                            اختر مشتركًا لعرض خطة التغذية
                        </h2>

                        <p className="mt-2 text-sm text-gray-500">
                            اختر مشتركًا من القائمة بالأعلى لعرض أو إعداد خطته
                            الغذائية.
                        </p>
                    </div>
                </section>
            ) : (
                <>
                    {/* Nutrition content */}
                    <NutritionPlan
                        key={selectedTraineeId}
                        summary={summary}
                        meals={meals}
                        onAddFood={(mealId) => {
                            console.log("Add food to meal:", mealId);
                        }}
                        onDeleteFood={(foodId) => {
                            console.log("Delete food:", foodId);
                        }}
                    />
                </>
            )}
        </div>
    );
}
