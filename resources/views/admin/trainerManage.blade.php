<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة المدربين</title>
    <link rel="stylesheet" href="{{ asset('front/css/style-trainer.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
