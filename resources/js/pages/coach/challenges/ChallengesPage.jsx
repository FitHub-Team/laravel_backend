
import { useEffect, useMemo, useState } from "react";

const STORAGE_KEY = "superfit_challenges";

const initialChallenges = [
  {
    id: 1,
    title: "تحدي خسارة الوزن",
    description: "تحدي لتحسين اللياقة وخسارة الوزن.",
    goal: "خسارة الوزن",
    duration: 30,
    startDate: "2026-10-01",
    endDate: "2026-10-30",
    participants: 18,
    status: "active",
  },
  {
    id: 2,
    title: "تحدي بناء العضلات",
    description: "برنامج تدريبي لزيادة الكتلة العضلية.",
    goal: "بناء العضلات",
    duration: 45,
    startDate: "2026-10-20",
    endDate: "2026-12-03",
    participants: 12,
    status: "upcoming",
  },
  {
    id: 3,
    title: "تحدي اللياقة",
    description: "تحسين اللياقة العامة والتحمل.",
    goal: "تحسين اللياقة",
    duration: 21,
    startDate: "2026-09-01",
    endDate: "2026-09-21",
    participants: 25,
    status: "completed",
  },
];

const emptyForm = {
  title: "",
  description: "",
  goal: "",
  duration: 30,
  startDate: "",
  endDate: "",
  participants: 0,
  status: "upcoming",
};

const statusLabels = {
  active: "نشط",
  upcoming: "قادم",
  completed: "مكتمل",
};

const statusStyles = {
  active: "bg-emerald-50 text-emerald-700",
  upcoming: "bg-blue-50 text-blue-700",
  completed: "bg-gray-100 text-gray-600",
};

function loadChallenges() {
  try {
    const stored = localStorage.getItem(STORAGE_KEY);

    if (stored !== null) {
      const parsed = JSON.parse(stored);
      if (Array.isArray(parsed)) return parsed;
    }
  } catch (error) {
    console.error("Failed to load challenges:", error);
  }

  return initialChallenges;
}

function formatDate(date) {
  if (!date) return "—";

  const parsed = new Date(`${date}T00:00:00`);

  if (Number.isNaN(parsed.getTime())) return date;

  return parsed.toLocaleDateString("ar", {
    year: "numeric",
    month: "short",
    day: "numeric",
  });
}

function Field({ label, children }) {
  return (
    <label className="flex flex-col gap-2 text-sm font-medium text-gray-700">
      <span>{label}</span>
      {children}
    </label>
  );
}

const inputClass =
  "w-full rounded-xl border border-gray-200 bg-white px-4 py-3 " +
  "text-sm outline-none transition focus:border-blue-500 " +
  "focus:ring-2 focus:ring-blue-100";

