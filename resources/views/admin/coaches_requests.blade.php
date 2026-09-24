<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>طلبات اعتماد المدربين | SuperFit</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link
        href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    <style>

        /* =========================================================
           SuperFit — Coach Approval Management
        ========================================================= */

        :root {

            --teal-950: #0a2f2c;
            --teal-900: #0b4541;
            --teal-800: #0f6e6e;
            --teal-700: #0f766e;
            --teal-600: #128f89;
            --teal-500: #17a89e;
            --teal-400: #2ec9b8;

            --teal-100: #e3f5f1;
            --teal-50: #f2faf8;

            --ink: #0e2624;
            --ink-soft: #58716e;

            --paper: #ffffff;
            --line: #d7ece7;

            --success-bg: #dcfce7;
            --success-fg: #15803d;

            --danger-bg: #fee2e2;
            --danger-fg: #dc2626;

            --warning-bg: #fef3c7;
            --warning-fg: #b45309;
        }


        /* =========================================================
           Base
        ========================================================= */

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html {
            scroll-behavior: smooth;
        }

        body {

            min-height: 100vh;

            font-family:
                'Cairo',
                'Segoe UI',
                Tahoma,
                sans-serif;

            background: var(--teal-50);

            color: var(--ink);

            background-image:
                radial-gradient(
                    circle at 100% 0%,
                    #d8f2ec 0%,
                    transparent 42%
                ),
                radial-gradient(
                    circle at 0% 100%,
                    #d8f2ec 0%,
                    transparent 42%
                );

            background-repeat: no-repeat;

            padding: 28px 24px 60px;
        }


        /* =========================================================
           Container
        ========================================================= */

        .container {

            width: 100%;

            max-width: 1280px;

            margin: 0 auto;
        }


        /* =========================================================
           Navbar
        ========================================================= */

        .navbar {

            display: flex;

            gap: 8px;

            background:
                linear-gradient(
                    135deg,
                    var(--teal-800),
                    var(--teal-950)
                );

            padding: 9px;

            border-radius: 17px;

            margin-bottom: 30px;

            box-shadow:
                0 10px 28px
                rgba(
                    15,
                    110,
                    110,
                    .18
                );
        }

        .nav-link {

            flex: 1;

            text-align: center;

            padding: 13px 16px;

            border-radius: 11px;

            color: #cdece6;

            text-decoration: none;

            font-size: 14px;

            font-weight: 600;

            transition:
                background .2s ease,
                color .2s ease,
                transform .2s ease;
        }

        .nav-link:hover {

            background:
                rgba(255, 255, 255, .08);

            color: #ffffff;

            transform: translateY(-1px);
        }

        .nav-link.active {

            background: var(--teal-400);

            color: var(--teal-950);

            font-weight: 800;
        }


        /* =========================================================
           Page Header
        ========================================================= */

        .page-header {

            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 20px;

            margin-bottom: 24px;
        }

        .page-kicker {

            display: inline-block;

            margin-bottom: 4px;

            color: var(--teal-600);

            font-size: 11px;

            font-weight: 800;

            letter-spacing: 1.4px;
        }

        .page-title {

            margin: 0;

            color: var(--teal-950);

            font-size: 27px;

            font-weight: 800;
        }

        .page-description {

            margin-top: 5px;

            color: var(--ink-soft);

            font-size: 13.5px;
        }


        /* =========================================================
           Statistics
        ========================================================= */

        .stats-wrapper {

            display: flex;

            gap: 10px;
        }

        .stat-card {

            min-width: 105px;

            padding: 12px 18px;

            text-align: center;

            background: var(--paper);

            border: 1px solid var(--line);

            border-radius: 14px;

            box-shadow:
                0 5px 18px
                rgba(
                    15,
                    110,
                    110,
                    .06
                );
        }

        .stat-number {

            display: block;

            color: var(--teal-600);

            font-size: 22px;

            font-weight: 800;

            line-height: 1.2;
        }

        .stat-label {

            display: block;

            margin-top: 3px;

            color: var(--ink-soft);

            font-size: 11.5px;

            font-weight: 600;
        }


        /* =========================================================
           Alerts
        ========================================================= */

        .alert {

            display: flex;

            align-items: center;

            gap: 10px;

            padding: 13px 16px;

            margin-bottom: 22px;

            border-radius: 12px;

            font-size: 13.5px;

            font-weight: 600;
        }

        .alert-success {

            background: var(--success-bg);

            color: var(--success-fg);

            border: 1px solid #bbf7d0;
        }

        .alert-icon {

            width: 25px;

            height: 25px;

            display: flex;

            align-items: center;

            justify-content: center;

            border-radius: 50%;

            background:
                rgba(
                    21,
                    128,
                    61,
                    .12
                );

            font-weight: 800;
        }


        /* =========================================================
           Card Panel
        ========================================================= */

        .card-panel {

            margin-bottom: 25px;

            background: var(--paper);

            border: 1px solid var(--line);

            border-radius: 18px;

            box-shadow:
                0 8px 28px
                rgba(
                    15,
                    110,
                    110,
                    .07
                );

            overflow: hidden;
        }


        /* =========================================================
           Card Header
        ========================================================= */

        .card-header {

            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 15px;

            padding: 20px 22px;

            border-bottom: 1px solid var(--line);
        }

        .card-heading {

            display: flex;

            align-items: center;

            gap: 11px;
        }

        .card-icon {

            width: 38px;

            height: 38px;

            display: flex;

            align-items: center;

            justify-content: center;

            border-radius: 11px;

            background: var(--teal-100);

            color: var(--teal-700);

            font-size: 18px;
        }

        .card-title {

            margin: 0;

            color: var(--teal-950);

            font-size: 16px;

            font-weight: 800;
        }

        .card-subtitle {

            margin-top: 2px;

            color: var(--ink-soft);

            font-size: 11.5px;
        }

        .card-count {

            display: inline-flex;

            align-items: center;

            justify-content: center;

            min-width: 34px;

            height: 30px;

            padding: 0 9px;

            border-radius: 8px;

            background: var(--teal-50);

            border: 1px solid var(--teal-100);

            color: var(--teal-700);

            font-size: 12px;

            font-weight: 800;
        }


        /* =========================================================
           Rejected Card
        ========================================================= */

        .card-header.rejected-header {

            border-bottom-color: #fee2e2;
        }

        .rejected-header .card-icon {

            background: var(--danger-bg);

            color: var(--danger-fg);
        }

        .rejected-header .card-title {

            color: #991b1b;
        }

        .rejected-header .card-count {

            background: #fef2f2;

            border-color: #fecaca;

            color: #b91c1c;
        }


        /* =========================================================
           Table
        ========================================================= */

        .table-responsive {

            width: 100%;

            overflow-x: auto;
        }

        table {

            width: 100%;

            min-width: 750px;

            border-collapse: collapse;

            text-align: right;
        }

        th {

            padding: 13px 18px;

            background: var(--teal-50);

            color: var(--teal-800);

            border-bottom: 1px solid var(--line);

            font-size: 12.5px;

            font-weight: 800;

            white-space: nowrap;
        }

        tbody tr {

            border-bottom: 1px solid var(--line);

            transition:
                background .18s ease;
        }

        tbody tr:last-child {

            border-bottom: none;
        }

        tbody tr:hover {

            background: #f7fcfb;
        }

        td {

            padding: 14px 18px;

            color: var(--ink);

            font-size: 13.5px;

            vertical-align: middle;
        }


        /* =========================================================
           ID
        ========================================================= */

        .id-badge {

            display: inline-flex;

            align-items: center;

            padding: 5px 9px;

            border-radius: 7px;

            background: var(--teal-50);

            border: 1px solid var(--teal-100);

            color: var(--teal-700);

            font-size: 12px;

            font-weight: 700;
        }


        /* =========================================================
           Name
        ========================================================= */

        .coach-name {

            display: flex;

            align-items: center;

            gap: 10px;
        }

        .coach-avatar {

            width: 35px;

            height: 35px;

            flex-shrink: 0;

            display: flex;

            align-items: center;

            justify-content: center;

            border-radius: 50%;

            background:
                linear-gradient(
                    135deg,
                    var(--teal-400),
                    var(--teal-600)
                );

            color: #ffffff;

            font-size: 13px;

            font-weight: 800;
        }

        .coach-name strong {

            color: var(--teal-950);

            font-weight: 700;
        }


        /* =========================================================
           Specialization
        ========================================================= */

        .specialization {

            display: inline-block;

            padding: 5px 10px;

            border-radius: 8px;

            background: var(--teal-50);

            border: 1px solid var(--teal-100);

            color: var(--teal-700);

            font-size: 12px;

            font-weight: 700;
        }


        /* =========================================================
           Experience
        ========================================================= */

        .experience {

            color: var(--ink-soft);

            font-weight: 600;
        }


        /* =========================================================
           Audit Button
        ========================================================= */

        .btn-audit {

            display: inline-flex;

            align-items: center;

            gap: 7px;

            padding: 8px 14px;

            border: 1px solid var(--teal-600);

            border-radius: 9px;

            background: var(--teal-800);

            color: #ffffff;

            font-family: inherit;

            font-size: 12px;

            font-weight: 700;

            cursor: pointer;

            transition:
                background .18s ease,
                transform .18s ease,
                box-shadow .18s ease;
        }

        .btn-audit:hover {

            background: var(--teal-600);

            transform: translateY(-1px);

            box-shadow:
                0 4px 12px
                rgba(
                    18,
                    143,
                    137,
                    .2
                );
        }


        /* =========================================================
           Empty State
        ========================================================= */

        .empty-state {

            padding: 45px 20px;

            text-align: center;
        }

        .empty-icon {

            width: 55px;

            height: 55px;

            display: flex;

            align-items: center;

            justify-content: center;

            margin: 0 auto 10px;

            border-radius: 50%;

            background: var(--teal-100);

            font-size: 23px;
        }

        .empty-state h3 {

            margin-bottom: 4px;

            color: var(--teal-950);

            font-size: 15px;
        }

        .empty-state p {

            color: var(--ink-soft);

            font-size: 12.5px;
        }


        /* =========================================================
           Drawer Checkbox
        ========================================================= */

        .drawer-toggle {

            display: none;
        }


        /* =========================================================
           Drawer Overlay
        ========================================================= */

        .drawer-overlay {

            position: fixed;

            inset: 0;

            z-index: 999;

            background:
                rgba(
                    10,
                    47,
                    44,
                    .38
                );

            backdrop-filter: blur(3px);

            opacity: 0;

            visibility: hidden;

            transition:
                opacity .25s ease,
                visibility .25s ease;

            cursor: pointer;
        }


        /* =========================================================
           Drawer
        ========================================================= */

        .drawer-content {

            position: fixed;

            top: 0;

            left: 0;

            z-index: 1000;

            width: 430px;

            max-width: 100%;

            height: 100vh;

            padding: 27px;

            background: var(--paper);

            box-shadow:
                16px 0 45px
                rgba(
                    10,
                    47,
                    44,
                    .20
                );

            transform: translateX(-100%);

            transition:
                transform .28s ease;

            overflow-y: auto;

            cursor: default;
        }


        /* =========================================================
           Drawer Active State
        ========================================================= */

        @foreach($pendingCoaches as $coach)

            #drawer-toggle-{{ $coach->id }}:checked ~ .drawer-overlay {
                opacity: 1;
                visibility: visible;
            }

            #drawer-toggle-{{ $coach->id }}:checked ~ .drawer-content-{{ $coach->id }} {
                transform: translateX(0);
            }

        @endforeach


        /* =========================================================
           Drawer Header
        ========================================================= */

        .drawer-header {

            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 15px;

            padding-bottom: 17px;

            margin-bottom: 22px;

            border-bottom: 1px solid var(--line);
        }

        .drawer-kicker {

            display: block;

            margin-bottom: 3px;

            color: var(--teal-600);

            font-size: 9px;

            font-weight: 800;

            letter-spacing: 1.2px;
        }

        .drawer-header h3 {

            color: var(--teal-950);

            font-size: 18px;

            font-weight: 800;
        }

        .close-btn {

            width: 34px;

            height: 34px;

            display: flex;

            align-items: center;

            justify-content: center;

            border-radius: 10px;

            color: var(--ink-soft);

            font-size: 23px;

            cursor: pointer;

            transition:
                background .18s ease,
                color .18s ease;
        }

        .close-btn:hover {

            background: var(--teal-100);

            color: var(--teal-800);
        }


        /* =========================================================
           Drawer Profile
        ========================================================= */

        .drawer-profile {

            text-align: center;

            padding-bottom: 20px;
        }

        .drawer-avatar {

            width: 78px;

            height: 78px;

            display: flex;

            align-items: center;

            justify-content: center;

            margin: 0 auto 11px;

            border-radius: 50%;

            background: var(--teal-100);

            border: 4px solid #f6fbfa;

            color: var(--teal-700);

            font-size: 27px;

            box-shadow:
                0 5px 18px
                rgba(
                    15,
                    110,
                    110,
                    .13
                );
        }

        .drawer-profile-name {

            color: var(--teal-950);

            font-size: 17px;

            font-weight: 800;
        }

        .drawer-profile-label {

            margin-top: 2px;

            color: var(--teal-600);

            font-size: 11px;

            font-weight: 600;
        }


        /* =========================================================
           Information
        ========================================================= */

        .info-list {

            border-top: 1px solid var(--line);
        }

        .info-group {

            padding: 13px 3px;

            border-bottom: 1px dashed var(--line);
        }

        .info-label {

            color: var(--ink-soft);

            font-size: 11.5px;

            font-weight: 700;
        }

        .info-value {

            margin-top: 4px;

            color: var(--ink);

            font-size: 13.5px;

            font-weight: 700;

            line-height: 1.7;

            overflow-wrap: anywhere;
        }

        .bio-value {

            color: var(--ink-soft);

            font-size: 12.5px;

            font-weight: 500;
        }


        /* =========================================================
           Actions
        ========================================================= */

        .actions-box {

            margin-top: 24px;

            padding-top: 20px;

            border-top: 1px solid var(--line);
        }

        .btn-approve {

            width: 100%;

            padding: 12px;

            margin-bottom: 13px;

            border: none;

            border-radius: 11px;

            background: var(--success-fg);

            color: #ffffff;

            font-family: inherit;

            font-size: 13px;

            font-weight: 800;

            cursor: pointer;

            transition:
                filter .18s ease,
                transform .18s ease;
        }

        .btn-approve:hover {

            filter: brightness(1.07);

            transform: translateY(-1px);
        }


        /* =========================================================
           Reject Box
        ========================================================= */

        .reject-box {

            padding: 15px;

            border-radius: 12px;

            background: #fff8f8;

            border: 1px solid #fecaca;
        }

        .reject-label {

            display: block;

            margin-bottom: 7px;

            color: #991b1b;

            font-size: 11.5px;

            font-weight: 700;
        }

        .reject-textarea {

            width: 100%;

            min-height: 85px;

            resize: vertical;

            padding: 10px;

            border: 1px solid #fca5a5;

            border-radius: 9px;

            background: #ffffff;

            color: var(--ink);

            font-family: inherit;

            font-size: 12.5px;

            outline: none;

            transition:
                border-color .18s ease,
                box-shadow .18s ease;
        }

        .reject-textarea::placeholder {

            color: #c08b8b;
        }

        .reject-textarea:focus {

            border-color: var(--danger-fg);

            box-shadow:
                0 0 0 3px
                rgba(
                    220,
                    38,
                    38,
                    .08
                );
        }

        .btn-reject {

            width: 100%;

            padding: 10px;

            margin-top: 8px;

            border: none;

            border-radius: 9px;

            background: var(--danger-fg);

            color: #ffffff;

            font-family: inherit;

            font-size: 12.5px;

            font-weight: 800;

            cursor: pointer;

            transition:
                filter .18s ease,
                transform .18s ease;
        }

        .btn-reject:hover {

            filter: brightness(1.05);

            transform: translateY(-1px);
        }


        /* =========================================================
           Drawer Scrollbar
        ========================================================= */

        .drawer-content::-webkit-scrollbar {

            width: 6px;
        }

        .drawer-content::-webkit-scrollbar-track {

            background: transparent;
        }

        .drawer-content::-webkit-scrollbar-thumb {

            background: #b9ddd6;

            border-radius: 10px;
        }

        .drawer-content::-webkit-scrollbar-thumb:hover {

            background: var(--teal-500);
        }


        /* =========================================================
           Responsive
        ========================================================= */

        @media (max-width: 850px) {

            body {
                padding: 20px 15px 40px;
            }

            .page-header {

                align-items: flex-start;
            }

            .page-title {

                font-size: 23px;
            }

        }


        @media (max-width: 650px) {

            body {
                padding: 15px 10px 30px;
            }

            .navbar {

                flex-direction: column;

                gap: 5px;
            }

            .nav-link {

                flex: none;
            }

            .page-header {

                flex-direction: column;
            }

            .stats-wrapper {

                width: 100%;
            }

            .stat-card {

                flex: 1;
            }

            .card-header {

                align-items: flex-start;
            }

            .drawer-content {

                width: 100%;

                padding: 22px 18px;
            }

        }


        @media (max-width: 480px) {

            .page-title {

                font-size: 21px;
            }

            .page-description {

                font-size: 12px;
            }

            .card-header {

                padding: 17px;
            }

            .card-heading {

                align-items: flex-start;
            }

            .card-icon {

                width: 34px;

                height: 34px;
            }

            .card-title {

                font-size: 14px;
            }

        }

    </style>

