import React from 'react';

const MUSCLE_GROUPS = ['Chest', 'Back', 'Legs', 'Shoulders', 'Arms', 'Core'];
const DIFFICULTIES = ['Beginner', 'Intermediate', 'Advanced'];
const EQUIPMENT = ['Barbell', 'Dumbbell', 'Cable', 'Machine', 'Bodyweight', 'Kettlebell'];

export default function CustomExerciseFilters({ filters, onChange }) {
    const selectClass =
        'border border-gray-200 rounded-lg px-3 py-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-[#407437]/30 focus:border-[#407437]';

    return (
        <div className="flex flex-wrap gap-3">
            <select
                value={filters.muscle_group || ''}
                onChange={(e) => onChange({ ...filters, muscle_group: e.target.value })}
                className={selectClass}
            >
                <option value="">كل العضلات</option>
                {MUSCLE_GROUPS.map((m) => (
                    <option key={m} value={m}>{m}</option>
                ))}
            </select>

            <select
                value={filters.difficulty || ''}
                onChange={(e) => onChange({ ...filters, difficulty: e.target.value })}
                className={selectClass}
            >
                <option value="">كل المستويات</option>
                {DIFFICULTIES.map((d) => (
                    <option key={d} value={d}>{d}</option>
                ))}
            </select>

            <select
                value={filters.equipment || ''}
                onChange={(e) => onChange({ ...filters, equipment: e.target.value })}
                className={selectClass}
            >
                <option value="">كل المعدات</option>
                {EQUIPMENT.map((eq) => (
                    <option key={eq} value={eq}>{eq}</option>
                ))}
            </select>
        </div>
    );
}