import { useState } from "react";
import { Plus, Trash2, X, Utensils } from "lucide-react";
const initialForm = {
    name: "",
    description: "",
    meal_type: "breakfast",

    calories: "",
    protein: "",
    carbs: "",
    fat: "",
    image: null,
    prep_time: "",
    servings: 1,

    ingredients: [
        {
            name: "",
            quantity: "",
            unit: "",
        },
    ],

    instructions: [""],

    is_public: false,
};

const RecipeFormModal = ({ open, onClose, onSubmit, saving }) => {
    const [form, setForm] = useState(initialForm);

    if (!open) {
        return null;
    }

    const updateField = (field, value) => {
        setForm((current) => ({
            ...current,
            [field]: value,
        }));
    };

    const updateIngredient = (index, field, value) => {
        setForm((current) => {
            const ingredients = [...current.ingredients];

            ingredients[index] = {
                ...ingredients[index],
                [field]: value,
            };

            return {
                ...current,
                ingredients,
            };
        });
    };

    const addIngredient = () => {
        setForm((current) => ({
            ...current,

            ingredients: [
                ...current.ingredients,
                {
                    name: "",
                    quantity: "",
                    unit: "",
                },
            ],
        }));
    };

    const removeIngredient = (index) => {
        setForm((current) => ({
            ...current,

            ingredients: current.ingredients.filter(
                (_, itemIndex) => itemIndex !== index,
            ),
        }));
    };

    const updateInstruction = (index, value) => {
        setForm((current) => {
            const instructions = [...current.instructions];

            instructions[index] = value;

            return {
                ...current,
                instructions,
            };
        });
    };

    const addInstruction = () => {
        setForm((current) => ({
            ...current,
            instructions: [...current.instructions, ""],
        }));
    };

    const removeInstruction = (index) => {
        setForm((current) => ({
            ...current,

            instructions: current.instructions.filter(
                (_, itemIndex) => itemIndex !== index,
            ),
        }));
    };

    const handleSubmit = async (event) => {
        event.preventDefault();

        const formData = new FormData();

        formData.append("name", form.name);

        formData.append("description", form.description || "");

        formData.append("meal_type", form.meal_type);

        if (form.calories !== "") {
            formData.append("calories", Number(form.calories));
        }

        if (form.protein !== "") {
            formData.append("protein", Number(form.protein));
        }

        if (form.carbs !== "") {
            formData.append("carbs", Number(form.carbs));
        }

        if (form.fat !== "") {
            formData.append("fat", Number(form.fat));
        }

        if (form.prep_time !== "") {
            formData.append("prep_time", Number(form.prep_time));
        }

        formData.append("servings", Number(form.servings || 1));

        formData.append("is_public", form.is_public ? "1" : "0");

        if (form.image instanceof File) {
            formData.append("image", form.image, form.image.name);
        }
        form.ingredients.forEach((ingredient, index) => {
            formData.append(`ingredients[${index}][name]`, ingredient.name);

            if (ingredient.quantity !== "") {
                formData.append(
                    `ingredients[${index}][quantity]`,
                    ingredient.quantity,
                );
            }

            if (ingredient.unit) {
                formData.append(`ingredients[${index}][unit]`, ingredient.unit);
            }
        });

        form.instructions
            .filter((instruction) => instruction.trim() !== "")
            .forEach((instruction, index) => {
                formData.append(`instructions[${index}]`, instruction);
            });
        // موقتا
        for (const [key, value] of formData.entries()) {
            console.log(
                key,
                value,
                value instanceof File ? "FILE" : typeof value,
            );
        }
        /////
        const success = await onSubmit(formData);

        if (success) {
            setForm(initialForm);
            onClose();
        }
    };

    return (
        <div
            dir="rtl"
            className="fixed inset-0 z-50 bg-black/40 flex items-center justify-center p-4"
        >
            <div className="bg-white w-full max-w-3xl max-h-[90vh] overflow-y-auto rounded-2xl shadow-xl">
                {/* Header */}

                <div className="sticky top-0 bg-white flex items-center justify-between px-6 py-4 border-b border-gray-100 z-10">
                    <div>
                        <h2 className="font-bold text-lg text-gray-900">
                            إضافة وصفة جديدة
                        </h2>

                        <p className="text-xs text-gray-400 mt-1">
                            أضف بيانات الوصفة والقيم الغذائية
                        </p>
                    </div>

                    <button
                        type="button"
                        onClick={onClose}
                        className="w-9 h-9 rounded-lg bg-gray-50 text-gray-500 flex items-center justify-center"
                    >
                        <X size={18} />
                    </button>
                </div>

                <form onSubmit={handleSubmit} className="p-6 space-y-6">
                    {/* Basic */}

                    <section>
                        <h3 className="font-bold text-sm text-gray-800 mb-4">
                            معلومات الوصفة
                        </h3>

                        <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <Input
                                label="اسم الوصفة"
                                required
                                value={form.name}
                                onChange={(value) => updateField("name", value)}
                            />

                            <Select
                                label="نوع الوجبة"
                                value={form.meal_type}
                                onChange={(value) =>
                                    updateField("meal_type", value)
                                }
                            />

                            <div className="md:col-span-2">
                                <label className="text-xs font-semibold text-gray-600">
                                    الوصف
                                </label>

                                <textarea
                                    rows={3}
                                    value={form.description}
                                    onChange={(event) =>
                                        updateField(
                                            "description",
                                            event.target.value,
                                        )
                                    }
                                    className="w-full mt-2 bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm outline-none focus:border-green-500"
                                />
                            </div>
                        </div>
                    </section>
                    <div className="md:col-span-2">
                        <label className="text-xs font-semibold text-gray-600">
                            صورة الوصفة
                        </label>

                        <div className="mt-2">
                            <label
                                className="
        w-full
        h-40
        border-2
        border-dashed
        border-gray-200
        rounded-2xl
        flex
        items-center
        justify-center
        cursor-pointer
        bg-gray-50
        hover:border-[#407437]
        transition-colors
        overflow-hidden
      "
                            >
                                {form.image ? (
                                    <img
                                        src={URL.createObjectURL(form.image)}
                                        alt="معاينة الوصفة"
                                        className="w-full h-full object-cover"
                                    />
                                ) : (
                                    <div className="text-center">
                                        <Utensils
                                            size={28}
                                            className="mx-auto text-[#407437]"
                                        />

                                        <p className="text-sm font-semibold text-gray-600 mt-3">
                                            اختر صورة للوصفة
                                        </p>

                                        <p className="text-xs text-gray-400 mt-1">
                                            JPG, PNG أو WEBP
                                        </p>
                                    </div>
                                )}

                                <input
                                    type="file"
                                    accept="image/jpeg,image/png,image/webp"
                                    onChange={(event) => {
                                        const file = event.target.files?.[0];

                                        if (!file) {
                                            return;
                                        }

                                        console.log("Selected image:", file);
                                        console.log(
                                            "Is File:",
                                            file instanceof File,
                                        );
                                        console.log("Type:", file.type);

                                        setForm((current) => ({
                                            ...current,
                                            image: file,
                                        }));
                                    }}
                                />
                            </label>

                            {form.image && (
                                <button
                                    type="button"
                                    onClick={() => updateField("image", null)}
                                    className="text-xs text-red-500 mt-2"
                                >
                                    إزالة الصورة
                                </button>
                            )}
                        </div>
                    </div>

                    {/* Nutrition */}

                    <section>
                        <h3 className="font-bold text-sm text-gray-800 mb-4">
                            القيم الغذائية
                        </h3>

                        <div className="grid grid-cols-2 md:grid-cols-4 gap-3">
                            <Input
                                label="السعرات"
                                type="number"
                                value={form.calories}
                                onChange={(value) =>
                                    updateField("calories", value)
                                }
                            />

                            <Input
                                label="البروتين g"
                                type="number"
                                value={form.protein}
                                onChange={(value) =>
                                    updateField("protein", value)
                                }
                            />

                            <Input
                                label="الكربوهيدرات g"
                                type="number"
                                value={form.carbs}
                                onChange={(value) =>
                                    updateField("carbs", value)
                                }
                            />

                            <Input
                                label="الدهون g"
                                type="number"
                                value={form.fat}
                                onChange={(value) => updateField("fat", value)}
                            />
                        </div>

                        <div className="grid grid-cols-2 gap-3 mt-3">
                            <Input
                                label="وقت التحضير بالدقائق"
                                type="number"
                                value={form.prep_time}
                                onChange={(value) =>
                                    updateField("prep_time", value)
                                }
                            />

                            <Input
                                label="عدد الحصص"
                                type="number"
                                value={form.servings}
                                onChange={(value) =>
                                    updateField("servings", value)
                                }
                            />
                        </div>
                    </section>

                    {/* Ingredients */}

                    <section>
                        <div className="flex items-center justify-between mb-4">
                            <h3 className="font-bold text-sm text-gray-800">
                                المكونات
                            </h3>

                            <button
                                type="button"
                                onClick={addIngredient}
                                className="flex items-center gap-1 text-xs font-semibold text-[#407437]"
                            >
                                <Plus size={14} />
                                إضافة مكون
                            </button>
                        </div>

                        <div className="space-y-3">
                            {form.ingredients.map((ingredient, index) => (
                                <div
                                    key={index}
                                    className="grid grid-cols-[1fr_110px_100px_40px] gap-2"
                                >
                                    <input
                                        required
                                        placeholder="المكون"
                                        value={ingredient.name}
                                        onChange={(event) =>
                                            updateIngredient(
                                                index,
                                                "name",
                                                event.target.value,
                                            )
                                        }
                                        className="bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 text-sm outline-none"
                                    />

                                    <input
                                        type="number"
                                        placeholder="الكمية"
                                        value={ingredient.quantity}
                                        onChange={(event) =>
                                            updateIngredient(
                                                index,
                                                "quantity",
                                                event.target.value,
                                            )
                                        }
                                        className="bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 text-sm outline-none"
                                    />

                                    <input
                                        placeholder="الوحدة"
                                        value={ingredient.unit}
                                        onChange={(event) =>
                                            updateIngredient(
                                                index,
                                                "unit",
                                                event.target.value,
                                            )
                                        }
                                        className="bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 text-sm outline-none"
                                    />

                                    <button
                                        type="button"
                                        disabled={form.ingredients.length === 1}
                                        onClick={() => removeIngredient(index)}
                                        className="text-red-400 disabled:opacity-30"
                                    >
                                        <Trash2 size={16} />
                                    </button>
                                </div>
                            ))}
                        </div>
                    </section>

                    {/* Instructions */}

                    <section>
                        <div className="flex items-center justify-between mb-4">
                            <h3 className="font-bold text-sm text-gray-800">
                                خطوات التحضير
                            </h3>

                            <button
                                type="button"
                                onClick={addInstruction}
                                className="flex items-center gap-1 text-xs font-semibold text-[#407437]"
                            >
                                <Plus size={14} />
                                إضافة خطوة
                            </button>
                        </div>

                        <div className="space-y-3">
                            {form.instructions.map((instruction, index) => (
                                <div
                                    key={index}
                                    className="flex items-center gap-2"
                                >
                                    <span className="w-7 h-7 shrink-0 bg-green-50 text-[#407437] rounded-full flex items-center justify-center text-xs font-bold">
                                        {index + 1}
                                    </span>

                                    <input
                                        value={instruction}
                                        placeholder="اكتب خطوة التحضير..."
                                        onChange={(event) =>
                                            updateInstruction(
                                                index,
                                                event.target.value,
                                            )
                                        }
                                        className="flex-1 bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 text-sm outline-none"
                                    />

                                    <button
                                        type="button"
                                        onClick={() => removeInstruction(index)}
                                        className="text-red-400"
                                    >
                                        <Trash2 size={16} />
                                    </button>
                                </div>
                            ))}
                        </div>
                    </section>

                    {/* Public */}

                    <label className="flex items-center gap-2 text-sm text-gray-600">
                        <input
                            type="checkbox"
                            checked={form.is_public}
                            onChange={(event) =>
                                updateField("is_public", event.target.checked)
                            }
                            className="accent-[#407437]"
                        />
                        جعل الوصفة عامة
                    </label>

                    {/* Actions */}

                    <div className="flex justify-end gap-3 pt-4 border-t border-gray-100">
                        <button
                            type="button"
                            onClick={onClose}
                            className="px-5 py-2.5 rounded-xl border border-gray-200 text-sm text-gray-600"
                        >
                            إلغاء
                        </button>

                        <button
                            type="submit"
                            disabled={saving}
                            className="px-5 py-2.5 rounded-xl bg-[#407437] hover:bg-green-800 text-white text-sm font-semibold disabled:opacity-50"
                        >
                            {saving ? "جاري الحفظ..." : "حفظ الوصفة"}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    );
};

const Input = ({ label, value, onChange, type = "text", required = false }) => {
    return (
        <div>
            <label className="text-xs font-semibold text-gray-600">
                {label}
            </label>

            <input
                type={type}
                required={required}
                value={value}
                min={type === "number" ? 0 : undefined}
                onChange={(event) => onChange(event.target.value)}
                className="w-full mt-2 bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 text-sm outline-none focus:border-green-500"
            />
        </div>
    );
};

const Select = ({ label, value, onChange }) => {
    return (
        <div>
            <label className="text-xs font-semibold text-gray-600">
                {label}
            </label>

            <select
                value={value}
                onChange={(event) => onChange(event.target.value)}
                className="w-full mt-2 bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 text-sm outline-none focus:border-green-500"
            >
                <option value="breakfast">إفطار</option>

                <option value="lunch">غداء</option>

                <option value="dinner">عشاء</option>

                <option value="snack">سناك</option>
            </select>
        </div>
    );
};

export default RecipeFormModal;
