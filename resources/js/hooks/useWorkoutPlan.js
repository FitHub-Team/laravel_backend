
import { useEffect, useState } from "react";
import workoutPlanService from "../services/workoutPlanService";
import useCoachTrainees from "./useCoachTrainees";

// API error messages
const errorMessage = (err, fallback) => {
    const errors = err?.response?.data?.errors;

    if (errors && typeof errors === "object") {
        const messages = Object.values(errors)
            .flat()
            .filter(Boolean);

        if (messages.length) {
            return messages.join(" | ");
        }
    }

    return (
        err?.response?.data?.message ||
        err?.message ||
        fallback
    );
};

// Days of the week
const daysOfWeek = [
    { id: "sunday", name: "الأحد" },
    { id: "monday", name: "الإثنين" },
    { id: "tuesday", name: "الثلاثاء" },
    { id: "wednesday", name: "الأربعاء" },
    { id: "thursday", name: "الخميس" },
    { id: "friday", name: "الجمعة" },
    { id: "saturday", name: "السبت" },
];

const dayNames = {
    الأحد: "sunday",
    الاحد: "sunday",
    الإثنين: "monday",
    الاثنين: "monday",
    الثلاثاء: "tuesday",
    الأربعاء: "wednesday",
    الاربعاء: "wednesday",
    الخميس: "thursday",
    الجمعة: "friday",
    السبت: "saturday",
};

// Exercise helpers
const getId = (item) =>
    item?.custom_exercise_id ??
    item?.exercise_id ??
    item?.exercise?.id ??
    item?.id;

const normalizeItem = (item) => {
    const nested =
        item.exercise && typeof item.exercise === "object"
            ? item.exercise
            : null;

    const id = getId(item);

    return {
        ...item,

        exercise_id: id,

        exercise:
            nested ??
            (item.name
                ? {
                      id,
                      name: item.name,
                      description: item.description,
                      muscle_group:
                          item.muscle_group ?? item.category,
                      equipment: item.equipment,
                      difficulty_level: item.difficulty_level,
                  }
                : null),

        sets: item.sets,
        reps: String(item.reps),
        rest_seconds:
            item.rest_seconds ??
            item.rest_time ??
            item.rest,
        notes: item.notes,
    };
};

// Normalize AI-generated workout plan
function normalizeAIPlan(result) {
    const response = result?.data?.weeks
        ? result.data
        : result;

    const week = response?.weeks?.[0];

    if (!Array.isArray(week?.days)) {
        throw new Error(
            "استجابة AI لا تحتوي أيام تدريب صالحة"
        );
    }

    const days = week.days.map((day) => {
        const dayId =
            dayNames[day.day_name_ar] ??
            daysOfWeek[Number(day.day_number) - 1]?.id;

        if (!dayId) {
            throw new Error(
                "تعذّر تحديد يوم في خطة AI"
            );
        }

        const isRest = day.is_rest || !day.session;

        return {
            day: dayId,

            focus: isRest
                ? "راحة"
                : day.focus_ar ||
                  day.session?.focus_ar ||
                  day.session?.focus ||
                  "",

            exercises: isRest
                ? []
                : (day.session?.exercises ?? []).map(
                      normalizeItem
                  ),
        };
    });

    return {
        ...response,
        days,
    };
}

// Normalize saved workout plan
function normalizeSavedPlan(result) {
    const plan = result?.data?.days
        ? result.data
        : result;

    if (!plan || !Array.isArray(plan.days)) {
        return plan ?? null;
    }

    return {
        ...plan,

        days: plan.days.map((day) => ({
            ...day,
            exercises: (day.exercises ?? []).map(
                normalizeItem
            ),
        })),
    };
}

