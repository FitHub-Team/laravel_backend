import { useEffect, useState } from "react";
import workoutPlanService from "../services/workoutPlanService";

// Display API validation details instead of hiding them in the browser console.
const errorMessage = (err, fallback) => {
  const errors = err?.response?.data?.errors;
  if (errors && typeof errors === "object") {
    const messages = Object.values(errors).flat().filter(Boolean);
    if (messages.length) return messages.join(" | ");
  }
  return err?.response?.data?.message || err?.message || fallback;
};

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
  "الأحد": "sunday", "الاحد": "sunday", "الإثنين": "monday", "الاثنين": "monday",
  "الثلاثاء": "tuesday", "الأربعاء": "wednesday", "الاربعاء": "wednesday",
  "الخميس": "thursday", "الجمعة": "friday", "السبت": "saturday",
};

const getId = (item) => item?.exercise_id ?? item?.exercise?.id ?? item?.id;
const normalizeItem = (item) => {
  const nested = item.exercise && typeof item.exercise === "object" ? item.exercise : null;
  const id = getId(item);
  return {
    ...item,
    exercise_id: id,
    exercise: nested ?? (item.name ? { id, name: item.name, description: item.description, muscle_group: item.muscle_group ?? item.category, equipment: item.equipment, difficulty_level: item.difficulty_level } : null),
    sets: item.sets ?? 3,
    reps: String(item.reps ?? "10-12"),
    rest_seconds: item.rest_seconds ?? item.rest_time ?? item.rest ?? 60,
    notes: item.notes ?? "",
  };
};

// The editor uses a flat days array, while AI provides weeks[0].days.
function normalizeAIPlan(result) {
  const response = result?.data?.weeks ? result.data : result;
  const week = response?.weeks?.[0];
  if (!Array.isArray(week?.days)) throw new Error("استجابة AI لا تحتوي أيام تدريب صالحة");
  const days = week.days.map((day) => {
    // Prefer the Arabic weekday because AI week numbering can begin on Saturday.
    const dayId = dayNames[day.day_name_ar] ?? daysOfWeek[Number(day.day_number) - 1]?.id;
    if (!dayId) throw new Error("تعذّر تحديد يوم في خطة AI");
    const isRest = day.is_rest || !day.session;
    return {
      day: dayId,
      focus: isRest ? "راحة" : (day.focus_ar || day.session?.focus_ar || day.session?.focus || ""),
      exercises: isRest ? [] : (day.session?.exercises ?? []).map(normalizeItem),
    };
  });
  return { ...response, days };
}

function normalizeSavedPlan(result) {
  const plan = result?.data?.days ? result.data : result;
  if (!plan || !Array.isArray(plan.days)) return plan ?? null;
  return {
    ...plan,
    days: plan.days.map((day) => ({
      ...day,
      exercises: (day.exercises ?? []).map(normalizeItem),
    })),
  };
}

