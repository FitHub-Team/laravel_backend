export const exerciseTranslations = {
  difficulty: {
    beginner: "مبتدئ",
    intermediate: "متوسط",
    advanced: "متقدم",
    expert: "خبير",
  },

  equipment: {
    Dumbbell: "دمبل",
    Barbell: "بار",
    "Body Only": "وزن الجسم",
    Cable: "كيبل",
    Machine: "جهاز",
    Kettlebell: "كيتل بيل",
    "Resistance Band": "حبل مقاومة",
    "E-Z Curl Bar": "بار EZ",
    Other: "أخرى",
  },

  muscleGroup: {
    Biceps: "بايسبس",
    Triceps: "ترايسبس",
    Chest: "الصدر",
    Back: "الظهر",
    Shoulders: "الأكتاف",
    Forearms: "الساعد",
    Abdominals: "عضلات البطن",
    Abs: "عضلات البطن",
    Quadriceps: "الفخذ الأمامي",
    Hamstrings: "الفخذ الخلفي",
    Glutes: "الأرداف",
    Calves: "السمانة",
    Lower_Back: "أسفل الظهر",
    "Lower Back": "أسفل الظهر",
    Abductors: "العضلات المُبعِدة",
    Adductors: "العضلات المُقرِّبة",
  },

  category: {
    strength: "قوة",
    cardio: "كارديو",
    stretching: "تمدد",
    mobility: "مرونة وحركة",
  },

  mechanics: {
    isolation: "عزل",
    compound: "مركب",
  },

  forceType: {
    push: "دفع",
    pull: "سحب",
    static: "ثابت",
  },
};

export const translateDifficulty = (value) =>
  exerciseTranslations.difficulty[value] || value;

export const translateEquipment = (value) =>
  exerciseTranslations.equipment[value] || value;

export const translateCategory = (value) =>
  exerciseTranslations.category[value] || value;

export const translateMechanics = (value) =>
  exerciseTranslations.mechanics[value] || value;

export const translateForceType = (value) =>
  exerciseTranslations.forceType[value] || value;

export const translateMuscleGroup = (value) => {
  if (!value) return "";

  return value
    .split(",")
    .map((muscle) => muscle.trim())
    .map(
      (muscle) =>
        exerciseTranslations.muscleGroup[muscle] || muscle
    )
    .join("، ");
};