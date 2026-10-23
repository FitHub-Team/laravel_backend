import { Clock, Dumbbell, Utensils } from "lucide-react";
import { useNavigate, useLocation, useSearchParams } from "react-router-dom";

import Button from "../common/Button";

export default function WorkoutActions({
    activeSection,
    onSectionChange,
    traineeId,
}) {
    const navigate = useNavigate();
    const location = useLocation();
    const [searchParams] = useSearchParams();
    const currentTraineeId = traineeId ?? searchParams.get("trainee");

    const isWorkoutActive =
        location.pathname === "/dashboard/workout-plan" &&
        activeSection !== "versions";

    const isNutritionActive = location.pathname === "/dashboard/nutrition-plan";

    const isVersionsActive =
        location.pathname.startsWith("/dashboard/versions") ||
        activeSection === "versions";

    const handleWorkout = () => {
        const url = currentTraineeId
            ? `/dashboard/workout-plan?trainee=${encodeURIComponent(currentTraineeId)}`
            : "/dashboard/workout-plan";

        if (location.pathname === "/dashboard/workout-plan") {
            onSectionChange?.("workout");
        }

        navigate(url);
    };

    const handleNutrition = () => {
        navigate(
            currentTraineeId
                ? `/dashboard/nutrition-plan?trainee=${encodeURIComponent(currentTraineeId)}`
                : "/dashboard/nutrition-plan",
        );
    };

    const handleVersions = () => {
        if (!traineeId) return;

        navigate(`/dashboard/versions/${traineeId}`);
    };

    return (
        <div className="rounded-xl bg-white p-4 shadow-sm">
            <div className="flex flex-wrap items-center gap-3">
                {/* Workout */}
                <Button
                    title="إنشاء خطة التمارين الأسبوعية"
                    Icon={Dumbbell}
                    color={
                        isWorkoutActive
                            ? "bg-[#407437] text-white"
                            : "text-gray-700"
                    }
                    className={isWorkoutActive ? "" : "border border-gray-200"}
                    onClick={handleWorkout}
                />

                {/* Nutrition */}
                <Button
                    title="الخطة الغذائية والماكروز"
                    Icon={Utensils}
                    color={
                        isNutritionActive
                            ? "bg-[#407437] text-white"
                            : "text-gray-700"
                    }
                    className={
                        isNutritionActive ? "" : "border border-gray-200"
                    }
                    onClick={handleNutrition}
                />

                {/* Versions */}
                <Button
                    title="الإصدارات السابقة"
                    Icon={Clock}
                    color={
                        isVersionsActive
                            ? "bg-[#407437] text-white"
                            : "text-gray-700"
                    }
                    className={isVersionsActive ? "" : "border border-gray-200"}
                    onClick={handleVersions}
                    disabled={!traineeId}
                />
            </div>
        </div>
    );
}
