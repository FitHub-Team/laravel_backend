
import { useEffect, useMemo, useState } from "react";

const initialChallenges = [
  {
    id: 1,
    title: "تحدي 10,000 خطوة يوميًا",
    description: "امشِ 10,000 خطوة يوميًا طوال الأسبوع.",
    points: 150,
    is_active: true,
    created_at: "2026-10-01T09:00:00Z",
    updated_at: "2026-10-01T09:00:00Z",
  },
  {
    id: 2,
    title: "تحدي شرب الماء",
    description: "التزم بشرب كمية كافية من الماء يوميًا.",
    points: 100,
    is_active: true,
    created_at: "2026-10-02T09:00:00Z",
    updated_at: "2026-10-02T09:00:00Z",
  },
  {
    id: 3,
    title: "تحدي التمارين الأسبوعية",
    description: "أكمل خمس جلسات تدريب خلال الأسبوع.",
    points: 200,
    is_active: false,
    created_at: "2026-10-03T09:00:00Z",
    updated_at: "2026-10-03T09:00:00Z",
  },
];

const STORAGE_KEY = "superfit_mock_challenges";

function loadChallenges() {
  try {
    const stored = localStorage.getItem(STORAGE_KEY);
    return stored ? JSON.parse(stored) : initialChallenges;
  } catch {
    return initialChallenges;
  }
}

const emptyForm = {
  title: "",
  description: "",
  points: 0,
  is_active: true,
};

