import { useState } from "react";
import { useNavigate } from "react-router-dom";

import CurrentPlansTable from "../../../components/workout/plan/CurrentPlansTable";
import PlansTabs from "../../../components/workout/plan/PlansTabs";

import useCoachPlans from "../../../hooks/userCoachPlane";

import {
    sendExistingPlan,
    deleteDraftPlan,
    cancelActivePlan,
} from "../../../services/coachPlansService";

export default function CurrentPlansPage() {
    const navigate = useNavigate();

    // Fetch coach plans
    const { plans, loading, error, refreshPlans } = useCoachPlans();

    // Loading states
    const [sendingPlanId, setSendingPlanId] = useState(null);
    const [processingPlanId, setProcessingPlanId] = useState(null);

    // Feedback messages
    const [sendError, setSendError] = useState("");
    const [sendSuccess, setSendSuccess] = useState("");

    // Clear previous messages
    const clearMessages = () => {
        setSendError("");
        setSendSuccess("");
    };

    // Open draft in workout editor
    const handleOpen = (plan) => {
        navigate(`/dashboard/workout-plan?trainee=${plan.trainee_id}`);
    };

    // View active plan
    const handleView = (plan) => {
        navigate(`/dashboard/workout-plan/${plan.id}`);
    };

    // Send saved draft
    const handleSend = async (plan) => {
        if (sendingPlanId !== null || processingPlanId !== null) {
            return;
        }

        if (plan.status !== "draft") return;

        setSendingPlanId(plan.id);
        clearMessages();

        try {
            const response = await sendExistingPlan(plan.id);

            if (response.status === false) {
                throw new Error(response.message || "فشل إرسال الخطة");
            }

            setSendSuccess("تم إرسال الخطة للمشترك بنجاح");

            await refreshPlans();
        } catch (err) {
            console.error("Send plan failed:", err);

            setSendError(
                err.response?.data?.message ||
                    err.message ||
                    "حدث خطأ أثناء إرسال الخطة",
            );
        } finally {
            setSendingPlanId(null);
        }
    };

    // Duplicate active plan
    const handleDuplicate = async (plan) => {
        try {
            const response = await duplicatePlan(plan.id);

            if (response.status === false) {
                throw new Error(response.message || "فشل نسخ الخطة");
            }

            navigate(`/dashboard/workout-plan?trainee=${plan.trainee_id}`);
        } catch (error) {
            setSendError(
                error.response?.data?.message ||
                    error.message ||
                    "تعذر إنشاء نسخة جديدة",
            );
        }
    };

    // Delete draft or cancel active plan
    const handlePlanAction = async (plan, action) => {
        if (processingPlanId !== null || sendingPlanId !== null) {
            return;
        }

        const isDelete = action === "delete";
        const isCancel = action === "cancel";

        if (!isDelete && !isCancel) return;

        // Validate action based on plan status
        if (isDelete && plan.status !== "draft") return;
        if (isCancel && plan.status !== "active") return;

        const message = isDelete
            ? "هل أنتِ متأكدة من حذف المسودة نهائيًا؟ سيتم حذف جميع تمارينها وأيامها."
            : "هل أنتِ متأكدة من إلغاء الخطة النشطة؟ سيتم الاحتفاظ بها في سجل الإصدارات.";

        const confirmed = window.confirm(message);

        if (!confirmed) return;

        setProcessingPlanId(plan.id);
        clearMessages();

        try {
            const response = isDelete
                ? await deleteDraftPlan(plan.id)
                : await cancelActivePlan(plan.id);

            if (response.status === false) {
                throw new Error(response.message || "فشلت العملية");
            }

            setSendSuccess(
                isDelete ? "تم حذف المسودة بنجاح" : "تم إلغاء الخطة بنجاح",
            );

            // Refresh table after successful action
            await refreshPlans();
        } catch (err) {
            console.error("Plan action failed:", err);

            setSendError(
                err.response?.data?.message ||
                    err.message ||
                    "حدث خطأ أثناء تنفيذ العملية",
            );
        } finally {
            setProcessingPlanId(null);
        }
    };

    // Delete draft
    const handleDelete = (plan) => {
        handlePlanAction(plan, "delete");
    };

    // Cancel active plan
    const handleCancel = (plan) => {
        handlePlanAction(plan, "cancel");
    };

    return (
        <div className="p-6" dir="rtl">
            {/* Navigation tabs */}
            <PlansTabs />

            {/* Page header */}
            <div className="mb-6">
                <h1 className="text-2xl font-bold text-gray-900">
                    الخطط الحالية
                </h1>

                <p className="mt-1 text-sm text-gray-500">
                    إدارة المسودات والخطط النشطة للمشتركين.
                </p>
            </div>

            {/* Success message */}
            {sendSuccess && (
                <div
                    role="status"
                    className="
                        mb-4 rounded-xl
                        border border-green-100
                        bg-green-50 p-4
                        text-sm font-medium text-[#407437]
                    "
                >
                    {sendSuccess}
                </div>
            )}

            {/* Error message */}
            {sendError && (
                <div
                    role="alert"
                    className="
                        mb-4 rounded-xl
                        border border-red-100
                        bg-red-50 p-4
                        text-sm font-medium text-red-600
                    "
                >
                    {sendError}
                </div>
            )}

            {/* Plans content */}
            {loading ? (
                <p className="text-sm text-gray-500">جاري تحميل الخطط...</p>
            ) : error ? (
                <p className="text-sm text-red-600">خطأ: {error}</p>
            ) : plans.length === 0 ? (
                <div
                    className="
                        rounded-2xl border
                        border-dashed border-gray-200
                        bg-white p-10 text-center
                    "
                >
                    <p className="text-sm text-gray-500">لا توجد خطط حالية.</p>
                </div>
            ) : (
                <CurrentPlansTable
                    plans={plans}
                    onOpen={handleOpen}
                    onView={handleView}
                    onSend={handleSend}
                    onDuplicate={handleDuplicate}
                    onDelete={handleDelete}
                    onCancel={handleCancel}
                    sendingPlanId={sendingPlanId}
                    processingPlanId={processingPlanId}
                />
            )}
        </div>
    );
}
