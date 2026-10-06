import { useState } from "react";
import { Trash2, Plus, Sparkles } from "lucide-react";
import Button from "../common/Button";
import ExerciseLibraryModal from "./ExerciseLibraryModal";

const Table = ({
  selectedDayData,
  exercises,
  handleDelete,
  selectedTrainee,
  workoutPlan,
  onAddExercise, // ← prop جديد لاستقبال التمرين المختار
  selectedDayFocus,
  onFocusChange,
}) => {
  const [isModalOpen, setIsModalOpen] = useState(false);

  // ===== عند اختيار تمرين من المكتبة =====
  const handleSelectExercise = (exercise) => {
    if (onAddExercise) {
      onAddExercise(exercise);
    }
    setIsModalOpen(false);
  };

  return (
    <div className="bg-white rounded-xl p-6 shadow-sm space-y-6">
      {/* Table Header */}
      <div className="flex flex-wrap items-center justify-between border-b pb-4 gap-4">
        <div>
          <div className="flex items-center gap-3">
            <h2 className="text-lg font-bold text-gray-800">
              جدول يوم {selectedDayData?.name || "غير محدد"}
            </h2>

            {exercises.length > 0 ? (
              <span className="bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs px-2.5 py-1 rounded-full font-medium">
                ✓ يوم تدريب نشط
              </span>
            ) : (
              <span className="bg-yellow-200 text-gray-700 border border-yellow-200 text-xs px-2.5 py-1 rounded-full font-medium">
                راحة
              </span>
            )}
          </div>
          <div className="flex items-center gap-3 mt-4 border-b border-green-200 pb-2 focus-within:border-green-500 transition-colors">
            <label
              htmlFor="workout-focus"
              className="text-sm font-semibold text-green-800 whitespace-nowrap"
            >
              التركيز:
            </label>

           <input
  id="workout-focus"
  type="text"
  value={selectedDayFocus}
  onChange={(e) => {
    console.log("🟠 INPUT CHANGED:", e.target.value);

    if (onFocusChange) {
      onFocusChange(e.target.value);
    } else {
      console.log("🔴 onFocusChange is undefined");
    }
  }}
  placeholder="مثال: تمارين الجزء العلوي"
  className="
    flex-1
    bg-transparent
    text-sm
    text-green-900
    placeholder:text-gray-400
    focus:outline-none
    text-right
  "
/>
          </div>
        </div>

        <div className="flex gap-1">
          <Button
            onClick={() => setIsModalOpen(true)}
            title="إضافة تمرين من المكتبة"
            Icon={Plus}
            color="bg-green-700 text-white"
            className="border border-gray-200"
          />

        
        </div>
      </div>

      {/* Exercises */}
      <div className="space-y-4">
        {exercises.length === 0 ? (
          <div className="text-center py-10 text-gray-400 text-sm">
            لم تتم إضافة أي تمارين لهذا اليوم بعد
          </div>
        ) : (
          exercises.map((exercise, index) => (
            <div
              key={exercise.id}
              className="bg-white border border-gray-200 rounded-xl p-4 shadow-sm"
            >
              {/* Exercise Header */}
              <div className="flex items-center justify-between mb-4 pb-2 border-b border-gray-100">
                <div className="flex items-center gap-3 min-w-0">
                  <span className="w-6 h-6 shrink-0 rounded-full bg-gray-100 text-gray-600 flex items-center justify-center text-xs font-bold">
                    {index + 1}
                  </span>

                  <h3 className="font-bold text-gray-800 text-sm sm:text-base truncate">
                    {exercise.name}
                  </h3>

                  <span className="bg-gray-100 text-gray-600 text-xs px-2.5 py-0.5 rounded whitespace-nowrap">
                    {exercise.category}
                  </span>
                </div>

                <button
                  type="button"
                  onClick={() => handleDelete(exercise.id)}
                  className="text-gray-400 hover:text-red-500 transition-colors shrink-0"
                  title="حذف التمرين"
                >
                  <Trash2 size={18} />
                </button>
              </div>

              {/* Exercise Details */}
              <div className="grid grid-cols-2 sm:grid-cols-5 gap-3 text-right">
                {/* Sets */}
                <div className="bg-gray-50 p-2.5 rounded-lg border border-gray-200">
                  <span className="block text-xs text-gray-400 mb-1">
                    المجموعات (Sets)
                  </span>
                  <input
                    type="text"
                    defaultValue={exercise.sets}
                    className="w-full bg-transparent font-semibold text-gray-800 text-sm focus:outline-none"
                  />
                </div>

                {/* Reps */}
                <div className="bg-gray-50 p-2.5 rounded-lg border border-gray-200">
                  <span className="block text-xs text-gray-400 mb-1">
                    التكرارات (Reps)
                  </span>
                  <input
                    type="text"
                    defaultValue={exercise.reps}
                    className="w-full bg-transparent font-semibold text-gray-800 text-sm focus:outline-none"
                  />
                </div>

                {/* Weight */}
                <div className="bg-gray-50 p-2.5 rounded-lg border border-gray-200">
                  <span className="block text-xs text-gray-400 mb-1">
                    الوزن المقترح
                  </span>
                  <input
                    type="text"
                    defaultValue={exercise.weight}
                    className="w-full bg-transparent font-semibold text-gray-800 text-sm focus:outline-none"
                  />
                </div>

                {/* Rest */}
                <div className="bg-gray-50 p-2.5 rounded-lg border border-gray-200">
                  <span className="block text-xs text-gray-400 mb-1">
                    الراحة (ثواني)
                  </span>
                  <input
                    type="text"
                    defaultValue={exercise.rest}
                    className="w-full bg-transparent font-semibold text-gray-800 text-sm focus:outline-none"
                  />
                </div>

                {/* Notes */}
                <div className="bg-gray-50 p-2.5 rounded-lg border border-gray-200 col-span-2 sm:col-span-1">
                  <span className="block text-xs text-gray-400 mb-1">
                    الملاحظات
                  </span>
                  <input
                    type="text"
                    defaultValue={exercise.notes}
                    className="w-full bg-transparent font-semibold text-gray-800 text-sm focus:outline-none truncate"
                  />
                </div>
              </div>
            </div>
          ))
        )}
      </div>

      {/* المودال */}
      <ExerciseLibraryModal
        isOpen={isModalOpen}
        onClose={() => setIsModalOpen(false)}
        onSelectExercise={handleSelectExercise}
      />
    </div>
  );
};

export default Table;
