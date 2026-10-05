import React from 'react';

export default function ConfirmDeleteModal({ open, onCancel, onConfirm, loading }) {
    if (!open) return null;

    return (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4">
            <div className="bg-white rounded-xl w-full max-w-md p-6 shadow-xl" dir="rtl">
                <h3 className="text-lg font-bold text-gray-800 mb-2">
                    تأكيد الحذف
                </h3>
                <p className="text-sm text-gray-600 mb-6">
                    هل أنت متأكد من حذف هذا التمرين؟<br />
                    لن تتمكن من التراجع عن هذا الإجراء.
                </p>
                <div className="flex justify-end gap-2">
                    <button
                        onClick={onCancel}
                        disabled={loading}
                        className="px-4 py-2 text-sm rounded-lg text-gray-600 hover:bg-gray-50"
                    >
                        إلغاء
                    </button>
                    <button
                        onClick={onConfirm}
                        disabled={loading}
                        className="px-4 py-2 text-sm rounded-lg bg-red-600 text-white hover:bg-red-700 disabled:opacity-60"
                    >
                        {loading ? 'جارٍ الحذف...' : 'حذف'}
                    </button>
                </div>
            </div>
        </div>
    );
}