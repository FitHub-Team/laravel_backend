import Button from "../common/Button";
import { Sparkles, CalendarDays } from "lucide-react";

const Day = ({
  daysOfWeek,
  availableDays,
  selectedDay,
  setSelectedDay,
  getDayExercisesCount,
  onGenerateAI,
  isGeneratingAI,
}) => {
  return (
    <div className="space-y-4">
      {/* ===== بطاقة أيام التمرين ===== */}
      <div className="rounded-2xl border border-slate-200 bg-white p-4">
        {/* Header */}
        <div className="flex items-center gap-2 mb-3 justify-between">
          <div className="flex gap-1.5 items-center">
            <div className="w-7 h-7 rounded-lg bg-green-50 flex items-center justify-center">
              <CalendarDays
                size={14}
                className="text-[#407437]"
              />
            </div>

            <h4 className="text-sm font-bold text-slate-800">
              اختر أيام التمرين
            </h4>
          </div>

          {/* ===== زر الذكاء الاصطناعي ===== */}
          <Button
            title={
              isGeneratingAI
                ? "جاري التوليد..."
                : "توليد خطة AI"
            }
            onClick={onGenerateAI}
            disabled={isGeneratingAI}
            Icon={Sparkles}
            color="bg-gray-700 text-white"
            className="border border-gray-200"
          />
        </div>

        {/* Days Grid */}
        <div className="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-7 gap-2">
          {daysOfWeek.map((day) => {
            const isSelected = selectedDay === day.id;

            const isAvailable =
              availableDays.includes(day.id);

            const exercisesCount =
              getDayExercisesCount(day.id);

            return (
              <button
                key={day.id}
                type="button"
                onClick={() => setSelectedDay(day.id)}
                className={`
                  py-3 px-2 rounded-xl text-center transition-all
                  flex flex-col items-center justify-center cursor-pointer
                  border
                  ${
                    isSelected
                      ? "bg-[#407437] text-white border-[#407437] shadow-md"
                      : isAvailable
                        ? "bg-green-50 text-[#407437] border-green-200 hover:bg-green-100"
                        : "bg-white text-slate-600 border-slate-200 hover:bg-slate-50"
                  }
                `}
              >
                <span className="font-bold text-sm">
                  {day.name}
                </span>

                <span
                  className={`text-[10px] mt-0.5 ${
                    isSelected
                      ? "text-emerald-100"
                      : "text-slate-400"
                  }`}
                >
                  {exercisesCount > 0
                    ? `${exercisesCount} ${
                        exercisesCount === 1
                          ? "تمرين"
                          : "تمارين"
                      }`
                    : "راحة"}
                </span>
              </button>
            );
          })}
        </div>

        {/* Legend */}
        <div className="flex items-center gap-2 text-[11px] text-slate-500 mt-3 pt-3 border-t border-slate-100">
          <span className="w-2 h-2 rounded-sm bg-green-200 shrink-0"></span>

          <span>
            الأيام باللون الأخضر الفاتح هي الأيام المتاحة
            التي اختارها المشترك
          </span>
        </div>
      </div>
    </div>
  );
};

export default Day;