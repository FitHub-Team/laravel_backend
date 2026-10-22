import React from "react";
import {
    Eye,
    Pencil,
    Send,
    Copy,
    FolderOpen,
    MoreVertical,
} from "lucide-react";
import { useNavigate } from "react-router-dom";
import PlansTabs from "../../../components/workout/plan/PlansTabs";
import PlanActionsMenu from "./PlanActionsMenu";
export default function CurrentPlansTable({
    plans = [],
    onOpen,
    onView,
    onSend,
    onDuplicate,
    sendingPlanId = null,
    status,
    onDelete,
    onCancel,
    processingPlanId = null,
}) {
    const getStatus = (status) => {
        const statuses = {
            draft: {
                label: "مسودة",
                className: "bg-amber-50 text-amber-700",
            },
            active: {
                label: "نشطة",
                className: "bg-[#EEF5EC] text-[#407437]",
            },
            completed: {
                label: "مكتملة",
                className: "bg-blue-50 text-blue-600",
            },
        };

        return (
            statuses[status] || {
                label: status || "غير محدد",
                className: "bg-gray-100 text-gray-600",
            }
        );
    };

    if (!plans.length) {
        return (
            <div className="rounded-2xl border border-dashed border-gray-200 bg-white p-12 text-center">
                <p className="text-sm font-medium text-gray-600">
                    لا توجد خطط حالية
                </p>

                <p className="mt-1 text-xs text-gray-400">
                    ابدأ بإنشاء خطة جديدة لأحد المشتركين.
                </p>
            </div>
        );
    }

    return (
        <main>
            <div
                dir="rtl"
                className=" rounded-2xl border border-gray-200 bg-white"
            >
                {/* Header */}
                <div className="hidden grid-cols-[2fr_1fr_1fr_1.3fr_2fr] gap-4 border-b border-gray-100 bg-gray-50/70 px-5 py-4 text-xs font-semibold text-gray-500 md:grid">
                    <div>المشترك</div>
                    <div>الحالة</div>
                    <div>الإصدار</div>
                    <div>آخر تعديل</div>
                    <div>الإجراءات</div>
                </div>

                {/* Rows */}
                <div className="divide-y divide-gray-100">
                    {plans.map((plan) => {
                        const status = getStatus(plan.status);

                        const traineeName =
                            plan.trainee?.full_name || "مشترك غير معروف";

                        const traineeEmail = plan.trainee?.email || "";

                        return (
                            <div
                                key={plan.id}
                                className="
                                grid grid-cols-1 gap-4
                                px-5 py-4
                                transition
                                hover:bg-[#FAFCF9]
                                md:grid-cols-[2fr_1fr_1fr_1.3fr_2fr]
                                md:items-center
                            "
                            >
                                {/* Trainee */}
                                <div className="min-w-0">
                                    <p className="truncate text-sm font-bold text-gray-900">
                                        {traineeName}
                                    </p>

                                    {traineeEmail && (
                                        <p className="mt-1 truncate text-xs text-gray-400">
                                            {traineeEmail}
                                        </p>
                                    )}
                                </div>

                                {/* Status */}
                                <div>
                                    <span
                                        className={`
                                        inline-flex items-center gap-1.5
                                        rounded-full px-3 py-1.5
                                        text-xs font-semibold
                                        ${status.className}
                                    `}
                                    >
                                        <span className="h-1.5 w-1.5 rounded-full bg-current" />

                                        {status.label}
                                    </span>
                                </div>

                                {/* Version */}
                                <div className="text-sm font-semibold text-gray-700">
                                    v{plan.version ?? 1}
                                </div>

                                {/* Last Update */}
                                <div>
                                    <p className="text-sm text-gray-700">
                                        {plan.updated_at
                                            ? new Date(
                                                  plan.updated_at,
                                              ).toLocaleDateString("ar")
                                            : "—"}
                                    </p>

                                    {plan.updated_at && (
                                        <p className="mt-1 text-xs text-gray-400">
                                            {new Date(
                                                plan.updated_at,
                                            ).toLocaleTimeString("ar", {
                                                hour: "2-digit",
                                                minute: "2-digit",
                                            })}
                                        </p>
                                    )}
                                </div>

                                {/* Actions */}
                                <div className="flex flex-wrap items-center gap-2">
                                    {plan.status === "draft" ? (
                                        <>
                                            <button
                                                type="button"
                                                onClick={() => onOpen?.(plan)}
                                                className="
                                                flex items-center gap-1.5
                                                rounded-lg
                                                bg-[#EEF5EC]
                                                px-3 py-2
                                                text-xs font-semibold
                                                text-[#407437]
                                                transition
                                                hover:bg-[#E1EEDF]
                                            "
                                            >
                                                <FolderOpen size={15} />
                                                تعديل الخطة
                                            </button>

                                            <button
                                                type="button"
                                                onClick={() => onSend?.(plan)}
                                                disabled={
                                                    sendingPlanId !== null
                                                }
                                                className="
                                                flex items-center gap-1.5
                                                rounded-lg
                                                bg-[#407437]
                                                px-3 py-2
                                                text-xs font-semibold
                                                text-white
                                                transition
                                                hover:bg-[#355F2C]
                                            "
                                            >
                                                <Send size={14} />
                                                إرسال
                                            </button>
                                        </>
                                    ) : (
                                        <>
                                            <button
                                                type="button"
                                                onClick={() => onView?.(plan)}
                                                className="
                                                flex items-center gap-1.5
                                                rounded-lg
                                                border border-gray-200
                                                px-3 py-2
                                                text-xs font-semibold
                                                text-gray-600
                                                transition
                                                hover:border-[#C8DCC3]
                                                hover:text-[#407437]
                                            "
                                            >
                                                <Eye size={15} />
                                                عرض
                                            </button>

                                            <button
                                                type="button"
                                                onClick={() =>
                                                    onDuplicate?.(plan)
                                                }
                                                className="
                                                flex items-center gap-1.5
                                                rounded-lg
                                                bg-[#EEF5EC]
                                                px-3 py-2
                                                text-xs font-semibold
                                                text-[#407437]
                                                transition
                                                hover:bg-[#E1EEDF]
                                            "
                                            >
                                                <Copy size={14} />
                                                استخدام كنسخة جديدة
                                            </button>

                                        </>
                                    )}
                                    {/*  الغاء الخطة  */}

                                    <PlanActionsMenu
                                        status={plan.status}
                                        onDelete={() => onDelete?.(plan)}
                                        onCancel={() => onCancel?.(plan)}
                                        disabled={
                                            processingPlanId !== null ||
                                            sendingPlanId !== null
                                        }
                                    />
                                </div>
                            </div>
                        );
                    })}
                </div>
            </div>
        </main>
    );
}
