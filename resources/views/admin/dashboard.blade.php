<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Fit — إحصائيات لوحة التحكم</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
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
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Cairo', sans-serif;
            background: var(--teal-50);
            color: var(--ink);
            min-height: 100vh;
            display: flex;
            overflow-x: hidden;
        }

        h1, h2, h3, .brand {
            font-family: 'Cairo', sans-serif;
        }

        /* ---------- خلفية المزخرفة ---------- */
        .bg-wave {
            position: fixed;
            inset: 0;
            z-index: 0;
            pointer-events: none;
            opacity: 0.55;
        }

        .bg-wave svg {
            width: 100%;
            height: 100%;
        }

        /* ---------- الشريط الجانبي Sidebar ---------- */
        .sidebar {
            width: 250px;
            min-height: 100vh;
            background: linear-gradient(180deg, var(--teal-800) 0%, var(--teal-950) 100%);
            color: #eafaf6;
            display: flex;
            flex-direction: column;
            padding: 28px 20px;
            position: relative;
            z-index: 2;
            flex-shrink: 0;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 40px;
        }

        .brand-mark {
            width: 38px;
            height: 38px;
            background: var(--teal-400);
            border-radius: 10px 3px 10px 3px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .brand-name {
            font-weight: 800;
            font-size: 20px;
            letter-spacing: 0.3px;
        }

        .nav-section-label {
            font-size: 11px;
            color: #9fd4ca;
            font-weight: 700;
            margin: 18px 4px 10px;
            letter-spacing: .4px;
        }

        .nav-btn {
            display: flex;
            align-items: center;
            gap: 12px;
            width: 100%;
            padding: 12px 14px;
            border-radius: 10px;
            border: none;
            background: transparent;
            color: #d8f2ec;
            font-family: 'Cairo', sans-serif;
            font-size: 14.5px;
            font-weight: 600;
            text-align: right;
            text-decoration: none;
            cursor: pointer;
            transition: background .18s ease, transform .18s ease;
            margin-bottom: 6px;
        }

        .nav-btn:hover {
            background: rgba(255, 255, 255, 0.08);
        }

        .nav-btn.active {
            background: var(--teal-400);
            color: var(--teal-950);
            font-weight: 800;
        }

        .nav-btn .ic {
            width: 20px;
            text-align: center;
            font-size: 16px;
        }

        .sidebar-footer {
            margin-top: auto;
            border-top: 1px solid rgba(255, 255, 255, 0.12);
            padding-top: 16px;
        }

        .logout-btn {
            background: rgba(255, 90, 90, 0.14);
            color: #ffd7d2;
            width: 100%;
        }

        .logout-btn:hover {
            background: rgba(255, 90, 90, 0.24);
        }

        /* ---------- المحتوى الرئيسي Main ---------- */
        .main {
            flex: 1;
            padding: 36px 44px 60px;
            position: relative;
            z-index: 1;
            max-width: 1200px;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 34px;
            flex-wrap: wrap;
            gap: 16px;
        }

        .topbar h1 {
            font-size: 28px;
            font-weight: 800;
            color: var(--teal-950);
        }

        .topbar p {
            color: var(--ink-soft);
            font-size: 14.5px;
            margin-top: 4px;
        }

        .session-pill {
            display: flex;
            align-items: center;
            gap: 10px;
            background: var(--paper);
            border: 1px solid var(--line);
            padding: 8px 16px;
            border-radius: 30px;
            font-size: 13.5px;
            color: var(--ink-soft);
            box-shadow: 0 2px 10px rgba(15, 110, 110, 0.06);
        }

        .session-pill .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #2ecc71;
        }

        /* ---------- كروت الإحصائيات ---------- */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 22px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: var(--paper);
            border-radius: 18px;
            padding: 26px 24px;
            border: 1px solid var(--line);
            box-shadow: 0 6px 20px rgba(15, 110, 110, 0.06);
            position: relative;
            overflow: hidden;
        }

        .stat-card::after {
            content: "";
            position: absolute;
            left: -30px;
            bottom: -30px;
            width: 120px;
            height: 120px;
            background: var(--leaf-tint, var(--teal-100));
            border-radius: 50%;
            opacity: .5;
        }

        .stat-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            position: relative;
            z-index: 1;
        }

        .stat-icon {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            color: #fff;
        }

        .stat-trend {
            font-size: 12.5px;
            font-weight: 700;
            padding: 4px 9px;
            border-radius: 20px;
        }

        .trend-up {
            background: #e2f9ee;
            color: #1a9e5c;
        }

        .stat-value {
            font-size: 34px;
            font-weight: 800;
            color: var(--teal-950);
            margin-top: 18px;
            position: relative;
            z-index: 1;
        }

        .stat-label {
            color: var(--ink-soft);
            font-size: 14px;
            margin-top: 4px;
            position: relative;
            z-index: 1;
        }

        .card-users .stat-icon { background: var(--teal-500); }
        .card-coaches .stat-icon { background: var(--teal-800); }
        .card-subs .stat-icon { background: #2ec9b8; }

        /* ---------- لوحة الإجراءات ---------- */
        .panel {
            background: var(--paper);
            border-radius: 20px;
            border: 1px solid var(--line);
            padding: 30px 30px 34px;
            box-shadow: 0 6px 24px rgba(15, 110, 110, 0.06);
        }

        .panel h2 {
            font-size: 19px;
            color: var(--teal-950);
            margin-bottom: 4px;
        }

        .panel > p {
            color: var(--ink-soft);
            font-size: 13.5px;
            margin-bottom: 22px;
        }

        .action-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
        }

        .action-card {
            border: 1px solid var(--line);
            background: var(--teal-50);
            border-radius: 14px;
            padding: 20px 18px;
            cursor: pointer;
            text-align: right;
            text-decoration: none;
            font-family: 'Cairo', sans-serif;
            transition: transform .16s ease, box-shadow .16s ease, background .16s ease;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .action-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 22px rgba(15, 110, 110, 0.12);
            background: #fff;
        }

        .action-ic {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: var(--teal-100);
            color: var(--teal-800);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .action-card strong {
            font-size: 15px;
            color: var(--ink);
        }

        .action-card span {
            font-size: 12.5px;
            color: var(--ink-soft);
        }

        .action-card.danger .action-ic {
            background: #fde3e0;
            color: #c94a3f;
        }

        .action-card.login .action-ic {
            background: #e0f7f0;
            color: #0f8f6f;
        }

        @media (max-width: 980px) {
            .stats-grid { grid-template-columns: 1fr; }
            .action-grid { grid-template-columns: repeat(2, 1fr); }
            .sidebar {
                position: fixed;
                transform: translateX(100%);
            }
        }
    </style>
</head>

<body>

    <div class="bg-wave">
        <svg viewBox="0 0 1440 900" preserveAspectRatio="xMidYMid slice">
            <path d="M0,0 L1440,0 L1440,120 C1200,180 1000,60 780,110 C560,160 420,40 0,90 Z" fill="#d8f2ec" />
            <path d="M1440,900 L0,900 L0,780 C260,720 460,840 700,790 C940,740 1160,860 1440,800 Z" fill="#d8f2ec" />
        </svg>
    </div>

    <!-- الشريط الجانبي -->
    <aside class="sidebar">
        <div class="brand">
            <div class="brand-mark">🌿</div>
            <div class="brand-name"> SuperFit</div>
        </div>

        <div class="nav-section-label">نظرة عامة</div>
        <a href="{{ route('admin.dashboard') }}" class="nav-btn {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <span class="ic">📊</span> إحصائيات لوحة التحكم
        </a>

        <div class="nav-section-label">الإدارة</div>
        <a href="{{ route('admin.users.manage') }}" class="nav-btn {{ request()->routeIs('admin.users.manage') ? 'active' : '' }}">
            <span class="ic">👥</span> إدارة المستخدمين
        </a>

        <a href="{{ route('admin.trainer.manage') }}" class="nav-btn {{ request()->routeIs('admin.trainer.manage') ? 'active' : '' }}">
            <span class="ic">🏋️</span> إدارة المدربين
        </a>

        <a href="{{ route('admin.coaches.requests') }}" class="nav-btn {{ request()->routeIs('admin.coaches.requests') ? 'active' : '' }}">
            <span class="ic">📑</span> طلبات الانضمام
        </a>

        <div class="nav-section-label">الحساب</div>
        <a href="{{ route('admin.login') }}" class="nav-btn">
            <span class="ic">🔑</span> تسجيل الدخول
        </a>

 <div class="sidebar-footer">
    <form action="{{ route('admin.logout') }}" method="POST">
        @csrf

        <button type="submit" class="nav-btn logout-btn">
            <span class="ic">⎋</span>
            تسجيل الخروج
        </button>
    </form>
</div>
    </aside>

    <!-- المحتوى الرئيسي -->
    <main class="main">
        <div class="topbar">
            <div>
                <h1>إحصائيات لوحة التحكم</h1>
                <p>نظرة عامة ومباشرة على مجتمع سوبر فيت الخاص بك.</p>
            </div>
            <div class="session-pill"><span class="dot"></span> جلسة المسؤول نشطة</div>
        </div>

        <!-- كروت الأرقام الإحصائية الديناميكية -->
        <section class="stats-grid">
            <div class="stat-card card-users" style="--leaf-tint:#e3f5f1">
                <div class="stat-top">
                    <div class="stat-icon">👥</div>
                    <div class="stat-trend trend-up">+8.2%</div>
                </div>
                <div class="stat-value">{{ number_format($totalUsers ?? 0) }}</div>
                <div class="stat-label">إجمالي المستخدمين</div>
            </div>

            <div class="stat-card card-coaches" style="--leaf-tint:#dff2ec">
                <div class="stat-top">
                    <div class="stat-icon">🏋️</div>
                    <div class="stat-trend trend-up">+3.4%</div>
                </div>
                <div class="stat-value">{{ number_format($totalCoaches ?? 0) }}</div>
                <div class="stat-label">إجمالي المدربين</div>
            </div>

            <div class="stat-card card-subs" style="--leaf-tint:#dff7f1">
                <div class="stat-top">
                    <div class="stat-icon">💳</div>
                    <div class="stat-trend trend-up">+12.6%</div>
                </div>
                <div class="stat-value">{{ number_format($activeSubscriptions ?? 0) }}</div>
                <div class="stat-label">الاشتراكات النشطة</div>
            </div>
        </section>

        <!-- روابط الوصول السريع -->
        <section class="panel">
            <h2>إجراءات سريعة</h2>
            <p>الانتقال المباشر لأكثر الأدوات استخداماً.</p>
            <div class="action-grid">
                <a href="{{ route('admin.users.manage') }}" class="action-card">
                    <div class="action-ic">👥</div>
                    <strong>إدارة المستخدمين</strong>
                    <span>عرض، إضافة أو تعديل الأعضاء</span>
                </a>

                <a href="{{ route('admin.trainer.manage') }}" class="action-card">
                    <div class="action-ic">🏋️</div>
                    <strong>إدارة المدربين</strong>
                    <span>إدارة ملفات المدربين</span>
                </a>

                <a href="{{ route('admin.login') }}" class="action-card login">
                    <div class="action-ic">🔑</div>
                    <strong>تسجيل الدخول</strong>
                    <span>الدخول بحساب آخر</span>
                </a>

  <form action="{{ route('admin.logout') }}" method="POST">
    @csrf

    <button type="submit" class="action-card danger">
        <div class="action-ic">⎋</div>
        <strong>تسجيل الخروج</strong>
        <span>إنهاء الجلسة الحالية</span>
    </button>
</form>
            </div>
        </section>
    </main>


<script>
    async function logout() {

        const token = localStorage.getItem('admin_token');

        if (!token) {
            window.location.href = "{{ route('admin.login') }}";
            return;
        }

        try {
            const response = await fetch('/api/admin/logout', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${token}`,
                    'X-CSRF-TOKEN': document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute('content')
                }
            });

            const data = await response.json();

            if (response.ok && data.status) {

                // حذف التوكن من المتصفح
                localStorage.removeItem('admin_token');

                // الرجوع لصفحة تسجيل الدخول
                window.location.href = "{{ route('admin.login') }}";

            } else {
                alert(data.message || 'حدث خطأ أثناء تسجيل الخروج');
            }

        } catch (error) {
            console.error('Logout error:', error);
            alert('تعذر الاتصال بالخادم');
        }
    }

    document.getElementById('logout-btn')
        ?.addEventListener('click', logout);

    document.getElementById('logout-action-btn')
        ?.addEventListener('click', logout);
</script>



</body>

</html>
