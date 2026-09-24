<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>إدارة المدربين | SuperFit</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ asset('front/css/style-trainer.css') }}">
</head>

<body>

    <input type="checkbox" id="drawer-check" class="drawer-toggle">

    <div class="container">

        {{-- Navbar --}}
        <nav class="navbar">

            <a href="{{ route('admin.dashboard') }}" class="nav-link">
                لوحة الإحصائيات
            </a>

            <a href="{{ route('admin.users-details') }}" class="nav-link">
                إدارة المستخدمين
            </a>

            <a href="#" class="nav-link active">
                إدارة المدربين
            </a>

        </nav>

        {{-- Success Message --}}
        @if(session('success'))
            <div class="alert alert-success">
                <span class="alert-icon">✓</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- Page Header --}}
        <div class="page-header">

            <div>
                <span class="page-kicker">SUPERFIT ADMIN</span>

                <h1>إدارة المدربين</h1>

                <p>
                    إدارة المدربين ومراجعة بياناتهم وحالة اعتماد حساباتهم
                </p>
            </div>

            <div class="trainer-count">
                <span class="count-number">{{ $trainers->count() }}</span>
                <span class="count-label">مدرب</span>
            </div>

        </div>

        {{-- Search --}}
        <div class="filter-bar">

            <div class="search-wrapper">

                <span class="search-icon">⌕</span>

                <input
                    type="text"
                    id="search-input"
                    class="search-input"
                    placeholder="ابحث باسم المدرب أو البريد الإلكتروني أو التخصص..."
                    autocomplete="off"
                >

            </div>

        </div>

        {{-- Table --}}
        <div class="table-card">

            <div class="table-header">

                <div>
                    <h2>قائمة المدربين</h2>
                    <p>جميع المدربين المسجلين في النظام</p>
                </div>

                <div class="table-status">
                    <span class="status-dot"></span>
                    بيانات مباشرة
                </div>

            </div>

            <div class="table-responsive">

                <table>

                    <thead>
                        <tr>
                            <th># ID</th>
                            <th>الاسم</th>
                            <th>البريد الإلكتروني</th>
                            <th>التخصص</th>
                            <th>حالة الاعتماد</th>
                            <th>التفاصيل</th>
                        </tr>
                    </thead>

                    <tbody id="trainers-table-body">

                        @forelse($trainers as $trainer)

                            @php
                                $trainerName = $trainer->user->name ?? $trainer->name ?? 'مدرب';
                                $trainerEmail = $trainer->user->email ?? $trainer->email ?? '-';
                            @endphp

                            <tr>

                                <td>
                                    <span class="id-badge">
                                        #{{ $trainer->id }}
                                    </span>
                                </td>

                                <td>
                                    <div class="trainer-name-cell">

                                        <div class="trainer-mini-avatar">
                                            {{ mb_substr($trainerName, 0, 1) }}
                                        </div>

                                        <strong>
                                            {{ $trainerName }}
                                        </strong>

                                    </div>
                                </td>

                                <td>
                                    <span class="email-text">
                                        {{ $trainerEmail }}
                                    </span>
                                </td>

                                <td>
                                    <span class="specialization-badge">
                                        {{ $trainer->specialization ?? 'عام' }}
                                    </span>
                                </td>

                                <td>

                                    @if($trainer->is_approved)

                                        <span class="approval-badge approved">
                                            <span class="badge-dot"></span>
                                            معتمد
                                        </span>

                                    @else

                                        <span class="approval-badge pending">
                                            <span class="badge-dot"></span>
                                            بانتظار الموافقة
                                        </span>

                                    @endif

                                </td>

                                <td>

                                    <label
                                        for="drawer-check"
                                        class="details-btn"
                                        data-id="{{ $trainer->id }}"
                                    >
                                        عرض التفاصيل
                                        <span>←</span>
                                    </label>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="6">

                                    <div class="empty-state">

                                        <div class="empty-icon">
                                            👤
                                        </div>

                                        <h3>لا يوجد مدربين</h3>

                                        <p>
                                            لا يوجد مدربين مسجلين في النظام حالياً.
                                        </p>

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            {{-- Table Footer --}}
            <div class="pagination-container">

                <div class="pagination-info">
                    إجمالي المدربين:
                    <strong>{{ $trainers->count() }}</strong>
                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        Trainer Details Drawer
    ========================================================== --}}

    <div class="drawer-overlay">

        <div class="drawer">

            <div class="drawer-header">

                <div>
                    <span class="drawer-kicker">TRAINER PROFILE</span>
                    <h2>تفاصيل المدرب</h2>
                </div>

                <label
                    for="drawer-check"
                    class="close-drawer"
                    aria-label="إغلاق"
                    title="إغلاق"
                >
                    &times;
                </label>

            </div>


            {{-- Profile --}}
            <div class="trainer-profile">

                <div
                    id="drawer-avatar-container"
                    class="drawer-avatar-container"
                >

                    <span id="drawer-avatar-fallback">
                        👤
                    </span>

                    <img
                        id="drawer-avatar-img"
                        src=""
                        alt="Profile"
                    >

                </div>

                <div
                    class="detail-value profile-name"
                    id="drawer-name"
                >
                    -
                </div>

                <div class="profile-label">
                    مدرب SuperFit
                </div>

            </div>


            {{-- Details --}}
            <div class="details-list">

                <div class="detail-group">

                    <span class="detail-label">
                        رقم الهوية
                    </span>

                    <span
                        class="detail-value"
                        id="drawer-national-id"
                    >
                        -
                    </span>

                </div>


                <div class="detail-group">

                    <span class="detail-label">
                        سنة الميلاد
                    </span>

                    <span
                        class="detail-value"
                        id="drawer-birth-year"
                    >
                        -
                    </span>

                </div>


                <div class="detail-group">

                    <span class="detail-label">
                        المكان
                    </span>

                    <span
                        class="detail-value"
                        id="drawer-location"
                    >
                        -
                    </span>

                </div>


                <div class="detail-group">

                    <span class="detail-label">
                        التخصص
                    </span>

                    <span
                        class="detail-value"
                        id="drawer-specialization"
                    >
                        -
                    </span>

                </div>


                <div class="detail-group">

                    <span class="detail-label">
                        سنوات الخبرة
                    </span>

                    <span
                        class="detail-value"
                        id="drawer-experience"
                    >
                        -
                    </span>

                </div>


                <div class="detail-group">

                    <span class="detail-label">
                        الشهادات المعتمدة
                    </span>

                    <span
                        class="detail-value"
                        id="drawer-certifications"
                    >
                        -
                    </span>

                </div>


                <div class="detail-group bio-group">

                    <span class="detail-label">
                        النبذة التعريفية
                    </span>

                    <span
                        class="detail-value"
                        id="drawer-bio"
                    >
                        -
                    </span>

                </div>

            </div>


            {{-- Actions --}}
            <div class="drawer-actions">

                <button
                    type="button"
                    id="toggle-approval-btn"
                    class="btn btn-approve"
                >
                    اعتماد
                </button>

                <button
                    type="button"
                    id="delete-trainer-btn"
                    class="btn btn-delete"
                >
                    حذف الحساب
                </button>

            </div>

        </div>

    </div>


    <script src="{{ asset('front/js/trainers_management.js') }}"></script>

</body>

</html>
