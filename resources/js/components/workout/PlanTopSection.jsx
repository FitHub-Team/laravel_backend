import {
    Save,
    Send,
    ChevronDown,
    Search,
    UserRound,
    X,
    CheckCircle2,
    AlertCircle,
} from "lucide-react";

import Button from "../common/Button";
import WorkoutActions from "./WorkoutActions";
import { formatLastUpdated } from "../../utils/dateHelper";

const statusLabels = {
    draft: "مسودة",
    active: "نشطة",
    completed: "مكتملة",
    cancelled: "ملغاة",
};

export default function PlanTopSection({
    planType = "workout",
    flashMessage,
    dismissFlash,
    traineeSearch,
    setTraineeSearch,
    showTraineeDropdown,
    setShowTraineeDropdown,
    dropdownRef,
    filteredTrainees,
    selectedTrainee,
    selectTrainee,
    workoutPlan,
    onSaveDraft,
    onSendPlan,
    isSaving = false,
    isLoading = false,
    canSave = true,
    activeSection,
}) {
    return (
        <div className="space-y-6">
            {/* Notification */}
            {flashMessage && (
                <div
                    role="status"
                    aria-live="polite"
                    className={`
                        fixed left-5 top-5 z-[100]
                        flex max-w-md items-start gap-3
                        rounded-xl border bg-white
                        px-4 py-3 shadow-lg
                        ${
                            flashMessage.type === "success"
                                ? "border-green-200 text-green-800"
                                : "border-red-200 text-red-700"
                        }
                    `}
                >
                    {flashMessage.type === "success" ? (
                        <CheckCircle2 size={20} className="mt-0.5 shrink-0" />
                    ) : (
                        <AlertCircle size={20} className="mt-0.5 shrink-0" />
                    )}

                    <span className="flex-1 text-sm leading-6">
                        {flashMessage.message}
                    </span>

                    <button
                        type="button"
                        onClick={dismissFlash}
                        aria-label="إغلاق الرسالة"
                        className="shrink-0"
                    >
                        <X size={16} />
                    </button>
                </div>
            )}

            {/* Trainee + Plan Actions */}
            <section className="rounded-xl bg-white p-4 shadow-sm">
                <div
                    className="
                        flex flex-col
                        items-start justify-between
                        gap-4
                        md:flex-row md:items-center
                    "
                >
                    {/* Trainee selector */}
                    <div ref={dropdownRef} className="relative w-full md:w-80">
                        <label className="mb-1 block text-right text-xs text-gray-500">
                            اختر المشترك لإعداد أو تعديل خطته
                        </label>

                        <button
                            type="button"
                            onClick={() =>
                                setShowTraineeDropdown((value) => !value)
                            }
                            className="
                                flex w-full
                                items-center justify-between
                                rounded-lg
                                border border-gray-200
                                bg-gray-50
                                p-3
                                text-right
                                hover:bg-gray-100
                            "
                        >
                            <ChevronDown size={18} className="text-gray-400" />

                            <span className="text-sm font-bold">
                                {selectedTrainee?.trainee?.full_name ||
                                    "اختر المشترك"}
                            </span>
                        </button>

                        {showTraineeDropdown && (
                            <div
                                className="
                                    absolute right-0 top-full z-50
                                    mt-2 w-full
                                    overflow-hidden
                                    rounded-xl
                                    border border-gray-200
                                    bg-white
                                    shadow-lg
                                "
                            >
                                <div className="relative border-b p-3">
                                    <Search
                                        size={16}
                                        className="absolute right-6 top-6 text-gray-400"
                                    />

                                    <input
                                        autoFocus
                                        type="text"
                                        placeholder="ابحث عن المشترك..."
                                        value={traineeSearch}
                                        onChange={(event) =>
                                            setTraineeSearch(event.target.value)
                                        }
                                        className="
                                            w-full rounded-lg
                                            border border-gray-200
                                            bg-gray-50
                                            py-2 pl-3 pr-9
                                            text-sm
                                            outline-none
                                        "
                                    />
                                </div>

                                <div className="max-h-60 overflow-y-auto">
                                    {filteredTrainees.map((entry) => (
                                        <button
                                            key={entry.id}
                                            type="button"
                                            onClick={async () => {
                                                setShowTraineeDropdown(false);

                                                setTraineeSearch("");

                                                await selectTrainee(entry);
                                            }}
                                            className="
                                                    w-full
                                                    border-b border-gray-50
                                                    px-4 py-3
                                                    text-right
                                                    hover:bg-emerald-50
                                                "
                                        >
                                            <div className="text-sm font-bold">
                                                {entry.trainee?.full_name}
                                            </div>

                                            <div className="mt-1 text-xs text-gray-400">
                                                {entry.trainee?.email}
                                            </div>
                                        </button>
                                    ))}

                                    {filteredTrainees.length === 0 && (
                                        <p className="p-4 text-center text-sm text-gray-400">
                                            لا يوجد مشترك بهذا الاسم
                                        </p>
                                    )}
                                </div>
                            </div>
                        )}
                    </div>

                    {/* Selected trainee info */}
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

                    {/* Save / Send */}
                    {selectedTrainee && (
                        <div className="flex flex-wrap gap-3">
                            <Button
                                title="حفظ كمسودة"
                                Icon={Save}
                                onClick={onSaveDraft}
                                disabled={isSaving || isLoading || !canSave}
                                color="bg-[#407437] text-white"
                            />

                            <Button
                                title="إرسال الخطة للمشترك"
                                Icon={Send}
                                onClick={onSendPlan}
                                disabled={isSaving || isLoading || !canSave}
                                color="bg-[#407437] text-white"
                            />
                        </div>
                    )}
                </div>
            </section>

            {/* Empty State */}
            {!selectedTrainee && (
                <section
                    className="
                        flex min-h-[420px]
                        items-center justify-center
                        rounded-xl
                        border border-gray-200
                        bg-white
                    "
                >
                    <div className="px-6 text-center">
                        <UserRound
                            size={30}
                            className="mx-auto mb-4 text-green-600"
                        />

                        <h3 className="mb-2 text-lg font-bold">
                            اختر مشتركًا لعرض خطته
                        </h3>

                        <p className="text-sm text-gray-500">
                            اختر مشتركًا من القائمة بالأعلى لعرض أو إنشاء الخطة.
                        </p>
                    </div>
                </section>
            )}

            {/* Navigation */}
           {selectedTrainee && planType === "workout" && (
    <WorkoutActions activeSection={activeSection} />
)}
        </div>
    );
}
