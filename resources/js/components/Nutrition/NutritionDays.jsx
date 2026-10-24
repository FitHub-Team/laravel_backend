import { CalendarDays, Sparkles } from "lucide-react";

const daysOfWeek = [
    { id: "saturday", name: "السبت" },
    { id: "sunday", name: "الأحد" },
    { id: "monday", name: "الإثنين" },
    { id: "tuesday", name: "الثلاثاء" },
    { id: "wednesday", name: "الأربعاء" },
    { id: "thursday", name: "الخميس" },
    { id: "friday", name: "الجمعة" },
];
import Button from "../common/Button";
export default function NutritionDays({ selectedDay, onSelectDay }) {
    return (
        <div className="rounded-2xl border border-slate-200 bg-white p-4 ">
            <div className="flex items-center gap-2 mb-3 justify-between">
                <div className="mb-4 flex items-center gap-2">
                    <div ClassName="w-7 h-7 rounded-lg bg-green-50 flex items-center justify-center">
                        {" "}
                        <CalendarDays size={18} className="text-[#407437]" />
                    </div>

                    <h4 className="text-sm font-bold text-slate-800">
                        اختر اليوم لعرض خطة التغذية
                    </h4>
                </div>
                <Button
                    title="توليد خطة AI"
                    // onClick={onGenerateAI}
                    // disabled={isGeneratingAI}
                    Icon={Sparkles}
                    color="bg-gray-700 text-white"
                    className="border border-gray-200"
                />
                {/* <Button
                    title={isGeneratingAI ? "جاري التوليد..." : "توليد خطة AI"}
                    onClick={onGenerateAI}
                    disabled={isGeneratingAI}
                    Icon={Sparkles}
                    color="bg-gray-700 text-white"
                    className="border border-gray-200"
                /> */}
            </div>

            <div className="grid grid-cols-2 gap-2 sm:grid-cols-4 md:grid-cols-7">
                {daysOfWeek.map((day) => {
                    const isSelected = selectedDay === day.id;

                    return (
                        <button
                            key={day.id}
                            type="button"
                            onClick={() => onSelectDay(day.id)}
                            className={`
                                rounded-xl border px-3 py-3
                                text-sm font-semibold transition-all
                                ${
                                    isSelected
                                        ? "border-[#407437] bg-[#407437] text-white"
                                        : "border-gray-200 bg-white text-gray-600 hover:bg-green-50"
                                }
                            `}
                        >
                            {day.name}
                        </button>
                    );
                })}
            </div>
        </div>
    );
}
