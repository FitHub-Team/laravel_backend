import { useEffect, useState } from "react";

import {
    Save,
    Send,
    UserRound,
} from "lucide-react";

import { useSearchParams } from "react-router-dom";

import FlashMessage from "../../../components/common/FlashMessage";
import Button from "../../../components/common/Button";
import TraineeSelector from "../../../components/common/TraineeSelector";

import WorkoutActions from "../../../components/workout/WorkoutActions";
import WorkoutDay from "../../../components/workout/WorkoutDay";
import WorkoutTable from "../../../components/workout/WorkoutTable";

import TraineeVersionsPage from "../Versions/TraineeVersionsPage";
import NutritionPlanBuilder from "../../../components/Nutrition/NutritionPlanBuilder";

import useWorkoutPlan from "../../../hooks/useWorkoutPlan";
import { formatLastUpdated } from "../../../utils/dateHelper";

const statusLabels = {
    draft: "مسودة",
    active: "نشطة",
    completed: "مكتملة",
    cancelled: "ملغاة",
};

export default function WorkoutPlan() {
    const [searchParams] = useSearchParams();
    const [activeSection, setActiveSection] = useState("workout");

    const {
        daysOfWeek,
        availableDays,
        selectedDay,
        setSelectedDay,
        selectedDayData,
        selectedDayFocus,
        workoutPlan,
        exercises,
        hasExercises,
        trainees,
        selectedTrainee,
        selectTrainee,
        addExercise,
        updateDayFocus,
        updateExercise,
        deleteExercise,
        getDayExercisesCount,
        saveWorkoutPlan,
        isSaving,
        generateAIPlan,
        isGeneratingAI,
        isLoading,
        flashMessage,
        dismissFlash,
        showFlash,
        isTraineesLoading,
        traineesError,
    } = useWorkoutPlan(searchParams.get("trainee"));

    const traineeId = selectedTrainee?.trainee?.id ?? null;
    const traineeName = selectedTrainee?.trainee?.full_name ?? "المشترك";

    // Close trainee dropdown on outside click
    useEffect(() => {
        const handleOutsideClick = (event) => {
            if (
                dropdownRef.current &&
                !dropdownRef.current.contains(event.target)
            ) {
                setShowTraineeDropdown(false);
            }
        };

        document.addEventListener("mousedown", handleOutsideClick);

        return () => {
            document.removeEventListener("mousedown", handleOutsideClick);
        };
    }, []);

  

    const save = (status) => saveWorkoutPlan(status);

    const generate = () => {
        if (!selectedTrainee) {
            showFlash("يرجى اختيار المشترك أولاً");
            return;
        }

        generateAIPlan().catch(() => {});
    };

    return (
        <div dir="rtl" className="mx-auto max-w-6xl space-y-6">
            <FlashMessage
                message={flashMessage?.message}
                type={flashMessage?.type}
                onClose={dismissFlash}
            />

            {/* Trainee selector and plan actions */}
            <section className="rounded-xl bg-white p-4 shadow-sm">
                <div className="flex flex-col items-start justify-between gap-4 md:flex-row md:items-center">
                    <TraineeSelector
                        trainees={trainees}
                        selectedTrainee={selectedTrainee}
                        onSelectTrainee={selectTrainee}
                        isLoading={isTraineesLoading}
                    />

                    {traineesError && (
                        <p className="mt-2 text-sm text-red-600">
                            {traineesError}
                        </p>
                    )}

                    {/* Selected trainee information */}
                    {selectedTrainee && (
                        <div className="text-sm text-gray-600">
                            <p>
                                الهدف:{" "}
                                {selectedTrainee.trainee?.user_profile?.goal
                                    ?.title ?? "غير محدد"}
                            </p>

                            <p className="mt-1 text-xs">
                                الحالة:{" "}
                                {statusLabels[workoutPlan?.status] ??
                                    "الخطة لم ترسل بعد"}
                                {workoutPlan?.updated_at
                                    ? ` • آخر تحديث: ${formatLastUpdated(
                                          workoutPlan.updated_at,
                                      )}`
                                    : ""}
                            </p>

                            <p className="mt-1 text-xs text-[#407437]">
                                الإصدار: v{workoutPlan?.version ?? 0}.0
                            </p>
                        </div>
                    )}

                    {/* Save and send buttons */}
                    {selectedTrainee && activeSection === "workout" && (
                        <div className="flex flex-wrap gap-3">
                            <Button
                                title="حفظ كمسودة"
                                Icon={Save}
                                onClick={() => save("draft")}
                                disabled={
                                    isSaving || isLoading || !hasExercises
                                }
                                color="bg-[#407437] text-white"
                            />

                            <Button
                                title="إرسال الخطة للمشترك"
                                Icon={Send}
                                onClick={() => save("active")}
                                disabled={
                                    isSaving || isLoading || !hasExercises
                                }
                                color="bg-[#407437] text-white"
                            />
                        </div>
                    )}
                </div>
            </section>

            {!selectedTrainee ? (
                <section className="flex min-h-[420px] items-center justify-center rounded-xl border border-gray-200 bg-white">
                    <div className="px-6 text-center">
                        <UserRound
                            size={30}
                            className="mx-auto mb-4 text-green-600"
                        />

                        <h3 className="mb-2 text-lg font-bold">
                            اختر مشتركًا لعرض خطته
                        </h3>

                        <p className="text-sm text-gray-500">
                            اختر مشتركًا من القائمة بالأعلى لعرض أو إنشاء خطة
                            التمارين.
                        </p>
                    </div>
                </section>
            ) : (
                <>
                    {/* Main section navigation */}
                    <WorkoutActions
                        activeSection={activeSection}
                        onSectionChange={setActiveSection}
                        traineeId={traineeId}
                    />

                    {/* Workout section */}
                    {activeSection === "workout" && (
                        <>
                            <WorkoutDay
                                daysOfWeek={daysOfWeek}
                                availableDays={availableDays}
                                selectedDay={selectedDay}
                                setSelectedDay={setSelectedDay}
                                getDayExercisesCount={getDayExercisesCount}
                                onGenerateAI={generate}
                                isGeneratingAI={isGeneratingAI}
                            />

                            <WorkoutTable
                                selectedDayData={selectedDayData}
                                exercises={exercises}
                                handleDelete={deleteExercise}
                                onAddExercise={addExercise}
                                selectedDayFocus={selectedDayFocus}
                                onFocusChange={updateDayFocus}
                                onUpdateExercise={updateExercise}
                            />
                        </>
                    )}

                    {/* Nutrition section */}
                    {activeSection === "nutrition" && (
                        <NutritionPlanBuilder traineeId={traineeId} />
                    )}

                    {/* Versions section, displayed within this page */}
                    {activeSection === "versions" && (
                        <TraineeVersionsPage
                            traineeId={traineeId}
                            traineeName={traineeName}
                            onBack={() => setActiveSection("workout")}
                        />
                    )}
                </>
            )}
        </div>
    );
}
