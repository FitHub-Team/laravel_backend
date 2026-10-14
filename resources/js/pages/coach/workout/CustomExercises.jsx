import React, { useEffect, useState } from "react";
import useCustomExercises from "../../../hooks/useCustomExercises";
import CustomExerciseCard from "../../../components/workout/custom-exercises/CustomExerciseCard";
import CustomExerciseModal from "../../../components/workout/custom-exercises/CustomExerciseModal";
import CustomExerciseForm from "../../../components/workout/custom-exercises/CustomExerciseForm";
import CustomExerciseDetails from "../../../components/workout/custom-exercises/CustomExerciseDetails";
import CustomExerciseFilters from "../../../components/workout/custom-exercises/CustomExerciseFilters";
import ConfirmDeleteModal from "../../../components/workout/custom-exercises/ConfirmDeleteModal";
import FlashMessage from "../../../components/common/FlashMessage";

export default function CustomExercises() {
    const {
        exercises,
        loading,
        error,
        filters,
        setFilters,
        createExercise,
        updateExercise,
        deleteExercise,
    } = useCustomExercises();

    const [search, setSearch] = useState("");
    const [modalMode, setModalMode] = useState(null); // 'create' | 'edit' | 'view' | null
    const [selected, setSelected] = useState(null);
    const [submitting, setSubmitting] = useState(false);
    const [deleteTarget, setDeleteTarget] = useState(null);
    const [deleting, setDeleting] = useState(false);
    const [flash, setFlash] = useState(null);

    // debounce للبحث
    useEffect(() => {
        const t = setTimeout(() => {
            setFilters((prev) => ({ ...prev, search: search || undefined }));
        }, 400);
        return () => clearTimeout(t);
    }, [search, setFilters]);

    const closeModal = () => {
        setModalMode(null);
        setSelected(null);
    };

    const handleCreate = async (data) => {
        setSubmitting(true);
        try {
            await createExercise(data);
            setFlash({ type: "success", message: "تم إنشاء التمرين بنجاح" });
            closeModal();
        } catch (err) {
            setFlash({
                type: "error",
                message: err?.response?.data?.message || "فشل إنشاء التمرين",
            });
        } finally {
            setSubmitting(false);
        }
    };

    const handleUpdate = async (data) => {
        setSubmitting(true);
        try {
            await updateExercise(selected.id, data);
            setFlash({ type: "success", message: "تم تحديث التمرين بنجاح" });
            closeModal();
        } catch (err) {
            setFlash({
                type: "error",
                message: err?.response?.data?.message || "فشل تحديث التمرين",
            });
        } finally {
            setSubmitting(false);
        }
    };

    const handleConfirmDelete = async () => {
        if (!deleteTarget) return;
        setDeleting(true);
        try {
            await deleteExercise(deleteTarget.id);
            setFlash({ type: "success", message: "تم حذف التمرين بنجاح" });
            setDeleteTarget(null);
        } catch (err) {
            setFlash({
                type: "error",
                message: err?.response?.data?.message || "فشل حذف التمرين",
            });
        } finally {
            setDeleting(false);
        }
    };

    return (
        <div className="p-6 flex flex-col gap-6" dir="rtl">
            {flash && (
                <FlashMessage
                    type={flash.type}
                    message={flash.message}
                    onClose={() => setFlash(null)}
                />
            )}

            {/* Header */}
            <div className="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <h1 className="text-2xl font-bold text-gray-800">
                        تماريني الخاصة
                    </h1>
                    <p className="text-sm text-gray-500 mt-1">
                        إدارة وإضافة التمارين التي أنشأتها لاستخدامها في خطط
                        المتدربين
                    </p>
                </div>
                <button
                    onClick={() => {
                        setSelected(null);
                        setModalMode("create");
                    }}
                    className="px-4 py-2 rounded-lg bg-[#407437] text-white text-sm hover:bg-[#355f2c] transition"
                >
                    + إضافة تمرين
                </button>
            </div>

            {/* Search + Filters */}
            <div className="flex flex-col gap-3">
                <input
                    type="text"
                    value={search}
                    onChange={(e) => setSearch(e.target.value)}
                    placeholder="ابحث عن تمرين..."
                    className="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#407437]/30 focus:border-[#407437]"
                />
                <CustomExerciseFilters
                    filters={filters}
                    onChange={setFilters}
                />
            </div>

            {/* Content */}
            {loading ? (
                <div className="py-16 text-center text-gray-400">
                    جارٍ التحميل...
                </div>
            ) : error ? (
                <div className="py-16 text-center text-red-500">{error}</div>
            ) : exercises.length === 0 ? (
                <div className="py-16 text-center flex flex-col items-center gap-3">
                   
                    <h3 className="text-lg font-bold text-gray-700">
                        لا توجد تمارين خاصة حتى الآن
                    </h3>
                    <p className="text-sm text-gray-500">
                        ابدأ بإضافة أول تمرين خاص بك لاستخدامه في خطط المتدربين.
                    </p>
                    <button
                        onClick={() => {
                            setSelected(null);
                            setModalMode("create");
                        }}
                        className="mt-2 px-4 py-2 rounded-lg bg-[#407437] text-white text-sm hover:bg-[#355f2c]"
                    >
                        إضافة تمرين
                    </button>
                </div>
            ) : (
                <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    {exercises.map((exercise) => (
                        <CustomExerciseCard
                            key={exercise.id}
                            exercise={exercise}
                            onView={(ex) => {
                                setSelected(ex);
                                setModalMode("view");
                            }}
                            onEdit={(ex) => {
                                setSelected(ex);
                                setModalMode("edit");
                            }}
                            onDelete={(ex) => setDeleteTarget(ex)}
                        />
                    ))}
                </div>
            )}

            {/* Create / Edit Modal */}
            <CustomExerciseModal
                open={modalMode === "create" || modalMode === "edit"}
                title={
                    modalMode === "edit" ? "تعديل التمرين" : "إضافة تمرين جديد"
                }
                onClose={closeModal}
            >
                <CustomExerciseForm
                    initialData={modalMode === "edit" ? selected : null}
                    onSubmit={
                        modalMode === "edit" ? handleUpdate : handleCreate
                    }
                    onCancel={closeModal}
                    submitting={submitting}
                />
            </CustomExerciseModal>

            {/* View Modal */}
            <CustomExerciseModal
                open={modalMode === "view"}
                title="تفاصيل التمرين"
                onClose={closeModal}
            >
                <CustomExerciseDetails exercise={selected} />
            </CustomExerciseModal>

            {/* Delete Confirmation */}
            <ConfirmDeleteModal
                open={!!deleteTarget}
                onCancel={() => setDeleteTarget(null)}
                onConfirm={handleConfirmDelete}
                loading={deleting}
            />
        </div>
    );
}
