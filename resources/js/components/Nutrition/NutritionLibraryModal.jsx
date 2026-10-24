import { useMemo, useState, useEffect } from "react";
import {
    Search,
    X,
    Plus,
    Utensils,
    Flame,
    Beef,
    Wheat,
    Droplet,
    Leaf,
    ChevronRight,
    RotateCcw,
} from "lucide-react";

const INITIAL_FOODS = [
    {
        id: 1,
        Food: "Cows' milk",
        Food_ar: "حليب البقر",
        Measure: "1 qt.",
        Measure_ar: "ربع جالون",
        Grams: 976,
        Calories: 660,
        Protein: 32,
        Fat: 40,
        "Sat.Fat": 36,
        Fiber: 0,
        Carbs: 48.0,
        Category: "Dairy products",
        Category_ar: "منتجات الألبان",
        FitHub_Macro_Group: "High-Carbs",
        FitHub_Macro_Group_ar: "عالي الكربوهيدرات",
    },
    {
        id: 2,
        Food: "Milk skim",
        Food_ar: "حليب خالي الدسم",
        Measure: "1 qt.",
        Measure_ar: "ربع جالون",
        Grams: 984,
        Calories: 360,
        Protein: 36,
        Fat: 0,
        "Sat.Fat": "t",
        Fiber: 0,
        Carbs: 52.0,
        Category: "Dairy products",
        Category_ar: "منتجات الألبان",
        FitHub_Macro_Group: "High-Carbs",
        FitHub_Macro_Group_ar: "عالي الكربوهيدرات",
    },
    {
        id: 3,
        Food: "Buttermilk",
        Food_ar: "اللبن الرائب",
        Measure: "1 cup",
        Measure_ar: "كوب واحد",
        Grams: 246,
        Calories: 127,
        Protein: 9,
        Fat: 5,
        "Sat.Fat": 4,
        Fiber: 0,
        Carbs: 13.0,
        Category: "Dairy products",
        Category_ar: "منتجات الألبان",
        FitHub_Macro_Group: "High-Carbs",
        FitHub_Macro_Group_ar: "عالي الكربوهيدرات",
    },
    {
        id: 4,
        Food: "Fortified milk",
        Food_ar: "حليب مدعّم",
        Measure: "6 cups",
        Measure_ar: "6 أكواب",
        Grams: 1419,
        Calories: 1373,
        Protein: 89,
        Fat: 42,
        "Sat.Fat": 23,
        Fiber: 1.4,
        Carbs: 119.0,
        Category: "Dairy products",
        Category_ar: "منتجات الألبان",
        FitHub_Macro_Group: "High-Carbs",
        FitHub_Macro_Group_ar: "عالي الكربوهيدرات",
    },
    {
        id: 5,
        Food: "Powdered milk",
        Food_ar: "حليب مجفف",
        Measure: "1 cup",
        Measure_ar: "كوب واحد",
        Grams: 103,
        Calories: 515,
        Protein: 27,
        Fat: 28,
        "Sat.Fat": 24,
        Fiber: 0,
        Carbs: 39.0,
        Category: "Dairy products",
        Category_ar: "منتجات الألبان",
        FitHub_Macro_Group: "High-Carbs",
        FitHub_Macro_Group_ar: "عالي الكربوهيدرات",
    },
    {
        id: 6,
        Food: "Goats' milk",
        Food_ar: "حليب الماعز",
        Measure: "1 cup",
        Measure_ar: "كوب واحد",
        Grams: 244,
        Calories: 165,
        Protein: 8,
        Fat: 10,
        "Sat.Fat": 8,
        Fiber: 0,
        Carbs: 11.0,
        Category: "Dairy products",
        Category_ar: "منتجات الألبان",
        FitHub_Macro_Group: "High-Carbs",
        FitHub_Macro_Group_ar: "عالي الكربوهيدرات",
    },
    {
        id: 7,
        Food: "Cheese",
        Food_ar: "الجبن",
        Measure: "1 cup",
        Measure_ar: "كوب واحد",
        Grams: 225,
        Calories: 240,
        Protein: 30,
        Fat: 11,
        "Sat.Fat": 10,
        Fiber: 0,
        Carbs: 6.0,
        Category: "Dairy products",
        Category_ar: "منتجات الألبان",
        FitHub_Macro_Group: "High-Protein",
        FitHub_Macro_Group_ar: "عالي البروتين",
    },
    {
        id: 8,
        Food: "Eggs raw",
        Food_ar: "بيض نيئ",
        Measure: "2",
        Measure_ar: "بيضتان",
        Grams: 100,
        Calories: 150,
        Protein: 12,
        Fat: 12,
        "Sat.Fat": 10,
        Fiber: 0,
        Carbs: 0.0,
        Category: "Dairy products",
        Category_ar: "منتجات الألبان",
        FitHub_Macro_Group: "High-Protein",
        FitHub_Macro_Group_ar: "عالي البروتين",
    },
    {
        id: 9,
        Food: "Olive oil",
        Food_ar: "زيت الزيتون",
        Measure: "1 T.",
        Measure_ar: "ملعقة كبيرة",
        Grams: 14,
        Calories: 125,
        Protein: 0,
        Fat: 14,
        "Sat.Fat": 3,
        Fiber: 0,
        Carbs: 0.0,
        Category: "Fats, Oils, Shortenings",
        Category_ar: "الدهون والزيوت",
        FitHub_Macro_Group: "Healthy-Fats",
        FitHub_Macro_Group_ar: "دهون صحية",
    },
    {
        id: 10,
        Food: "Beef",
        Food_ar: "لحم البقر",
        Measure: "3 oz.",
        Measure_ar: "3 أونصات",
        Grams: 85,
        Calories: 245,
        Protein: 23,
        Fat: 16,
        "Sat.Fat": 15,
        Fiber: 0,
        Carbs: 0.0,
        Category: "Meat, Poultry",
        Category_ar: "اللحوم والدواجن",
        FitHub_Macro_Group: "High-Protein",
        FitHub_Macro_Group_ar: "عالي البروتين",
    },
    {
        id: 11,
        Food: "chicken",
        Food_ar: "الدجاج",
        Measure: "3 oz.",
        Measure_ar: "3 أونصات",
        Grams: 85,
        Calories: 185,
        Protein: 23,
        Fat: 9,
        "Sat.Fat": 7,
        Fiber: 0,
        Carbs: 0.0,
        Category: "Meat, Poultry",
        Category_ar: "اللحوم والدواجن",
        FitHub_Macro_Group: "High-Protein",
        FitHub_Macro_Group_ar: "عالي البروتين",
    },
    {
        id: 12,
        Food: "Lentils",
        Food_ar: "العدس",
        Measure: "1 cup",
        Measure_ar: "كوب واحد",
        Grams: 200,
        Calories: 212,
        Protein: 15,
        Fat: 0,
        "Sat.Fat": 0,
        Fiber: 2.4,
        Carbs: 38.0,
        Category: "Vegetables F-P",
        Category_ar: "الخضراوات من F إلى P",
        FitHub_Macro_Group: "High-Carbs",
        FitHub_Macro_Group_ar: "عالي الكربوهيدرات",
    },
    {
        id: 13,
        Food: "Apples, raw",
        Food_ar: "تفاح طازج",
        Measure: "1 med",
        Measure_ar: "حبة متوسطة",
        Grams: 130,
        Calories: 70,
        Protein: 0,
        Fat: 0,
        "Sat.Fat": 0,
        Fiber: 1,
        Carbs: 18.0,
        Category: "Fruits A-F",
        Category_ar: "الفواكه من A إلى F",
        FitHub_Macro_Group: "High-Carbs",
        FitHub_Macro_Group_ar: "عالي الكربوهيدرات",
    },
    {
        id: 14,
        Food: "Banana",
        Food_ar: "الموز",
        Measure: "1 med.",
        Measure_ar: "حبة متوسطة",
        Grams: 150,
        Calories: 85,
        Protein: 1,
        Fat: 0,
        "Sat.Fat": 0,
        Fiber: 0.9,
        Carbs: 23.0,
        Category: "Fruits A-F",
        Category_ar: "الفواكه من A إلى F",
        FitHub_Macro_Group: "High-Carbs",
        FitHub_Macro_Group_ar: "عالي الكربوهيدرات",
    },
    {
        id: 15,
        Food: "Rice",
        Food_ar: "الأرز",
        Measure: "1 cup",
        Measure_ar: "كوب واحد",
        Grams: 208,
        Calories: 748,
        Protein: 15,
        Fat: 3,
        "Sat.Fat": 0,
        Fiber: 1.2,
        Carbs: 154.0,
        Category: "Breads, cereals, fastfood,grains",
        Category_ar: "الخبز والحبوب",
        FitHub_Macro_Group: "High-Carbs",
        FitHub_Macro_Group_ar: "عالي الكربوهيدرات",
    },
    {
        id: 16,
        Food: "Spinach",
        Food_ar: "السبانخ",
        Measure: "1 cup",
        Measure_ar: "كوب واحد",
        Grams: 100,
        Calories: 26,
        Protein: 3,
        Fat: 0,
        "Sat.Fat": 0,
        Fiber: 1,
        Carbs: 3.0,
        Category: "Vegetables R-Z",
        Category_ar: "الخضراوات من R إلى Z",
        FitHub_Macro_Group: "High-Protein",
        FitHub_Macro_Group_ar: "عالي البروتين",
    },
    {
        id: 17,
        Food: "Dates",
        Food_ar: "التمر",
        Measure: "1 cup",
        Measure_ar: "كوب واحد",
        Grams: 178,
        Calories: 505,
        Protein: 4,
        Fat: 0,
        "Sat.Fat": 0,
        Fiber: 3.6,
        Carbs: 134.0,
        Category: "Fruits A-F",
        Category_ar: "الفواكه من A إلى F",
        FitHub_Macro_Group: "High-Carbs",
        FitHub_Macro_Group_ar: "عالي الكربوهيدرات",
    },
];

