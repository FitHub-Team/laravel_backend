<!DOCTYPE html>
<html lang="ar" dir="rtl" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>إحصائيات لوحة التحكم — SuperFit</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <script>
        (function () {
            try {
                var t = localStorage.getItem('sf-theme');
                if (t === 'dark') {
                    document.documentElement.setAttribute('data-bs-theme', 'dark');
                }
            } catch (e) {}
        })();
    </script>

    <style>
        /* =========================================================
           Design Tokens
        ========================================================= */
        :root {
            --sf-teal-900: #0B3B3C;
            --sf-teal-700: #0F766E;
            --sf-teal-500: #14B8A6;
            --sf-teal-100: #CCFBF1;
            --sf-green-600: #16A34A;
            --sf-green-400: #4ADE80;
            --sf-lime-300: #BEF264;
            --sf-bg: #F3FAF8;
            --sf-surface: #FFFFFF;
            --sf-text: #0F2D2E;
            --sf-muted: #5B7B7A;
            --sf-border: #D6EBE7;
            --sf-warning: #F59E0B;
            --sf-danger: #E11D48;
            --sf-info: #0EA5E9;

            --bs-primary: var(--sf-teal-700);
            --bs-success: var(--sf-green-600);
            --bs-body-bg: var(--sf-bg);
            --bs-body-color: var(--sf-text);
            --bs-border-color: var(--sf-border);
            --bs-primary-rgb: 15, 118, 110;
        }

        [data-bs-theme="dark"] {
            --sf-bg: #071816;
            --sf-surface: #0E2320;
            --sf-text: #E8F5F2;
            --sf-muted: #9CB5B1;
            --sf-border: #1C3B37;
            --sf-teal-100: #133F3A;
            --bs-body-bg: var(--sf-bg);
            --bs-body-color: var(--sf-text);
            --bs-border-color: var(--sf-border);
        }

        * { box-sizing: border-box; }

        html, body { min-height: 100%; margin: 0; padding: 0; }

        body {
            font-family: 'Cairo', 'Segoe UI', Tahoma, sans-serif;
            background: var(--sf-bg);
            color: var(--sf-text);
            background-image:
                radial-gradient(circle at 100% 0%, rgba(20, 184, 166, 0.08), transparent 42%),
                radial-gradient(circle at 0% 100%, rgba(22, 163, 74, 0.08), transparent 42%);
            background-attachment: fixed;
        }

        /* =========================================================
           Layout
        ========================================================= */
        .sf-layout {
            display: flex;
            min-height: 100vh;
            align-items: stretch;
        }

        /* =========================================================
           Sidebar
        ========================================================= */
        .sf-sidebar {
            width: 280px;
            flex-shrink: 0;
            background: linear-gradient(180deg, var(--sf-teal-900), #062624 140%);
            color: #D9F2EC;
            padding: 22px 16px;
            display: flex;
            flex-direction: column;
            border-inline-end: 1px solid rgba(255, 255, 255, 0.05);
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: rgba(20, 184, 166, 0.35) transparent;
            transition: transform .3s ease;
        }

        .sf-sidebar::-webkit-scrollbar { width: 6px; }
        .sf-sidebar::-webkit-scrollbar-thumb {
            background: rgba(20, 184, 166, 0.35);
            border-radius: 10px;
        }

        [data-bs-theme="dark"] .sf-sidebar {
            background: linear-gradient(180deg, #062624, #030d0c 140%);
        }

        .sf-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 12px 22px;
            text-decoration: none;
            color: inherit;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
            margin-bottom: 18px;
        }

        .sf-brand-mark {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--sf-teal-500), var(--sf-green-600));
            color: #ffffff;
            font-size: 20px;
            box-shadow: 0 8px 20px rgba(20, 184, 166, 0.35);
        }

        .sf-brand-text {
            display: flex;
            flex-direction: column;
            line-height: 1.1;
        }

        .sf-brand-name {
            font-size: 17px;
            font-weight: 800;
            color: #ffffff;
        }

        .sf-brand-sub {
            font-size: 11px;
            font-weight: 600;
            color: rgba(217, 242, 236, 0.55);
            margin-top: 3px;
            letter-spacing: 0.6px;
        }

        .sf-nav {
            display: flex;
            flex-direction: column;
            gap: 6px;
            flex: 1;
        }

        .sf-nav-section + .sf-nav-section { margin-top: 4px; }

        .sf-nav-section-toggle {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            padding: 10px 12px;
            background: transparent;
            border: none;
            color: rgba(217, 242, 236, 0.55);
            font-family: inherit;
            font-size: 11.5px;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;
            cursor: pointer;
            border-radius: 10px;
            transition: color .2s ease, background-color .2s ease;
        }

        .sf-nav-section-toggle:hover {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.04);
        }

        .sf-nav-chevron {
            font-size: 14px;
            transition: transform .25s ease;
        }

        .sf-nav-section.open .sf-nav-chevron { transform: rotate(180deg); }

        .sf-nav-section-items {
            display: grid;
            grid-template-rows: 0fr;
            transition: grid-template-rows .3s ease;
        }

        .sf-nav-section.open .sf-nav-section-items {
            grid-template-rows: 1fr;
        }

        .sf-nav-section-items-inner {
            overflow: hidden;
            min-height: 0;
            display: flex;
            flex-direction: column;
            gap: 4px;
            padding: 4px 0 6px;
        }

        .sf-nav-btn {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 10px 12px;
            border-radius: 11px;
            color: #D9F2EC;
            text-decoration: none;
            font-family: inherit;
            font-size: 13.5px;
            font-weight: 600;
            transition: background-color .2s ease, color .2s ease, transform .2s ease;
            border: none;
            background: transparent;
            width: 100%;
            text-align: start;
            cursor: pointer;
        }

        .sf-nav-btn i {
            font-size: 16px;
            width: 20px;
            text-align: center;
            color: rgba(217, 242, 236, 0.75);
            transition: color .2s ease;
        }

        .sf-nav-btn:hover {
            background: rgba(255, 255, 255, 0.06);
            color: #ffffff;
        }

        .sf-nav-btn.active {
            background: linear-gradient(135deg, var(--sf-teal-500), var(--sf-green-600));
            color: #062624;
            font-weight: 800;
            box-shadow: 0 6px 18px rgba(20, 184, 166, 0.30);
        }

        .sf-nav-btn.active i { color: #062624; }

        .sf-nav-logout-form { margin: 0; }

        .sf-nav-btn-danger {
            color: #FFD5D5;
        }

        .sf-nav-btn-danger i { color: #FFAFAF; }

        .sf-nav-btn-danger:hover {
            background: rgba(225, 29, 72, 0.18);
            color: #ffffff;
        }

        /* =========================================================
           Main area
        ========================================================= */
        .sf-main-wrap {
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
        }

        .sf-topbar {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 20px 28px;
            border-bottom: 1px solid var(--sf-border);
            background: rgba(243, 250, 248, 0.85);
            backdrop-filter: saturate(140%) blur(8px);
            position: sticky;
            top: 0;
            z-index: 5;
        }

        [data-bs-theme="dark"] .sf-topbar {
            background: rgba(7, 24, 22, 0.85);
        }

        .sf-topbar-title { flex: 1; min-width: 0; }

        .sf-topbar-title h1 {
            font-size: 20px;
            font-weight: 800;
            margin: 0 0 2px;
            color: var(--sf-text);
        }

        .sf-topbar-title p {
            font-size: 13px;
            color: var(--sf-muted);
            margin: 0;
        }

        .sf-topbar-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sf-session-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 14px;
            border-radius: 999px;
            background: var(--sf-surface);
            border: 1px solid var(--sf-border);
            font-size: 12px;
            font-weight: 700;
            color: var(--sf-text);
        }

        .sf-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--sf-green-600);
            box-shadow: 0 0 0 0 rgba(22, 163, 74, 0.55);
            animation: sfDotPulse 2s ease-out infinite;
        }

        .sf-icon-btn {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: var(--sf-surface);
            border: 1px solid var(--sf-border);
            color: var(--sf-text);
            cursor: pointer;
            font-size: 17px;
            transition: background-color .2s ease, color .2s ease, transform .18s ease;
        }

        .sf-icon-btn:hover {
            background: var(--sf-teal-100);
            color: var(--sf-teal-700);
        }

        .sf-icon-btn:active { transform: scale(0.96); }

        /* =========================================================
           Main content
        ========================================================= */
        .sf-main {
            padding: 28px;
            flex: 1;
        }

        /* =========================================================
           Section title
        ========================================================= */
        .sf-section-title {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 6px;
        }

        .sf-section-title h2 {
            font-size: 18px;
            font-weight: 800;
            margin: 0;
        }

        .sf-section-sub {
            color: var(--sf-muted);
            font-size: 13px;
            margin: 0 0 20px;
        }

        /* =========================================================
           KPI Cards
        ========================================================= */
        .sf-kpi-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 20px;
            margin-bottom: 32px;
        }

        .sf-kpi {
            display: flex;
            align-items: center;
            gap: 20px;
            padding: 24px;
            background: var(--sf-surface);
            border: 1px solid var(--sf-border);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(15, 118, 110, 0.06);
            transition: transform .22s ease, box-shadow .22s ease, border-color .22s ease;
            animation: sfFadeUp 600ms both;
            animation-delay: calc(var(--i, 0) * 90ms);
        }

        @media (hover: hover) {
            .sf-kpi:hover {
                transform: translateY(-3px);
                box-shadow: 0 16px 40px rgba(15, 118, 110, 0.12);
                border-color: rgba(20, 184, 166, 0.35);
            }
        }

        .sf-kpi-chart {
            width: 92px;
            height: 92px;
            flex-shrink: 0;
            position: relative;
        }

        .sf-kpi-chart svg {
            width: 100%;
            height: 100%;
            transform: rotate(-90deg);
            overflow: visible;
        }

        .sf-kpi-bg {
            fill: none;
            stroke: var(--sf-teal-100);
            stroke-width: 8;
        }

        .sf-kpi-fill {
            fill: none;
            stroke: url(#sfKpiGrad);
            stroke-width: 8;
            stroke-linecap: round;
            transition: stroke-dashoffset 1.4s cubic-bezier(.22, 1, .36, 1);
        }

        .sf-kpi-icon-inner {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--sf-teal-700);
            font-size: 22px;
        }

        .sf-kpi-body { min-width: 0; }

        .sf-kpi-value {
            font-size: 28px;
            font-weight: 800;
            color: var(--sf-text);
            line-height: 1.15;
            margin-bottom: 4px;
        }

        .sf-kpi-label {
            font-size: 13px;
            color: var(--sf-muted);
            font-weight: 600;
        }

        /* =========================================================
           Quick Actions
        ========================================================= */
        .sf-actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 16px;
        }

        .sf-action {
            display: flex;
            flex-direction: column;
            gap: 12px;
            padding: 22px;
            background: var(--sf-surface);
            border: 1px solid var(--sf-border);
            border-radius: 18px;
            text-decoration: none;
            color: inherit;
            cursor: pointer;
            transition: transform .22s ease, box-shadow .22s ease, border-color .22s ease;
            animation: sfFadeUp 550ms both;
            animation-delay: calc(var(--i, 0) * 70ms);
            text-align: start;
            font-family: inherit;
        }

        @media (hover: hover) {
            .sf-action:hover {
                transform: translateY(-3px);
                box-shadow: 0 14px 34px rgba(15, 118, 110, 0.10);
                border-color: rgba(20, 184, 166, 0.35);
            }
        }

        .sf-action-ic {
            width: 46px;
            height: 46px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            background: var(--sf-teal-100);
            color: var(--sf-teal-700);
        }

        .sf-action-title {
            font-weight: 800;
            font-size: 15px;
            color: var(--sf-text);
            margin: 0;
        }

        .sf-action-desc {
            font-size: 12.5px;
            color: var(--sf-muted);
            margin: 0;
            line-height: 1.7;
        }

        .sf-action.danger .sf-action-ic {
            background: rgba(225, 29, 72, 0.10);
            color: var(--sf-danger);
        }

        /* =========================================================
           Reveal
        ========================================================= */
        html.js [data-sf-reveal] {
            opacity: 0;
            transform: translateY(14px);
            transition: opacity .6s ease, transform .6s ease;
        }

        html.js [data-sf-reveal].is-visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* =========================================================
           Animations
        ========================================================= */
        @keyframes sfFadeUp {
            from { opacity: 0; transform: translateY(14px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        @keyframes sfDotPulse {
            0%   { box-shadow: 0 0 0 0 rgba(22, 163, 74, 0.55); }
            70%  { box-shadow: 0 0 0 8px rgba(22, 163, 74, 0); }
            100% { box-shadow: 0 0 0 0 rgba(22, 163, 74, 0); }
        }

        /* =========================================================
           Offcanvas (mobile sidebar)
        ========================================================= */
        @media (max-width: 991.98px) {
            .sf-layout { display: block; }
            .sf-sidebar {
                position: fixed;
                top: 0;
                bottom: 0;
                inset-inline-start: 0;
                height: 100vh;
                z-index: 1050;
                transform: translateX(100%);
                transition: transform .3s ease;
                box-shadow: -20px 0 50px rgba(0, 0, 0, 0.20);
            }
            [dir="rtl"] .sf-sidebar {
                transform: translateX(100%);
            }
            .sf-sidebar.is-open { transform: translateX(0) !important; }

            .sf-sidebar-backdrop {
                position: fixed;
                inset: 0;
                background: rgba(10, 30, 28, 0.45);
                backdrop-filter: blur(2px);
                z-index: 1040;
                opacity: 0;
                visibility: hidden;
                transition: opacity .25s ease, visibility .25s ease;
            }

            .sf-sidebar-backdrop.is-open {
                opacity: 1;
                visibility: visible;
            }

            .sf-main { padding: 20px 16px 40px; }
            .sf-topbar { padding: 14px 16px; }

            .sf-kpi-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (min-width: 992px) {
            .sf-sidebar-backdrop { display: none; }
        }

        @media (max-width: 480px) {
            .sf-topbar-title h1 { font-size: 17px; }
            .sf-topbar-title p { display: none; }
            .sf-session-pill span:last-child { display: none; }
            .sf-kpi { padding: 18px; gap: 14px; }
            .sf-kpi-chart { width: 74px; height: 74px; }
            .sf-kpi-value { font-size: 22px; }
        }

        /* =========================================================
           Reduced Motion
        ========================================================= */
        @media (prefers-reduced-motion: reduce) {
            .sf-kpi,
            .sf-action,
            .sf-dot {
                animation: none !important;
            }
            html.js [data-sf-reveal] {
                opacity: 1 !important;
                transform: none !important;
                transition: none !important;
            }
            .sf-kpi-fill {
                transition: none !important;
            }
            * { transition-duration: .001ms !important; }
        }
    </style>
</head>
<body>

    {{-- SVG gradient shared --}}
    <svg width="0" height="0" style="position:absolute" aria-hidden="true">
        <defs>
            <linearGradient id="sfKpiGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" stop-color="#0F766E"/>
                <stop offset="100%" stop-color="#16A34A"/>
            </linearGradient>
        </defs>
    </svg>

    <div class="sf-layout">

        {{-- =========================================================
             Sidebar
        ========================================================== --}}
        <aside class="sf-sidebar" id="sfSidebar" aria-label="التنقل الجانبي">

            <a href="{{ route('admin.dashboard') }}" class="sf-brand">
                <span class="sf-brand-mark" aria-hidden="true">
                    <i class="bi bi-lightning-charge-fill"></i>
                </span>
                <span class="sf-brand-text">
                    <span class="sf-brand-name">SuperFit</span>
                    <span class="sf-brand-sub">ADMIN PANEL</span>
                </span>
            </a>

            <nav class="sf-nav">

                {{-- نظرة عامة --}}
                <div class="sf-nav-section open">
                    <button type="button" class="sf-nav-section-toggle" data-sf-section-toggle aria-expanded="true">
                        <span>نظرة عامة</span>
                        <i class="bi bi-chevron-down sf-nav-chevron" aria-hidden="true"></i>
                    </button>
                    <div class="sf-nav-section-items">
                        <div class="sf-nav-section-items-inner">
                            <a href="{{ route('admin.dashboard') }}"
                               class="sf-nav-btn {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                                <i class="bi bi-bar-chart-line" aria-hidden="true"></i>
                                <span>إحصائيات لوحة التحكم</span>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- الإدارة --}}
                <div class="sf-nav-section open">
                    <button type="button" class="sf-nav-section-toggle" data-sf-section-toggle aria-expanded="true">
                        <span>الإدارة</span>
                        <i class="bi bi-chevron-down sf-nav-chevron" aria-hidden="true"></i>
                    </button>
                    <div class="sf-nav-section-items">
                        <div class="sf-nav-section-items-inner">
                            <a href="{{ route('admin.users-details') }}"
                               class="sf-nav-btn {{ request()->routeIs('admin.users-details') ? 'active' : '' }}">
                                <i class="bi bi-people" aria-hidden="true"></i>
                                <span>إدارة تفاصيل المستخدمين</span>
                            </a>
                            <a href="{{ route('admin.trainer.manage') }}"
                               class="sf-nav-btn {{ request()->routeIs('admin.trainer.manage') ? 'active' : '' }}">
                                <i class="bi bi-person-badge" aria-hidden="true"></i>
                                <span>إدارة المدربين</span>
                            </a>
                            <a href="{{ route('admin.coaches.requests') }}"
                               class="sf-nav-btn {{ request()->routeIs('admin.coaches.requests') ? 'active' : '' }}">
                                <i class="bi bi-clipboard-check" aria-hidden="true"></i>
                                <span>طلبات الانضمام</span>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- الملف الرياضي --}}
                <div class="sf-nav-section open">
                    <button type="button" class="sf-nav-section-toggle" data-sf-section-toggle aria-expanded="true">
                        <span>الملف الرياضي</span>
                        <i class="bi bi-chevron-down sf-nav-chevron" aria-hidden="true"></i>
                    </button>
                    <div class="sf-nav-section-items">
                        <div class="sf-nav-section-items-inner">
                            <a href="{{ route('admin.goals.manage') }}"
                               class="sf-nav-btn {{ request()->routeIs('admin.goals.manage') ? 'active' : '' }}">
                                <i class="bi bi-bullseye" aria-hidden="true"></i>
                                <span>الأهداف</span>
                            </a>
                            <a href="{{ route('admin.activity-level.manage') }}"
                               class="sf-nav-btn {{ request()->routeIs('admin.activity-level.manage') ? 'active' : '' }}">
                                <i class="bi bi-activity" aria-hidden="true"></i>
                                <span>مستوى النشاط</span>
                            </a>
                            <a href="{{ route('admin.health-restrictions.manage') }}"
                               class="sf-nav-btn {{ request()->routeIs('admin.health-restrictions.manage') ? 'active' : '' }}">
                                <i class="bi bi-heart-pulse" aria-hidden="true"></i>
                                <span>القيود الصحية</span>
                            </a>
                            <a href="{{ route('admin.skills.index') }}"
                               class="sf-nav-btn {{ request()->routeIs('admin.skills.index') ? 'active' : '' }}">
                                <i class="bi bi-award" aria-hidden="true"></i>
                                <span>المهارات</span>
                            </a>
                        </div>
                    </div>
                </div>
{{-- المدفوعات --}}
                     <div class="sf-nav-section open">
                    <button type="button" class="sf-nav-section-toggle" data-sf-section-toggle aria-expanded="true">
                        <span>المدفوعات</span>
                        <i class="bi bi-chevron-down sf-nav-chevron" aria-hidden="true"></i>
                    </button>
                    <div class="sf-nav-section-items">
                        <div class="sf-nav-section-items-inner">
                            <a href="{{ route('admin.payments.index') }}" class="sf-nav-btn">
                                <i class="bi bi-credit-card" aria-hidden="true"></i>
                                <span>الدفع</span>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- الحساب --}}
                <div class="sf-nav-section open">
                    <button type="button" class="sf-nav-section-toggle" data-sf-section-toggle aria-expanded="true">
                        <span>الحساب</span>
                        <i class="bi bi-chevron-down sf-nav-chevron" aria-hidden="true"></i>
                    </button>
                    <div class="sf-nav-section-items">
                        <div class="sf-nav-section-items-inner">
                            <a href="{{ route('admin.login') }}" class="sf-nav-btn">
                                <i class="bi bi-box-arrow-in-right" aria-hidden="true"></i>
                                <span>تسجيل الدخول</span>
                            </a>
                            <form action="{{ route('admin.logout') }}" method="POST" class="sf-nav-logout-form">
                                @csrf
                                <button type="submit" class="sf-nav-btn sf-nav-btn-danger">
                                    <i class="bi bi-box-arrow-left" aria-hidden="true"></i>
                                    <span>تسجيل الخروج</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </nav>

        </aside>

        <div class="sf-sidebar-backdrop" id="sfSidebarBackdrop"></div>

        {{-- =========================================================
             Main
        ========================================================== --}}
        <div class="sf-main-wrap">

            <header class="sf-topbar">
                <button type="button" class="sf-icon-btn d-lg-none" id="sfSidebarToggle" aria-label="فتح القائمة">
                    <i class="bi bi-list" aria-hidden="true"></i>
                </button>

                <div class="sf-topbar-title">
                    <h1>إحصائيات لوحة التحكم</h1>
                    <p>نظرة عامة ومباشرة على مجتمع سوبر فيت الخاص بك.</p>
                </div>

                <div class="sf-topbar-actions">
                    <span class="sf-session-pill" aria-label="حالة الجلسة">
                        <span class="sf-dot" aria-hidden="true"></span>
                        <span>جلسة المسؤول نشطة</span>
                    </span>

                    <button type="button" class="sf-icon-btn" id="sfThemeToggle" aria-label="تبديل المظهر">
                        <i class="bi bi-moon-stars" id="sfThemeIcon" aria-hidden="true"></i>
                    </button>
                </div>
            </header>

            <main class="sf-main">

                @php
                    $circleCircumference = 251.2;

                    $usersPercentage        = $usersPercentage        ?? 25;
                    $coachesPercentage      = $coachesPercentage      ?? 60;
                    $subscriptionsPercentage = $subscriptionsPercentage ?? 15;

                    $clampPercentage = fn ($value) => max(0, min(100, $value));

                    $usersOffset = $circleCircumference
                        - ($circleCircumference * $clampPercentage($usersPercentage) / 100);

                    $coachesOffset = $circleCircumference
                        - ($circleCircumference * $clampPercentage($coachesPercentage) / 100);

                    $subscriptionsOffset = $circleCircumference
                        - ($circleCircumference * $clampPercentage($subscriptionsPercentage) / 100);
                @endphp

                {{-- KPI Cards --}}
                <section aria-labelledby="sfStatsHeading">

                    <div class="sf-section-title" data-sf-reveal>
                        <h2 id="sfStatsHeading">ملخص الأداء</h2>
                    </div>
                    <p class="sf-section-sub" data-sf-reveal>أرقام سريعة حول المستخدمين والمدربين والاشتراكات.</p>

                    <div class="sf-kpi-grid">

                        {{-- Users --}}
                        <article class="sf-kpi" data-sf-reveal style="--i:0">
                            <div class="sf-kpi-chart">
                                <svg viewBox="0 0 100 100" aria-hidden="true">
                                    <circle class="sf-kpi-bg" cx="50" cy="50" r="40"></circle>
                                    <circle class="sf-kpi-fill"
                                            cx="50" cy="50" r="40"
                                            stroke-dasharray="{{ $circleCircumference }}"
                                            stroke-dashoffset="{{ $circleCircumference }}"
                                            data-offset="{{ $usersOffset }}"></circle>
                                </svg>
                                <span class="sf-kpi-icon-inner" aria-hidden="true">
                                    <i class="bi bi-people-fill"></i>
                                </span>
                            </div>
                            <div class="sf-kpi-body">
                                <div class="sf-kpi-value"
                                     data-count="{{ (int) ($totalUsers ?? 0) }}">0</div>
                                <div class="sf-kpi-label">إجمالي المستخدمين</div>
                            </div>
                        </article>

                        {{-- Coaches --}}
                        <article class="sf-kpi" data-sf-reveal style="--i:1">
                            <div class="sf-kpi-chart">
                                <svg viewBox="0 0 100 100" aria-hidden="true">
                                    <circle class="sf-kpi-bg" cx="50" cy="50" r="40"></circle>
                                    <circle class="sf-kpi-fill"
                                            cx="50" cy="50" r="40"
                                            stroke-dasharray="{{ $circleCircumference }}"
                                            stroke-dashoffset="{{ $circleCircumference }}"
                                            data-offset="{{ $coachesOffset }}"></circle>
                                </svg>
                                <span class="sf-kpi-icon-inner" aria-hidden="true">
                                    <i class="bi bi-person-badge-fill"></i>
                                </span>
                            </div>
                            <div class="sf-kpi-body">
                                <div class="sf-kpi-value"
                                     data-count="{{ (int) ($totalCoaches ?? 0) }}">0</div>
                                <div class="sf-kpi-label">إجمالي المدربين</div>
                            </div>
                        </article>

                        {{-- Subscriptions --}}
                        <article class="sf-kpi" data-sf-reveal style="--i:2">
                            <div class="sf-kpi-chart">
                                <svg viewBox="0 0 100 100" aria-hidden="true">
                                    <circle class="sf-kpi-bg" cx="50" cy="50" r="40"></circle>
                                    <circle class="sf-kpi-fill"
                                            cx="50" cy="50" r="40"
                                            stroke-dasharray="{{ $circleCircumference }}"
                                            stroke-dashoffset="{{ $circleCircumference }}"
                                            data-offset="{{ $subscriptionsOffset }}"></circle>
                                </svg>
                                <span class="sf-kpi-icon-inner" aria-hidden="true">
                                    <i class="bi bi-graph-up-arrow"></i>
                                </span>
                            </div>
                            <div class="sf-kpi-body">
                                <div class="sf-kpi-value"
                                     data-count="{{ (int) ($activeSubscriptions ?? 0) }}">0</div>
                                <div class="sf-kpi-label">الاشتراكات النشطة</div>
                            </div>
                        </article>

                    </div>

                </section>

                {{-- Quick Actions --}}
                <section aria-labelledby="sfActionsHeading">

                    <div class="sf-section-title" data-sf-reveal>
                        <h2 id="sfActionsHeading">إجراءات سريعة</h2>
                    </div>
                    <p class="sf-section-sub" data-sf-reveal>الانتقال المباشر لأكثر الأدوات استخداماً.</p>

                    <div class="sf-actions-grid">

                        <a href="{{ route('admin.users-details') }}" class="sf-action" data-sf-reveal style="--i:0">
                            <div class="sf-action-ic"><i class="bi bi-people" aria-hidden="true"></i></div>
                            <div>
                                <p class="sf-action-title">إدارة المستخدمين</p>
                                <p class="sf-action-desc">عرض، إضافة أو تعديل الأعضاء</p>
                            </div>
                        </a>

                        <a href="{{ route('admin.trainer.manage') }}" class="sf-action" data-sf-reveal style="--i:1">
                            <div class="sf-action-ic"><i class="bi bi-person-badge" aria-hidden="true"></i></div>
                            <div>
                                <p class="sf-action-title">إدارة المدربين</p>
                                <p class="sf-action-desc">إدارة ملفات المدربين</p>
                            </div>
                        </a>

                        <a href="{{ route('admin.coaches.requests') }}" class="sf-action" data-sf-reveal style="--i:2">
                            <div class="sf-action-ic"><i class="bi bi-clipboard-check" aria-hidden="true"></i></div>
                            <div>
                                <p class="sf-action-title">طلبات الانضمام</p>
                                <p class="sf-action-desc">مراجعة واعتماد المدربين الجدد</p>
                            </div>
                        </a>

                        <a href="{{ route('admin.goals.manage') }}" class="sf-action" data-sf-reveal style="--i:3">
                            <div class="sf-action-ic"><i class="bi bi-bullseye" aria-hidden="true"></i></div>
                            <div>
                                <p class="sf-action-title">الأهداف الرياضية</p>
                                <p class="sf-action-desc">إدارة أهداف المستخدمين</p>
                            </div>
                        </a>

                        <a href="{{ route('admin.skills.index') }}" class="sf-action" data-sf-reveal style="--i:4">
                            <div class="sf-action-ic"><i class="bi bi-award" aria-hidden="true"></i></div>
                            <div>
                                <p class="sf-action-title">المهارات</p>
                                <p class="sf-action-desc">مهارات الكوتش والأخصائي</p>
                            </div>
                        </a>

                        <a href="{{ route('admin.login') }}" class="sf-action" data-sf-reveal style="--i:5">
                            <div class="sf-action-ic"><i class="bi bi-box-arrow-in-right" aria-hidden="true"></i></div>
                            <div>
                                <p class="sf-action-title">تسجيل الدخول</p>
                                <p class="sf-action-desc">الدخول بحساب آخر</p>
                            </div>
                        </a>

                        <form action="{{ route('admin.logout') }}" method="POST" style="display:contents;">
                            @csrf
                            <button type="submit" class="sf-action danger" data-sf-reveal style="--i:6">
                                <div class="sf-action-ic"><i class="bi bi-box-arrow-left" aria-hidden="true"></i></div>
                                <div>
                                    <p class="sf-action-title">تسجيل الخروج</p>
                                    <p class="sf-action-desc">إنهاء الجلسة الحالية</p>
                                </div>
                            </button>
                        </form>

                    </div>

                </section>

            </main>

        </div>

    </div>

    {{-- =========================================================
         Toasts + Confirm Modal
    ========================================================== --}}
    <div class="toast-container position-fixed top-0 start-0 p-3" id="sfToastContainer"
         aria-live="polite" aria-atomic="true" style="z-index: 1090;"></div>

    <div class="modal fade" id="sfConfirmModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius:18px; border:1px solid var(--sf-border);">
                <div class="modal-header">
                    <h5 class="modal-title">تأكيد العملية</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
                </div>
                <div class="modal-body">
                    <p data-sf-confirm-message class="mb-0"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-sf-confirm-cancel data-bs-dismiss="modal">إلغاء</button>
                    <button type="button" class="btn btn-danger" data-sf-confirm-ok>تأكيد</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    (function () {
        'use strict';

        document.documentElement.classList.add('js');

        // ============================================
        // SF Utilities
        // ============================================

        function sfCsrf() {
            var meta = document.querySelector('meta[name="csrf-token"]');
            return meta ? meta.getAttribute('content') : '';
        }

        function sfFetchJson(url, options) {
            options = options || {};
            var headers = Object.assign({
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': sfCsrf()
            }, options.headers || {});

            return fetch(url, Object.assign({}, options, { headers: headers }))
                .then(function (response) {
                    return response.text().then(function (text) {
                        var data;
                        try {
                            data = text ? JSON.parse(text) : {};
                        } catch (e) {
                            data = { raw: text };
                        }
                        if (!response.ok) {
                            var err = new Error(
                                (data && data.message) ? data.message : 'HTTP ' + response.status
                            );
                            err.status = response.status;
                            err.data = data;
                            throw err;
                        }
                        return data;
                    });
                });
        }

        function sfToast(message, type) {
            var container = document.getElementById('sfToastContainer');
            if (!container) return;
            type = type || 'info';
            var map = { success: 'success', danger: 'danger', info: 'info', warning: 'warning' };
            var cls = map[type] || 'info';

            var el = document.createElement('div');
            el.className = 'toast align-items-center text-bg-' + cls + ' border-0';
            el.setAttribute('role', 'alert');
            el.setAttribute('aria-live', 'assertive');
            el.setAttribute('aria-atomic', 'true');
            el.innerHTML =
                '<div class="d-flex">' +
                    '<div class="toast-body">' + message + '</div>' +
                    '<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="إغلاق"></button>' +
                '</div>';

            container.appendChild(el);
            var t = bootstrap.Toast.getOrCreateInstance(el, { delay: 4000 });
            t.show();
            el.addEventListener('hidden.bs.toast', function () { el.remove(); });
        }

        function sfConfirm(message) {
            return new Promise(function (resolve) {
                var modalEl = document.getElementById('sfConfirmModal');
                if (!modalEl || !window.bootstrap) {
                    resolve(window.confirm(message));
                    return;
                }
                var msgEl = modalEl.querySelector('[data-sf-confirm-message]');
                var okBtn = modalEl.querySelector('[data-sf-confirm-ok]');
                var cancelBtn = modalEl.querySelector('[data-sf-confirm-cancel]');

                if (msgEl) msgEl.textContent = message;

                var modal = bootstrap.Modal.getOrCreateInstance(modalEl);
                var done = false;
                function finish(v) {
                    if (done) return;
                    done = true;
                    modal.hide();
                    resolve(v);
                }

                function onOk() { finish(true); }
                function onCancel() { finish(false); }

                if (okBtn) okBtn.addEventListener('click', onOk, { once: true });
                if (cancelBtn) cancelBtn.addEventListener('click', onCancel, { once: true });

                modalEl.addEventListener('hidden.bs.modal', function () {
                    if (okBtn) okBtn.removeEventListener('click', onOk);
                    if (cancelBtn) cancelBtn.removeEventListener('click', onCancel);
                    if (!done) { done = true; resolve(false); }
                }, { once: true });

                modal.show();
            });
        }

        function sfSetText(id, value) {
            var el = document.getElementById(id);
            if (!el) return;
            el.textContent = (value === undefined || value === null || value === '') ? '-' : value;
        }

        function sfSetValue(id, value) {
            var el = document.getElementById(id);
            if (!el) return;
            el.value = (value === undefined || value === null) ? '' : value;
        }

        function sfDebounce(fn, wait) {
            var t;
            return function () {
                var ctx = this, args = arguments;
                clearTimeout(t);
                t = setTimeout(function () { fn.apply(ctx, args); }, wait || 150);
            };
        }

        function sfSetLoading(button, isLoading, loadingText) {
            if (!button) return;
            if (isLoading) {
                if (!button.dataset.sfOrig) button.dataset.sfOrig = button.innerHTML;
                button.disabled = true;
                if (loadingText) {
                    button.innerHTML =
                        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>' +
                        '<span class="ms-2">' + loadingText + '</span>';
                } else {
                    button.innerHTML =
                        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
                }
            } else {
                button.disabled = false;
                if (button.dataset.sfOrig) button.innerHTML = button.dataset.sfOrig;
            }
        }

        function sfCountUp() {
            var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            var els = document.querySelectorAll('[data-count]');
            if (!els.length) return;

            els.forEach(function (el) {
                var target = parseInt(el.getAttribute('data-count'), 10) || 0;

                if (reduce) {
                    el.textContent = new Intl.NumberFormat('en-US').format(target);
                    return;
                }

                var duration = 900;
                var start = null;

                function step(ts) {
                    if (start === null) start = ts;
                    var progress = Math.min((ts - start) / duration, 1);
                    var eased = 1 - Math.pow(1 - progress, 3);
                    var value = Math.round(target * eased);
                    el.textContent = new Intl.NumberFormat('en-US').format(value);
                    if (progress < 1) requestAnimationFrame(step);
                }
                requestAnimationFrame(step);
            });
        }

        function sfAnimateCircles() {
            var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            var circles = document.querySelectorAll('.sf-kpi-fill[data-offset]');
            if (!circles.length) return;

            requestAnimationFrame(function () {
                requestAnimationFrame(function () {
                    circles.forEach(function (c) {
                        var target = c.getAttribute('data-offset');
                        if (reduce) {
                            c.style.transition = 'none';
                        }
                        c.style.strokeDashoffset = target;
                    });
                });
            });
        }

        function sfReveal() {
            if (!('IntersectionObserver' in window)) {
                document.querySelectorAll('[data-sf-reveal]').forEach(function (el) {
                    el.classList.add('is-visible');
                });
                return;
            }
            var observer = new IntersectionObserver(function (entries, obs) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        obs.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.08, rootMargin: '0px 0px -40px 0px' });

            document.querySelectorAll('[data-sf-reveal]').forEach(function (el) {
                observer.observe(el);
            });
        }

        function sfStagger() {
            ['[data-sf-reveal]', '.sf-kpi', '.sf-action'].forEach(function (sel) {
                document.querySelectorAll(sel).forEach(function (el, i) {
                    if (!el.style.getPropertyValue('--i')) {
                        el.style.setProperty('--i', Math.min(i, 12));
                    }
                });
            });
        }

        function sfTheme() {
            var toggle = document.getElementById('sfThemeToggle');
            var icon = document.getElementById('sfThemeIcon');

            function apply(theme) {
                document.documentElement.setAttribute('data-bs-theme', theme);
                try { localStorage.setItem('sf-theme', theme); } catch (e) {}
                if (icon) icon.className = theme === 'dark' ? 'bi bi-sun' : 'bi bi-moon-stars';
            }

            function current() {
                return document.documentElement.getAttribute('data-bs-theme') === 'dark' ? 'dark' : 'light';
            }

            if (icon) icon.className = current() === 'dark' ? 'bi bi-sun' : 'bi bi-moon-stars';

            if (toggle) {
                toggle.addEventListener('click', function () {
                    apply(current() === 'dark' ? 'light' : 'dark');
                });
            }
        }

        function sfPreventDoubleSubmit() {
            document.querySelectorAll('form').forEach(function (form) {
                form.addEventListener('submit', function (e) {
                    if (form.dataset.sfSubmitting === '1') {
                        e.preventDefault();
                        return;
                    }
                    form.dataset.sfSubmitting = '1';
                    var submitBtn = form.querySelector('button[type="submit"]');
                    if (submitBtn && !submitBtn.disabled) {
                        sfSetLoading(submitBtn, true);
                        setTimeout(function () {
                            form.dataset.sfSubmitting = '0';
                            sfSetLoading(submitBtn, false);
                        }, 3000);
                    } else {
                        setTimeout(function () { form.dataset.sfSubmitting = '0'; }, 3000);
                    }
                });
            });
        }

        function sfSidebar() {
            var toggle = document.getElementById('sfSidebarToggle');
            var sidebar = document.getElementById('sfSidebar');
            var backdrop = document.getElementById('sfSidebarBackdrop');

            function close() {
                if (!sidebar) return;
                sidebar.classList.remove('is-open');
                if (backdrop) backdrop.classList.remove('is-open');
            }
            function open() {
                if (!sidebar) return;
                sidebar.classList.add('is-open');
                if (backdrop) backdrop.classList.add('is-open');
            }

            if (toggle) {
                toggle.addEventListener('click', function () {
                    if (sidebar && sidebar.classList.contains('is-open')) close();
                    else open();
                });
            }
            if (backdrop) backdrop.addEventListener('click', close);
            window.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') close();
            });
        }

        function sfSidebarSections() {
            document.querySelectorAll('[data-sf-section-toggle]').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var section = btn.closest('.sf-nav-section');
                    if (!section) return;
                    section.classList.toggle('open');
                    btn.setAttribute('aria-expanded', section.classList.contains('open') ? 'true' : 'false');
                });
            });
        }

        function sfInterceptConfirmForms() {
            document.querySelectorAll('form[onsubmit]').forEach(function (form) {
                var attr = form.getAttribute('onsubmit') || '';
                if (attr.indexOf('confirm(') === -1) return;
                var match = attr.match(/confirm\((['"])([\s\S]*?)\1\)/);
                if (!match) return;
                var message = match[2];
                form.removeAttribute('onsubmit');
                form.addEventListener('submit', function (e) {
                    if (form.dataset.sfConfirmed === '1') return;
                    e.preventDefault();
                    sfConfirm(message).then(function (ok) {
                        if (ok) {
                            form.dataset.sfConfirmed = '1';
                            form.submit();
                        }
                    });
                });
            });
        }

        // ============================================
        // Init
        // ============================================

        document.addEventListener('DOMContentLoaded', function () {
            sfStagger();
            sfReveal();
            sfTheme();
            sfSidebar();
            sfSidebarSections();
            sfCountUp();
            sfAnimateCircles();
            sfPreventDoubleSubmit();
            sfInterceptConfirmForms();
        });
    })();
    </script>

</body>
</html>
