import React from 'react';

export default function CustomExerciseModal({ open, title, onClose, children }) {
    if (!open) return null;

    return (
        <div
            className="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4"
            onClick={onClose}
        >
            <div
                className="bg-white rounded-xl w-full max-w-lg max-h-[90vh] overflow-y-auto p-6 shadow-xl"
                onClick={(e) => e.stopPropagation()}
                dir="rtl"
            >
                <div className="flex items-center justify-between mb-4">
                    <h2 className="text-lg font-bold text-gray-800">{title}</h2>
                    <button
                        onClick={onClose}
                        className="text-gray-400 hover:text-gray-600 text-xl leading-none"
                        aria-label="إغلاق"
                    >
                        ×
                    </button>
                </div>
                {children}
            </div>
        </div>
    );
}