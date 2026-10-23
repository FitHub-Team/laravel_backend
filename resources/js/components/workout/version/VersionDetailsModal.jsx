
import { useEffect } from "react";
import { createPortal } from "react-dom";
import { X } from "lucide-react";

export default function VersionDetailsModal({
    open,
    onClose,
    children,
}) {
    useEffect(() => {
        if (!open) return;

        const previousOverflow = document.body.style.overflow;
        document.body.style.overflow = "hidden";

        const handleEscape = (event) => {
            if (event.key === "Escape") onClose();
        };

        window.addEventListener("keydown", handleEscape);

        return () => {
            document.body.style.overflow = previousOverflow;
            window.removeEventListener("keydown", handleEscape);
        };
    }, [open, onClose]);

    if (!open) return null;

    return createPortal(
        <div
            dir="rtl"
            className="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 p-0 backdrop-blur-[2px] sm:p-5"
            onMouseDown={(event) => {
                if (event.target === event.currentTarget) {
                    onClose();
                }
            }}
        >
            <section
                role="dialog"
                aria-modal="true"
                aria-labelledby="version-modal-title"
                className="
                    flex h-full w-full flex-col
                    overflow-hidden bg-white shadow-2xl
                    sm:h-auto sm:max-h-[90vh]
                    sm:max-w-4xl sm:rounded-2xl
                "
            >
                <header className="flex shrink-0 items-center justify-between border-b border-gray-100 px-5 py-4">
                    <div>
                        <h2
                            id="version-modal-title"
                            className="text-base font-bold text-gray-900"
                        >
                            تفاصيل إصدار الخطة
                        </h2>
                        <p className="mt-1 text-xs text-gray-500">
                            استعراض تفاصيل الخطة التدريبية
                        </p>
                    </div>

                    <button
                        type="button"
                        onClick={onClose}
                        aria-label="إغلاق النافذة"
                        className="flex h-10 w-10 items-center justify-center rounded-lg text-gray-500 transition hover:bg-gray-100 hover:text-gray-900"
                    >
                        <X size={20} />
                    </button>
                </header>

                <div className="min-h-0 flex-1 overflow-y-auto overscroll-contain p-4 sm:p-6">
                    {children}
                </div>
            </section>
        </div>,
        document.body
    );
}
