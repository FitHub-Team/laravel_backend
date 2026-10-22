import { X, Utensils, Info } from "lucide-react";

export default function NutritionVersionsModal({
    isOpen,
    onClose,
    traineeName,
}) {
    if (!isOpen) return null;

    return (
        <div
            dir="rtl"
            className="fixed inset-0 z-[110] flex items-center justify-center bg-black/50 p-4"
            onMouseDown={(event) => {
                if (event.target === event.currentTarget) onClose();
            }}
        >
            <div
                role="dialog"
                aria-modal="true"
                aria-label="إصدارات الخطط الغذائية"
                className="w-full max-w-2xl rounded-2xl bg-white shadow-xl"
            >
                <header className="flex items-center justify-between border-b p-5">
                    <div className="flex items-center gap-3">
                        <div className="rounded-xl bg-orange-50 p-3 text-orange-600">
                            <Utensils size={22} />
                        </div>

                        <div>
                            <h2 className="font-bold text-[#1A3D27]">
                                إصدارات الخطط الغذائية
                            </h2>
                            <p className="mt-1 text-xs text-gray-500">
                                المشترك: {traineeName}
                            </p>
                        </div>
                    </div>

                    <button
                        type="button"
                        onClick={onClose}
                        aria-label="إغلاق"
                        className="rounded-lg p-2 text-gray-500 hover:bg-gray-100"
                    >
                        <X size={20} />
                    </button>
                </header>

                <div className="flex flex-col items-center gap-4 px-6 py-14 text-center">
                    <div className="rounded-full bg-orange-50 p-5 text-orange-600">
                        <Utensils size={30} />
                    </div>

                    <h3 className="font-bold text-gray-800">
                        سجل الخطط الغذائية
                    </h3>

                    <p className="max-w-sm text-sm leading-7 text-gray-500">
                        سيتم عرض الإصدارات الغذائية المحفوظة
                        بعد ربط API الخاص بها.
                    </p>

                    <div className="flex items-center gap-2 rounded-lg bg-blue-50 px-4 py-3 text-xs text-blue-700">
                        <Info size={16} />
                        لم يتم ربط بيانات إصدارات التغذية بعد.
                    </div>
                </div>
            </div>
        </div>
    );
}