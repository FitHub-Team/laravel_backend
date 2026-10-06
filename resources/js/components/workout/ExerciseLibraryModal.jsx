import { useMemo, useState } from "react";
import { ChevronLeft, Dumbbell, X } from "lucide-react";

import useExercises from "../../hooks/useExercises";
import ExerciseFilters from "./exercise-library/ExerciseFilters";
import ExerciseCard from "./exercise-library/ExerciseCard";
import ExerciseDetails from "./exercise-library/ExerciseDetails";
import FlashMessage from "../../components/common/FlashMessage";

const ExerciseLibraryModal = ({
  isOpen,
  onClose,
  onSelectExercise,
  selectedTrainee,
})  => {
  const [search, setSearch] = useState("");
  const [categoryFilter, setCategoryFilter] = useState("");
  const [difficultyFilter, setDifficultyFilter] = useState("");
  const [flashMessage, setFlashMessage] = useState("");
  const [selectedExercise, setSelectedExercise] = useState(null);

  // ================= جلب التمارين =================
  const { data: exercises = [], isLoading, isError } = useExercises(isOpen);

  // ================= الفئات =================
  const categories = useMemo(() => {
    const uniqueCategories = new Set(
      exercises.map((exercise) => exercise.category).filter(Boolean),
    );

    return Array.from(uniqueCategories);
  }, [exercises]);

  // ================= مستويات الصعوبة =================
  const difficulties = useMemo(() => {
    const uniqueDifficulties = new Set(
      exercises.map((exercise) => exercise.difficulty_level).filter(Boolean),
    );

    return Array.from(uniqueDifficulties);
  }, [exercises]);

  // ================= فلترة التمارين =================
  const filteredExercises = useMemo(() => {
    const normalizedSearch = search.toLowerCase().trim();

    return exercises.filter((exercise) => {
      const matchesSearch = exercise.name
        ?.toLowerCase()
        .includes(normalizedSearch);

      const matchesCategory =
        !categoryFilter || exercise.category === categoryFilter;

      const matchesDifficulty =
        !difficultyFilter || exercise.difficulty_level === difficultyFilter;

      return matchesSearch && matchesCategory && matchesDifficulty;
    });
  }, [exercises, search, categoryFilter, difficultyFilter]);

  // ================= إغلاق المودال =================
  const handleClose = () => {
    setSearch("");
    setCategoryFilter("");
    setDifficultyFilter("");
    setFlashMessage("");
    setSelectedExercise(null);

    onClose();
  };

  // ================= اختيار تمرين =================
  const handleSelect = (exercise) => {
    setSelectedExercise(exercise);
  };

  // ================= الرجوع للمكتبة =================
  const handleBack = () => {
    setSelectedExercise(null);
  };

  // ================= إضافة التمرين للخطة =================
  const handleAddExercise = () => {
    if (!selectedExercise) return;

    if (!onSelectExercise) {
      setFlashMessage("لا يمكن إضافة التمرين، حاول مرة أخرى");
      return;
    }

    onSelectExercise(selectedExercise);
    setSelectedExercise(null);
    handleClose();
  };

  if (!isOpen) return null;

  return (
    <div
      dir="rtl"
      className="
    fixed
    inset-0
    z-[100]
    flex
    items-center
    justify-center
    p-4
    bg-slate-900/40
    animate-fadeIn
  "
      onClick={handleClose}
    >
      <FlashMessage
        message={flashMessage}
        type="error"
        onClose={() => setFlashMessage("")}
      />

      <div
        className="
          relative
          w-full
          max-w-5xl
          max-h-[92vh]
          bg-white
          rounded-3xl
          shadow-[0_25px_80px_-20px_rgba(21,128,61,0.25)]
          flex
          flex-col
          overflow-hidden
          animate-scaleIn
          border
          border-green-100
        "
        onClick={(event) => event.stopPropagation()}
      >
        {/* ================= HEADER ================= */}
        <div
          className="
            relative
            flex
            items-center
            justify-between
            px-6
            py-5
            border-b
            border-green-100
          "
        >
          {/* Decorative background */}
          <div
            className="
              absolute
              -top-20
              -right-20
              w-40
              h-40
              bg-green-100/60
              rounded-full
              blur-3xl
              pointer-events-none
            "
          />

          <div className="relative flex items-center gap-3">
            {/* Back button - بقى أخضر */}
            {selectedExercise && (
              <button
                type="button"
                onClick={handleBack}
                className="
                  w-9
                  h-9
                  rounded-full
                  flex
                  items-center
                  justify-center
                  text-green-700
                  bg-green-50
                  hover:bg-green-100
                  hover:text-green-800
                  transition-all
                "
                title="العودة إلى المكتبة"
              >
                <ChevronLeft size={18} className="rotate-180" />
              </button>
            )}

            {/* Main icon */}
            <div
              className="
                w-11
                h-11
                rounded-2xl
                bg-green-700
                flex
                items-center
                justify-center
                shadow-[0_8px_20px_-6px_rgba(21,128,61,0.45)]
              "
            >
              <Dumbbell size={20} className="text-white" />
            </div>

            <div>
              <h2 className="text-base font-bold text-green-900 leading-tight">
                {selectedExercise ? "تفاصيل التمرين" : "مكتبة التمارين"}
              </h2>

              <p className="text-xs text-[#314158] mt-0.5">
                {selectedExercise
                  ? "راجع التفاصيل قبل الإضافة إلى الخطة"
                  : "اختر تمريناً لإضافته إلى خطة المتدرب"}
              </p>
            </div>
          </div>

          {/* Close */}
          <button
            type="button"
            onClick={handleClose}
            className="
              relative
              w-9
              h-9
              rounded-full
              flex
              items-center
              justify-center
              text-green-600
              hover:bg-green-50
              hover:text-green-800
              transition
            "
          >
            <X size={18} />
          </button>
        </div>

        {/* ================= BODY ================= */}
        {selectedExercise ? (
          <ExerciseDetails
  exercise={selectedExercise}
  onAdd={handleAddExercise}
  onBack={() => setSelectedExercise(null)}
/>
        ) : (
          <>
            <ExerciseFilters
              search={search}
              onSearchChange={setSearch}
              categoryFilter={categoryFilter}
              onCategoryChange={setCategoryFilter}
              difficultyFilter={difficultyFilter}
              onDifficultyChange={setDifficultyFilter}
              categories={categories}
              difficulties={difficulties}
              resultsCount={filteredExercises.length}
            />
            {/* ================= HEALTH WARNING ================= */}
            {selectedTrainee?.trainee?.user_profile?.medical_restrictions && (
              <div className="px-6 pt-4">
                <div className="flex items-start gap-3 rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3">
                  {/* Icon */}
                  <div className="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-amber-100">
                    <span className="text-lg">⚠️</span>
                  </div>

                  {/* Content */}
                  <div className="min-w-0">
                    <p className="text-sm font-bold text-amber-800">
                      تنبيه صحي
                    </p>

                    <p className="mt-1 text-xs leading-6 text-amber-700">
                      يجب مراعاة القيود الصحية للمتدرب عند اختيار التمارين.
                    </p>

                    <p className="mt-1 text-xs font-semibold leading-6 text-amber-800">
                      {selectedTrainee.trainee.user_profile.medical_restrictions}
                    </p>
                  </div>
                </div>
              </div>
            )}
            {/* ================= EXERCISES GRID ================= */}
            <div className="flex-1 overflow-y-auto px-6 py-5 modal-scroll">
              {/* Loading */}
              {isLoading && (
                <div className="flex flex-col items-center justify-center py-20 gap-3">
                  <div
                    className="
                      w-10
                      h-10
                      border-[3px]
                      border-green-100
                      border-t-green-700
                      rounded-full
                      animate-spin
                    "
                  />

                  <p className="text-sm text-green-600">
                    جاري تحميل التمارين...
                  </p>
                </div>
              )}

              {/* Error - لسه أحمر لأنه دلالي */}
              {isError && (
                <div className="text-center py-20">
                  <div
                    className="
                      w-14
                      h-14
                      rounded-2xl
                      bg-red-50
                      flex
                      items-center
                      justify-center
                      mx-auto
                      mb-3
                    "
                  >
                    <X size={22} className="text-red-500" />
                  </div>

                  <p className="text-red-500 text-sm">
                    حدث خطأ أثناء تحميل التمارين
                  </p>
                </div>
              )}

              {/* Empty */}
              {!isLoading && !isError && filteredExercises.length === 0 && (
                <div className="text-center py-20">
                  <div
                    className="
                        w-16
                        h-16
                        rounded-2xl
                        bg-green-50
                        flex
                        items-center
                        justify-center
                        mx-auto
                        mb-3
                      "
                  >
                    <Dumbbell size={26} className="text-green-300" />
                  </div>

                  <p className="text-green-600 text-sm">
                    لا توجد تمارين مطابقة
                  </p>
                </div>
              )}

              {/* Exercises */}
              {!isLoading && !isError && filteredExercises.length > 0 && (
                <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                  {filteredExercises.map((exercise) => (
                    <ExerciseCard
                      key={exercise.id}
                      exercise={exercise}
                      onSelect={() => handleSelect(exercise)}
                    />
                  ))}
                </div>
              )}
            </div>

            {/* ================= FOOTER ================= */}
            <div
              className="
                px-6
                py-4
                border-t
                border-green-100
                flex
                items-center
                justify-between
              "
            >
              <p className="text-xs text-[#314158]">
                اضغط على تمرين لعرض تفاصيله
              </p>

              {/* زر الإغلاق - بقى أخضر زي زر الرجوع */}
              <button
                type="button"
                onClick={handleClose}
                className="
                  px-5
                  h-[40px]
                  rounded-full
                  border
                  border-green-200
                  bg-white
                  text-green-700
                  text-sm
                  font-semibold
                  hover:bg-green-50
                  hover:border-green-300
                  transition
                "
              >
                إغلاق
              </button>
            </div>
          </>
        )}
      </div>

      {/* ================= MODAL STYLES ================= */}
      <style>{`
        .modal-scroll::-webkit-scrollbar {
          width: 8px;
        }

        .modal-scroll::-webkit-scrollbar-track {
          background: transparent;
        }

        .modal-scroll::-webkit-scrollbar-thumb {
          background: #314158;
          border-radius: 999px;
          border: 2px solid #fff;
        }

        .modal-scroll::-webkit-scrollbar-thumb:hover {
          background: #314158;
        }

        .modal-scroll {
          scrollbar-width: thin;
          scrollbar-color: #bbf7d0 transparent;
        }

        @keyframes fadeIn {
          from {
            opacity: 0;
          }

          to {
            opacity: 1;
          }
        }

        @keyframes scaleIn {
          from {
            opacity: 0;
            transform: scale(0.96) translateY(10px);
          }

          to {
            opacity: 1;
            transform: scale(1) translateY(0);
          }
        }

        .animate-fadeIn {
          animation: fadeIn 0.2s ease-out;
        }

        .animate-scaleIn {
          animation: scaleIn 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
      `}</style>
    </div>
  );
};

export default ExerciseLibraryModal;
