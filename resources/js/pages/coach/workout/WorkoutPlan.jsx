import { useEffect, useRef, useState } from "react";
import {
  Save,
  Send,
  ChevronDown,
  Search,
  UserRound,
} from "lucide-react";
import { useSearchParams } from "react-router-dom";

import Button from "../../../components/common/Button";
import WorkoutActions from "../../../components/workout/WorkoutActions";
import WorkoutDay from "../../../components/workout/WorkoutDay";
import WorkoutTable from "../../../components/workout/WorkoutTable";

import NutritionPlan from "../../../components/Nutrition/NutritionPlan";

import useWorkoutPlan from "../../../hooks/useWorkoutPlan";

import { formatLastUpdated } from "../../../utils/dateHelper";

const WorkoutPlan = () => {
  const meals = [
    {
      id: "breakfast",
      title: "الإفطار",
      calories: 662,
      items: [
        {
          name: "شوفان الحبة الكاملة",
          qty: "80 جرام",
          cal: 300,
          macros: "ب: 10غ ك: 54غ د: 5غ",
        },
        {
          name: "بيض بلدي مسلوق",
          qty: "3 حبات",
          cal: 216,
          macros: "ب: 18.9غ ك: 1.2غ د: 14.7غ",
        },
        {
          name: "مكسرات لوز ني",
          qty: "1 حبة",
          cal: 216,
          macros: "ب: 18.9غ ك: 1.2غ د: 14.7غ",
        },
      ],
    },
    {
      id: "lunch",
      title: "الغداء",
      calories: 662,
      items: [
        {
          name: "صدر دجاج مشوي متبل",
          qty: "3 حبات",
          cal: 216,
          macros: "ب: 18.9غ ك: 1.2غ د: 14.7غ",
        },
        {
          name: "أرز بسمتي أبيض",
          qty: "1 حبة",
          cal: 216,
          macros: "ب: 18.9غ ك: 1.2غ د: 14.7غ",
        },
        {
          name: "سلطة خضراء مشكلة بزيت الزيتون",
          qty: "1 حبة",
          cal: 216,
          macros: "ب: 18.9غ ك: 1.2غ د: 14.7غ",
        },
      ],
    },
    {
      id: "dinner",
      title: "العشاء",
      calories: 662,
      items: [
        {
          name: "بطاطا حلوة مشوية",
          qty: "1 حبة",
          cal: 216,
          macros: "ب: 18.9غ ك: 1.2غ د: 14.7غ",
        },
        {
          name: "صدر دجاج مشوي متبل",
          qty: "1 حبة",
          cal: 216,
          macros: "ب: 18.9غ ك: 1.2غ د: 14.7غ",
        },
      ],
    },
    {
      id: "snacks",
      title: "الوجبات الخفيفة",
      calories: 662,
      items: [
        {
          name: "زبادي يوناني مع توت",
          qty: "200 مل",
          cal: 130,
          macros: "ب: 21غ ك: 8غ د: 0.5غ",
        },
        {
          name: "مكيال واي بروتين بعد التمرين",
          qty: "1 مكيال",
          cal: 120,
          macros: "ب: 25غ ك: 3غ د: 0.5غ",
        },
      ],
    },
  ];

  const macroGoals = [
    {
      label: "السعرات اليومية المستهدفة",
      value: "2850",
      unit: "سعرة حرارية",
    },
    {
      label: "البروتين المستهدف",
      value: "180",
      unit: "جرام / يوم (المخطط: 186جم)",
    },
    {
      label: "الكربوهيدرات المستهدفة",
      value: "340",
      unit: "جرام / يوم (المخطط: 186جم)",
    },
    {
      label: "الدهون الصحية المستهدفة",
      value: "75",
      unit: "جرام / يوم (المخطط: 186جم)",
    },
  ];

  const [searchParams] = useSearchParams();
  const traineeId = searchParams.get("trainee");

  const [traineeSearch, setTraineeSearch] = useState("");
  const [showTraineeDropdown, setShowTraineeDropdown] = useState(false);
  const traineeDropdownRef = useRef(null);

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
    trainees,
    selectedTrainee,
    selectTrainee,
    addExercise,
    updateDayFocus,
    deleteExercise,
    getDayExercisesCount,
    saveWorkoutPlan,
    isSaving,
    isLoading,
    generateAIPlan,
    isGeneratingAI,
  } = useWorkoutPlan(traineeId);

  const filteredTrainees = trainees.filter((subscription) =>
    subscription.trainee?.full_name
      ?.toLowerCase()
      .includes(traineeSearch.toLowerCase()),
  );

  useEffect(() => {
    const handleClickOutside = (event) => {
      if (
        traineeDropdownRef.current &&
        !traineeDropdownRef.current.contains(event.target)
      ) {
        setShowTraineeDropdown(false);
      }
    };

    document.addEventListener("mousedown", handleClickOutside);

    return () => {
      document.removeEventListener("mousedown", handleClickOutside);
    };
  }, []);

  const handleSaveDraft = async () => {
    try {
      await saveWorkoutPlan("draft");
      alert("تم حفظ الخطة كمسودة بنجاح");
    } catch (error) {
      console.error("Validation errors:", error.response?.data?.errors);
      console.error("Full response:", error.response?.data);
      throw error;
    }
  };

  const handleSendPlan = async () => {
    try {
      await saveWorkoutPlan("active");
      alert("تم إرسال الخطة بنجاح");
    } catch (error) {
      alert("حدث خطأ أثناء إرسال الخطة");
    }
  };

  const handleGenerateAI = async () => {
    if (!selectedTrainee) {
      alert("يرجى اختيار المشترك أولاً");
      return;
    }

    try {
      const result = await generateAIPlan();

      console.log("AI PLAN:", result);
      console.log("AI WEEK:", result?.weeks?.[0]);
      console.log(
        "AI WEEK JSON:",
        JSON.stringify(result?.weeks?.[0], null, 2),
      );

      alert("تم توليد خطة التمارين بنجاح");
    } catch (error) {
      console.error("AI generation error:", error);

      alert(
        error.response?.data?.message ||
          error.message ||
          "حدث خطأ أثناء توليد خطة التمارين",
      );
    }
  };

  return (
    <div dir="rtl">
      <div className="max-w-6xl mx-auto space-y-6">
        {/* ================= اختيار المشترك ================= */}

        <div className="bg-white rounded-xl p-4 shadow-sm">
          <div className="flex flex-col md:flex-row items-center justify-between gap-4">
            <div
              className="flex flex-col items-end w-full md:w-80 relative"
              ref={traineeDropdownRef}
            >
              <label className="text-xs text-gray-500 mb-1 text-right w-full">
                اختر المشترك لإعداد أو تعديل خطته
              </label>

              <button
                type="button"
                onClick={() => setShowTraineeDropdown((prev) => !prev)}
                className="
                  w-full
                  bg-gray-50
                  border
                  border-gray-200
                  rounded-lg
                  p-3
                  text-right
                  flex
                  items-center
                  justify-between
                  hover:bg-gray-100
                  transition-colors
                "
              >
                <ChevronDown size={18} className="text-gray-400" />

                <span className="text-sm font-bold text-gray-800">
                  {selectedTrainee
                    ? selectedTrainee.trainee.full_name
                    : "اختر المشترك"}
                </span>
              </button>

              {showTraineeDropdown && (
                <div
                  className="
                    absolute
                    top-full
                    mt-2
                    right-0
                    w-full
                    bg-white
                    border
                    border-gray-200
                    rounded-xl
                    shadow-lg
                    z-50
                    overflow-hidden
                  "
                >
                  <div className="p-3 border-b border-gray-100">
                    <div className="relative">
                      <Search
                        size={16}
                        className="
                          absolute
                          right-3
                          top-1/2
                          -translate-y-1/2
                          text-gray-400
                        "
                      />

                      <input
                        type="text"
                        placeholder="ابحث عن المشترك..."
                        value={traineeSearch}
                        onChange={(e) => setTraineeSearch(e.target.value)}
                        className="
                          w-full
                          bg-gray-50
                          border
                          border-gray-200
                          rounded-lg
                          py-2
                          pr-9
                          pl-3
                          text-sm
                          text-right
                          focus:outline-none
                          focus:border-emerald-500
                        "
                        autoFocus
                      />
                    </div>
                  </div>

                  <div className="max-h-60 overflow-y-auto">
                    {filteredTrainees.map((subscription) => (
                      <button
                        key={subscription.id}
                        type="button"
                        onClick={async () => {
                          await selectTrainee(subscription);
                          setShowTraineeDropdown(false);
                          setTraineeSearch("");
                        }}
                        className="
                          w-full
                          px-4
                          py-3
                          text-right
                          hover:bg-emerald-50
                          transition-colors
                          border-b
                          border-gray-50
                        "
                      >
                        <p className="text-sm font-bold text-gray-800">
                          {subscription.trainee.full_name}
                        </p>

                        <p className="text-xs text-gray-400 mt-1">
                          {subscription.trainee.email}
                        </p>
                      </button>
                    ))}

                    {filteredTrainees.length === 0 && (
                      <div className="p-4 text-center text-sm text-gray-400">
                        لا يوجد مشترك بهذا الاسم
                      </div>
                    )}
                  </div>
                </div>
              )}
            </div>

            {/* ================= حالة الخطة ================= */}

            {selectedTrainee && (
              <>
                <div>
                  <p>
                    الهدف:{" "}
                    {selectedTrainee?.trainee?.user_profile?.goal?.title ??
                      "غير محدد"}
                  </p>

                  <p className="text-xs mt-1 text-gray-600">
                    الحالة:{" "}
                    <span
                      className={`font-medium ${
                        workoutPlan?.status === "active"
                          ? "text-blue-500"
                          : workoutPlan?.status === "completed"
                            ? "text-green-500"
                            : workoutPlan?.status === "cancelled"
                              ? "text-red-500"
                              : "text-orange-500"
                      }`}
                    >
                      {{
                        draft: "مسودة",
                        active: "نشطة",
                        completed: "مكتملة",
                        cancelled: "ملغاة",
                      }[workoutPlan?.status] ?? "الخطة لم ترسل بعد"}
                    </span>

                    {workoutPlan && (
                      <>
                        {" • "}
                        آخر تحديث:{" "}
                        <span className="font-medium text-gray-600">
                          {formatLastUpdated(workoutPlan.updated_at)}
                        </span>
                      </>
                    )}
                  </p>
                </div>

                <div
                  className="
                    flex
                    flex-col
                    items-center
                    justify-center
                    bg-green-50
                    text-[#407437]
                    px-3
                    py-1
                    rounded-full
                    border
                    border-green-100
                  "
                >
                  <span className="text-[10px] font-bold">إصدار</span>

                  <span className="text-[10px]">
                    v {workoutPlan?.version ?? 0}.0
                  </span>
                </div>
              </>
            )}

            {/* ================= الأزرار ================= */}

            {selectedTrainee && (
              <div className="flex items-center gap-3 w-full md:w-auto justify-end">
                <Button
                  title="حفظ كمسودة"
                  Icon={Save}
                  onClick={handleSaveDraft}
                  disabled={isSaving || !exercises.length}
                  color="bg-[#407437] text-white"
                />

                <Button
                  title="إرسال الخطة للمشترك"
                  Icon={Send}
                  onClick={handleSendPlan}
                  disabled={isSaving || !exercises.length}
                  color="bg-[#407437] text-white"
                />
              </div>
            )}
          </div>
        </div>

        {/* ================= محتوى الخطة ================= */}

        {!selectedTrainee && (
          <div className="bg-white rounded-xl border border-gray-200 min-h-[420px] flex items-center justify-center">
            <div className="text-center px-6">
              <div className="w-16 h-16 mx-auto mb-4 rounded-full bg-green-50 flex items-center justify-center">
                <UserRound
                  size={30}
                  strokeWidth={1.8}
                  className="text-green-600"
                />
              </div>

              <h3 className="text-lg font-bold text-gray-800 mb-2">
                اختر مشتركًا لعرض خطته
              </h3>

              <p className="text-sm text-gray-500 max-w-md leading-6">
                قم باختيار أحد المشتركين من القائمة بالأعلى لعرض خطة التمارين
                والتغذية الخاصة به أو إنشاء خطة جديدة.
              </p>
            </div>
          </div>
        )}

        {selectedTrainee && (
          <>
            <WorkoutActions
              activeSection={activeSection}
              onSectionChange={setActiveSection}
            />

            {activeSection === "workout" && (
              <>
                <WorkoutDay
                  daysOfWeek={daysOfWeek}
                  availableDays={availableDays}
                  selectedDay={selectedDay}
                  setSelectedDay={setSelectedDay}
                  getDayExercisesCount={getDayExercisesCount}
                  onGenerateAI={handleGenerateAI}
                  isGeneratingAI={isGeneratingAI}
                />

                <WorkoutTable
                  selectedDayData={selectedDayData}
                  exercises={exercises}
                  handleDelete={deleteExercise}
                  workoutPlan={workoutPlan}
                  onAddExercise={addExercise}
                  selectedDayFocus={selectedDayFocus}
                  onFocusChange={updateDayFocus}
                />
              </>
            )}

            {activeSection === "nutrition" && (
              <NutritionPlan
                summary={{
                  totalCalories: 2106,
                  protein: 180,
                  carbs: 230,
                  fat: 60,
                  savedCalories: 2050,
                }}
                meals={[]}
                onAddFood={() => {}}
                onDeleteFood={() => {}}
              />
            )}

            {/*
            {activeSection === "nutrition" && <NutritionTable />}
            {activeSection === "versions" && <WorkoutVersions />}
            */}
          </>
        )}
      </div>
    </div>
  );
};

export default WorkoutPlan;