export default function ChallengesPage() {
  const [challenges, setChallenges] = useState(loadChallenges);
  const [search, setSearch] = useState("");
  const [statusFilter, setStatusFilter] = useState("all");

  const [modalOpen, setModalOpen] = useState(false);
  const [detailsOpen, setDetailsOpen] = useState(false);
  const [editingId, setEditingId] = useState(null);
  const [selectedChallenge, setSelectedChallenge] = useState(null);

  const [form, setForm] = useState({ ...emptyForm });
  const [error, setError] = useState("");

  useEffect(() => {
    try {
      localStorage.setItem(
        STORAGE_KEY,
        JSON.stringify(challenges)
      );
    } catch (error) {
      console.error("Failed to save challenges:", error);
    }
  }, [challenges]);

  const stats = useMemo(() => ({
    total: challenges.length,
    active: challenges.filter(c => c.status === "active").length,
    upcoming: challenges.filter(c => c.status === "upcoming").length,
    completed: challenges.filter(c => c.status === "completed").length,
  }), [challenges]);

  const filteredChallenges = useMemo(() => {
    return challenges.filter(challenge => {
      const term = search.trim().toLowerCase();

      const matchesSearch =
        challenge.title.toLowerCase().includes(term) ||
        challenge.goal.toLowerCase().includes(term);

      const matchesStatus =
        statusFilter === "all" ||
        challenge.status === statusFilter;

      return matchesSearch && matchesStatus;
    });
  }, [challenges, search, statusFilter]);

  function updateForm(field, value) {
    setForm(prev => ({ ...prev, [field]: value }));
    setError("");
  }

  function openCreateModal() {
    setEditingId(null);
    setForm({ ...emptyForm });
    setError("");
    setModalOpen(true);
  }

  function openEditModal(challenge) {
    setEditingId(challenge.id);
    setForm({ ...challenge });
    setError("");
    setModalOpen(true);
  }

  function closeModal() {
    setModalOpen(false);
    setEditingId(null);
    setError("");
  }

  function saveChallenge(event) {
    event.preventDefault();

    if (!form.title.trim() || !form.goal.trim()) {
      setError("يرجى إدخال اسم التحدي والهدف.");
      return;
    }

    if (!form.startDate || !form.endDate) {
      setError("يرجى تحديد تاريخ البداية والنهاية.");
      return;
    }

    if (form.endDate < form.startDate) {
      setError("تاريخ النهاية يجب أن يكون بعد تاريخ البداية.");
      return;
    }

    const duration = Number(form.duration);
    const participants = Number(form.participants);

    if (!Number.isInteger(duration) || duration < 1) {
      setError("مدة التحدي يجب أن تكون عدد أيام صحيحًا.");
      return;
    }

    if (!Number.isInteger(participants) || participants < 0) {
      setError("عدد المشاركين يجب أن يكون عددًا صحيحًا غير سالب.");
      return;
    }

    const payload = {
      ...form,
      title: form.title.trim(),
      goal: form.goal.trim(),
      description: form.description.trim(),
      duration,
      participants,
    };

    if (editingId !== null) {
      setChallenges(prev =>
        prev.map(challenge =>
          challenge.id === editingId
            ? { ...payload, id: editingId }
            : challenge
        )
      );
    } else {
      setChallenges(prev => [
        {
          ...payload,
          id: crypto.randomUUID(),
        },
        ...prev,
      ]);
    }

    closeModal();
  }

  function deleteChallenge(challenge) {
    const confirmed = window.confirm(
      `هل تريد حذف تحدي "${challenge.title}"؟`
    );

    if (!confirmed) return;

    setChallenges(prev =>
      prev.filter(item => item.id !== challenge.id)
    );

    setDetailsOpen(false);
  }

  function showDetails(challenge) {
    setSelectedChallenge(challenge);
    setDetailsOpen(true);
  }

  const statCards = [
    {
      label: "إجمالي التحديات",
      value: stats.total,
      color: "text-gray-900",
      bg: "bg-gray-100",
    },
    {
      label: "التحديات النشطة",
      value: stats.active,
      color: "text-emerald-600",
      bg: "bg-emerald-50",
    },
    {
      label: "التحديات القادمة",
      value: stats.upcoming,
      color: "text-blue-600",
      bg: "bg-blue-50",
    },
    {
      label: "التحديات المكتملة",
      value: stats.completed,
      color: "text-purple-600",
      bg: "bg-purple-50",
    },
  ];

  return (
    <div
      dir="rtl"
      className="min-h-screen bg-gray-50 p-4 text-gray-900 md:p-6"
    >
      <div className="mx-auto max-w-7xl space-y-6">

        {/* Page Header */}
        <div className="flex flex-wrap items-center justify-between gap-4">
          <div>
            <h1 className="text-2xl font-bold">
              إدارة التحديات
            </h1>
            <p className="mt-2 text-sm text-gray-500">
              إنشاء وإدارة التحديات الرياضية للمتدربين
            </p>
          </div>

          <button
            type="button"
            onClick={openCreateModal}
            className="rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-700"
          >
            + إضافة تحدي جديد
          </button>
        </div>

        {/* Statistics */}
        <div className="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
          {statCards.map(card => (
            <div
              key={card.label}
              className="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm"
            >
              <p className="text-sm text-gray-500">
                {card.label}
              </p>

              <div className={`mt-4 inline-flex rounded-xl p-3 ${card.bg}`}>
                <span className={`text-2xl font-bold ${card.color}`}>
                  {card.value}
                </span>
              </div>
            </div>
          ))}
        </div>

        {/* Filters */}
        <div className="rounded-2xl border border-gray-100 bg-white p-4 shadow-sm">
          <div className="flex flex-col gap-3 md:flex-row">
            <input
              type="search"
              value={search}
              onChange={e => setSearch(e.target.value)}
              placeholder="ابحث عن تحدي أو هدف..."
              className={`${inputClass} md:flex-1`}
            />

            <select
              value={statusFilter}
              onChange={e => setStatusFilter(e.target.value)}
              className={`${inputClass} md:w-52`}
            >
              <option value="all">جميع الحالات</option>
              <option value="active">نشط</option>
              <option value="upcoming">قادم</option>
              <option value="completed">مكتمل</option>
            </select>
          </div>
        </div>

        {/* Challenges Table */}
        <div className="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
          <div className="flex items-center justify-between border-b border-gray-100 p-5">
            <h2 className="font-semibold">قائمة التحديات</h2>
            <span className="text-sm text-gray-500">
              {filteredChallenges.length} تحديات
            </span>
          </div>

          <div className="overflow-x-auto">
            <table className="w-full min-w-[900px] text-right text-sm">
              <thead className="bg-gray-50 text-gray-500">
                <tr>
                  {[
                    "اسم التحدي",
                    "الهدف",
                    "المدة",
                    "المشاركون",
                    "تاريخ البداية",
                    "الحالة",
                    "الإجراءات",
                  ].map(header => (
                    <th
                      key={header}
                      className="whitespace-nowrap px-5 py-4 font-medium"
                    >
                      {header}
                    </th>
                  ))}
                </tr>
              </thead>

              <tbody className="divide-y divide-gray-100">
                {filteredChallenges.map(challenge => (
                  <tr
                    key={challenge.id}
                    className="hover:bg-gray-50"
                  >
                    <td className="px-5 py-4 font-semibold">
                      {challenge.title}
                    </td>
                    <td className="px-5 py-4">
                      {challenge.goal}
                    </td>
                    <td className="px-5 py-4">
                      {challenge.duration} يوم
                    </td>
                    <td className="px-5 py-4">
                      {challenge.participants}
                    </td>
                    <td className="px-5 py-4">
                      {formatDate(challenge.startDate)}
                    </td>
                    <td className="px-5 py-4">
                      <span
                        className={`rounded-full px-3 py-1 text-xs font-semibold ${statusStyles[challenge.status] || statusStyles.upcoming}`}
                      >
                        {statusLabels[challenge.status] || challenge.status}
                      </span>
                    </td>
                    <td className="px-5 py-4">
                      <div className="flex items-center gap-3">
                        <button
                          type="button"
                          onClick={() => showDetails(challenge)}
                          className="font-medium text-blue-600 hover:underline"
                        >
                          عرض
                        </button>

                        <button
                          type="button"
                          onClick={() => openEditModal(challenge)}
                          className="font-medium text-gray-600 hover:underline"
                        >
                          تعديل
                        </button>

                        <button
                          type="button"
                          onClick={() => deleteChallenge(challenge)}
                          className="font-medium text-red-600 hover:underline"
                        >
                          حذف
                        </button>
                      </div>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>

          {filteredChallenges.length === 0 && (
            <div className="p-12 text-center text-gray-500">
              لا توجد تحديات مطابقة للبحث.
            </div>
          )}
        </div>
      </div>

      {/* Create / Edit Modal */}
      {modalOpen && (
        <div
          className="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
          dir="rtl"
          onMouseDown={e => {
            if (e.target === e.currentTarget) closeModal();
          }}
        >
          <div
            role="dialog"
            aria-modal="true"
            aria-labelledby="challenge-form-title"
            className="max-h-[90vh] w-full max-w-2xl overflow-y-auto rounded-2xl bg-white p-6 shadow-xl"
          >
            <div className="mb-6 flex items-center justify-between">
              <h2 id="challenge-form-title" className="text-xl font-bold">
                {editingId !== null
                  ? "تعديل التحدي"
                  : "إضافة تحدي جديد"}
              </h2>
              <button
                type="button"
                onClick={closeModal}
                className="text-2xl text-gray-400 hover:text-gray-800"
                aria-label="إغلاق"
              >
                ×
              </button>
            </div>

            <form onSubmit={saveChallenge} className="space-y-5">
              <Field label="اسم التحدي">
                <input
                  required
                  className={inputClass}
                  value={form.title}
                  onChange={e => updateForm("title", e.target.value)}
                  placeholder="مثال: تحدي خسارة الوزن"
                />
              </Field>

              <Field label="وصف التحدي">
                <textarea
                  rows={3}
                  className={inputClass}
                  value={form.description}
                  onChange={e => updateForm("description", e.target.value)}
                  placeholder="اكتب وصفًا مختصرًا للتحدي"
                />
              </Field>

              <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <Field label="هدف التحدي">
                  <input
                    required
                    className={inputClass}
                    value={form.goal}
                    onChange={e => updateForm("goal", e.target.value)}
                    placeholder="مثال: بناء العضلات"
                  />
                </Field>

                <Field label="المدة بالأيام">
                  <input
                    required
                    type="number"
                    min="1"
                    step="1"
                    className={inputClass}
                    value={form.duration}
                    onChange={e => updateForm("duration", e.target.value)}
                  />
                </Field>

                <Field label="تاريخ البداية">
                  <input
                    required
                    type="date"
                    className={inputClass}
                    value={form.startDate}
                    onChange={e => updateForm("startDate", e.target.value)}
                  />
                </Field>

                <Field label="تاريخ النهاية">
                  <input
                    required
                    type="date"
                    min={form.startDate || undefined}
                    className={inputClass}
                    value={form.endDate}
                    onChange={e => updateForm("endDate", e.target.value)}
                  />
                </Field>

                <Field label="عدد المشاركين">
                  <input
                    required
                    type="number"
                    min="0"
                    step="1"
                    className={inputClass}
                    value={form.participants}
                    onChange={e => updateForm("participants", e.target.value)}
                  />
                </Field>

                <Field label="حالة التحدي">
                  <select
                    className={inputClass}
                    value={form.status}
                    onChange={e => updateForm("status", e.target.value)}
                  >
                    <option value="upcoming">قادم</option>
                    <option value="active">نشط</option>
                    <option value="completed">مكتمل</option>
                  </select>
                </Field>
              </div>

              {error && (
                <p role="alert" className="rounded-lg bg-red-50 p-3 text-sm text-red-600">
                  {error}
                </p>
              )}

              <div className="flex justify-end gap-3 border-t border-gray-100 pt-5">
                <button
                  type="button"
                  onClick={closeModal}
                  className="rounded-xl border border-gray-200 px-5 py-3 text-sm"
                >
                  إلغاء
                </button>

                <button
                  type="submit"
                  className="rounded-xl bg-blue-600 px-6 py-3 text-sm font-semibold text-white hover:bg-blue-700"
                >
                  {editingId !== null ? "حفظ التعديلات" : "إضافة التحدي"}
                </button>
              </div>
            </form>
          </div>
        </div>
      )}

      {/* Details Modal */}
      {detailsOpen && selectedChallenge && (
        <div
          className="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
          dir="rtl"
          onMouseDown={e => {
            if (e.target === e.currentTarget) setDetailsOpen(false);
          }}
        >
          <div
            role="dialog"
            aria-modal="true"
            aria-labelledby="challenge-details-title"
            className="w-full max-w-lg rounded-2xl bg-white p-6 shadow-xl"
          >
            <div className="mb-5 flex items-center justify-between">
              <h2 id="challenge-details-title" className="text-xl font-bold">
                تفاصيل التحدي
              </h2>
              <button
                type="button"
                onClick={() => setDetailsOpen(false)}
                className="text-2xl text-gray-400"
                aria-label="إغلاق"
              >
                ×
              </button>
            </div>

            <div className="space-y-4 text-sm">
              {[
                ["اسم التحدي", selectedChallenge.title],
                ["الوصف", selectedChallenge.description || "—"],
                ["الهدف", selectedChallenge.goal],
                ["المدة", `${selectedChallenge.duration} يوم`],
                ["المشاركون", selectedChallenge.participants],
                ["تاريخ البداية", formatDate(selectedChallenge.startDate)],
                ["تاريخ النهاية", formatDate(selectedChallenge.endDate)],
                ["الحالة", statusLabels[selectedChallenge.status] || "—"],
              ].map(([label, value]) => (
                <div
                  key={label}
                  className="flex justify-between gap-4 border-b border-gray-100 pb-3"
                >
                  <span className="shrink-0 text-gray-500">
                    {label}
                  </span>
                  <span className="text-left font-medium">
                    {value}
                  </span>
                </div>
              ))}
            </div>

            <button
              type="button"
              onClick={() => setDetailsOpen(false)}
              className="mt-6 w-full rounded-xl bg-blue-600 py-3 text-sm font-semibold text-white"
            >
              إغلاق
            </button>
          </div>
        </div>
      )}
    </div>
  );
}