export default function useWorkoutPlan(traineeId) {
  const [selectedDay, setSelectedDay] = useState("sunday");
  const [workoutPlan, setWorkoutPlan] = useState(null);
  const [trainees, setTrainees] = useState([]);
  const [selectedTrainee, setSelectedTrainee] = useState(null);
  const [isLoading, setIsLoading] = useState(false);
  const [isSaving, setIsSaving] = useState(false);
  const [isTraineesLoading, setIsTraineesLoading] = useState(false);
  const [isGeneratingAI, setIsGeneratingAI] = useState(false);
  const [error, setError] = useState(null);
  const [flashMessage, setFlashMessage] = useState(null);
  const showFlash = (message, type = "error") => {
    setFlashMessage({ id: Date.now(), message, type });
  };
  const dismissFlash = () => setFlashMessage(null);

  const availableDays = selectedTrainee?.trainee?.user_profile?.available_days ?? [];
  const currentDay = workoutPlan?.days?.find((day) => day.day === selectedDay);
  const selectedDayData = daysOfWeek.find((day) => day.id === selectedDay);
  const exercises = currentDay?.exercises ?? [];
  const selectedDayFocus = currentDay?.focus ?? "";
  const hasExercises = (workoutPlan?.days ?? []).some((day) => (day.exercises ?? []).length > 0);

  const getWorkoutPlan = async (id) => {
    if (!id) return;
    setIsLoading(true);
    setError(null);
    try {
      const plan = normalizeSavedPlan(await workoutPlanService.getWorkoutPlan(id));
      setWorkoutPlan(plan);
      setSelectedDay(plan?.days?.[0]?.day ?? "sunday");
      return plan;
    } catch (err) {
      setWorkoutPlan(null);
      setError(err);
      showFlash(errorMessage(err, "تعذّر تحميل خطة التدريب"));
      throw err;
    } finally {
      setIsLoading(false);
    }
  };

  const selectTrainee = async (subscription) => {
    if (!subscription?.trainee?.id) return;
    setSelectedTrainee(subscription);
    setSelectedDay("sunday");
    setWorkoutPlan(null);
    try { await getWorkoutPlan(subscription.trainee.id); }
    catch (_) { /* The hook already displays the loading failure. */ }
  };

  const generateAIPlan = async () => {
    if (!selectedTrainee?.trainee?.id) throw new Error("لم يتم اختيار مشترك");
    setIsGeneratingAI(true);
    setError(null);
    try {
      const raw = await workoutPlanService.generateAIWorkoutPlan(selectedTrainee.trainee.id);
      const plan = normalizeAIPlan(raw);
      setWorkoutPlan(plan);
      setSelectedDay(plan.days.find((day) => day.exercises.length)?.day ?? plan.days[0]?.day ?? "sunday");
      showFlash("تم توليد خطة التمارين بنجاح", "success");
      return plan;
    } catch (err) {
      setError(err);
      showFlash(errorMessage(err, "تعذّر توليد خطة التمارين"));
      throw err;
    } finally {
      setIsGeneratingAI(false);
    }
  };

  const updateSelectedDay = (updater) => {
    setWorkoutPlan((previous) => {
      const current = previous ?? { days: [] };
      const existing = (current.days ?? []).some((day) => day.day === selectedDay);
      const days = existing
        ? current.days.map((day) => day.day === selectedDay ? updater(day) : day)
        : [...(current.days ?? []), updater({ day: selectedDay, focus: "", exercises: [] })];
      return { ...current, days };
    });
  };

  const addExercise = (exercise) => {
    if (!exercise) return;
    const item = normalizeItem({ ...exercise, exercise_id: getId(exercise), sets: 3, reps: "10-12", rest_seconds: 60, notes: "" });
    if (!Number.isInteger(Number(item.exercise_id)) || Number(item.exercise_id) <= 0) {
      showFlash("لا يمكن إضافة التمرين: معرّفه غير صالح");
      return;
    }
    updateSelectedDay((day) => ({ ...day, exercises: [...(day.exercises ?? []), item] }));
  };

  const updateDayFocus = (focus) => updateSelectedDay((day) => ({ ...day, focus }));

  // The index disambiguates repeated exercises within one day.
  const updateExercise = (exerciseId, changes, index) => {
    updateSelectedDay((day) => ({
      ...day,
      exercises: (day.exercises ?? []).map((item, i) => {
        const matching = Number(getId(item)) === Number(exerciseId);
        if ((index != null ? i === index && matching : matching)) {
          return { ...item, ...changes };
        }
        return item;
      }),
    }));
  };

  const deleteExercise = (exerciseId, index) => {
    updateSelectedDay((day) => ({
      ...day,
      exercises: (day.exercises ?? []).filter((item, i) =>
        index != null ? !(i === index && Number(getId(item)) === Number(exerciseId)) : Number(getId(item)) !== Number(exerciseId)
      ),
    }));
  };

  const getDayExercisesCount = (dayId) => workoutPlan?.days?.find((day) => day.day === dayId)?.exercises?.length ?? 0;

  const saveWorkoutPlan = async (status = "draft") => {
    if (isSaving) return;
    if (!selectedTrainee?.trainee?.id) {
      showFlash("يرجى اختيار المشترك");
      return;
    }
    setIsSaving(true);
    setError(null);
    try {
      const allExercises = (workoutPlan?.days ?? []).flatMap((day) =>
        (day.exercises ?? []).map((exercise) => {
          const exerciseId = Number(getId(exercise));
          const sets = Number(exercise.sets);
          const rest = Number(exercise.rest_seconds ?? exercise.rest_time ?? 60);
          const reps = String(exercise.reps ?? "").trim();
          if (!Number.isInteger(exerciseId) || exerciseId <= 0 ||
            !Number.isInteger(sets) || sets < 1 ||
            !Number.isInteger(rest) || rest < 0 || !reps) {
            throw new Error("هناك تمرين ببيانات غير صالحة. راجعي المجموعات والتكرارات والراحة.");
          }
          return { day_of_week: day.day, exercise_id: exerciseId, sets, reps, rest_time: rest, notes: exercise.notes ?? null };
        })
      );
      if (!allExercises.length) throw new Error("أضيفي تمرينًا واحدًا على الأقل قبل الحفظ");
      const payload = {
        start_date: workoutPlan?.start_date ?? null,
        end_date: workoutPlan?.end_date ?? null,
        status,
        days: (workoutPlan?.days ?? []).map((day) => ({ day: day.day, focus: day.focus ?? null })),
        exercises: allExercises,
      };
      const response = await workoutPlanService.saveWorkoutPlan(selectedTrainee.trainee.id, payload);
      if (status === "active") {
        clearWorkoutTable();
        showFlash("تم إرسال الخطة للمشترك وتفريغ الجدول", "success");
      } else {
        showFlash("تم حفظ الخطة كمسودة بنجاح", "success");
      }
      return response;
    } catch (err) {
      setError(err);
      showFlash(errorMessage(err, "تعذّر حفظ الخطة"));
      return null;
    } finally {
      setIsSaving(false);
    }
  };

  const clearWorkoutTable = () => {
    setWorkoutPlan({ days: [], status: "draft" });
    setSelectedDay("sunday");
  };

  useEffect(() => {
    let active = true;
    setIsTraineesLoading(true);
    workoutPlanService.getTrainees()
      .then((data) => { if (active) setTrainees(Array.isArray(data) ? data : []); })
      .catch((err) => { if (active) { setTrainees([]); showFlash(errorMessage(err, "تعذّر تحميل المشتركين")); } })
      .finally(() => { if (active) setIsTraineesLoading(false); });
    return () => { active = false; };
  }, []);

  useEffect(() => {
    if (!traineeId || trainees.length === 0) return;
    const subscription = trainees.find((item) => String(item.trainee?.id) === String(traineeId));
    if (subscription) selectTrainee(subscription);
  }, [traineeId, trainees]);

  return {
    daysOfWeek, availableDays, selectedDay, setSelectedDay, selectedDayData,
    selectedDayFocus, workoutPlan, exercises, hasExercises,
    trainees, selectedTrainee, selectTrainee,
    addExercise, updateDayFocus, updateExercise, deleteExercise, getDayExercisesCount,
    saveWorkoutPlan, isSaving, generateAIPlan, isGeneratingAI,
    isLoading, isTraineesLoading, error, clearWorkoutTable, flashMessage, dismissFlash, showFlash,
  };
}