const categories = [
    { value: "", label: "كل التصنيفات" },
    { value: "Dairy products", label: "منتجات الألبان" },
    { value: "Meat, Poultry", label: "اللحوم والدواجن" },
    { value: "Fish, Seafood", label: "الأسماك والمأكولات البحرية" },
    { value: "Fats, Oils, Shortenings", label: "الدهون والزيوت" },
    { value: "Vegetables A-E", label: "الخضراوات" },
    { value: "Vegetables F-P", label: "الخضراوات والبقوليات" },
    { value: "Vegetables R-Z", label: "الخضراوات" },
    { value: "Fruits A-F", label: "الفواكه" },
    { value: "Fruits G-P", label: "الفواكه" },
    {
        value: "Breads, cereals, fastfood,grains",
        label: "الخبز والحبوب",
    },
];

const macroGroupLabels = {
    "High-Carbs": "عالي الكربوهيدرات",
    "High-Protein": "عالي البروتين",
    "Healthy-Fats": "دهون صحية",
};

const NutritionLibraryModal = ({ isOpen, onClose, onSelectFood }) => {
    const [foods] = useState(INITIAL_FOODS);
    const [search, setSearch] = useState("");
    const [categoryFilter, setCategoryFilter] = useState("");
    const [macroFilter, setMacroFilter] = useState("");
    const [selectedFood, setSelectedFood] = useState(null);
    const [quantity, setQuantity] = useState("100");

    useEffect(() => {
        if (!isOpen) return;

        const handleKeyDown = (event) => {
            if (event.key === "Escape") onClose?.();
        };

        window.addEventListener("keydown", handleKeyDown);

        return () => window.removeEventListener("keydown", handleKeyDown);
    }, [isOpen, onClose]);

    useEffect(() => {
        if (!isOpen) {
            setSearch("");
            setCategoryFilter("");
            setMacroFilter("");
            setSelectedFood(null);
            setQuantity("100");
        }
    }, [isOpen]);

    const filteredFoods = useMemo(() => {
        const normalizedSearch = search.trim().toLowerCase();

        return foods.filter((food) => {
            const matchesSearch =
                !normalizedSearch ||
                [
                    food.Food,
                    food.Food_ar,
                    food.Category,
                    food.Category_ar,
                    food.FitHub_Macro_Group,
                    food.FitHub_Macro_Group_ar,
                ].some((value) =>
                    String(value ?? "")
                        .toLowerCase()
                        .includes(normalizedSearch),
                );

            const matchesCategory =
                !categoryFilter || food.Category === categoryFilter;

            const matchesMacro =
                !macroFilter || food.FitHub_Macro_Group === macroFilter;

            return matchesSearch && matchesCategory && matchesMacro;
        });
    }, [foods, search, categoryFilter, macroFilter]);

    if (!isOpen) return null;

    const getNutritionValue = (value) => {
        const number = Number(value);
        return Number.isFinite(number) ? number : 0;
    };

    const getScaledValue = (value) => {
        const grams = Math.max(0, Number(quantity) || 0);
        const baseGrams = Number(selectedFood?.Grams) || 100;

        return (
            Math.round(((getNutritionValue(value) * grams) / baseGrams) * 100) /
            100
        );
    };

    const handleSelectFood = () => {
        if (!selectedFood || typeof onSelectFood !== "function") {
            return;
        }

        const grams = Math.max(0, Number(quantity) || 0);

        onSelectFood({
            ...selectedFood,
            selected_quantity_grams: grams,
            selected_calories: getScaledValue(selectedFood.Calories),
            selected_protein: getScaledValue(selectedFood.Protein),
            selected_fat: getScaledValue(selectedFood.Fat),
            selected_carbs: getScaledValue(selectedFood.Carbs),
            selected_fiber: getScaledValue(selectedFood.Fiber),
        });

        setSelectedFood(null);
        setQuantity("100");
        onClose?.();
    };

    const MacroValue = ({ icon: Icon, label, value, color }) => (
        <div className="rounded-xl border border-slate-100 bg-slate-50 p-3">
            <div className="mb-2 flex items-center gap-2">
                <Icon size={16} className={color} />
                <span className="text-xs text-slate-500">{label}</span>
            </div>

            <p className="text-lg font-bold text-slate-800">{value}</p>
        </div>
    );

    return (
        <div
            dir="rtl"
            className="fixed inset-0 z-[100] flex items-center justify-center bg-slate-950/55 p-2 backdrop-blur-[3px] sm:p-5"
            onMouseDown={(event) => {
                if (event.target === event.currentTarget) {
                    onClose?.();
                }
            }}
        >
            <section
                role="dialog"
                aria-modal="true"
                aria-label="مكتبة التغذية"
                className="flex h-[min(92dvh,850px)] w-full max-w-6xl flex-col overflow-hidden rounded-[24px] border border-slate-200 bg-white shadow-2xl sm:rounded-[28px]"
            >
                {/* Header */}
                <header className="flex shrink-0 items-center justify-between border-b border-slate-100 px-4 py-4 sm:px-7 sm:py-5">
                    <div className="flex min-w-0 items-center gap-3">
                        {selectedFood && (
                            <button
                                type="button"
                                onClick={() => setSelectedFood(null)}
                                className="grid h-10 w-10 shrink-0 place-items-center rounded-xl border border-slate-200 text-slate-600 transition hover:bg-slate-50"
                                aria-label="العودة إلى المكتبة"
                            >
                                <ChevronRight size={19} />
                            </button>
                        )}

                        <span className="grid h-11 w-11 shrink-0 place-items-center rounded-2xl bg-[#407437] text-white shadow-sm">
                            <Utensils size={21} />
                        </span>

                        <div className="min-w-0">
                            <h2 className="text-base font-bold text-slate-900 sm:text-lg">
                                {selectedFood
                                    ? selectedFood.Food_ar
                                    : "مكتبة التغذية"}
                            </h2>

                            <p className="mt-0.5 text-xs text-slate-500 sm:text-sm">
                                {selectedFood
                                    ? selectedFood.Food
                                    : "ابحثي عن الطعام وأضيفيه إلى خطة التغذية"}
                            </p>
                        </div>
                    </div>

                    <button
                        type="button"
                        onClick={onClose}
                        aria-label="إغلاق مكتبة التغذية"
                        className="grid h-10 w-10 shrink-0 place-items-center rounded-xl text-slate-500 transition hover:bg-slate-100 hover:text-slate-900"
                    >
                        <X size={21} />
                    </button>
                </header>

                {selectedFood ? (
                    /* Food Details */
                    <main className="min-h-0 flex-1 overflow-y-auto px-4 py-5 sm:px-7">
                        <div className="mx-auto max-w-3xl space-y-5">
                            <div className="rounded-2xl border border-slate-200 p-5">
                                <div className="flex flex-wrap items-start justify-between gap-3">
                                    <div>
                                        <h3 className="text-xl font-bold text-slate-900">
                                            {selectedFood.Food_ar}
                                        </h3>

                                        <p className="mt-1 text-sm text-slate-500">
                                            {selectedFood.Food}
                                        </p>
                                    </div>

                                    <span className="rounded-xl bg-[#407437]/10 px-3 py-2 text-xs font-semibold text-[#407437]">
                                        {selectedFood.FitHub_Macro_Group_ar}
                                    </span>
                                </div>

                                <div className="mt-5 grid grid-cols-2 gap-3 sm:grid-cols-3">
                                    <MacroValue
                                        icon={Flame}
                                        label="السعرات"
                                        value={`${getScaledValue(selectedFood.Calories)} سعرة`}
                                        color="text-orange-500"
                                    />

                                    <MacroValue
                                        icon={Beef}
                                        label="البروتين"
                                        value={`${getScaledValue(selectedFood.Protein)} غ`}
                                        color="text-blue-500"
                                    />

                                    <MacroValue
                                        icon={Droplet}
                                        label="الدهون"
                                        value={`${getScaledValue(selectedFood.Fat)} غ`}
                                        color="text-purple-500"
                                    />

                                    <MacroValue
                                        icon={Wheat}
                                        label="الكربوهيدرات"
                                        value={`${getScaledValue(selectedFood.Carbs)} غ`}
                                        color="text-amber-500"
                                    />

                                    <MacroValue
                                        icon={Leaf}
                                        label="الألياف"
                                        value={`${getScaledValue(selectedFood.Fiber)} غ`}
                                        color="text-green-600"
                                    />

                                    <MacroValue
                                        icon={Droplet}
                                        label="الدهون المشبعة"
                                        value={
                                            selectedFood["Sat.Fat"] === "t"
                                                ? "غير محدد"
                                                : `${getScaledValue(selectedFood["Sat.Fat"])} غ`
                                        }
                                        color="text-rose-500"
                                    />
                                </div>
                            </div>

                            <div className="rounded-2xl border border-slate-200 p-5">
                                <label
                                    htmlFor="nutrition-quantity"
                                    className="mb-2 block text-sm font-semibold text-slate-800"
                                >
                                    الكمية بالجرام
                                </label>

                                <input
                                    id="nutrition-quantity"
                                    type="number"
                                    min="0"
                                    step="any"
                                    value={quantity}
                                    onChange={(event) =>
                                        setQuantity(event.target.value)
                                    }
                                    className="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-[#407437] focus:ring-2 focus:ring-[#407437]/10"
                                />

                                <p className="mt-2 text-xs leading-5 text-slate-500">
                                    القيم الغذائية أعلاه محسوبة بحسب الكمية التي
                                    أدخلتيها، اعتمادًا على وزن الحصة الأصلية
                                    المسجل في المكتبة.
                                </p>
                            </div>
                        </div>
                    </main>
                ) : (
                    <>
                        {/* Search and Filters */}
                        <div className="shrink-0 border-b border-slate-100 bg-slate-50/60 px-4 py-4 sm:px-7">
                            <div className="relative">
                                <Search
                                    size={18}
                                    className="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400"
                                />

                                <input
                                    type="search"
                                    value={search}
                                    onChange={(event) =>
                                        setSearch(event.target.value)
                                    }
                                    placeholder="ابحثي عن الطعام بالعربي أو الإنجليزي..."
                                    className="w-full rounded-xl border border-slate-200 bg-white py-3 pr-10 pl-4 text-sm outline-none transition placeholder:text-slate-400 focus:border-[#407437]/50 focus:ring-2 focus:ring-[#407437]/5"
                                />
                            </div>

                            <div className="mt-3 grid grid-cols-1 gap-3 sm:grid-cols-2">
                                <select
                                    value={categoryFilter}
                                    onChange={(event) =>
                                        setCategoryFilter(event.target.value)
                                    }
                                    className="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-700 outline-none focus:border-[#407437]"
                                >
                                    {categories.map((category) => (
                                        <option
                                            key={category.value}
                                            value={category.value}
                                        >
                                            {category.label}
                                        </option>
                                    ))}
                                </select>

                                <select
                                    value={macroFilter}
                                    onChange={(event) =>
                                        setMacroFilter(event.target.value)
                                    }
                                    className="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-700 outline-none focus:border-[#407437]"
                                >
                                    <option value="">
                                        كل مجموعات الماكروز
                                    </option>
                                    <option value="High-Protein">
                                        عالي البروتين
                                    </option>
                                    <option value="High-Carbs">
                                        عالي الكربوهيدرات
                                    </option>
                                    <option value="Healthy-Fats">
                                        دهون صحية
                                    </option>
                                </select>
                            </div>
                        </div>

                        {/* Food List */}
                        <main className="min-h-0 flex-1 overflow-y-auto bg-white px-4 py-5 sm:px-7">
                            {filteredFoods.length === 0 ? (
                                <div className="flex min-h-52 flex-col items-center justify-center gap-3 text-center">
                                    <span className="grid h-14 w-14 place-items-center rounded-2xl bg-[#407437]/10 text-[#407437]">
                                        <Search size={24} />
                                    </span>

                                    <p className="text-sm font-semibold text-slate-900">
                                        لا توجد أطعمة مطابقة
                                    </p>

                                    <p className="text-xs text-slate-500">
                                        جرّبي تغيير البحث أو خيارات التصفية.
                                    </p>

                                    <button
                                        type="button"
                                        onClick={() => {
                                            setSearch("");
                                            setCategoryFilter("");
                                            setMacroFilter("");
                                        }}
                                        className="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2 text-sm text-slate-600 transition hover:bg-slate-50"
                                    >
                                        <RotateCcw size={15} />
                                        إعادة ضبط الفلاتر
                                    </button>
                                </div>
                            ) : (
                                <div className="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
                                    {filteredFoods.map((food) => (
                                        <button
                                            key={food.id}
                                            type="button"
                                            onClick={() => {
                                                setSelectedFood(food);
                                                setQuantity(String(food.Grams));
                                            }}
                                            className="group rounded-2xl border border-slate-200 bg-white p-4 text-right transition hover:border-[#407437]/40 hover:bg-[#407437]/[0.02] hover:shadow-sm"
                                        >
                                            <div className="flex items-start justify-between gap-3">
                                                <div className="flex min-w-0 items-start gap-3">
                                                    <span className="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-[#407437]/10 text-[#407437]">
                                                        <Utensils size={18} />
                                                    </span>

                                                    <div className="min-w-0">
                                                        <h3 className="truncate text-sm font-bold text-slate-800">
                                                            {food.Food_ar}
                                                        </h3>

                                                        <p className="mt-1 truncate text-xs text-slate-400">
                                                            {food.Food}
                                                        </p>
                                                    </div>
                                                </div>

                                                <Plus
                                                    size={19}
                                                    className="shrink-0 text-slate-400 transition group-hover:text-[#407437]"
                                                />
                                            </div>

                                            <div className="mt-4 flex items-center justify-between gap-2">
                                                <span className="rounded-lg bg-slate-50 px-2 py-1 text-[11px] text-slate-500">
                                                    {food.Grams} غ
                                                </span>

                                                <span className="text-sm font-bold text-slate-800">
                                                    {food.Calories}{" "}
                                                    <span className="text-xs font-normal text-slate-400">
                                                        سعرة
                                                    </span>
                                                </span>
                                            </div>

                                            <div className="mt-3 grid grid-cols-3 gap-2 border-t border-slate-100 pt-3">
                                                <div>
                                                    <p className="text-[10px] text-slate-400">
                                                        البروتين
                                                    </p>
                                                    <p className="mt-1 text-xs font-semibold text-slate-700">
                                                        {food.Protein} غ
                                                    </p>
                                                </div>

                                                <div>
                                                    <p className="text-[10px] text-slate-400">
                                                        الكربوهيدرات
                                                    </p>
                                                    <p className="mt-1 text-xs font-semibold text-slate-700">
                                                        {food.Carbs} غ
                                                    </p>
                                                </div>

                                                <div>
                                                    <p className="text-[10px] text-slate-400">
                                                        الدهون
                                                    </p>
                                                    <p className="mt-1 text-xs font-semibold text-slate-700">
                                                        {food.Fat} غ
                                                    </p>
                                                </div>
                                            </div>

                                            <p className="mt-3 text-[11px] text-[#407437]">
                                                {macroGroupLabels[
                                                    food.FitHub_Macro_Group
                                                ] ?? food.FitHub_Macro_Group_ar}
                                            </p>
                                        </button>
                                    ))}
                                </div>
                            )}
                        </main>

                        <footer className="flex shrink-0 items-center justify-between border-t border-slate-100 bg-white px-4 py-3 sm:px-7">
                            <p className="text-xs text-slate-500">
                                {filteredFoods.length} طعام
                            </p>

                            <button
                                type="button"
                                onClick={onClose}
                                className="rounded-xl px-4 py-2 text-sm font-medium text-slate-500 transition hover:bg-slate-100"
                            >
                                إغلاق
                            </button>
                        </footer>
                    </>
                )}

                {selectedFood && (
                    <footer className="flex shrink-0 flex-col-reverse gap-2 border-t border-slate-100 bg-white px-4 py-3 sm:flex-row sm:items-center sm:justify-between sm:px-7">
                        <button
                            type="button"
                            onClick={() => setSelectedFood(null)}
                            className="rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50"
                        >
                            رجوع للمكتبة
                        </button>

                        <button
                            type="button"
                            onClick={handleSelectFood}
                            disabled={
                                typeof onSelectFood !== "function" ||
                                quantity === "" ||
                                !Number.isFinite(Number(quantity)) ||
                                Number(quantity) < 0
                            }
                            className="inline-flex items-center justify-center gap-2 rounded-xl bg-[#407437] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#345f2d] disabled:cursor-not-allowed disabled:opacity-50"
                        >
                            <Plus size={17} />
                            إضافة إلى خطة التغذية
                        </button>
                    </footer>
                )}
            </section>
        </div>
    );
};

export default NutritionLibraryModal;
