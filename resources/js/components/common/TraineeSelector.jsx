import { useEffect, useRef, useState } from "react";
import { ChevronDown, Search, Check, UserRound } from "lucide-react";

export default function TraineeSelector({
    trainees = [],
    selectedTrainee,
    onSelectTrainee,
    isLoading = false,
}) {
    const [isOpen, setIsOpen] = useState(false);
    const [search, setSearch] = useState("");
    const [isSelecting, setIsSelecting] = useState(false);

    const dropdownRef = useRef(null);

    // Close dropdown when clicking outside
    useEffect(() => {
        const handleOutsideClick = (event) => {
            if (
                dropdownRef.current &&
                !dropdownRef.current.contains(event.target)
            ) {
                setIsOpen(false);
            }
        };

        document.addEventListener("mousedown", handleOutsideClick);

        return () => {
            document.removeEventListener("mousedown", handleOutsideClick);
        };
    }, []);

    // Search trainees by name or email
    const filteredTrainees = trainees.filter((entry) => {
        const name = entry.trainee?.full_name?.toLowerCase() ?? "";

        const email = entry.trainee?.email?.toLowerCase() ?? "";

        const query = search.trim().toLowerCase();

        return name.includes(query) || email.includes(query);
    });

    const handleSelect = async (entry) => {
        if (isSelecting) return;

        setIsSelecting(true);

        try {
            await onSelectTrainee(entry);

            setIsOpen(false);
            setSearch("");
        } catch (error) {
            console.error("Failed to select trainee:", error);
        } finally {
            setIsSelecting(false);
        }
    };

    const selectedId = selectedTrainee?.trainee?.id;

    return (
        <div ref={dropdownRef} dir="rtl" className="relative w-full md:w-80">
            {/* Label */}
            <label className="mb-2 block text-right text-xs font-medium text-gray-500">
                اختر المشترك لإعداد أو تعديل خطته
            </label>

            {/* Selected trainee button */}
            <button
                type="button"
                onClick={() => setIsOpen((previous) => !previous)}
                disabled={isLoading || isSelecting}
                aria-expanded={isOpen}
                className="
                    flex w-full items-center
                    justify-between gap-3
                    rounded-xl border border-gray-200
                    bg-gray-50 px-4 py-3
                    text-right transition-colors
                    hover:border-[#407437]
                    hover:bg-[#F5F9F3]
                    disabled:cursor-not-allowed
                    disabled:opacity-60
                "
            >
                <div className="flex min-w-0 items-center gap-3">
                    <UserRound size={18} className="shrink-0 text-[#407437]" />

                    <span className="truncate text-sm font-semibold text-gray-800">
                        {isLoading
                            ? "جاري تحميل المشتركين..."
                            : selectedTrainee?.trainee?.full_name ||
                              "اختر المشترك"}
                    </span>
                </div>

                <ChevronDown
                    size={18}
                    className={`shrink-0 text-gray-400 transition-transform ${
                        isOpen ? "rotate-180" : ""
                    }`}
                />
            </button>

            {/* Dropdown */}
            {isOpen && (
                <div
                    className="
                        absolute right-0 top-full z-50 mt-2
                        w-full overflow-hidden rounded-xl
                        border border-gray-200
                        bg-white shadow-lg
                    "
                >
                    {/* Search */}
                    <div className="relative border-b border-gray-100 p-3">
                        <Search
                            size={16}
                            className="absolute right-6 top-6 text-gray-400"
                        />

                        <input
                            autoFocus
                            type="text"
                            placeholder="ابحث بالاسم أو البريد..."
                            value={search}
                            onChange={(event) => setSearch(event.target.value)}
                            className="
                                w-full rounded-lg border
                                border-gray-200 bg-gray-50
                                py-2.5 pl-3 pr-9 text-sm
                                outline-none transition-colors
                                focus:border-[#407437]
                                focus:bg-white
                            "
                        />
                    </div>

                    {/* Trainees list */}
                    <div className="max-h-60 overflow-y-auto">
                        {filteredTrainees.map((entry) => {
                            const isSelected =
                                String(entry.trainee?.id) ===
                                String(selectedId);

                            return (
                                <button
                                    key={entry.id}
                                    type="button"
                                    onClick={() => handleSelect(entry)}
                                    disabled={isSelecting}
                                    className={`
                                        flex w-full items-center
                                        justify-between gap-3
                                        border-b border-gray-50
                                        px-4 py-3 text-right
                                        transition-colors
                                        hover:bg-[#EEF5EC]
                                        disabled:opacity-60
                                        ${isSelected ? "bg-[#F5F9F3]" : ""}
                                    `}
                                >
                                    <div className="min-w-0">
                                        <p className="truncate text-sm font-semibold text-gray-800">
                                            {entry.trainee?.full_name}
                                        </p>

                                        <p className="mt-1 truncate text-xs text-gray-400">
                                            {entry.trainee?.email}
                                        </p>
                                    </div>

                                    {isSelected && (
                                        <Check
                                            size={17}
                                            className="shrink-0 text-[#407437]"
                                        />
                                    )}
                                </button>
                            );
                        })}

                        {/* Empty state */}
                        {filteredTrainees.length === 0 && (
                            <div className="px-4 py-6 text-center">
                                <UserRound
                                    size={24}
                                    className="mx-auto mb-2 text-gray-300"
                                />

                                <p className="text-sm text-gray-400">
                                    {trainees.length === 0
                                        ? "لا يوجد مشتركون متاحون"
                                        : "لا يوجد مشترك بهذا الاسم"}
                                </p>
                            </div>
                        )}
                    </div>
                </div>
            )}
        </div>
    );
}