export default function useWorkoutPlan(traineeId) {
    // =========================
    // Shared trainee management
    // =========================

    const {
        trainees,
        selectedTrainee,
        selectTrainee: selectCoachTrainee,
        isTraineesLoading,
        traineesError,
    } = useCoachTrainees(traineeId);

    // =========================
    // Workout state
    // =========================

    const [selectedDay, setSelectedDay] =
        useState("sunday");

    const [workoutPlan, setWorkoutPlan] =
        useState(null);

    const [isLoading, setIsLoading] =
        useState(false);

    const [isSaving, setIsSaving] =
        useState(false);

    const [isGeneratingAI, setIsGeneratingAI] =
        useState(false);

    const [error, setError] =
        useState(null);

    const [flashMessage, setFlashMessage] =
        useState(null);

    // =========================
    // Notifications
    // =========================

    const showFlash = (
        message,
        type = "error"
    ) => {
        setFlashMessage({
            id: Date.now(),
            message,
            type,
        });
    };

    const dismissFlash = () => {
        setFlashMessage(null);
    };

    // =========================
    // Computed data
    // =========================

    const availableDays =
        selectedTrainee?.trainee?.user_profile
            ?.available_days ?? [];

    const currentDay =
        workoutPlan?.days?.find(
            (day) => day.day === selectedDay
        );

    const selectedDayData =
        daysOfWeek.find(
            (day) => day.id === selectedDay
        );

    const exercises =
        currentDay?.exercises ?? [];

    const selectedDayFocus =
        currentDay?.focus ?? "";

    const hasExercises =
        (workoutPlan?.days ?? []).some(
            (day) =>
                (day.exercises ?? []).length > 0
        );

    // =========================
    // Fetch workout plan
    // =========================

    const getWorkoutPlan = async (id) => {
        if (!id) return;

        setIsLoading(true);
        setError(null);

        try {
            const response =
                await workoutPlanService.getWorkoutPlan(
                    id
                );

            const plan =
                normalizeSavedPlan(response);

            setWorkoutPlan(plan);

            setSelectedDay(
                plan?.days?.[0]?.day ?? "sunday"
            );

            return plan;
        } catch (err) {
            setWorkoutPlan(null);
            setError(err);

            showFlash(
                errorMessage(
                    err,
                    "تعذّر تحميل خطة التدريب"
                )
            );

            throw err;
        } finally {
            setIsLoading(false);
        }
    };

    // =========================
    // Select trainee
    // =========================

    const selectTrainee = (subscription) => {
        if (!subscription?.trainee?.id) {
            return;
        }

        selectCoachTrainee(subscription);

        setSelectedDay("sunday");
        setWorkoutPlan(null);
    };

    // Load workout plan whenever selected trainee changes
    useEffect(() => {
        const selectedId =
            selectedTrainee?.trainee?.id;

        if (!selectedId) return;

        getWorkoutPlan(selectedId).catch(() => {});

        // Runs when the selected trainee ID changes
    }, [selectedTrainee?.trainee?.id]);

    // =========================
    // Generate AI workout plan
    // =========================

    const generateAIPlan = async () => {
        const selectedId =
            selectedTrainee?.trainee?.id;

        if (!selectedId) {
            throw new Error(
                "لم يتم اختيار مشترك"
            );
        }

        setIsGeneratingAI(true);
        setError(null);

        try {
            const raw =
                await workoutPlanService
                    .generateAIWorkoutPlan(selectedId);

            const plan =
                normalizeAIPlan(raw);

            setWorkoutPlan(plan);

            setSelectedDay(
                plan.days.find(
                    (day) => day.exercises.length
                )?.day ??
                    plan.days[0]?.day ??
                    "sunday"
            );

            showFlash(
                "تم توليد خطة التمارين بنجاح",
                "success"
            );

            return plan;
        } catch (err) {
            setError(err);

            showFlash(
                errorMessage(
                    err,
                    "تعذّر توليد خطة التمارين"
                )
            );

            throw err;
        } finally {
            setIsGeneratingAI(false);
        }
    };

    // =========================
    // Update selected day
    // =========================

    const updateSelectedDay = (updater) => {
        setWorkoutPlan((previous) => {
            const current =
                previous ?? { days: [] };

            const existing =
                (current.days ?? []).some(
                    (day) =>
                        day.day === selectedDay
                );

            const days = existing
                ? current.days.map((day) =>
                      day.day === selectedDay
                          ? updater(day)
                          : day
                  )
                : [
                      ...(current.days ?? []),
                      updater({
                          day: selectedDay,
                          focus: "",
                          exercises: [],
                      }),
                  ];

            return {
                ...current,
                days,
            };
        });
    };

    // =========================
    // Add exercise
    // =========================

    const addExercise = (exercise) => {
        if (!exercise) return;

        const item = normalizeItem({
            ...exercise,

            exercise_id:
                exercise.custom_exercise_id != null
                    ? null
                    : getId(exercise),
        });

        if (
            !Number.isInteger(
                Number(item.exercise_id)
            ) ||
            Number(item.exercise_id) <= 0
        ) {
            showFlash(
                "لا يمكن إضافة التمرين: معرّفه غير صالح"
            );

            return;
        }

        updateSelectedDay((day) => ({
            ...day,

            exercises: [
                ...(day.exercises ?? []),
                item,
            ],
        }));
    };

    // =========================
    // Update day focus
    // =========================

    const updateDayFocus = (focus) => {
        updateSelectedDay((day) => ({
            ...day,
            focus,
        }));
    };

    // =========================
    // Update exercise
    // =========================

    const updateExercise = (
        exerciseId,
        changes,
        index
    ) => {
        updateSelectedDay((day) => ({
            ...day,

            exercises: (
                day.exercises ?? []
            ).map((item, i) => {
                const matching =
                    Number(getId(item)) ===
                    Number(exerciseId);

                if (
                    index != null
                        ? i === index && matching
                        : matching
                ) {
                    return {
                        ...item,
                        ...changes,
                    };
                }

                return item;
            }),
        }));
    };

    // =========================
    // Delete exercise
    // =========================

    const deleteExercise = (
        exerciseId,
        index
    ) => {
        updateSelectedDay((day) => ({
            ...day,

            exercises: (
                day.exercises ?? []
            ).filter((item, i) =>
                index != null
                    ? !(
                          i === index &&
                          Number(getId(item)) ===
                              Number(exerciseId)
                      )
                    : Number(getId(item)) !==
                      Number(exerciseId)
            ),
        }));
    };

    // =========================
    // Exercise count
    // =========================

    const getDayExercisesCount = (dayId) => {
        return (
            workoutPlan?.days?.find(
                (day) => day.day === dayId
            )?.exercises?.length ?? 0
        );
    };

    // =========================
    // Clear workout table
    // =========================

    const clearWorkoutTable = () => {
        setWorkoutPlan({
            days: [],
            status: "draft",
        });

        setSelectedDay("sunday");
    };

    // =========================
    // Save workout plan
    // =========================

    const saveWorkoutPlan = async (
        status = "draft"
    ) => {
        if (isSaving) return;

        const selectedId =
            selectedTrainee?.trainee?.id;

        if (!selectedId) {
            showFlash(
                "يرجى اختيار المشترك"
            );

            return;
        }

        setIsSaving(true);
        setError(null);

        try {
            const allExercises =
                (workoutPlan?.days ?? []).flatMap(
                    (day) =>
                        (
                            day.exercises ?? []
                        ).map((exercise) => ({
                            day_of_week:
                                day.day,

                            exercise_id:
                                exercise.custom_exercise_id !=
                                null
                                    ? null
                                    : Number(
                                          exercise.exercise_id
                                      ),

                            custom_exercise_id:
                                exercise.custom_exercise_id ??
                                null,

                            sets: Number(
                                exercise.sets
                            ),

                            reps: String(
                                exercise.reps ?? ""
                            ).trim(),

                            rest_time: Number(
                                exercise.rest_seconds ??
                                    exercise.rest_time
                            ),

                            notes:
                                exercise.notes ?? null,
                        }))
                );

            if (!allExercises.length) {
                throw new Error(
                    "أضيفي تمرينًا واحدًا على الأقل قبل الحفظ"
                );
            }

            const payload = {
                start_date:
                    workoutPlan?.start_date ?? null,

                end_date:
                    workoutPlan?.end_date ?? null,

                status,

                days: (
                    workoutPlan?.days ?? []
                ).map((day) => ({
                    day: day.day,
                    focus:
                        day.focus ?? null,
                })),

                exercises:
                    allExercises,
            };

            const response =
                await workoutPlanService
                    .saveWorkoutPlan(
                        selectedId,
                        payload
                    );

            if (status === "active") {
                clearWorkoutTable();

                showFlash(
                    "تم إرسال الخطة للمشترك وتفريغ الجدول",
                    "success"
                );
            } else {
                showFlash(
                    "تم حفظ الخطة كمسودة بنجاح",
                    "success"
                );
            }

            return response;
        } catch (err) {
            setError(err);

            showFlash(
                errorMessage(
                    err,
                    "تعذّر حفظ الخطة"
                )
            );

            return null;
        } finally {
            setIsSaving(false);
        }
    };

    // =========================
    // Return
    // =========================

    return {
        // Trainees
        trainees,
        selectedTrainee,
        selectTrainee,
        isTraineesLoading,
        traineesError,

        // Days
        daysOfWeek,
        availableDays,
        selectedDay,
        setSelectedDay,
        selectedDayData,
        selectedDayFocus,

        // Workout
        workoutPlan,
        exercises,
        hasExercises,

        // Exercise actions
        addExercise,
        updateDayFocus,
        updateExercise,
        deleteExercise,
        getDayExercisesCount,

        // Plan actions
        saveWorkoutPlan,
        clearWorkoutTable,
        generateAIPlan,

        // Loading
        isLoading,
        isSaving,
        isGeneratingAI,

        // Errors and notifications
        error,
        flashMessage,
        dismissFlash,
        showFlash,
    };
}
