import { useEffect, useState } from "react";
import workoutPlanService from "../services/workoutPlanService";

// جميع أيام الأسبوع المستخدمة في الخطة
const allDaysOfWeek = [
  { id: "sunday", name: "الأحد" },
  { id: "monday", name: "الإثنين" },
  { id: "tuesday", name: "الثلاثاء" },
  { id: "wednesday", name: "الأربعاء" },
  { id: "thursday", name: "الخميس" },
  { id: "friday", name: "الجمعة" },
  { id: "saturday", name: "السبت" },
];

const useWorkoutPlan = (traineeId) => {
  // =========================================================
  // الحالات
  // =========================================================

  // اليوم المحدد حاليًا
  const [selectedDay, setSelectedDay] = useState("sunday");

  // بيانات خطة التمرين كاملة
  const [workoutPlan, setWorkoutPlan] = useState(null);

  // قائمة المشتركين
  const [trainees, setTrainees] = useState([]);

  // المشترك المحدد حاليًا
  const [selectedTrainee, setSelectedTrainee] = useState(null);

  // حالة تحميل الخطة
  const [isLoading, setIsLoading] = useState(false);

  // حالة حفظ الخطة
  const [isSaving, setIsSaving] = useState(false);

  // حالة تحميل المشتركين
  const [isTraineesLoading, setIsTraineesLoading] = useState(false);

  // حالة توليد الخطة بالـ AI
  const [isGeneratingAI, setIsGeneratingAI] = useState(false);

  // تخزين أي خطأ
  const [error, setError] = useState(null);

  // =========================================================
  // الأيام
  // =========================================================

  const daysOfWeek = allDaysOfWeek;

  // الأيام التي اختارها المشترك كأيام متاحة للتمرين
  const availableDays =
    selectedTrainee?.trainee?.user_profile?.available_days || [];

  // بيانات اليوم المحدد
  const selectedDayData = daysOfWeek.find(
    (day) => day.id === selectedDay
  );

  // تمارين اليوم المحدد
  const exercises =
    workoutPlan?.days?.find(
      (day) => day.day === selectedDay
    )?.exercises || [];

  // التركيز الخاص باليوم المحدد
  const selectedDayFocus =
    workoutPlan?.days?.find(
      (day) => day.day === selectedDay
    )?.focus || "";

  // =========================================================
  // جلب خطة التمرين الخاصة بالمشترك
  // =========================================================

  const getWorkoutPlan = async (id) => {
    if (!id) return;

    try {
      setIsLoading(true);
      setError(null);

      const plan = await workoutPlanService.getWorkoutPlan(id);

      setWorkoutPlan(plan);

      // إذا كانت الخطة تحتوي على أيام
      // نحدد أول يوم موجود فيها
      if (plan?.days?.length) {
        setSelectedDay(plan.days[0].day);
      } else {
        setSelectedDay("sunday");
      }
    } catch (error) {
      console.error("Error loading workout plan:", error);

      setWorkoutPlan(null);
      setError(error);
    } finally {
      setIsLoading(false);
    }
  };

  // =========================================================
  // جلب جميع المشتركين
  // =========================================================

  const getTrainees = async () => {
    try {
      setIsTraineesLoading(true);

      const traineesData =
        await workoutPlanService.getTrainees();

      setTrainees(traineesData);
    } catch (error) {
      console.error("Error loading trainees:", error);

      setTrainees([]);
    } finally {
      setIsTraineesLoading(false);
    }
  };

  // =========================================================
  // توليد خطة التمرين باستخدام AI
  // =========================================================

// توليد خطة التمرين باستخدام AI
const generateAIPlan = async () => {
  if (!selectedTrainee?.trainee?.id) {
    throw new Error("لم يتم اختيار مشترك");
  }

  try {
    setIsGeneratingAI(true);

    const traineeId = selectedTrainee.trainee.id;

    console.log("🚀 Calling AI for trainee:", traineeId);

    const result =
      await workoutPlanService.generateAIWorkoutPlan(traineeId);

    console.log("🤖 AI RESULT:", result);

    // ==========================================
    // تحويل خطة AI إلى نفس شكل workoutPlan
    // ==========================================

    const aiWeek = result?.weeks?.[0];

    if (!aiWeek?.days) {
      throw new Error("لم يتم العثور على أيام في خطة AI");
    }

    const aiDays = aiWeek.days.map((aiDay) => {
      // تحويل رقم اليوم إلى اسم اليوم المستخدم في النظام
      const dayData = daysOfWeek[aiDay.day_number - 1];

      const dayId = dayData?.id;

      // يوم راحة
      if (aiDay.is_rest || !aiDay.session) {
        return {
          day: dayId,
          focus: "راحة",
          exercises: [],
        };
      }

      // يوم تمرين
      const exercises = (aiDay.session.exercises || []).map(
        (exercise, index) => ({
          // id مؤقت للعرض في الـ Table
          id: `ai-${aiDay.day_number}-${index}`,

          // الاسم الذي رجعه الـ AI
          name: exercise.name,

          // بيانات إضافية
          target_muscle: exercise.target_muscle,
          equipment: exercise.equipment,

          // بيانات التمرين
          sets: exercise.sets,
          reps: exercise.reps,
          rest_time: exercise.rest_seconds,

          // سيتم ربطه لاحقاً مع exercise الحقيقي من DB
          exercise_id: null,

          notes: "",
        })
      );

      return {
        day: dayId,
        focus: aiDay.session.focus,
        exercises,
      };
    });

    const generatedPlan = {
      ...result,

      // الشكل الذي يفهمه الـ Table والـ save
      days: aiDays,

      // نحتفظ بمعلومات الخطة
      duration_weeks: result.duration_weeks,
      disclaimer: result.disclaimer,
      summary: result.summary,
    };

    console.log("🟢 GENERATED WORKOUT PLAN:", generatedPlan);

    // أهم سطر
    setWorkoutPlan(generatedPlan);

    // اختيار أول يوم فيه تمارين
    const firstWorkoutDay = aiDays.find(
      (day) => day.exercises.length > 0
    );

    if (firstWorkoutDay) {
      setSelectedDay(firstWorkoutDay.day);
    }

    return generatedPlan;
  } catch (error) {
    console.error("❌ AI SERVICE ERROR:", error);
    throw error;
  } finally {
    setIsGeneratingAI(false);
  }
};
  // =========================================================
  // إضافة تمرين جديد لليوم المحدد
  // =========================================================

  const addExercise = (exercise) => {
    if (!exercise) return;

    const newExercise = {
      ...exercise,
      exercise_id: exercise.id,
      sets: 3,
      reps: 10,
      rest_time: 60,
      notes: "",
    };

    setWorkoutPlan((prev) => {
      const currentPlan = prev || {
        days: [],
      };

      const days = [...(currentPlan.days || [])];

      const existingDayIndex = days.findIndex(
        (day) => day.day === selectedDay
      );

      if (existingDayIndex >= 0) {
        days[existingDayIndex] = {
          ...days[existingDayIndex],

          exercises: [
            ...(days[existingDayIndex].exercises || []),
            newExercise,
          ],
        };
      } else {
        days.push({
          day: selectedDay,
          focus: "",
          exercises: [newExercise],
        });
      }

      return {
        ...currentPlan,
        days,
      };
    });
  };

  // =========================================================
  // تعديل التركيز الخاص باليوم المحدد
  // =========================================================

  const updateDayFocus = (focus) => {
    console.log("🟢 updateDayFocus called:", {
      selectedDay,
      focus,
    });

    setWorkoutPlan((prev) => {
      const currentPlan = prev || {
        days: [],
      };

      const days = [...(currentPlan.days || [])];

      const existingDayIndex = days.findIndex(
        (day) => day.day === selectedDay
      );

      console.log("🟡 Before update:", {
        currentPlan,
        existingDayIndex,
        days,
      });

      if (existingDayIndex >= 0) {
        days[existingDayIndex] = {
          ...days[existingDayIndex],
          focus,
        };
      } else {
        days.push({
          day: selectedDay,
          focus,
          exercises: [],
        });
      }

      const updatedPlan = {
        ...currentPlan,
        days,
      };

      console.log("🔵 After update:", updatedPlan);

      return updatedPlan;
    });
  };

  // =========================================================
  // حذف تمرين من اليوم المحدد
  // =========================================================

  const deleteExercise = (exerciseId) => {
    setWorkoutPlan((prev) => {
      if (!prev) return prev;

      return {
        ...prev,

        days: (prev.days || []).map((day) => {
          // لا نعدل الأيام الأخرى
          if (day.day !== selectedDay) {
            return day;
          }

          return {
            ...day,

            exercises: (day.exercises || []).filter(
              (exercise) =>
                exercise.id !== exerciseId &&
                exercise.exercise_id !== exerciseId
            ),
          };
        }),
      };
    });
  };

  // =========================================================
  // معرفة عدد التمارين الموجودة في يوم معين
  // =========================================================

  const getDayExercisesCount = (dayId) => {
    const planDay = workoutPlan?.days?.find(
      (day) => day.day === dayId
    );

    return planDay?.exercises?.length || 0;
  };

  // =========================================================
  // حفظ أو إرسال خطة التمرين
  // status = draft | active
  // =========================================================

  const saveWorkoutPlan = async (status = "draft") => {
    if (!selectedTrainee?.trainee?.id) {
      return;
    }

    try {
      setIsSaving(true);
      setError(null);

      // تحويل تمارين جميع الأيام إلى الشكل المطلوب من Backend
      const exercisesPayload = (
        workoutPlan?.days || []
      ).flatMap((day) =>
        (day.exercises || []).map((exercise) => ({
          day_of_week: day.day,
          exercise_id:
            exercise.exercise_id ?? exercise.id,
          sets: Number(exercise.sets),
          reps: Number(exercise.reps),
          rest_time:
            exercise.rest_time ?? null,
          notes: exercise.notes ?? null,
        }))
      );

      // البيانات التي سيتم إرسالها للـ API
      const payload = {
        start_date:
          workoutPlan?.start_date ?? null,

        end_date:
          workoutPlan?.end_date ?? null,

        status,

        days: (workoutPlan?.days || []).map((day) => ({
          day: day.day,
          focus: day.focus ?? null,
        })),

        exercises: exercisesPayload,
      };

      const response =
        await workoutPlanService.saveWorkoutPlan(
          selectedTrainee.trainee.id,
          payload
        );

      console.log(
        "Workout plan saved:",
        response
      );

      return response;
    } catch (error) {
      console.error(
        "Error saving workout plan:",
        error
      );

      setError(error);

      throw error;
    } finally {
      setIsSaving(false);
    }
  };

  // =========================================================
  // اختيار مشترك من القائمة
  // =========================================================

  const selectTrainee = async (subscription) => {
    if (!subscription?.trainee?.id) {
      return;
    }

    // تحديد المشترك
    setSelectedTrainee(subscription);

    // البدء من يوم الأحد
    setSelectedDay("sunday");

    // مسح الخطة القديمة مؤقتًا
    setWorkoutPlan(null);

    // جلب خطة المشترك الجديد
    await getWorkoutPlan(
      subscription.trainee.id
    );
  };

  // =========================================================
  // جلب المشتركين عند فتح الصفحة
  // =========================================================

  useEffect(() => {
    getTrainees();
  }, []);

  // =========================================================
  // إذا جاء traineeId من URL
  // نحدد المشترك تلقائيًا
  // =========================================================

  useEffect(() => {
    if (!traineeId || trainees.length === 0) {
      return;
    }

    const subscription = trainees.find(
      (item) =>
        String(item.trainee?.id) ===
        String(traineeId)
    );

    if (!subscription) {
      return;
    }

    setSelectedTrainee(subscription);

    getWorkoutPlan(
      subscription.trainee.id
    );
  }, [traineeId, trainees]);

  // =========================================================
  // البيانات والدوال التي يستطيع المكون استخدامها
  // =========================================================

  return {
    // الأيام
    daysOfWeek,
    availableDays,

    // اليوم المحدد
    selectedDay,
    setSelectedDay,
    selectedDayData,
    selectedDayFocus,

    // الخطة
    workoutPlan,
    exercises,

    // المشتركين
    trainees,
    selectedTrainee,
    selectTrainee,

    // التمارين
    addExercise,
    updateDayFocus,
    deleteExercise,
    getDayExercisesCount,

    // الحفظ
    saveWorkoutPlan,
    isSaving,

    // AI
    generateAIPlan,
    isGeneratingAI,

    // حالات التحميل والأخطاء
    isLoading,
    isTraineesLoading,
    error,
  };
};

export default useWorkoutPlan;