export default function ChallengesPage() {
  const [challenges, setChallenges] = useState(loadChallenges);
  const [search, setSearch] = useState("");
  const [status, setStatus] = useState("all");
  const [editingId, setEditingId] = useState(null);
  const [isFormOpen, setIsFormOpen] = useState(false);
  const [form, setForm] = useState(emptyForm);

  useEffect(() => {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(challenges));
  }, [challenges]);

  const filteredChallenges = useMemo(() => {
    return challenges.filter((challenge) => {
      const matchesSearch = challenge.title
        .toLowerCase()
        .includes(search.toLowerCase());

      const matchesStatus =
        status === "all" ||
        (status === "active" && challenge.is_active) ||
        (status === "inactive" && !challenge.is_active);

      return matchesSearch && matchesStatus;
    });
  }, [challenges, search, status]);

  const activeCount = challenges.filter(
    (challenge) => challenge.is_active
  ).length;

  const openAddForm = () => {
    setEditingId(null);
    setForm({ ...emptyForm });
    setIsFormOpen(true);
  };

  const openEditForm = (challenge) => {
    setEditingId(challenge.id);
    setForm({
      title: challenge.title,
      description: challenge.description || "",
      points: challenge.points,
      is_active: challenge.is_active,
    });
    setIsFormOpen(true);
  };

  const saveChallenge = (event) => {
    event.preventDefault();

    const title = form.title.trim();
    const points = Number(form.points);

    if (!title || !Number.isInteger(points) || points < 0) {
      alert("يرجى إدخال اسم التحدي ونقاط صحيحة.");
      return;
    }

    const now = new Date().toISOString();

    if (editingId !== null) {
      setChallenges((previous) =>
        previous.map((challenge) =>
          challenge.id === editingId
            ? {
                ...challenge,
                ...form,
                title,
                points,
                updated_at: now,
              }
            : challenge
        )
      );
    } else {
      setChallenges((previous) => [
        {
          ...form,
          id: Math.max(0, ...previous.map((item) => item.id)) + 1,
          title,
          points,
          created_at: now,
          updated_at: now,
        },
        ...previous,
      ]);
    }

    setIsFormOpen(false);
    setEditingId(null);
  };

  const toggleChallenge = (id) => {
    setChallenges((previous) =>
      previous.map((challenge) =>
        challenge.id === id
          ? {
              ...challenge,
              is_active: !challenge.is_active,
              updated_at: new Date().toISOString(),
            }
          : challenge
      )
    );
  };

  const deleteChallenge = (id) => {
    if (!window.confirm("هل أنت متأكد من حذف هذا التحدي؟")) {
      return;
    }

    setChallenges((previous) =>
      previous.filter((challenge) => challenge.id !== id)
    );
  };

  return (
    <div dir="rtl" className="min-h-screen p-6 text-slate-800">
      <div className="mx-auto max-w-7xl space-y-6">
        {/* Page header */}
        <div className="flex flex-wrap items-center justify-between gap-4">
          <div>
            <h1 className="text-2xl font-bold">إدارة التحديات</h1>
            <p className="mt-1 text-sm text-slate-500">
              إدارة التحديات الأسبوعية ونقاط المكافآت.
            </p>
          </div>

          <button
            onClick={openAddForm}
            className="rounded-xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white hover:bg-emerald-700"
          >
            + إضافة تحدٍ جديد
          </button>
        </div>

        {/* Statistics */}
        <div className="grid gap-4 sm:grid-cols-3">
          {[
            ["إجمالي التحديات", challenges.length],
            ["التحديات النشطة", activeCount],
            ["التحديات غير النشطة", challenges.length - activeCount],
          ].map(([label, value]) => (
            <div
              key={label}
              className="rounded-2xl border border-slate-200 bg-white p-5"
            >
              <p className="text-sm text-slate-500">{label}</p>
              <p className="mt-3 text-3xl font-bold">{value}</p>
            </div>
          ))}
        </div>

        {/* Search and filters */}
        <div className="flex flex-wrap gap-3 rounded-2xl border border-slate-200 bg-white p-4">
          <input
            value={search}
            onChange={(event) => setSearch(event.target.value)}
            placeholder="ابحث عن تحدٍ..."
            className="min-w-52 flex-1 rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-emerald-500"
          />

          <select
            value={status}
            onChange={(event) => setStatus(event.target.value)}
            className="rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm"
          >
            <option value="all">جميع التحديات</option>
            <option value="active">نشطة</option>
            <option value="inactive">غير نشطة</option>
          </select>
        </div>

        {/* Challenges table */}
        <div className="overflow-hidden rounded-2xl border border-slate-200 bg-white">
          <div className="overflow-x-auto">
            <table className="w-full text-right text-sm">
              <thead className="bg-slate-50 text-slate-500">
                <tr>
                  <th className="px-5 py-4">التحدي</th>
                  <th className="px-5 py-4">النقاط</th>
                  <th className="px-5 py-4">الحالة</th>
                  <th className="px-5 py-4">تاريخ الإنشاء</th>
                  <th className="px-5 py-4">الإجراءات</th>
                </tr>
              </thead>

              <tbody className="divide-y divide-slate-100">
                {filteredChallenges.map((challenge) => (
                  <tr key={challenge.id} className="hover:bg-slate-50">
                    <td className="px-5 py-4">
                      <p className="font-semibold">{challenge.title}</p>
                      <p className="mt-1 max-w-sm text-slate-500">
                        {challenge.description || "لا يوجد وصف"}
                      </p>
                    </td>

                    <td className="px-5 py-4">{challenge.points}</td>

                    <td className="px-5 py-4">
                      <span
                        className={`rounded-full px-3 py-1 text-xs ${
                          challenge.is_active
                            ? "bg-emerald-50 text-emerald-700"
                            : "bg-slate-100 text-slate-600"
                        }`}
                      >
                        {challenge.is_active ? "نشط" : "غير نشط"}
                      </span>
                    </td>

                    <td className="px-5 py-4 text-slate-500">
                      {new Date(challenge.created_at).toLocaleDateString(
                        "ar"
                      )}
                    </td>

                    <td className="px-5 py-4">
                      <div className="flex flex-wrap gap-2">
                        <button
                          onClick={() => openEditForm(challenge)}
                          className="rounded-lg border px-3 py-2 hover:bg-slate-100"
                        >
                          تعديل
                        </button>
                        <button
                          onClick={() => toggleChallenge(challenge.id)}
                          className="rounded-lg border px-3 py-2 hover:bg-slate-100"
                        >
                          {challenge.is_active ? "تعطيل" : "تفعيل"}
                        </button>
                        <button
                          onClick={() => deleteChallenge(challenge.id)}
                          className="rounded-lg border border-red-200 px-3 py-2 text-red-600 hover:bg-red-50"
                        >
                          حذف
                        </button>
                      </div>
                    </td>
                  </tr>
                ))}

                {filteredChallenges.length === 0 && (
                  <tr>
                    <td
                      colSpan={5}
                      className="px-5 py-12 text-center text-slate-500"
                    >
                      لا توجد تحديات مطابقة للبحث.
                    </td>
                  </tr>
                )}
              </tbody>
            </table>
          </div>
        </div>

        {/* Add / Edit modal */}
        {isFormOpen && (
          <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
            <div className="w-full max-w-lg rounded-2xl bg-white p-6 shadow-xl">
              <h2 className="mb-5 text-xl font-bold">
                {editingId !== null ? "تعديل التحدي" : "إضافة تحدٍ جديد"}
              </h2>

              <form onSubmit={saveChallenge} className="space-y-4">
                <div>
                  <label className="mb-2 block text-sm font-medium">
                    عنوان التحدي
                  </label>
                  <input
                    required
                    maxLength={255}
                    value={form.title}
                    onChange={(event) =>
                      setForm({ ...form, title: event.target.value })
                    }
                    className="w-full rounded-xl border px-4 py-3"
                  />
                </div>

                <div>
                  <label className="mb-2 block text-sm font-medium">
                    الوصف
                  </label>
                  <textarea
                    rows={3}
                    value={form.description}
                    onChange={(event) =>
                      setForm({
                        ...form,
                        description: event.target.value,
                      })
                    }
                    className="w-full rounded-xl border px-4 py-3"
                  />
                </div>

                <div>
                  <label className="mb-2 block text-sm font-medium">
                    نقاط المكافأة
                  </label>
                  <input
                    type="number"
                    min="0"
                    step="1"
                    required
                    value={form.points}
                    onChange={(event) =>
                      setForm({ ...form, points: event.target.value })
                    }
                    className="w-full rounded-xl border px-4 py-3"
                  />
                </div>

                <label className="flex items-center gap-3 text-sm">
                  <input
                    type="checkbox"
                    checked={form.is_active}
                    onChange={(event) =>
                      setForm({
                        ...form,
                        is_active: event.target.checked,
                      })
                    }
                  />
                  تفعيل التحدي
                </label>

                <div className="flex justify-end gap-3 pt-3">
                  <button
                    type="button"
                    onClick={() => setIsFormOpen(false)}
                    className="rounded-xl border px-5 py-3"
                  >
                    إلغاء
                  </button>
                  <button
                    type="submit"
                    className="rounded-xl bg-emerald-600 px-5 py-3 text-white"
                  >
                    حفظ التحدي
                  </button>
                </div>
              </form>
            </div>
          </div>
        )}
      </div>
    </div>
  );
}
