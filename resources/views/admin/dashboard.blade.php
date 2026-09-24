
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="{{ asset('front/css/dashboard.css') }}">
    <title>Super Fit — إحصائيات لوحة التحكم</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap"
          rel="stylesheet">


</head>

<body>

    <!-- الخلفية -->

    <div class="bg-wave">

        <svg viewBox="0 0 1440 900"
             preserveAspectRatio="xMidYMid slice">

            <path
                d="M0,0 L1440,0 L1440,120 C1200,180 1000,60 780,110 C560,160 420,40 0,90 Z"
                fill="#d8f2ec"
            />

            <path
                d="M1440,900 L0,900 L0,780 C260,720 460,840 700,790 C940,740 1160,860 1440,800 Z"
                fill="#d8f2ec"
            />

        </svg>

    </div>


    <!-- ========================================================= -->
    <!-- Sidebar -->
    <!-- ========================================================= -->

    <aside class="sidebar">

        <!-- Brand -->

        <div class="brand">

            <div class="brand-mark">

                <svg viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="2"
                     stroke-linecap="round"
                     stroke-linejoin="round">

                    <path d="M11 20A7 7 0 0 1 4 13c0-6 6-10 15-11 0 9-3 15-8 18z"></path>

                    <path d="M4 13c3 0 6-1 8-3"></path>

                </svg>

            </div>

            <div class="brand-name">
                SuperFit
            </div>

        </div>


        <!-- ===================================================== -->
        <!-- نظرة عامة -->
        <!-- ===================================================== -->

        <div class="nav-section open">

            <div class="nav-section-label"
                 onclick="toggleSidebarSection(this)">

                <span class="nav-section-title">
                    نظرة عامة
                </span>

                <span class="nav-section-arrow">

                    <svg viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="2"
                         stroke-linecap="round"
                         stroke-linejoin="round">

                        <polyline points="6 9 12 15 18 9"></polyline>

                    </svg>

                </span>

            </div>

            <div class="nav-section-items">

                <a href="{{ route('admin.dashboard') }}"
                   class="nav-btn {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">

                    <span class="ic">

                        <svg viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="2"
                             stroke-linecap="round"
                             stroke-linejoin="round">

                            <line x1="18" y1="20" x2="18" y2="10"></line>

                            <line x1="12" y1="20" x2="12" y2="4"></line>

                            <line x1="6" y1="20" x2="6" y2="14"></line>

                        </svg>

                    </span>

                    إحصائيات لوحة التحكم

                </a>

            </div>

        </div>


        <!-- ===================================================== -->
        <!-- الإدارة -->
        <!-- ===================================================== -->

        <div class="nav-section open">

            <div class="nav-section-label"
                 onclick="toggleSidebarSection(this)">

                <span class="nav-section-title">
                    الإدارة
                </span>

                <span class="nav-section-arrow">

                    <svg viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="2"
                         stroke-linecap="round"
                         stroke-linejoin="round">

                        <polyline points="6 9 12 15 18 9"></polyline>

                    </svg>

                </span>

            </div>


            <div class="nav-section-items">

                <!-- إدارة المستخدمين -->
{{--
                <a href="{{ route('admin.users.manage') }}"
                   class="nav-btn {{ request()->routeIs('admin.users.manage') ? 'active' : '' }}">

                    <span class="ic">

                        <svg viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="2"
                             stroke-linecap="round"
                             stroke-linejoin="round">

                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>

                            <circle cx="9" cy="7" r="4"></circle>

                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>

                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>

                        </svg>

                    </span>

                    إدارة المستخدمين

                </a> --}}


                <!-- إدارة تفاصيل المستخدمين -->

                <a href="{{ route('admin.users-details') }}"
                   class="nav-btn {{ request()->routeIs('admin.users-details') ? 'active' : '' }}">

                    <span class="ic">

                        <svg viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="2"
                             stroke-linecap="round"
                             stroke-linejoin="round">

                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>

                            <circle cx="9" cy="7" r="4"></circle>

                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>

                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>

                        </svg>

                    </span>

                    إدارة تفاصيل المستخدمين

                </a>


                <!-- إدارة المدربين -->

                <a href="{{ route('admin.trainer.manage') }}"
                   class="nav-btn {{ request()->routeIs('admin.trainer.manage') ? 'active' : '' }}">

                    <span class="ic">

                        <svg viewBox="0 0 512 512"
                             fill="currentColor">

                            <path d="M448 96V64c0-17.7-14.3-32-32-32h-32c-17.7 0-32 14.3-32 32V96H160V64c0-17.7-14.3-32-32-32H96C78.3 32 64 46.3 64 64V96H48c-8.8 0-16 7.2-16 16v32c0 8.8 7.2 16 16 16H64v96H48c-8.8 0-16 7.2-16 16v32c0 8.8 7.2 16 16 16H64v32c0 17.7 14.3 32 32 32h32c17.7 0 32-14.3 32-32V352h192v32c0 17.7 14.3 32 32 32h32c17.7 0 32-14.3 32-32V352h16c8.8 0 16-7.2 16-16V304c0-8.8-7.2-16-16-16H464V192h16c8.8 0 16-7.2 16-16V144c0-8.8-7.2-16-16-16H448z"></path>

                        </svg>

                    </span>

                    إدارة المدربين

                </a>


                <!-- طلبات الانضمام -->

                <a href="{{ route('admin.coaches.requests') }}"
                   class="nav-btn {{ request()->routeIs('admin.coaches.requests') ? 'active' : '' }}">

                    <span class="ic">

                        <svg viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="2"
                             stroke-linecap="round"
                             stroke-linejoin="round">

                            <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>

                            <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>

                            <line x1="9" y1="12" x2="15" y2="12"></line>

                            <line x1="9" y1="16" x2="15" y2="16"></line>

                        </svg>

                    </span>

                    طلبات الانضمام

                </a>

            </div>

        </div>


        <!-- ===================================================== -->
        <!-- الملف الرياضي -->
        <!-- ===================================================== -->

        <div class="nav-section open">

            <div class="nav-section-label"
                 onclick="toggleSidebarSection(this)">

                <span class="nav-section-title">
                    الملف الرياضي
                </span>

                <span class="nav-section-arrow">

                    <svg viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="2"
                         stroke-linecap="round"
                         stroke-linejoin="round">

                        <polyline points="6 9 12 15 18 9"></polyline>

                    </svg>

                </span>

            </div>


            <div class="nav-section-items">

                <!-- الأهداف -->

                <a href="{{ route('admin.goals.manage') }}"
                   class="nav-btn {{ request()->routeIs('admin.goals.manage') ? 'active' : '' }}">

                    <span class="ic">

                        <svg viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="2"
                             stroke-linecap="round"
                             stroke-linejoin="round">

                            <circle cx="12" cy="12" r="9"></circle>

                            <circle cx="12" cy="12" r="5"></circle>

                            <circle cx="12" cy="12" r="1"></circle>

                        </svg>

                    </span>

                    الأهداف

                </a>


                <!-- مستوى النشاط -->

                <a href="{{ route('admin.activity-level.manage') }}"
                   class="nav-btn {{ request()->routeIs('admin.activity-level.manage') ? 'active' : '' }}">

                    <span class="ic">

                        <svg viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="2"
                             stroke-linecap="round"
                             stroke-linejoin="round">

                            <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>

                        </svg>

                    </span>

                    مستوى النشاط

                </a>


                <!-- القيود الصحية -->

                <a href="{{ route('admin.health-restrictions.manage') }}"
                   class="nav-btn {{ request()->routeIs('admin.health-restrictions.manage') ? 'active' : '' }}">

                    <span class="ic">

                        <svg viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="2"
                             stroke-linecap="round"
                             stroke-linejoin="round">

                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 1 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>

                            <line x1="12" y1="8" x2="12" y2="14"></line>

                            <line x1="9" y1="11" x2="15" y2="11"></line>

                        </svg>

                    </span>

                    القيود الصحية

                </a>


                <!-- التفضيلات -->

                <a href="{{ route('admin.preferences.manage') }}"
                   class="nav-btn {{ request()->routeIs('admin.preferences.manage') ? 'active' : '' }}">

                    <span class="ic">

                        <svg viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="2"
                             stroke-linecap="round"
                             stroke-linejoin="round">

                            <line x1="4" y1="21" x2="4" y2="14"></line>

                            <line x1="4" y1="10" x2="4" y2="3"></line>

                            <line x1="12" y1="21" x2="12" y2="12"></line>

                            <line x1="12" y1="8" x2="12" y2="3"></line>

                            <line x1="20" y1="21" x2="20" y2="16"></line>

                            <line x1="20" y1="12" x2="20" y2="3"></line>

                            <line x1="1" y1="14" x2="7" y2="14"></line>

                            <line x1="9" y1="8" x2="15" y2="8"></line>

                            <line x1="17" y1="16" x2="23" y2="16"></line>

                        </svg>

                    </span>

                    تفضيلات

                </a>


                <!-- المهارات -->

                <a href="{{ route('admin.skills.manage') }}"
                   class="nav-btn {{ request()->routeIs('admin.skills.manage') ? 'active' : '' }}">

                    <span class="ic">

                        <svg viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="2"
                             stroke-linecap="round"
                             stroke-linejoin="round">

                            <circle cx="12" cy="8" r="6"></circle>

                            <path d="M8.21 13.89 7 23l5-3 5 3-1.21-9.12"></path>

                        </svg>

                    </span>

                    المهارات

                </a>

            </div>

        </div>


        <!-- ===================================================== -->
        <!-- الحساب -->
        <!-- ===================================================== -->

        <div class="nav-section open">

            <div class="nav-section-label"
                 onclick="toggleSidebarSection(this)">

                <span class="nav-section-title">
                    الحساب
                </span>

                <span class="nav-section-arrow">

                    <svg viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="2"
                         stroke-linecap="round"
                         stroke-linejoin="round">

                        <polyline points="6 9 12 15 18 9"></polyline>

                    </svg>

                </span>

            </div>


            <div class="nav-section-items">

                <!-- تسجيل الدخول -->

                <a href="{{ route('admin.login') }}"
                   class="nav-btn">

                    <span class="ic">

                        <svg viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="2"
                             stroke-linecap="round"
                             stroke-linejoin="round">

                            <path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"></path>

                        </svg>

                    </span>

                    تسجيل الدخول

                </a>


                <!-- تسجيل الخروج -->

                <div class="sidebar-footer">

                    <form action="{{ route('admin.logout') }}"
                          method="POST">

                        @csrf

                        <button type="submit"
                                class="nav-btn logout-btn">

                            <span class="ic">

                                <svg viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor"
                                     stroke-width="2"
                                     stroke-linecap="round"
                                     stroke-linejoin="round">

                                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>

                                    <polyline points="16 17 21 12 16 7"></polyline>

                                    <line x1="21" y1="12" x2="9" y2="12"></line>

                                </svg>

                            </span>

                            تسجيل الخروج

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </aside>


    <!-- ========================================================= -->
    <!-- المحتوى الرئيسي -->
    <!-- ========================================================= -->

    <main class="main">

        <div class="topbar">

            <div>

                <h1>
                    إحصائيات لوحة التحكم
                </h1>

                <p>
                    نظرة عامة ومباشرة على مجتمع سوبر فيت الخاص بك.
                </p>

            </div>

            <div class="session-pill">

                <span class="dot"></span>

                جلسة المسؤول نشطة

            </div>

        </div>


        <!-- ===================================================== -->
        <!-- حساب نسب الدوائر -->
        <!-- ===================================================== -->

        @php

            $circleCircumference = 251.2;

            $usersPercentage =
                $usersPercentage ?? 25;

            $coachesPercentage =
                $coachesPercentage ?? 60;

            $subscriptionsPercentage =
                $subscriptionsPercentage ?? 15;

            $clampPercentage =
                fn($value) => max(
                    0,
                    min(100, $value)
                );

            $usersOffset =
                $circleCircumference -
                (
                    $circleCircumference *
                    $clampPercentage($usersPercentage) /
                    100
                );

            $coachesOffset =
                $circleCircumference -
                (
                    $circleCircumference *
                    $clampPercentage($coachesPercentage) /
                    100
                );

            $subscriptionsOffset =
                $circleCircumference -
                (
                    $circleCircumference *
                    $clampPercentage($subscriptionsPercentage) /
                    100
                );

        @endphp


        <!-- ===================================================== -->
        <!-- الإحصائيات -->
        <!-- ===================================================== -->

        <section class="stats-grid">


            <!-- المستخدمين -->

            <div class="stat-card card-users"
                 style="--leaf-tint:#e3f5f1">

                <div class="stat-chart-container">

                    <svg viewBox="0 0 100 100">

                        <circle
                            class="stat-chart-bg"
                            cx="50"
                            cy="50"
                            r="40"
                        />

                        <circle
                            class="stat-chart-fill"
                            cx="50"
                            cy="50"
                            r="40"
                            stroke-dasharray="{{ $circleCircumference }}"
                            stroke-dashoffset="{{ $usersOffset }}"
                        />

                    </svg>

                </div>

                <div class="stat-content">

                    <div class="stat-value">
                        {{ number_format($totalUsers ?? 0) }}
                    </div>

                    <div class="stat-label">
                        إجمالي المستخدمين
                    </div>

                </div>

            </div>


            <!-- المدربين -->

            <div class="stat-card card-coaches"
                 style="--leaf-tint:#dff2ec">

                <div class="stat-chart-container">

                    <svg viewBox="0 0 100 100">

                        <circle
                            class="stat-chart-bg"
                            cx="50"
                            cy="50"
                            r="40"
                        />

                        <circle
                            class="stat-chart-fill"
                            cx="50"
                            cy="50"
                            r="40"
                            stroke-dasharray="{{ $circleCircumference }}"
                            stroke-dashoffset="{{ $coachesOffset }}"
                        />

                    </svg>

                </div>

                <div class="stat-content">

                    <div class="stat-value">
                        {{ number_format($totalCoaches ?? 0) }}
                    </div>

                    <div class="stat-label">
                        إجمالي المدربين
                    </div>

                </div>

            </div>


            <!-- الاشتراكات -->

            <div class="stat-card card-subs"
                 style="--leaf-tint:#dff7f1">

                <div class="stat-chart-container">

                    <svg viewBox="0 0 100 100">

                        <circle
                            class="stat-chart-bg"
                            cx="50"
                            cy="50"
                            r="40"
                        />

                        <circle
                            class="stat-chart-fill"
                            cx="50"
                            cy="50"
                            r="40"
                            stroke-dasharray="{{ $circleCircumference }}"
                            stroke-dashoffset="{{ $subscriptionsOffset }}"
                        />

                    </svg>

                </div>

                <div class="stat-content">

                    <div class="stat-value">
                        {{ number_format($activeSubscriptions ?? 0) }}
                    </div>

                    <div class="stat-label">
                        الاشتراكات النشطة
                    </div>

                </div>

            </div>

        </section>


        <!-- ===================================================== -->
        <!-- الإجراءات السريعة -->
        <!-- ===================================================== -->

        <section class="panel">

            <h2>
                إجراءات سريعة
            </h2>

            <p>
                الانتقال المباشر لأكثر الأدوات استخداماً.
            </p>


            <div class="action-grid">


                <!-- إدارة المستخدمين -->

                <a href="{{ route('admin.users-details') }}"
                   class="action-card">

                    <div class="action-ic">

                        <svg viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="2"
                             stroke-linecap="round"
                             stroke-linejoin="round">

                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>

                            <circle cx="9" cy="7" r="4"></circle>

                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>

                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>

                        </svg>

                    </div>

                    <strong>
                        إدارة المستخدمين
                    </strong>

                    <span>
                        عرض، إضافة أو تعديل الأعضاء
                    </span>

                </a>


                <!-- المدربين -->

                <a href="{{ route('admin.trainer.manage') }}"
                   class="action-card">

                    <div class="action-ic">

                        <svg viewBox="0 0 512 512"
                             fill="currentColor">

                            <path d="M448 96V64c0-17.7-14.3-32-32-32h-32c-17.7 0-32 14.3-32 32V96H160V64c0-17.7-14.3-32-32-32H96C78.3 32 64 46.3 64 64V96H48c-8.8 0-16 7.2-16 16v32c0 8.8 7.2 16 16 16H64v96H48c-8.8 0-16 7.2-16 16v32c0 8.8 7.2 16 16 16H64v32c0 17.7 14.3 32 32 32h32c17.7 0 32-14.3 32-32V352h192v32c0 17.7 14.3 32 32 32h32c17.7 0 32-14.3 32-32V352h16c8.8 0 16-7.2 16-16V304c0-8.8-7.2-16-16-16H464V192h16c8.8 0 16-7.2 16-16V144c0-8.8-7.2-16-16-16H448z"></path>

                        </svg>

                    </div>

                    <strong>
                        إدارة المدربين
                    </strong>

                    <span>
                        إدارة ملفات المدربين
                    </span>

                </a>


                <!-- تسجيل الدخول -->

                <a href="{{ route('admin.login') }}"
                   class="action-card login">

                    <div class="action-ic">

                        <svg viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="2"
                             stroke-linecap="round"
                             stroke-linejoin="round">

                            <path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"></path>

                        </svg>

                    </div>

                    <strong>
                        تسجيل الدخول
                    </strong>

                    <span>
                        الدخول بحساب آخر
                    </span>

                </a>


                <!-- تسجيل الخروج -->

                <form action="{{ route('admin.logout') }}"
                      method="POST">

                    @csrf

                    <button type="submit"
                            class="action-card danger"
                            style="width:100%;">

                        <div class="action-ic">

                            <svg viewBox="0 0 24 24"
                                 fill="none"
                                 stroke="currentColor"
                                 stroke-width="2"
                                 stroke-linecap="round"
                                 stroke-linejoin="round">

                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>

                                <polyline points="16 17 21 12 16 7"></polyline>

                                <line x1="21" y1="12" x2="9" y2="12"></line>

                            </svg>

                        </div>

                        <strong>
                            تسجيل الخروج
                        </strong>

                        <span>
                            إنهاء الجلسة الحالية
                        </span>

                    </button>

                </form>

            </div>

        </section>

    </main>


    <!-- ========================================================= -->
    <!-- JavaScript -->
    <!-- ========================================================= -->

    <script>

        /*
         * فتح وإغلاق أقسام الـ Sidebar
         */
        function toggleSidebarSection(label) {

            const section =
                label.closest('.nav-section');

            if (!section) {
                return;
            }

            section.classList.toggle('open');
        }


        /*
         * إذا كان المستخدم داخل صفحة تابعة لقسم معيّن
         * نخلي هذا القسم مفتوح تلقائياً.
         */

        document.addEventListener('DOMContentLoaded', function () {

            document
                .querySelectorAll('.nav-section')
                .forEach(function (section) {

                    const activeItem =
                        section.querySelector('.nav-btn.active');

                    if (activeItem) {
                        section.classList.add('open');
                    }

                });

        });

    </script>

</body>

</html>

