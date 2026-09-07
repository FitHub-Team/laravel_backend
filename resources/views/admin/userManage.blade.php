<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>إدارة المستخدمين - SuperFit</title>
    <link rel="stylesheet" href="{{ asset('front/css/style-user.css') }}">
</head>

<body>

    <input type="checkbox" id="drawer-check" class="drawer-toggle">

    <div class="container">
        <nav class="navbar">
            <a href="{{ route('admin.dashboard') }}" class="nav-link">لوحة الإحصائيات (Dashboard)</a>
            <a href="{{ route('admin.trainer.manage') }}" class="nav-link">إدارة المدربين (Coach Management)</a>
        </nav>

        @if(session('success'))
            <div style="background: #dcfce7; color: #15803d; padding: 12px; border-radius: 8px; margin-bottom: 16px; font-size: 14px;">
                {{ session('success') }}
            </div>
        @endif

        <!-- البحث -->
        <div class="filter-bar">
            <input
                type="text"
                id="search-input"
                class="search-input"
                placeholder="ابحث باسم المستخدم أو البريد الإلكتروني..."
                autocomplete="off"
            >
        </div>

        <!-- جدول المستخدمين -->
        <div class="table-card">
            <table>
                <thead>
                    <tr>
                        <th># ID</th>
                        <th>الاسم</th>
                        <th>البريد الإلكتروني</th>
                        <th>عرض التفاصيل</th>
                    </tr>
                </thead>
               <tbody id="users-table-body">
                @forelse($users as $user)
                    <tr id="user-row-{{ $user->id }}">
                        <td>#{{ $user->id }}</td>
                        <td class="user-name-cell"><strong>{{ $user->full_name ?? $user->name ?? 'مستخدم' }}</strong></td>
                        <td class="user-email-cell">{{ $user->email ?? '-' }}</td>
                        <td>
                            <button type="button" class="btn-view details-btn" data-id="{{ $user->id }}" style="cursor: pointer; border:none; background:none; color:inherit;">
                                عرض التفاصيل
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align: center; color: #64748b; padding: 20px;">لا يوجد مستخدمون حالياً.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>

            <div class="pagination-container">
                <div class="pagination-info">إجمالي المستخدمين: {{ is_countable($users) ? count($users) : $users->count() }}</div>
            </div>
        </div>
    </div>

    <!-- الـ Drawer الجانبي للتفاصيل والتعديل -->
    <div class="drawer-overlay">
        <div class="drawer">
            <div class="drawer-header">
                <h2>تفاصيل وتعديل المستخدم</h2>
                <label for="drawer-check" class="close-drawer" aria-label="إغلاق" title="إغلاق">&times;</label>
            </div>

            <form id="edit-user-form">
                <div style="text-align: center; margin-bottom: 20px;">
                    <div id="drawer-avatar-container" style="width: 80px; height: 80px; background: #e2e8f0; border-radius: 50%; margin: 0 auto 10px auto; display: flex; align-items: center; justify-content: center; font-size: 30px; color: #64748b; overflow: hidden;">
                        <span id="drawer-avatar-fallback">👤</span>
                        <img id="drawer-avatar-img" src="" alt="Profile" style="width: 100%; height: 100%; object-fit: cover; display: none;">
                    </div>
                </div>

                <div class="detail-group" style="margin-bottom: 12px;">
                    <label class="detail-label" style="display:block; margin-bottom:4px; font-weight:bold;">الاسم الكامل</label>
                    <input type="text" id="drawer-edit-name" name="name" class="search-input" style="width:100%;" required>
                </div>

                <div class="detail-group" style="margin-bottom: 12px;">
                    <label class="detail-label" style="display:block; margin-bottom:4px; font-weight:bold;">البريد الإلكتروني</label>
                    <input type="email" id="drawer-edit-email" name="email" class="search-input" style="width:100%;" required>
                </div>

                <div class="detail-group" style="margin-bottom: 12px;">
                    <label class="detail-label" style="display:block; margin-bottom:4px; font-weight:bold;">الجنس</label>
                    <select id="drawer-edit-gender" name="gender" class="search-input" style="width:100%;">
                        <option value="male">ذكر</option>
                        <option value="female">أنثى</option>
                    </select>
                </div>

                <div class="detail-group" style="margin-bottom: 12px;">
                    <label class="detail-label" style="display:block; margin-bottom:4px; font-weight:bold;">تاريخ الميلاد</label>
                    <input type="date" id="drawer-edit-dob" name="dob" class="search-input" style="width:100%;">
                </div>

                <div style="display: flex; gap: 10px; margin-bottom: 12px;">
                    <div style="flex: 1;">
                        <label class="detail-label" style="display:block; margin-bottom:4px; font-weight:bold;">الطول (سم)</label>
                        <input type="number" id="drawer-edit-height" name="height" class="search-input" style="width:100%;">
                    </div>
                    <div style="flex: 1;">
                        <label class="detail-label" style="display:block; margin-bottom:4px; font-weight:bold;">الوزن (كغ)</label>
                        <input type="number" id="drawer-edit-weight" name="weight" class="search-input" style="width:100%;">
                    </div>
                </div>

                <div class="detail-group" style="margin-bottom: 20px;">
                    <label class="detail-label" style="display:block; margin-bottom:4px; font-weight:bold;">الهدف الصحي</label>
                    <input type="text" id="drawer-edit-goal" name="goal" class="search-input" style="width:100%;">
                </div>

                <div class="drawer-actions" style="display:flex; gap:10px;">
                    <button type="submit" id="save-user-btn" class="btn" style="flex: 2; background-color: #10b981; color: white; padding: 10px; border:none; border-radius:6px; cursor:pointer;">حفظ التعديلات</button>
                    <button type="button" id="delete-user-btn" class="btn" style="flex: 1; background-color: #fee2e2; color: #ef4444; padding: 10px; border:none; border-radius:6px; cursor:pointer;">حذف</button>
                </div>
            </form>
        </div>
    </div>

    <!-- تمرير بيانات المستخدمين كـ JSON مباشر -->
    <script>
        window.usersData = @json($users);
    </script>
    <script src="{{ asset('front/js/users-management.js') }}"></script>
</body>
</html>
