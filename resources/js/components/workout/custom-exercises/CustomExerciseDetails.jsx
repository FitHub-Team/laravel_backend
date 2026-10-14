import React from 'react';

export default function CustomExerciseDetails({ exercise }) {
    if (!exercise) return null;

    const Row = ({ label, value }) => (
        <div className="flex flex-col gap-1">
            <span className="text-xs text-gray-500">{label}</span>
            <span className="text-sm text-gray-800">{value || '—'}</span>
        </div>
    );

    return (
        <div className="flex flex-col gap-4">
            <h3 className="text-lg font-bold text-gray-800">{exercise.name}</h3>

            <div className="grid grid-cols-2 gap-4">
                <Row label="العضلة المستهدفة" value={exercise.muscle_group} />
                <Row label="المعدات" value={exercise.equipment} />
                <Row label="مستوى الصعوبة" value={exercise.difficulty} />
            </div>

            <div>
                <span className="text-xs text-gray-500">الوصف</span>
                <p className="text-sm text-gray-800 mt-1 whitespace-pre-line">
                    {exercise.description || '—'}
                </p>
            </div>

            <div>
                <span className="text-xs text-gray-500">التعليمات</span>
                <p className="text-sm text-gray-800 mt-1 whitespace-pre-line">
                    {exercise.instructions}
                </p>
            </div>
        </div>
    );
}