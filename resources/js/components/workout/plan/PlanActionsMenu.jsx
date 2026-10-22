
import { useEffect, useRef, useState } from "react";
import { MoreVertical, Trash2, Ban } from "lucide-react";

export default function PlanActionsMenu({
    status,
    onDelete,
    onCancel,
    disabled = false,
}) {
    const [isOpen, setIsOpen] = useState(false);
    const menuRef = useRef(null);

    useEffect(() => {
        if (!isOpen) return;

        const handleOutsideClick = (event) => {
            if (!menuRef.current?.contains(event.target)) {
                setIsOpen(false);
            }
        };

        const handleEscape = (event) => {
            if (event.key === "Escape") {
                setIsOpen(false);
            }
        };

        document.addEventListener("pointerdown", handleOutsideClick);
        document.addEventListener("keydown", handleEscape);

        return () => {
            document.removeEventListener(
                "pointerdown",
                handleOutsideClick
            );
            document.removeEventListener("keydown", handleEscape);
        };
    }, [isOpen]);

    if (!["draft", "active"].includes(status)) {
        return null;
    }

    const handleAction = (event, callback) => {
        event.stopPropagation();
        setIsOpen(false);
        callback?.();
    };

    return (
        <div
            ref={menuRef}
            className="relative inline-block"
            onClick={(event) => event.stopPropagation()}
        >
            <button
                type="button"
                disabled={disabled}
                aria-label="خيارات الخطة"
                aria-haspopup="menu"
                aria-expanded={isOpen}
                onClick={() => setIsOpen((prev) => !prev)}
                className="
                    flex h-9 w-9 items-center justify-center
                    rounded-lg text-slate-500
                    transition-colors
                    hover:bg-slate-100 hover:text-slate-700
                    disabled:cursor-not-allowed
                    disabled:opacity-50
                "
            >
                <MoreVertical size={18} />
            </button>

            {isOpen && (
                <div
                    role="menu"
                    className="
                        absolute left-0 top-full z-50 mt-2
                        min-w-[165px] rounded-xl border
                        border-slate-100 bg-white p-1.5
                        shadow-lg
                    "
                >
                    {status === "draft" && (
                        <button
                            type="button"
                            role="menuitem"
                            onClick={(event) =>
                                handleAction(event, onDelete)
                            }
                            className="
                                flex w-full items-center gap-2
                                rounded-lg px-3 py-2.5
                                text-right text-sm text-red-600
                                transition-colors hover:bg-red-50
                            "
                        >
                            <Trash2 size={16} />
                            حذف المسودة
                        </button>
                    )}

                    {status === "active" && (
                        <button
                            type="button"
                            role="menuitem"
                            onClick={(event) =>
                                handleAction(event, onCancel)
                            }
                            className="
                                flex w-full items-center gap-2
                                rounded-lg px-3 py-2.5
                                text-right text-sm text-red-600
                                transition-colors hover:bg-red-50
                            "
                        >
                            <Ban size={16} />
                            إلغاء الخطة
                        </button>
                    )}
                </div>
            )}
        </div>
    );
}
