import { useNavigate, useParams } from "react-router-dom";
import { ArrowRight, Eye, History, User } from "lucide-react";
import { useEffect, useState } from "react";

import workoutPlanService from "../../../services/workoutPlanService";

export default function TraineeVersionsPage() {
    const { traineeId } = useParams();
    const navigate = useNavigate();

    const [versions, setVersions] = useState([]);
    const [trainee, setTrainee] = useState(null);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState("");

    useEffect(() => {
        const fetchVersions = async () => {
            try {
                setLoading(true);
                setError("");

                const data =
                    await workoutPlanService.getWorkoutVersions(traineeId);
                console.log("All versions:", data);
                console.log("First version:", data?.[0]);
                console.log("Trainee:", data?.[0]?.trainee);

                setVersions(data);
                setTrainee(data?.[0]?.trainee ?? null);

                setVersions(Array.isArray(data) ? data : []);

                // يدعم استجابة تحتوي على بيانات المتدرّب
                // داخل أول خطة، إذا كانت العلاقة موجودة.
                setTrainee(data?.[0]?.trainee ?? null);
            } catch (err) {
                setError(
                    err.response?.data?.message ||
                        "حدث خطأ أثناء تحميل إصدارات الخطة",
                );
            } finally {
                setLoading(false);
            }
        };

        if (traineeId) {
            fetchVersions();
        } else {
            setError("رقم المتدرّب غير موجود في الرابط");
            setLoading(false);
        }
    }, [traineeId]);

    const getStatus = (status) => {
        const statuses = {
            active: {
                label: "نشطة",
                className: "bg-[#EEF5EC] text-[#407437]",
            },
            completed: {
                label: "مكتملة",
                className: "bg-blue-50 text-blue-600",
            },
            cancelled: {
                label: "ملغاة",
                className: "bg-red-50 text-red-600",
            },
            draft: {
                label: "مسودة",
                className: "bg-gray-100 text-gray-600",
            },
        };

        return (
            statuses[status] || {
                label: status || "غير محدد",
                className: "bg-gray-100 text-gray-600",
            }
        );
    };

    if (loading) {
        return (
            <div dir="rtl" className="p-8 text-center text-gray-500">
                جاري تحميل إصدارات الخطط...
            </div>
        );
    }

    if (error) {
        return (
            <div dir="rtl" className="p-8 text-center">
                <p className="text-red-600">{error}</p>

                <button
                    type="button"
                    onClick={() => navigate("/dashboard/versions")}
                    className="mt-4 rounded-lg bg-[#EEF5EC] px-4 py-2 text-sm text-[#407437]"
                >
                    الرجوع
                </button>
            </div>
        );
    }

    return (
        <main
            dir="rtl"
            className="mx-auto max-w-7xl space-y-6 px-4 py-7 sm:px-6 lg:px-8"
        >
            <button
                type="button"
                onClick={() => navigate("/dashboard/versions")}
                className="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-600 transition hover:border-[#C8DCC3] hover:text-[#407437]"
            >
                <ArrowRight size={17} />
                رجوع
            </button>

            <div>
                <p className="mb-2 text-xs font-medium text-[#407437]">
                    SUPERFIT / WORKOUT VERSIONS
                </p>

                <h1 className="text-2xl font-bold text-gray-900 sm:text-3xl">
                    إصدارات خطة التمارين
                </h1>

                <p className="mt-2 text-sm text-gray-500">
                    جميع الإصدارات السابقة الخاصة بالمشترك.
                </p>
            </div>

            <section className="flex items-center gap-3 rounded-2xl border border-[#DCE8D8] bg-[#F4F9F2] p-4">
                <div className="flex h-11 w-11 items-center justify-center overflow-hidden rounded-xl bg-white text-[#407437]">
                    {trainee?.profile_image ? (
                        <img
                            src={trainee.profile_image}
                            alt={trainee.full_name || "المتدرّب"}
                            className="h-full w-full object-cover"
                        />
                    ) : (
                        <User size={20} />
                    )}
                </div>

                <div>
                    <p className="text-xs text-gray-500">المشترك</p>

                    <p className="mt-1 font-bold text-[#1A3D27]">
                        {trainee?.full_name || `المتدرّب #${traineeId}`}
                    </p>

                    <p className="mt-1 text-xs text-gray-400">
                        {trainee?.email || ""}
                    </p>
                </div>
            </section>

            <div className="overflow-hidden rounded-2xl border border-gray-200 bg-white">
                <div className="hidden grid-cols-[1fr_1fr_1.5fr_1fr_1fr] gap-4 border-b border-gray-100 bg-gray-50/70 px-5 py-4 text-xs font-semibold text-gray-500 md:grid">
                    <div>الإصدار</div>
                    <div>الحالة</div>
                    <div>تاريخ الإنشاء</div>
                    <div>عدد التمارين</div>
                    <div>الإجراءات</div>
                </div>

                <div className="divide-y divide-gray-100">
                    {versions.length === 0 ? (
                        <p className="p-8 text-center text-sm text-gray-500">
                            لا توجد إصدارات لهذه الخطة.
                        </p>
                    ) : (
                        versions.map((version) => {
                            const status = getStatus(version.status);

                            return (
                                <div
                                    key={version.id}
                                    className="grid grid-cols-1 gap-4 px-5 py-4 transition hover:bg-[#FAFCF9] md:grid-cols-[1fr_1fr_1.5fr_1fr_1fr] md:items-center"
                                >
                                    <div>
                                        <p className="text-sm font-bold text-gray-900">
                                            V{version.version}
                                        </p>
                                    </div>

                                    <div>
                                        <span
                                            className={`inline-flex items-center gap-1.5 rounded-full px-3 py-1.5 text-xs font-semibold ${status.className}`}
                                        >
                                            <span className="h-1.5 w-1.5 rounded-full bg-current" />
                                            {status.label}
                                        </span>
                                    </div>

                                    <div className="text-sm text-gray-700">
                                        {version.created_at
                                            ? new Date(
                                                  version.created_at,
                                              ).toLocaleDateString("ar")
                                            : "—"}
                                    </div>

                                    <div className="text-sm font-semibold text-gray-700">
                                        {version.workout_exercises_count ?? 0}{" "}
                                        تمرين
                                    </div>

                                    <div>
                                        <button
                                            type="button"
                                            onClick={() =>
                                                navigate(
                                                    `/dashboard/versions/${traineeId}/${version.id}`,
                                                )
                                            }
                                            className="inline-flex items-center gap-1.5 rounded-lg bg-[#EEF5EC] px-3 py-2 text-xs font-semibold text-[#407437] transition hover:bg-[#E1EEDF]"
                                        >
                                            <Eye size={15} />
                                            عرض
                                        </button>
                                    </div>
                                </div>
                            );
                        })
                    )}
                </div>
            </div>
        </main>
    );
}
