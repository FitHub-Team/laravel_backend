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
            color: var(--teal-950);
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

        /* ---------- كروت الإحصائيات الدائرية ---------- */
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
            display: flex;
            align-items: center;
            gap: 20px;
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
            pointer-events: none;
        }

        .stat-chart-container {
            position: relative;
            width: 84px;
            height: 84px;
            flex-shrink: 0;
            z-index: 1;
        }

        .stat-chart-container svg {
            width: 100%;
            height: 100%;
            transform: rotate(-90deg);
        }

        .stat-chart-bg {
            fill: none;
            stroke: var(--line);
            stroke-width: 8;
        }

        .stat-chart-fill {
            fill: none;
            stroke-width: 8;
            stroke-linecap: round;
            transition: stroke-dashoffset 1s ease-in-out;
        }

        .card-users .stat-chart-fill { stroke: var(--teal-500); }
        .card-coaches .stat-chart-fill { stroke: var(--teal-800); }
        .card-subs .stat-chart-fill { stroke: var(--teal-400); }

        .stat-icon-center {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: var(--teal-950);
        }

        .stat-content {
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            z-index: 1;
        }

        .stat-value {
            font-size: 32px;
            font-weight: 800;
            color: var(--teal-950);
            line-height: 1.1;
        }

        .stat-label {
            color: var(--ink-soft);
            font-size: 14px;
            margin-top: 4px;
            font-weight: 600;
        }

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

        /* أيقونات SVG بديلة الإيموجي */
        .ic svg,
        .action-ic svg,
        .brand-mark svg,
        .stat-icon-center svg {
            display: block;
            width: 1em;
            height: 1em;
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
            <div class="brand-mark"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 20A7 7 0 0 1 4 13c0-6 6-10 15-11 0 9-3 15-8 18z"></path><path d="M4 13c3 0 6-1 8-3"></path></svg></div>
            <div class="brand-name"> SuperFit</div>
        </div>

        <div class="nav-section-label">نظرة عامة</div>
        <a href="{{ route('admin.dashboard') }}" class="nav-btn {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg></span> إحصائيات لوحة التحكم
        </a>

        <div class="nav-section-label">الإدارة</div>
        <a href="{{ route('admin.users.manage') }}" class="nav-btn {{ request()->routeIs('admin.users.manage') ? 'active' : '' }}">
            <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg></span> إدارة المستخدمين
        </a>

        <a href="{{ route('admin.trainer.manage') }}" class="nav-btn {{ request()->routeIs('admin.trainer.manage') ? 'active' : '' }}">
            <span class="ic"><svg viewBox="0 0 512 512" fill="currentColor"><path d="M448 96V64c0-17.7-14.3-32-32-32h-32c-17.7 0-32 14.3-32 32V96H160V64c0-17.7-14.3-32-32-32H96C78.3 32 64 46.3 64 64V96H48c-8.8 0-16 7.2-16 16v32c0 8.8 7.2 16 16 16H64v96H48c-8.8 0-16 7.2-16 16v32c0 8.8 7.2 16 16 16H64v32c0 17.7 14.3 32 32 32h32c17.7 0 32-14.3 32-32V352h192v32c0 17.7 14.3 32 32 32h32c17.7 0 32-14.3 32-32V352h16c8.8 0 16-7.2 16-16V304c0-8.8-7.2-16-16-16H464V192h16c8.8 0 16-7.2 16-16V144c0-8.8-7.2-16-16-16H448z"></path></svg></span> إدارة المدربين
        </a>

        <a href="{{ route('admin.coaches.requests') }}" class="nav-btn {{ request()->routeIs('admin.coaches.requests') ? 'active' : '' }}">
            <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect><line x1="9" y1="12" x2="15" y2="12"></line><line x1="9" y1="16" x2="15" y2="16"></line></svg></span> طلبات الانضمام
        </a>

        <div class="nav-section-label">الملف الرياضي</div>
        <a href="{{ route('admin.goals.manage') }}" class="nav-btn {{ request()->routeIs('admin.goals.manage') ? 'active' : '' }}">
            <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"></circle><circle cx="12" cy="12" r="5"></circle><circle cx="12" cy="12" r="1"></circle></svg></span> الأهداف
        </a>

        <a href="{{ route('admin.activity-level.manage') }}" class="nav-btn {{ request()->routeIs('admin.activity-level.manage') ? 'active' : '' }}">
            <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg></span> مستوى النشاط
        </a>

        <a href="{{ route('admin.health-restrictions.manage') }}" class="nav-btn {{ request()->routeIs('admin.health-restrictions.manage') ? 'active' : '' }}">
            <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 1 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path><line x1="12" y1="8" x2="12" y2="14"></line><line x1="9" y1="11" x2="15" y2="11"></line></svg></span> القيود الصحية
        </a>

        <a href="{{ route('admin.preferences.manage') }}" class="nav-btn {{ request()->routeIs('admin.preferences.manage') ? 'active' : '' }}">
            <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="4" y1="21" x2="4" y2="14"></line><line x1="4" y1="10" x2="4" y2="3"></line><line x1="12" y1="21" x2="12" y2="12"></line><line x1="12" y1="8" x2="12" y2="3"></line><line x1="20" y1="21" x2="20" y2="16"></line><line x1="20" y1="12" x2="20" y2="3"></line><line x1="1" y1="14" x2="7" y2="14"></line><line x1="9" y1="8" x2="15" y2="8"></line><line x1="17" y1="16" x2="23" y2="16"></line></svg></span> تفضيلات
        </a>

        <a href="{{ route('admin.skills.manage') }}" class="nav-btn {{ request()->routeIs('admin.skills.manage') ? 'active' : '' }}">
            <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="6"></circle><path d="M8.21 13.89 7 23l5-3 5 3-1.21-9.12"></path></svg></span> المهارات
        </a>

        <div class="nav-section-label">الحساب</div>
        <a href="{{ route('admin.login') }}" class="nav-btn">
            <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"></path></svg></span> تسجيل الدخول
        </a>

        <div class="sidebar-footer">
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf

                <button type="submit" class="nav-btn logout-btn">
                    <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg></span>
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

        @php
            // محيط الدائرة (نصف القطر = 40 => 2 * π * 40 ≈ 251.2)
            $circleCircumference = 251.2;

            // النسب المئوية القادمة من الخادم (تُحسب بناءً على البيانات الفعلية:
            // مثلاً نسبة نمو/نقص عدد المشتركين مقارنة بالفترة السابقة أو نسبتهم من الإجمالي).
            // في حال عدم توفرها من الكنترولر، تُستخدم قيم افتراضية آمنة.
            $usersPercentage         = $usersPercentage ?? 25;
            $coachesPercentage       = $coachesPercentage ?? 60;
            $subscriptionsPercentage = $subscriptionsPercentage ?? 15;

            // نتأكد أن القيمة محصورة بين 0 و100 حتى لو جاءت نسبة نقص (سالبة) أو زيادة تتجاوز 100
            $clampPercentage = fn($value) => max(0, min(100, $value));

            $usersOffset         = $circleCircumference - ($circleCircumference * $clampPercentage($usersPercentage) / 100);
            $coachesOffset       = $circleCircumference - ($circleCircumference * $clampPercentage($coachesPercentage) / 100);
            $subscriptionsOffset = $circleCircumference - ($circleCircumference * $clampPercentage($subscriptionsPercentage) / 100);
        @endphp

        <!-- كروت الأرقام الإحصائية المزودة بدوائر تقدم ملونة -->
        <section class="stats-grid">
            <div class="stat-card card-users" style="--leaf-tint:#e3f5f1">
                <div class="stat-chart-container">
                    <svg viewBox="0 0 100 100">
                        <circle class="stat-chart-bg" cx="50" cy="50" r="40" />
                        <circle class="stat-chart-fill" cx="50" cy="50" r="40" stroke-dasharray="{{ $circleCircumference }}" stroke-dashoffset="{{ $usersOffset }}" />
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ number_format($totalUsers ?? 0) }}</div>
                    <div class="stat-label">إجمالي المستخدمين</div>
                </div>
            </div>

            <div class="stat-card card-coaches" style="--leaf-tint:#dff2ec">
                <div class="stat-chart-container">
                    <svg viewBox="0 0 100 100">
                        <circle class="stat-chart-bg" cx="50" cy="50" r="40" />
                        <circle class="stat-chart-fill" cx="50" cy="50" r="40" stroke-dasharray="{{ $circleCircumference }}" stroke-dashoffset="{{ $coachesOffset }}" />
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ number_format($totalCoaches ?? 0) }}</div>
                    <div class="stat-label">إجمالي المدربين</div>
                </div>
            </div>

            <div class="stat-card card-subs" style="--leaf-tint:#dff7f1">
                <div class="stat-chart-container">
                    <svg viewBox="0 0 100 100">
                        <circle class="stat-chart-bg" cx="50" cy="50" r="40" />
                        <circle class="stat-chart-fill" cx="50" cy="50" r="40" stroke-dasharray="{{ $circleCircumference }}" stroke-dashoffset="{{ $subscriptionsOffset }}" />
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ number_format($activeSubscriptions ?? 0) }}</div>
                    <div class="stat-label">الاشتراكات النشطة</div>
                </div>
            </div>
        </section>

        <!-- روابط الوصول السريع -->
        <section class="panel">
            <h2>إجراءات سريعة</h2>
            <p>الانتقال المباشر لأكثر الأدوات استخداماً.</p>
            <div class="action-grid">
                <a href="{{ route('admin.users.manage') }}" class="action-card">
                    <div class="action-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg></div>
                    <strong>إدارة المستخدمين</strong>
                    <span>عرض، إضافة أو تعديل الأعضاء</span>
                </a>

                <a href="{{ route('admin.trainer.manage') }}" class="action-card">
                    <div class="action-ic"><svg viewBox="0 0 512 512" fill="currentColor"><path d="M448 96V64c0-17.7-14.3-32-32-32h-32c-17.7 0-32 14.3-32 32V96H160V64c0-17.7-14.3-32-32-32H96C78.3 32 64 46.3 64 64V96H48c-8.8 0-16 7.2-16 16v32c0 8.8 7.2 16 16 16H64v96H48c-8.8 0-16 7.2-16 16v32c0 8.8 7.2 16 16 16H64v32c0 17.7 14.3 32 32 32h32c17.7 0 32-14.3 32-32V352h192v32c0 17.7 14.3 32 32 32h32c17.7 0 32-14.3 32-32V352h16c8.8 0 16-7.2 16-16V304c0-8.8-7.2-16-16-16H464V192h16c8.8 0 16-7.2 16-16V144c0-8.8-7.2-16-16-16H448z"></path></svg></div>
                    <strong>إدارة المدربين</strong>
                    <span>إدارة ملفات المدربين</span>
                </a>

                <a href="{{ route('admin.login') }}" class="action-card login">
                    <div class="action-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"></path></svg></div>
                    <strong>تسجيل الدخول</strong>
                    <span>الدخول بحساب آخر</span>
                </a>

                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf

                    <button type="submit" class="action-card danger">
                        <div class="action-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg></div>
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
