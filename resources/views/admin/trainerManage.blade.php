<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة المدربين</title>
    {{-- <link rel="stylesheet" href="{{ asset('front/css/style-trainer.css') }}"> --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <style>

:root {
    --teal-950: #0a2f2c;
    --teal-800: #0f6e6e;
    --teal-600: #128f89;
    --teal-500: #17a89e;
    --teal-400: #2ec9b8;
    --teal-100: #e3f5f1;
    --teal-50: #f2faf8;
    --ink: #0e2624;
    --ink-soft: #4c6663;
    --paper: #ffffff;
    --line: #d7ece7;
}

* {
    box-sizing: border-box;
}

body {
    margin: 0;
    font-family: 'Cairo', 'Inter', sans-serif;
    background: var(--teal-50);
    color: var(--ink);
    min-height: 100vh;
    background-image:
        radial-gradient(circle at 100% 0%, #d8f2ec 0%, transparent 45%),
        radial-gradient(circle at 0% 100%, #d8f2ec 0%, transparent 45%);
    background-repeat: no-repeat;
}

.container {
    max-width: 1180px;
    margin: 0 auto;
    padding: 32px 24px 60px;
}

/* ---------------- Navbar ---------------- */
.navbar {
    display: flex;
    gap: 10px;
    background: linear-gradient(135deg, var(--teal-800), var(--teal-950));
    padding: 10px;
    border-radius: 16px;
    margin-bottom: 28px;
    box-shadow: 0 8px 24px rgba(15, 110, 110, 0.18);
}

.nav-link {
    flex: 1;
    text-align: center;
    padding: 13px 16px;
    border-radius: 11px;
    color: #cdece6;
    text-decoration: none;
    font-weight: 600;
    font-size: 15px;
    transition: background .18s ease, color .18s ease;
}

.nav-link:hover {
    background: rgba(255, 255, 255, 0.08);
    color: #fff;
}

.nav-link.active {
    background: var(--teal-400);
    color: var(--teal-950);
    font-weight: 700;
}

/* ---------------- Filter bar ---------------- */
.filter-bar {
    display: flex;
    margin-bottom: 20px;
}

.search-input {
    width: 100%;
    max-width: 420px;
    padding: 13px 18px;
    border: 1px solid var(--line);
    border-radius: 12px;
    background: var(--paper);
    font-family: inherit;
    font-size: 14.5px;
    color: var(--ink);
    box-shadow: 0 2px 10px rgba(15, 110, 110, 0.06);
    outline: none;
    transition: border-color .15s ease, box-shadow .15s ease;
}

.search-input::placeholder {
    color: #9fb8b4;
}

.search-input:focus {
    border-color: var(--teal-500);
    box-shadow: 0 0 0 3px rgba(23, 168, 158, 0.15);
}

/* ---------------- Table card ---------------- */
.table-card {
    background: var(--paper);
    border: 1px solid var(--line);
    border-radius: 18px;
    padding: 8px;
    box-shadow: 0 6px 24px rgba(15, 110, 110, 0.07);
    overflow: hidden;
}

table {
    width: 100%;
    border-collapse: collapse;
}

thead th {
    text-align: right;
    font-size: 13px;
    font-weight: 700;
    color: var(--teal-800);
    background: var(--teal-50);
    padding: 14px 18px;
    border-bottom: 1px solid var(--line);
    white-space: nowrap;
}

thead th:first-child {
    border-top-right-radius: 12px;
}

thead th:last-child {
    border-top-left-radius: 12px;
}

tbody tr {
    border-bottom: 1px solid var(--line);
    transition: background .15s ease;
}

tbody tr:last-child {
    border-bottom: none;
}

tbody tr:hover {
    background: var(--teal-50);
}

tbody td {
    padding: 14px 18px;
    font-size: 14.5px;
    color: var(--ink);
    vertical-align: middle;
}

tbody td:first-child {
    color: var(--ink-soft);
    font-weight: 600;
}

/* "عرض التفاصيل" trigger label (acts as a button via the drawer checkbox) */
.details-btn {
    display: inline-block;
    color: var(--teal-600);
    font-weight: 700;
    text-decoration: none;
    cursor: pointer;
    font-size: 14px;
    padding: 6px 12px;
    border-radius: 8px;
    border: 1px solid var(--teal-100);
    background: var(--teal-50);
    transition: background .15s ease, border-color .15s ease, color .15s ease;
}

.details-btn:hover {
    background: var(--teal-400);
    border-color: var(--teal-400);
    color: var(--teal-950);
}

/* ---------------- Pagination ---------------- */
.pagination-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
    padding: 16px 18px 10px;
}

.pagination-info {
    font-size: 13.5px;
    color: var(--ink-soft);
    font-weight: 600;
}

.pagination-buttons {
    display: flex;
    gap: 6px;
}

.pagination-buttons button,
.pagination-buttons .page-btn {
    min-width: 34px;
    height: 34px;
    border-radius: 9px;
    border: 1px solid var(--line);
    background: var(--paper);
    color: var(--ink-soft);
    font-family: inherit;
    font-weight: 600;
    font-size: 13.5px;
    cursor: pointer;
    transition: all .15s ease;
}

.pagination-buttons button:hover {
    background: var(--teal-100);
    color: var(--teal-800);
}

.pagination-buttons button.active {
    background: var(--teal-500);
    border-color: var(--teal-500);
    color: #fff;
}

/* ---------------- Drawer (side panel) ---------------- */
.drawer-toggle {
    display: none;
}

.drawer-overlay {
    position: fixed;
    inset: 0;
    background: rgba(10, 47, 44, 0.35);
    backdrop-filter: blur(2px);
    opacity: 0;
    visibility: hidden;
    transition: opacity .25s ease, visibility .25s ease;
    z-index: 100;
    display: flex;
    justify-content: flex-end;
}

.drawer-toggle:checked~.drawer-overlay {
    opacity: 1;
    visibility: visible;
}

.drawer {
    width: 100%;
    max-width: 400px;
    height: 100%;
    background: var(--paper);
    box-shadow: -16px 0 40px rgba(10, 47, 44, 0.18);
    padding: 26px 26px 30px;
    transform: translateX(20px);
    transition: transform .25s ease;
    overflow-y: auto;
}

.drawer-toggle:checked~.drawer-overlay .drawer {
    transform: translateX(0);
}

.drawer-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 22px;
    padding-bottom: 16px;
    border-bottom: 1px solid var(--line);
}

.drawer-header h2 {
    font-family: 'Cairo', sans-serif;
    font-size: 18px;
    font-weight: 700;
    color: var(--teal-950);
    margin: 0;
}

.close-drawer {
    cursor: pointer;
    font-size: 22px;
    line-height: 1;
    color: var(--ink-soft);
    width: 32px;
    height: 32px;
    border-radius: 9px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background .15s ease;
}

.close-drawer:hover {
    background: var(--teal-100);
    color: var(--teal-800);
}

#drawer-avatar-container {
    background: var(--teal-100) !important;
    color: var(--teal-800) !important;
    border: 3px solid var(--teal-50);
    box-shadow: 0 4px 14px rgba(15, 110, 110, 0.15);
}

.detail-group {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 12px;
    padding: 12px 4px;
    border-bottom: 1px dashed var(--line);
}

.detail-group:last-of-type {
    border-bottom: none;
}

.detail-label {
    font-size: 13px;
    color: var(--ink-soft);
    font-weight: 600;
    flex-shrink: 0;
}

.detail-value {
    font-size: 14px;
    color: var(--ink);
    font-weight: 700;
    text-align: left;
}

.drawer-actions {
    display: flex;
    gap: 10px;
    margin-top: 26px;
}

.btn {
    padding: 12px 16px;
    border-radius: 11px;
    border: none;
    font-family: inherit;
    font-weight: 700;
    font-size: 14px;
    cursor: pointer;
    transition: filter .15s ease, transform .15s ease;
}

.btn:hover {
    filter: brightness(0.95);
    transform: translateY(-1px);
}

/* Inline colors set in the Blade file (approved / pending badges,
   success banner, edit / delete buttons) are preserved as-is;
   this stylesheet only keeps their shape, radius and spacing consistent
   with the rest of the theme. */

@media (max-width: 720px) {
    .navbar {
        flex-direction: column;
    }

    thead {
        display: none;
    }

    table,
    tbody,
    tr,
    td {
        display: block;
        width: 100%;
    }

    tbody tr {
        background: var(--paper);
        border: 1px solid var(--line);
        border-radius: 12px;
        margin-bottom: 10px;
        padding: 6px 4px;
    }

    tbody td {
        display: flex;
        justify-content: space-between;
        padding: 10px 14px;
    }

    .drawer {
        max-width: 100%;
    }
}
    </style>
</head>
<body>
    <input type="checkbox" id="drawer-check" class="drawer-toggle">

    <div class="container">
        <nav class="navbar">
            <a href="{{ route('admin.dashboard') }}" class="nav-link">لوحة الإحصائيات (Dashboard)</a>
            <a href="{{ route('admin.users.manage') }}" class="nav-link active">إدارة المستخدمين (Users Management)</a>
        </nav>

        @if(session('success'))
            <div style="background: #dcfce7; color: #15803d; padding: 12px; border-radius: 8px; margin-bottom: 16px; font-size: 14px;">
                {{ session('success') }}
            </div>
        @endif

        <div class="filter-bar">
            <input
                type="text"
                id="search-input"
                class="search-input"
                placeholder="ابحث باسم المدرب أو البريد الإلكتروني أو التخصص..."
                autocomplete="off"
            >
        </div>

        <div class="table-card">
            <table>
                <thead>
                    <tr>
                        <th># ID</th>
                        <th>الاسم</th>
                        <th>البريد الإلكتروني</th>
                        <th>التخصص</th>
                        <th>حالة الاعتماد</th>
                        <th>عرض التفاصيل</th>
                    </tr>
                </thead>
                <tbody id="trainers-table-body">
                    @forelse($trainers as $trainer)
                        <tr>
                            <td>#{{ $trainer->id }}</td>
                            <td><strong>{{ $trainer->user->name ?? $trainer->name ?? 'مدرب' }}</strong></td>
                            <td>{{ $trainer->user->email ?? $trainer->email ?? '-' }}</td>
                            <td>{{ $trainer->specialization ?? 'عام' }}</td>
                            <td>
                                @if($trainer->is_approved)
                                    <span style="color:#15803d; background:#dcfce7; padding:4px 8px; border-radius:6px; font-size:12px;">معتمد</span>
                                @else
                                    <span style="color:#b45309; background:#fef3c7; padding:4px 8px; border-radius:6px; font-size:12px;">بانتظار الموافقة</span>
                                @endif
                            </td>
                            <td>
                                <label for="drawer-check" class="details-btn" data-id="{{ $trainer->id }}">عرض التفاصيل</label>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; color: #64748b; padding: 20px;">لا يوجد مدربين معتمدين حالياً.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="pagination-container">
                <div class="pagination-info">إجمالي المدربين المعتمدين: {{ $trainers->count() }}</div>
            </div>
        </div>
    </div>

    <!-- الـ Drawer الجانبي للتفاصيل -->
    <div class="drawer-overlay">
        <div class="drawer">
            <div class="drawer-header">
                <h2>تفاصيل المدرب</h2>
                <label for="drawer-check" class="close-drawer" aria-label="إغلاق" title="إغلاق">&times;</label>
            </div>

            <div style="text-align: center; margin-bottom: 20px;">
                <div id="drawer-avatar-container" style="width: 80px; height: 80px; background: #e2e8f0; border-radius: 50%; margin: 0 auto 10px auto; display: flex; align-items: center; justify-content: center; font-size: 30px; color: #64748b; overflow: hidden;">
                    <span id="drawer-avatar-fallback">👤</span>
                    <img id="drawer-avatar-img" src="" alt="Profile" style="width: 100%; height: 100%; object-fit: cover; display: none;">
                </div>
                <span class="detail-value" id="drawer-name" style="font-size: 16px; font-weight: bold;">-</span>
            </div>

            <div class="detail-group">
                <span class="detail-label">رقم الهوية (National ID)</span>
                <span class="detail-value" id="drawer-national-id">-</span>
            </div>
            <div class="detail-group">
                <span class="detail-label">سنة الميلاد (Birth Year)</span>
                <span class="detail-value" id="drawer-birth-year">-</span>
            </div>
            <div class="detail-group">
                <span class="detail-label">المكان (Location)</span>
                <span class="detail-value" id="drawer-location">-</span>
            </div>
            <div class="detail-group">
                <span class="detail-label">التخصص (Specialization)</span>
                <span class="detail-value" id="drawer-specialization">-</span>
            </div>
            <div class="detail-group">
                <span class="detail-label">سنوات الخبرة (Experience)</span>
                <span class="detail-value" id="drawer-experience">-</span>
            </div>
            <div class="detail-group">
                <span class="detail-label">الشهادات المعتمدة (Certifications)</span>
                <span class="detail-value" id="drawer-certifications">-</span>
            </div>
            <div class="detail-group">
                <span class="detail-label">النبذة التعريفية (Bio)</span>
                <span class="detail-value" id="drawer-bio">-</span>
            </div>

            <div class="drawer-actions">
                <button id="toggle-approval-btn" class="btn" style="flex: 1; background-color: #dcfce7; color: #15803d;">اعتماد</button>
                <button id="delete-trainer-btn" class="btn" style="flex: 1; background-color: #fee2e2; color: #ef4444;">حذف الحساب</button>
            </div>
        </div>
    </div>

    <script src="{{ asset('front/js/trainers_management.js') }}"></script>
</body>
</html>
