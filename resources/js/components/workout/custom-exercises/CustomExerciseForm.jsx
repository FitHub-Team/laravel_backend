import React, { useEffect, useState } from 'react';

const DIFFICULTIES = ['Beginner', 'Intermediate', 'Advanced'];

const EMPTY_FORM = {
    name: '',
    description: '',
    muscle_group: '',
    equipment: '',
    difficulty: 'Beginner',
    instructions: '',
};

export default function CustomExerciseForm({ initialData, onSubmit, onCancel, submitting }) {
    const [form, setForm] = useState(EMPTY_FORM);
    const [errors, setErrors] = useState({});

    useEffect(() => {
        if (initialData) {
            setForm({
                name: initialData.name || '',
                description: initialData.description || '',
                muscle_group: initialData.muscle_group || '',
                equipment: initialData.equipment || '',
                difficulty: initialData.difficulty || 'Beginner',
                instructions: initialData.instructions || '',
            });
        } else {
            setForm(EMPTY_FORM);
        }
        setErrors({});
    }, [initialData]);

    const handleChange = (e) => {
        const { name, value } = e.target;
        setForm((prev) => ({ ...prev, [name]: value }));
        if (errors[name]) {
            setErrors((prev) => ({ ...prev, [name]: undefined }));
        }
    };

    const validate = () => {
        const newErrors = {};
        if (!form.name.trim()) newErrors.name = 'اسم التمرين مطلوب';
        if (!form.muscle_group.trim()) newErrors.muscle_group = 'العضلة المستهدفة مطلوبة';
        if (!form.difficulty) newErrors.difficulty = 'مستوى الصعوبة مطلوب';
        if (!form.instructions.trim()) newErrors.instructions = 'التعليمات مطلوبة';
        setErrors(newErrors);
        return Object.keys(newErrors).length === 0;
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        if (!validate()) return;
        onSubmit(form);
    };

    const inputClass =
        'w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#407437]/30 focus:border-[#407437]';

    return (
        <form onSubmit={handleSubmit} className="flex flex-col gap-4">
            <div>
                <label className="block text-sm font-medium text-gray-700 mb-1">اسم التمرين *</label>
                <input
                    type="text"
                    name="name"
                    value={form.name}
                    onChange={handleChange}
                    className={inputClass}
                    placeholder="مثال: Cable Chest Press"
                />
                {errors.name && <p className="text-xs text-red-500 mt-1">{errors.name}</p>}
            </div>

            <div>
                <label className="block text-sm font-medium text-gray-700 mb-1">الوصف</label>
                <textarea
                    name="description"
                    value={form.description}
                    onChange={handleChange}
                    rows={2}
                    className={inputClass}
                    placeholder="وصف مختصر للتمرين"
                />
            </div>

            <div className="grid grid-cols-2 gap-3">
                <div>
                    <label className="block text-sm font-medium text-gray-700 mb-1">العضلة المستهدفة *</label>
                    <input
                        type="text"
                        name="muscle_group"
                        value={form.muscle_group}
                        onChange={handleChange}
                        className={inputClass}
                        placeholder="Chest / Back / Legs..."
                    />
                    {errors.muscle_group && <p className="text-xs text-red-500 mt-1">{errors.muscle_group}</p>}
                </div>

                <div>
                    <label className="block text-sm font-medium text-gray-700 mb-1">المعدات</label>
                    <input
                        type="text"
                        name="equipment"
                        value={form.equipment}
                        onChange={handleChange}
                        className={inputClass}
                        placeholder="Cable / Dumbbell..."
                    />
                </div>
            </div>

            <div>
                <label className="block text-sm font-medium text-gray-700 mb-1">مستوى الصعوبة *</label>
                <select
                    name="difficulty"
                    value={form.difficulty}
                    onChange={handleChange}
                    className={inputClass}
                >
                    {DIFFICULTIES.map((level) => (
                        <option key={level} value={level}>{level}</option>
                    ))}
                </select>
                {errors.difficulty && <p className="text-xs text-red-500 mt-1">{errors.difficulty}</p>}
            </div>

            <div>
                <label className="block text-sm font-medium text-gray-700 mb-1">التعليمات *</label>
                <textarea
                    name="instructions"
                    value={form.instructions}
                    onChange={handleChange}
                    rows={4}
                    className={inputClass}
                    placeholder="خطوات تنفيذ التمرين..."
                />
                {errors.instructions && <p className="text-xs text-red-500 mt-1">{errors.instructions}</p>}
            </div>

            <div className="flex justify-end gap-2 pt-2 border-t border-gray-100">
                <button
                    type="button"
                    onClick={onCancel}
                    disabled={submitting}
                    className="px-4 py-2 text-sm rounded-lg text-gray-600 hover:bg-gray-50"
                >
                    إلغاء
                </button>
                <button
                    type="submit"
                    disabled={submitting}
                    className="px-4 py-2 text-sm rounded-lg bg-[#407437] text-white hover:bg-[#355f2c] disabled:opacity-60"
                >
                    {submitting ? 'جارٍ الحفظ...' : 'حفظ'}
                </button>
            </div>
        </form>
    );
}