</head>


<body>

<div class="container">


    {{-- =========================================================
        Navbar
    ========================================================== --}}

    <nav class="navbar">

        <a
            href="{{ route('admin.dashboard') }}"
            class="nav-link"
        >
            لوحة الإحصائيات
        </a>

        <a
            href="{{ route('admin.trainer.manage') }}"
            class="nav-link active"
        >
            إدارة المدربين
        </a>

    </nav>


    {{-- =========================================================
        Success Message
    ========================================================== --}}

    @if(session('success'))

        <div class="alert alert-success">

            <span class="alert-icon">
                ✓
            </span>

            <span>
                {{ session('success') }}
            </span>

        </div>

    @endif


    {{-- =========================================================
        Page Header
    ========================================================== --}}

    <div class="page-header">

        <div>

            <span class="page-kicker">
                SUPERFIT ADMIN
            </span>

            <h1 class="page-title">
                طلبات اعتماد المدربين
            </h1>

            <p class="page-description">
                مراجعة طلبات المدربين وقبولها أو رفضها
            </p>

        </div>


        <div class="stats-wrapper">

            <div class="stat-card">

                <span class="stat-number">
                    {{ $pendingCoaches->count() }}
                </span>

                <span class="stat-label">
                    طلب معلق
                </span>

            </div>

            <div class="stat-card">

                <span class="stat-number">
                    {{ $rejectedCoaches->count() }}
                </span>

                <span class="stat-label">
                    طلب مرفوض
                </span>

            </div>

        </div>

    </div>


    {{-- =========================================================
        1. Pending Coaches
    ========================================================== --}}

    <div class="card-panel">

        <div class="card-header">

            <div class="card-heading">

                <div class="card-icon">
                    ⏳
                </div>

                <div>

                    <h2 class="card-title">
                        طلبات بانتظار التدقيق والقبول
                    </h2>

                    <p class="card-subtitle">
                        راجعي بيانات المدربين قبل اعتماد الحساب
                    </p>

                </div>

            </div>

            <span class="card-count">
                {{ $pendingCoaches->count() }}
            </span>

        </div>


        <div class="table-responsive">

            <table>

                <thead>

                    <tr>

                        <th>#</th>

                        <th>
                            الاسم
                        </th>

                        <th>
                            التخصص
                        </th>

                        <th>
                            الخبرة
                        </th>

                        <th>
                            الإجراءات
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($pendingCoaches as $coach)

                        <tr>

                            <td>

                                <span class="id-badge">
                                    #{{ $coach->id }}
                                </span>

                            </td>


                            <td>

                                <div class="coach-name">

                                    <div class="coach-avatar">
                                        {{ mb_substr($coach->name ?? 'م', 0, 1) }}
                                    </div>

                                    <strong>
                                        {{ $coach->name ?? 'مدرب جديد' }}
                                    </strong>

                                </div>

                            </td>


                            <td>

                                <span class="specialization">
                                    {{ $coach->specialization ?? 'عام' }}
                                </span>

                            </td>


                            <td>

                                <span class="experience">
                                    {{ $coach->experience ?? '0' }} سنوات
                                </span>

                            </td>


                            <td>

                                {{-- Open Drawer --}}

                                <label
                                    for="drawer-toggle-{{ $coach->id }}"
                                    class="btn-audit"
                                >
                                    🔍
                                    تدقيق الطلب
                                </label>


                                {{-- Drawer Checkbox --}}

                                <input
                                    type="checkbox"
                                    id="drawer-toggle-{{ $coach->id }}"
                                    class="drawer-toggle"
                                >


                                {{-- Drawer Overlay --}}

                                <label
                                    for="drawer-toggle-{{ $coach->id }}"
                                    class="drawer-overlay"
                                ></label>


                                {{-- =================================================
                                    Drawer
                                ================================================== --}}

                                <div
                                    class="drawer-content drawer-content-{{ $coach->id }}"
                                >

                                    <div class="drawer-header">

                                        <div>

                                            <span class="drawer-kicker">
                                                TRAINER APPLICATION
                                            </span>

                                            <h3>
                                                تفاصيل طلب المدرب
                                            </h3>

                                        </div>

                                        <label
                                            for="drawer-toggle-{{ $coach->id }}"
                                            class="close-btn"
                                        >
                                            &times;
                                        </label>

                                    </div>


                                    {{-- Profile --}}

                                    <div class="drawer-profile">

                                        <div class="drawer-avatar">
                                            👤
                                        </div>

                                        <div class="drawer-profile-name">
                                            {{ $coach->name ?? 'غير محدد' }}
                                        </div>

                                        <div class="drawer-profile-label">
                                            طلب اعتماد مدرب
                                        </div>

                                    </div>


                                    {{-- Information --}}

                                    <div class="info-list">


                                        <div class="info-group">

                                            <div class="info-label">
                                                اسم المدرب
                                            </div>

                                            <div class="info-value">
                                                {{ $coach->name ?? 'غير محدد' }}
                                            </div>

                                        </div>


                                        <div class="info-group">

                                            <div class="info-label">
                                                التخصص
                                            </div>

                                            <div class="info-value">
                                                {{ $coach->specialization ?? '-' }}
                                            </div>

                                        </div>


                                        <div class="info-group">

                                            <div class="info-label">
                                                سنوات الخبرة
                                            </div>

                                            <div class="info-value">
                                                {{ $coach->experience ?? '0' }} سنوات
                                            </div>

                                        </div>


                                        <div class="info-group">

                                            <div class="info-label">
                                                النبذة التعريفية
                                            </div>

                                            <div class="info-value bio-value">
                                                {{ $coach->bio ?? 'لا توجد نبذة مكتوبة.' }}
                                            </div>

                                        </div>


                                    </div>


                                    {{-- Actions --}}

                                    <div class="actions-box">


                                        {{-- Approve --}}

                                        <form
                                            action="{{ route('admin.coaches.approve', $coach->id) }}"
                                            method="POST"
                                        >

                                            @csrf

                                            <button
                                                type="submit"
                                                class="btn-approve"
                                            >
                                                ✓
                                                قبول الطلب واعتماده
                                            </button>

                                        </form>


                                        {{-- Reject --}}

                                        <div class="reject-box">

                                            <form
                                                action="{{ route('admin.coaches.reject', $coach->id) }}"
                                                method="POST"
                                            >

                                                @csrf

                                                <label class="reject-label">
                                                    رفض الطلب مع ذكر السبب:
                                                </label>

                                                <textarea
                                                    name="rejection_reason"
                                                    rows="3"
                                                    required
                                                    class="reject-textarea"
                                                    placeholder="اكتبي سبب عدم القبول هنا..."
                                                ></textarea>

                                                <button
                                                    type="submit"
                                                    class="btn-reject"
                                                >
                                                    ✕
                                                    تأكيد الرفض
                                                </button>

                                            </form>

                                        </div>


                                    </div>

                                </div>

                            </td>

                        </tr>


                    @empty

                        <tr>

                            <td colspan="5">

                                <div class="empty-state">

                                    <div class="empty-icon">
                                        ✓
                                    </div>

                                    <h3>
                                        لا توجد طلبات معلقة
                                    </h3>

                                    <p>
                                        لا توجد طلبات مدربين بانتظار المراجعة حالياً.
                                    </p>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>


    {{-- =========================================================
        2. Rejected Coaches
    ========================================================== --}}

    <div class="card-panel">

        <div class="card-header rejected-header">

            <div class="card-heading">

                <div class="card-icon">
                    ✕
                </div>

                <div>

                    <h2 class="card-title">
                        الطلبات المرفوضة
                    </h2>

                    <p class="card-subtitle">
                        سجل طلبات المدربين التي تم رفضها
                    </p>

                </div>

            </div>

            <span class="card-count">
                {{ $rejectedCoaches->count() }}
            </span>

        </div>


        <div class="table-responsive">

            <table>

                <thead>

                    <tr>

                        <th>#</th>

                        <th>
                            الاسم
                        </th>

                        <th>
                            التخصص
                        </th>

                        <th>
                            سبب الرفض
                        </th>

                        <th>
                            التاريخ
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($rejectedCoaches as $coach)

                        <tr>

                            <td>

                                <span class="id-badge">
                                    #{{ $coach->id }}
                                </span>

                            </td>


                            <td>

                                <div class="coach-name">

                                    <div class="coach-avatar">
                                        {{ mb_substr($coach->user->name ?? $coach->name ?? 'ك', 0, 1) }}
                                    </div>

                                    <strong>
                                        {{ $coach->user->name ?? $coach->name ?? 'كوتش' }}
                                    </strong>

                                </div>

                            </td>


                            <td>

                                <span class="specialization">
                                    {{ $coach->specialization ?? '-' }}
                                </span>

                            </td>


                            <td>

                                <span
                                    style="
                                        color:#b91c1c;
                                        font-size:13px;
                                        font-weight:600;
                                    "
                                >
                                    {{ $coach->rejection_reason }}
                                </span>

                            </td>


                            <td>

                                <span
                                    style="
                                        color:var(--ink-soft);
                                        font-size:12px;
                                        font-weight:600;
                                    "
                                >
                                    {{ $coach->updated_at
                                        ? $coach->updated_at->format('Y-m-d')
                                        : '-'
                                    }}
                                </span>

                            </td>

                        </tr>


                    @empty

                        <tr>

                            <td colspan="5">

                                <div class="empty-state">

                                    <div class="empty-icon">
                                        ✓
                                    </div>

                                    <h3>
                                        لا توجد طلبات مرفوضة
                                    </h3>

                                    <p>
                                        لا يوجد مدربين تم رفض طلباتهم حالياً.
                                    </p>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>


</div>

</body>

</html>
