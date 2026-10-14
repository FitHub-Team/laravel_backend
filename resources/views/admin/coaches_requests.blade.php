<!DOCTYPE html>
<html lang="ar" dir="rtl" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>طلبات اعتماد المدربين | SuperFit</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <script>
        (function () {
            try { var t = localStorage.getItem('sf-theme'); if (t === 'dark') document.documentElement.setAttribute('data-bs-theme', 'dark'); } catch (e) {}
        })();
    </script>

    <style>
        :root {
            --sf-teal-900:#0B3B3C;--sf-teal-700:#0F766E;--sf-teal-500:#14B8A6;--sf-teal-100:#CCFBF1;
            --sf-green-600:#16A34A;--sf-green-400:#4ADE80;--sf-lime-300:#BEF264;
            --sf-bg:#F3FAF8;--sf-surface:#FFFFFF;--sf-text:#0F2D2E;--sf-muted:#5B7B7A;--sf-border:#D6EBE7;
            --sf-warning:#F59E0B;--sf-danger:#E11D48;--sf-info:#0EA5E9;
            --bs-primary:var(--sf-teal-700);--bs-success:var(--sf-green-600);
            --bs-body-bg:var(--sf-bg);--bs-body-color:var(--sf-text);--bs-border-color:var(--sf-border);
        }
        [data-bs-theme="dark"] {
            --sf-bg:#071816;--sf-surface:#0E2320;--sf-text:#E8F5F2;--sf-muted:#9CB5B1;--sf-border:#1C3B37;--sf-teal-100:#133F3A;
            --bs-body-bg:var(--sf-bg);--bs-body-color:var(--sf-text);--bs-border-color:var(--sf-border);
        }
        *{box-sizing:border-box}
        html,body{min-height:100%;margin:0;padding:0}
        body{
            font-family:'Cairo','Segoe UI',Tahoma,sans-serif;background:var(--sf-bg);color:var(--sf-text);
            background-image:radial-gradient(circle at 100% 0%,rgba(20,184,166,.08),transparent 42%),
                             radial-gradient(circle at 0% 100%,rgba(22,163,74,.08),transparent 42%);
            background-attachment:fixed;
        }
        .sf-layout{display:flex;min-height:100vh;align-items:stretch}
        .sf-sidebar{width:280px;flex-shrink:0;background:linear-gradient(180deg,var(--sf-teal-900),#062624 140%);color:#D9F2EC;
            padding:22px 16px;display:flex;flex-direction:column;border-inline-end:1px solid rgba(255,255,255,.05);
            position:sticky;top:0;height:100vh;overflow-y:auto;scrollbar-width:thin;scrollbar-color:rgba(20,184,166,.35) transparent}
        .sf-sidebar::-webkit-scrollbar{width:6px}
        .sf-sidebar::-webkit-scrollbar-thumb{background:rgba(20,184,166,.35);border-radius:10px}
        [data-bs-theme="dark"] .sf-sidebar{background:linear-gradient(180deg,#062624,#030d0c 140%)}
        .sf-brand{display:flex;align-items:center;gap:12px;padding:10px 12px 22px;text-decoration:none;color:inherit;
            border-bottom:1px solid rgba(255,255,255,.06);margin-bottom:18px}
        .sf-brand-mark{width:42px;height:42px;border-radius:12px;display:flex;align-items:center;justify-content:center;
            background:linear-gradient(135deg,var(--sf-teal-500),var(--sf-green-600));color:#fff;font-size:20px;box-shadow:0 8px 20px rgba(20,184,166,.35)}
        .sf-brand-text{display:flex;flex-direction:column;line-height:1.1}
        .sf-brand-name{font-size:17px;font-weight:800;color:#fff}
        .sf-brand-sub{font-size:11px;font-weight:600;color:rgba(217,242,236,.55);margin-top:3px;letter-spacing:.6px}
        .sf-nav{display:flex;flex-direction:column;gap:6px;flex:1}
        .sf-nav-section-toggle{width:100%;display:flex;align-items:center;justify-content:space-between;gap:10px;padding:10px 12px;
            background:transparent;border:none;color:rgba(217,242,236,.55);font-family:inherit;font-size:11.5px;font-weight:800;
            letter-spacing:1px;text-transform:uppercase;cursor:pointer;border-radius:10px;transition:color .2s ease,background-color .2s ease}
        .sf-nav-section-toggle:hover{color:#fff;background:rgba(255,255,255,.04)}
        .sf-nav-chevron{font-size:14px;transition:transform .25s ease}
        .sf-nav-section.open .sf-nav-chevron{transform:rotate(180deg)}
        .sf-nav-section-items{display:grid;grid-template-rows:0fr;transition:grid-template-rows .3s ease}
        .sf-nav-section.open .sf-nav-section-items{grid-template-rows:1fr}
        .sf-nav-section-items-inner{overflow:hidden;min-height:0;display:flex;flex-direction:column;gap:4px;padding:4px 0 6px}
        .sf-nav-btn{display:flex;align-items:center;gap:11px;padding:10px 12px;border-radius:11px;color:#D9F2EC;text-decoration:none;
            font-family:inherit;font-size:13.5px;font-weight:600;border:none;background:transparent;width:100%;text-align:start;
            cursor:pointer;transition:background-color .2s ease,color .2s ease}
        .sf-nav-btn i{font-size:16px;width:20px;text-align:center;color:rgba(217,242,236,.75)}
        .sf-nav-btn:hover{background:rgba(255,255,255,.06);color:#fff}
        .sf-nav-btn.active{background:linear-gradient(135deg,var(--sf-teal-500),var(--sf-green-600));color:#062624;font-weight:800;box-shadow:0 6px 18px rgba(20,184,166,.30)}
        .sf-nav-btn.active i{color:#062624}
        .sf-nav-btn-danger{color:#FFD5D5}
        .sf-nav-btn-danger i{color:#FFAFAF}
        .sf-nav-btn-danger:hover{background:rgba(225,29,72,.18);color:#fff}
        .sf-main-wrap{flex:1;min-width:0;display:flex;flex-direction:column}
        .sf-topbar{display:flex;align-items:center;gap:16px;padding:20px 28px;border-bottom:1px solid var(--sf-border);
            background:rgba(243,250,248,.85);backdrop-filter:saturate(140%) blur(8px);position:sticky;top:0;z-index:5}
        [data-bs-theme="dark"] .sf-topbar{background:rgba(7,24,22,.85)}
        .sf-topbar-title{flex:1;min-width:0}
        .sf-topbar-title h1{font-size:20px;font-weight:800;margin:0 0 2px;color:var(--sf-text)}
        .sf-topbar-title p{font-size:13px;color:var(--sf-muted);margin:0}
        .sf-icon-btn{width:40px;height:40px;border-radius:12px;display:inline-flex;align-items:center;justify-content:center;
            background:var(--sf-surface);border:1px solid var(--sf-border);color:var(--sf-text);cursor:pointer;font-size:17px;
            transition:background-color .2s ease,color .2s ease}
        .sf-icon-btn:hover{background:var(--sf-teal-100);color:var(--sf-teal-700)}
        .sf-main{padding:28px;flex:1}

        .sf-page-head{display:flex;flex-wrap:wrap;gap:16px;justify-content:space-between;align-items:flex-start;margin-bottom:24px}
        .sf-page-kicker{color:var(--sf-teal-700);font-size:11px;font-weight:800;letter-spacing:1.4px;display:inline-block;margin-bottom:4px}
        .sf-page-title{font-size:26px;font-weight:800;margin:0;color:var(--sf-text)}
        .sf-page-desc{color:var(--sf-muted);font-size:13.5px;margin:6px 0 0}
        .sf-stats{display:flex;gap:10px}
        .sf-stat{min-width:110px;padding:12px 18px;text-align:center;background:var(--sf-surface);border:1px solid var(--sf-border);
            border-radius:14px;box-shadow:0 5px 18px rgba(15,118,110,.06)}
        .sf-stat strong{display:block;font-size:22px;font-weight:800;color:var(--sf-teal-600);line-height:1.2}
        .sf-stat span{display:block;font-size:11.5px;color:var(--sf-muted);font-weight:600;margin-top:3px}

        .sf-btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;padding:10px 18px;border-radius:12px;
            font-family:inherit;font-size:13.5px;font-weight:700;cursor:pointer;border:1px solid transparent;text-decoration:none;
            transition:transform .18s ease,box-shadow .18s ease}
        .sf-btn-primary{color:#fff;background:linear-gradient(135deg,var(--sf-teal-700),var(--sf-green-600));box-shadow:0 8px 20px rgba(15,118,110,.22)}
        .sf-btn-primary:hover{box-shadow:0 12px 28px rgba(15,118,110,.30);transform:translateY(-1px)}
        .sf-btn-success{background:var(--sf-green-600);color:#fff}
        .sf-btn-success:hover{background:#15803d;transform:translateY(-1px)}
        .sf-btn-danger{background:var(--sf-danger);color:#fff}
        .sf-btn-danger:hover{background:#be123c;transform:translateY(-1px)}
        .sf-btn-light{background:var(--sf-surface);border-color:var(--sf-border);color:var(--sf-text)}
        .sf-btn-light:hover{background:var(--sf-teal-100);border-color:rgba(20,184,166,.35);color:var(--sf-teal-700)}
        .sf-btn-soft-teal{background:var(--sf-teal-100);border-color:rgba(20,184,166,.20);color:var(--sf-teal-700)}
        .sf-btn-soft-teal:hover{background:rgba(20,184,166,.20)}
        .sf-btn:active{transform:scale(.97)}

        .sf-alert{display:flex;align-items:center;gap:10px;padding:13px 16px;border-radius:12px;margin-bottom:22px;font-size:13.5px;font-weight:600}
        .sf-alert-success{background:rgba(22,163,74,.10);color:var(--sf-green-600);border:1px solid rgba(22,163,74,.20)}
        .sf-alert i{font-size:18px}

        .sf-tabs{display:inline-flex;padding:6px;background:var(--sf-surface);border:1px solid var(--sf-border);
            border-radius:14px;margin-bottom:20px;gap:4px;flex-wrap:wrap}
        .sf-tab-btn{display:inline-flex;align-items:center;gap:8px;border:none;background:transparent;padding:10px 18px;
            border-radius:10px;font-family:inherit;font-size:13.5px;font-weight:700;color:var(--sf-muted);cursor:pointer;
            transition:background-color .2s ease,color .2s ease}
        .sf-tab-btn:hover{color:var(--sf-teal-700)}
        .sf-tab-btn.active{background:linear-gradient(135deg,var(--sf-teal-700),var(--sf-green-600));color:#fff;font-weight:800}
        .sf-tab-badge{display:inline-flex;align-items:center;justify-content:center;min-width:24px;height:22px;padding:0 7px;
            border-radius:999px;background:rgba(0,0,0,.08);font-size:11px;font-weight:800}
        .sf-tab-btn.active .sf-tab-badge{background:rgba(255,255,255,.22)}

        .sf-card{background:var(--sf-surface);border:1px solid var(--sf-border);border-radius:18px;
            box-shadow:0 10px 30px rgba(15,118,110,.06);overflow:hidden}
        .sf-card-head{display:flex;flex-wrap:wrap;gap:14px;justify-content:space-between;align-items:center;padding:20px 22px;border-bottom:1px solid var(--sf-border)}
        .sf-card-head h2{font-size:17px;font-weight:800;margin:0;color:var(--sf-text)}
        .sf-card-head p{color:var(--sf-muted);font-size:12.5px;margin:3px 0 0}
        .sf-count-pill{display:inline-flex;align-items:center;gap:8px;padding:8px 14px;border-radius:999px;
            background:var(--sf-teal-100);color:var(--sf-teal-700);font-size:12.5px;font-weight:800}
        [data-bs-theme="dark"] .sf-count-pill{background:rgba(20,184,166,.15)}

        .sf-table-wrap{width:100%;overflow-x:auto}
        table.sf-table{width:100%;min-width:820px;border-collapse:collapse;text-align:right}
        table.sf-table th{padding:13px 18px;background:var(--sf-teal-100);color:var(--sf-teal-700);border-bottom:1px solid var(--sf-border);
            font-size:12.5px;font-weight:800;white-space:nowrap}
        [data-bs-theme="dark"] table.sf-table th{background:rgba(20,184,166,.14)}
        table.sf-table tbody tr{border-bottom:1px solid var(--sf-border);transition:background-color .18s ease}
        table.sf-table tbody tr:last-child{border-bottom:none}
        table.sf-table tbody tr:hover{background:rgba(20,184,166,.04)}
        table.sf-table td{padding:14px 18px;font-size:13.5px;vertical-align:middle}
        .sf-id-badge{display:inline-flex;align-items:center;padding:4px 9px;border-radius:8px;background:var(--sf-teal-100);
            color:var(--sf-teal-700);font-size:12px;font-weight:700}
        [data-bs-theme="dark"] .sf-id-badge{background:rgba(20,184,166,.15)}

        .sf-name-cell{display:flex;align-items:center;gap:12px}
        .sf-mini-avatar{width:38px;height:38px;border-radius:50%;display:flex;align-items:center;justify-content:center;
            background:linear-gradient(135deg,var(--sf-teal-500),var(--sf-green-600));color:#fff;font-size:14px;font-weight:800;flex-shrink:0}
        .sf-name-cell strong{color:var(--sf-text);font-weight:700}
        .sf-spec{display:inline-block;padding:5px 11px;border-radius:8px;background:var(--sf-teal-100);color:var(--sf-teal-700);
            font-size:12px;font-weight:700}
        [data-bs-theme="dark"] .sf-spec{background:rgba(20,184,166,.15)}
        .sf-exp{color:var(--sf-muted);font-weight:600}

        .sf-reject-reason{color:var(--sf-danger);font-size:13px;font-weight:600}

        .sf-empty{padding:60px 20px;text-align:center}
        .sf-empty-ic{width:64px;height:64px;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;
            background:var(--sf-teal-100);color:var(--sf-teal-700);font-size:26px}
        [data-bs-theme="dark"] .sf-empty-ic{background:rgba(20,184,166,.15)}
        .sf-empty h3{font-size:15px;font-weight:800;color:var(--sf-text);margin:0 0 4px}
        .sf-empty p{color:var(--sf-muted);font-size:12.5px;margin:0}

        /* Offcanvas */
        .offcanvas{border:none}
        .offcanvas.offcanvas-end{width:440px;max-width:100%}
        [data-bs-theme="light"] .offcanvas{background:var(--sf-surface);color:var(--sf-text)}
        [data-bs-theme="dark"]  .offcanvas{background:var(--sf-surface);color:var(--sf-text);border-inline-start:1px solid var(--sf-border)}
        .sf-offcanvas-header{display:flex;align-items:center;justify-content:space-between;gap:15px;padding:20px 22px;
            border-bottom:1px solid var(--sf-border)}
        .sf-offcanvas-header h3{margin:0;font-size:17px;font-weight:800;color:var(--sf-text)}
        .sf-offcanvas-kicker{color:var(--sf-teal-700);font-size:10px;font-weight:800;letter-spacing:1.3px;display:block;margin-bottom:3px}
        .sf-offcanvas-body{padding:22px}

        .sf-profile{text-align:center;padding-bottom:20px;border-bottom:1px dashed var(--sf-border);margin-bottom:20px}
        .sf-profile-avatar{width:82px;height:82px;border-radius:50%;display:flex;align-items:center;justify-content:center;
            margin:0 auto 12px;background:var(--sf-teal-100);color:var(--sf-teal-700);font-size:32px;
            border:3px solid var(--sf-surface);box-shadow:0 6px 20px rgba(15,118,110,.15)}
        [data-bs-theme="dark"] .sf-profile-avatar{background:rgba(20,184,166,.15)}
        .sf-profile-name{font-size:17px;font-weight:800;color:var(--sf-text);margin-bottom:3px}
        .sf-profile-label{font-size:11.5px;color:var(--sf-teal-700);font-weight:700}

        .sf-info-row{padding:12px 0;border-bottom:1px dashed var(--sf-border)}
        .sf-info-row:last-child{border-bottom:none}
        .sf-info-label{font-size:11.5px;font-weight:700;color:var(--sf-muted);margin-bottom:4px}
        .sf-info-value{font-size:13.5px;font-weight:700;color:var(--sf-text);line-height:1.7;overflow-wrap:anywhere}
        .sf-info-value.muted{color:var(--sf-muted);font-weight:500;font-size:12.5px}

        .sf-offcanvas-actions{margin-top:22px;padding-top:18px;border-top:1px solid var(--sf-border);display:flex;flex-direction:column;gap:12px}
        .sf-offcanvas-actions .sf-btn{width:100%;padding:12px}

        .sf-reject-box{background:rgba(225,29,72,.04);border:1px solid rgba(225,29,72,.20);border-radius:12px;padding:14px;margin-top:4px}
        [data-bs-theme="dark"] .sf-reject-box{background:rgba(225,29,72,.08)}
        .sf-reject-label{display:block;margin-bottom:8px;color:var(--sf-danger);font-size:12px;font-weight:700}
        .sf-reject-textarea{width:100%;min-height:80px;resize:vertical;padding:10px;border:1px solid rgba(225,29,72,.30);
            border-radius:10px;background:var(--sf-surface);color:var(--sf-text);font-family:inherit;font-size:12.5px;
            outline:none;transition:border-color .2s ease,box-shadow .2s ease}
        .sf-reject-textarea:focus{border-color:var(--sf-danger);box-shadow:0 0 0 4px rgba(225,29,72,.10)}

        html.js [data-sf-reveal]{opacity:0;transform:translateY(14px);transition:opacity .6s ease,transform .6s ease}
        html.js [data-sf-reveal].is-visible{opacity:1;transform:translateY(0)}

        @media (max-width:991.98px){
            .sf-layout{display:block}
            .sf-sidebar{position:fixed;top:0;bottom:0;inset-inline-start:0;height:100vh;z-index:1050;transform:translateX(100%);
                transition:transform .3s ease;box-shadow:-20px 0 50px rgba(0,0,0,.20)}
            .sf-sidebar.is-open{transform:translateX(0)!important}
            .sf-sidebar-backdrop{position:fixed;inset:0;background:rgba(10,30,28,.45);backdrop-filter:blur(2px);z-index:1040;
                opacity:0;visibility:hidden;transition:opacity .25s ease,visibility .25s ease}
            .sf-sidebar-backdrop.is-open{opacity:1;visibility:visible}
            .sf-main{padding:20px 16px 40px}
            .sf-topbar{padding:14px 16px}
            .sf-page-title{font-size:22px}
        }
        @media (min-width:992px){.sf-sidebar-backdrop{display:none}}
        @media (max-width:480px){
            .sf-topbar-title h1{font-size:17px}
            .sf-topbar-title p{display:none}
            .sf-stats{width:100%}
            .sf-stat{flex:1}
            .offcanvas.offcanvas-end{width:100%}
            .sf-tab-btn{padding:8px 12px;font-size:12.5px}
        }
        @media (prefers-reduced-motion:reduce){
            *,*::before,*::after{animation-duration:.001ms!important;animation-iteration-count:1!important;transition-duration:.001ms!important}
            html.js [data-sf-reveal]{opacity:1!important;transform:none!important}
        }
    </style>
</head>
<body>

<div class="sf-layout">

    {{-- Sidebar --}}
    <aside class="sf-sidebar" id="sfSidebar" aria-label="التنقل الجانبي">
        <a href="{{ route('admin.dashboard') }}" class="sf-brand">
            <span class="sf-brand-mark"><i class="bi bi-lightning-charge-fill"></i></span>
            <span class="sf-brand-text">
                <span class="sf-brand-name">SuperFit</span>
                <span class="sf-brand-sub">ADMIN PANEL</span>
            </span>
        </a>

        <nav class="sf-nav">
            <div class="sf-nav-section open">
                <button type="button" class="sf-nav-section-toggle" data-sf-section-toggle aria-expanded="true">
                    <span>نظرة عامة</span><i class="bi bi-chevron-down sf-nav-chevron"></i>
                </button>
                <div class="sf-nav-section-items"><div class="sf-nav-section-items-inner">
                    <a href="{{ route('admin.dashboard') }}" class="sf-nav-btn {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-bar-chart-line"></i><span>إحصائيات لوحة التحكم</span>
                    </a>
                </div></div>
            </div>
            <div class="sf-nav-section open">
                <button type="button" class="sf-nav-section-toggle" data-sf-section-toggle aria-expanded="true">
                    <span>الإدارة</span><i class="bi bi-chevron-down sf-nav-chevron"></i>
                </button>
                <div class="sf-nav-section-items"><div class="sf-nav-section-items-inner">
                    <a href="{{ route('admin.users-details') }}" class="sf-nav-btn {{ request()->routeIs('admin.users-details') ? 'active' : '' }}">
                        <i class="bi bi-people"></i><span>إدارة تفاصيل المستخدمين</span>
                    </a>
                    <a href="{{ route('admin.trainer.manage') }}" class="sf-nav-btn {{ request()->routeIs('admin.trainer.manage') ? 'active' : '' }}">
                        <i class="bi bi-person-badge"></i><span>إدارة المدربين</span>
                    </a>
                    <a href="{{ route('admin.coaches.requests') }}" class="sf-nav-btn {{ request()->routeIs('admin.coaches.requests') ? 'active' : '' }}">
                        <i class="bi bi-clipboard-check"></i><span>طلبات الانضمام</span>
                    </a>
                </div></div>
            </div>
            <div class="sf-nav-section open">
                <button type="button" class="sf-nav-section-toggle" data-sf-section-toggle aria-expanded="true">
                    <span>الملف الرياضي</span><i class="bi bi-chevron-down sf-nav-chevron"></i>
                </button>
                <div class="sf-nav-section-items"><div class="sf-nav-section-items-inner">
                    <a href="{{ route('admin.goals.manage') }}" class="sf-nav-btn {{ request()->routeIs('admin.goals.manage') ? 'active' : '' }}">
                        <i class="bi bi-bullseye"></i><span>الأهداف</span>
                    </a>
                    <a href="{{ route('admin.activity-level.manage') }}" class="sf-nav-btn {{ request()->routeIs('admin.activity-level.manage') ? 'active' : '' }}">
                        <i class="bi bi-activity"></i><span>مستوى النشاط</span>
                    </a>
                    <a href="{{ route('admin.health-restrictions.manage') }}" class="sf-nav-btn {{ request()->routeIs('admin.health-restrictions.manage') ? 'active' : '' }}">
                        <i class="bi bi-heart-pulse"></i><span>القيود الصحية</span>
                    </a>
                    <a href="{{ route('admin.skills.index') }}" class="sf-nav-btn {{ request()->routeIs('admin.skills.index') ? 'active' : '' }}">
                        <i class="bi bi-award"></i><span>المهارات</span>
                    </a>
                </div></div>
            </div>
            <div class="sf-nav-section open">
                <button type="button" class="sf-nav-section-toggle" data-sf-section-toggle aria-expanded="true">
                    <span>الحساب</span><i class="bi bi-chevron-down sf-nav-chevron"></i>
                </button>
                <div class="sf-nav-section-items"><div class="sf-nav-section-items-inner">
                    <a href="{{ route('admin.login') }}" class="sf-nav-btn">
                        <i class="bi bi-box-arrow-in-right"></i><span>تسجيل الدخول</span>
                    </a>
                    <form action="{{ route('admin.logout') }}" method="POST" style="display:contents">
                        @csrf
                        <button type="submit" class="sf-nav-btn sf-nav-btn-danger">
                            <i class="bi bi-box-arrow-left"></i><span>تسجيل الخروج</span>
                        </button>
                    </form>
                </div></div>
            </div>
        </nav>
    </aside>

    <div class="sf-sidebar-backdrop" id="sfSidebarBackdrop"></div>

    <div class="sf-main-wrap">
        <header class="sf-topbar">
            <button type="button" class="sf-icon-btn d-lg-none" id="sfSidebarToggle" aria-label="فتح القائمة">
                <i class="bi bi-list"></i>
            </button>
            <div class="sf-topbar-title">
                <h1>طلبات اعتماد المدربين</h1>
                <p>مراجعة طلبات المدربين وقبولها أو رفضها</p>
            </div>
            <div class="sf-topbar-actions">
                <button type="button" class="sf-icon-btn" id="sfThemeToggle" aria-label="تبديل المظهر">
                    <i class="bi bi-moon-stars" id="sfThemeIcon"></i>
                </button>
            </div>
        </header>

        <main class="sf-main">

            @if(session('success'))
                <div class="sf-alert sf-alert-success" role="alert" data-sf-autohide>
                    <i class="bi bi-check-circle-fill"></i>
                    <span>{{ session('success') }}</span>
                    <button type="button" class="btn-close ms-auto" data-sf-alert-close aria-label="إغلاق"></button>
                </div>
            @endif

            <div class="sf-page-head" data-sf-reveal>
                <div>
                    <span class="sf-page-kicker">SUPERFIT ADMIN</span>
                    <h2 class="sf-page-title">طلبات اعتماد المدربين</h2>
                    <p class="sf-page-desc">مراجعة طلبات المدربين وقبولها أو رفضها</p>
                </div>
                <div class="sf-stats">
                    <div class="sf-stat">
                        <strong>{{ $pendingCoaches->count() }}</strong>
                        <span>طلب معلق</span>
                    </div>
                    <div class="sf-stat">
                        <strong>{{ $rejectedCoaches->count() }}</strong>
                        <span>طلب مرفوض</span>
                    </div>
                </div>
            </div>

            {{-- Tabs --}}
            <div class="sf-tabs" role="tablist" data-sf-reveal>
                <button type="button" class="sf-tab-btn active" data-sf-tab="pending" role="tab" aria-selected="true">
                    <i class="bi bi-hourglass-split"></i>
                    الجديدة
                    <span class="sf-tab-badge">{{ $pendingCoaches->count() }}</span>
                </button>
                <button type="button" class="sf-tab-btn" data-sf-tab="rejected" role="tab" aria-selected="false">
                    <i class="bi bi-x-circle"></i>
                    المرفوضة
                    <span class="sf-tab-badge">{{ $rejectedCoaches->count() }}</span>
                </button>
            </div>

            {{-- ================= Pending ================= --}}
            <div class="sf-tab-pane" id="sfTabPending" data-sf-reveal>
                <div class="sf-card">
                    <div class="sf-card-head">
                        <div>
                            <h2>طلبات بانتظار التدقيق والقبول</h2>
                            <p>راجعي بيانات المدربين قبل اعتماد الحساب</p>
                        </div>
                        <span class="sf-count-pill">
                            <i class="bi bi-hourglass-split"></i>
                            <span>{{ $pendingCoaches->count() }}</span>
                        </span>
                    </div>

                    <div class="sf-table-wrap">
                        <table class="sf-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>الاسم</th>
                                    <th>التخصص</th>
                                    <th>الخبرة</th>
                                    <th style="text-align:center">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pendingCoaches as $coach)
                                    <tr>
                                        <td><span class="sf-id-badge">#{{ $coach->id }}</span></td>
                                        <td>
                                            <div class="sf-name-cell">
                                                <div class="sf-mini-avatar">{{ mb_substr($coach->name ?? 'م', 0, 1) }}</div>
                                                <strong>{{ $coach->name ?? 'مدرب جديد' }}</strong>
                                            </div>
                                        </td>
                                        <td><span class="sf-spec">{{ $coach->specialization ?? 'عام' }}</span></td>
                                        <td><span class="sf-exp">{{ $coach->experience ?? '0' }} سنوات</span></td>
                                        <td style="text-align:center">
                                            <button type="button"
                                                    class="sf-btn sf-btn-soft-teal open-coach-btn"
                                                    data-id="{{ $coach->id }}"
                                                    style="padding:8px 14px;font-size:12.5px">
                                                <i class="bi bi-search"></i>
                                                تدقيق الطلب
                                            </button>

                                            {{-- Hidden data holder for the offcanvas --}}
                                            <script type="application/json"
                                                    id="sf-coach-data-{{ $coach->id }}"
                                                    data-coach>
                                                {!! json_encode([
                                                    'id' => $coach->id,
                                                    'name' => $coach->name ?? 'مدرب جديد',
                                                    'specialization' => $coach->specialization ?? 'عام',
                                                    'experience' => $coach->experience ?? '0',
                                                    'bio' => $coach->bio ?? 'لا توجد نبذة مكتوبة.',
                                                    'approve_url' => route('admin.coaches.approve', $coach->id),
                                                    'reject_url' => route('admin.coaches.reject', $coach->id),
                                                ], JSON_UNESCAPED_UNICODE) !!}
                                            </script>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5">
                                            <div class="sf-empty">
                                                <div class="sf-empty-ic"><i class="bi bi-check-circle"></i></div>
                                                <h3>لا توجد طلبات معلقة</h3>
                                                <p>لا توجد طلبات مدربين بانتظار المراجعة حالياً.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- ================= Rejected ================= --}}
            <div class="sf-tab-pane" id="sfTabRejected" style="display:none" data-sf-reveal>
                <div class="sf-card">
                    <div class="sf-card-head">
                        <div>
                            <h2>الطلبات المرفوضة</h2>
                            <p>سجل طلبات المدربين التي تم رفضها</p>
                        </div>
                        <span class="sf-count-pill">
                            <i class="bi bi-x-circle"></i>
                            <span>{{ $rejectedCoaches->count() }}</span>
                        </span>
                    </div>

                    <div class="sf-table-wrap">
                        <table class="sf-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>الاسم</th>
                                    <th>التخصص</th>
                                    <th>سبب الرفض</th>
                                    <th>التاريخ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($rejectedCoaches as $coach)
                                    <tr>
                                        <td><span class="sf-id-badge">#{{ $coach->id }}</span></td>
                                        <td>
                                            <div class="sf-name-cell">
                                                <div class="sf-mini-avatar">
                                                    {{ mb_substr($coach->user->name ?? $coach->name ?? 'ك', 0, 1) }}
                                                </div>
                                                <strong>{{ $coach->user->name ?? $coach->name ?? 'كوتش' }}</strong>
                                            </div>
                                        </td>
                                        <td><span class="sf-spec">{{ $coach->specialization ?? '-' }}</span></td>
                                        <td><span class="sf-reject-reason">{{ $coach->rejection_reason }}</span></td>
                                        <td style="color:var(--sf-muted);font-size:12px;font-weight:600">
                                            {{ $coach->updated_at ? $coach->updated_at->format('Y-m-d') : '-' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5">
                                            <div class="sf-empty">
                                                <div class="sf-empty-ic"><i class="bi bi-check-circle"></i></div>
                                                <h3>لا توجد طلبات مرفوضة</h3>
                                                <p>لا يوجد مدربين تم رفض طلباتهم حالياً.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>

{{-- ================= Offcanvas: Coach Audit ================= --}}
<div class="offcanvas offcanvas-end" tabindex="-1" id="sfCoachOffcanvas" aria-labelledby="sfCoachOffcanvasLabel">

    <div class="sf-offcanvas-header">
        <div>
            <span class="sf-offcanvas-kicker">TRAINER APPLICATION</span>
            <h3 id="sfCoachOffcanvasLabel">تفاصيل طلب المدرب</h3>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="إغلاق"></button>
    </div>

    <div class="sf-offcanvas-body">

        <div class="sf-profile">
            <div class="sf-profile-avatar">👤</div>
            <div class="sf-profile-name" id="sfCoachName">-</div>
            <div class="sf-profile-label">طلب اعتماد مدرب</div>
        </div>

        <div class="sf-info-row">
            <div class="sf-info-label">اسم المدرب</div>
            <div class="sf-info-value" id="sfCoachName2">-</div>
        </div>
        <div class="sf-info-row">
            <div class="sf-info-label">التخصص</div>
            <div class="sf-info-value" id="sfCoachSpec">-</div>
        </div>
        <div class="sf-info-row">
            <div class="sf-info-label">سنوات الخبرة</div>
            <div class="sf-info-value" id="sfCoachExp">-</div>
        </div>
        <div class="sf-info-row">
            <div class="sf-info-label">النبذة التعريفية</div>
            <div class="sf-info-value muted" id="sfCoachBio">-</div>
        </div>

        <div class="sf-offcanvas-actions">

            <form id="sfCoachApproveForm" method="POST" action="#" style="display:contents">
                @csrf
                <button type="submit" class="sf-btn sf-btn-success">
                    <i class="bi bi-check-lg"></i>
                    قبول الطلب واعتماده
                </button>
            </form>

            <div class="sf-reject-box">
                <form id="sfCoachRejectForm" method="POST" action="#">
                    @csrf
                    <label class="sf-reject-label" for="sfCoachRejectReason">رفض الطلب مع ذكر السبب:</label>
                    <textarea name="rejection_reason"
                              id="sfCoachRejectReason"
                              rows="3"
                              required
                              class="sf-reject-textarea"
                              placeholder="اكتبي سبب عدم القبول هنا..."></textarea>
                    <button type="submit" class="sf-btn sf-btn-danger" style="width:100%;margin-top:8px">
                        <i class="bi bi-x-lg"></i>
                        تأكيد الرفض
                    </button>
                </form>
            </div>

        </div>

    </div>
</div>

{{-- Toast + Confirm --}}
<div class="toast-container position-fixed top-0 start-0 p-3" id="sfToastContainer" aria-live="polite" aria-atomic="true" style="z-index:1090"></div>

<div class="modal fade" id="sfConfirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:18px;border:1px solid var(--sf-border)">
            <div class="modal-header">
                <h5 class="modal-title">تأكيد العملية</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
            </div>
            <div class="modal-body"><p data-sf-confirm-message class="mb-0"></p></div>
            <div class="modal-footer">
                <button type="button" class="sf-btn sf-btn-light" data-sf-confirm-cancel data-bs-dismiss="modal">إلغاء</button>
                <button type="button" class="sf-btn sf-btn-danger" data-sf-confirm-ok>تأكيد</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
(function () {
    'use strict';
    document.documentElement.classList.add('js');

    // ==================================================
    // SF Utilities
    // ==================================================
    function sfCsrf(){var m=document.querySelector('meta[name="csrf-token"]');return m?m.getAttribute('content'):''}
    function sfFetchJson(url,options){
        options=options||{};
        var h=Object.assign({'Accept':'application/json','X-Requested-With':'XMLHttpRequest','X-CSRF-TOKEN':sfCsrf()},options.headers||{});
        return fetch(url,Object.assign({},options,{headers:h})).then(function(r){
            return r.text().then(function(t){var d;try{d=t?JSON.parse(t):{}}catch(e){d={raw:t}}
                if(!r.ok){var err=new Error((d&&d.message)?d.message:'HTTP '+r.status);err.status=r.status;err.data=d;throw err}
                return d;});
        });
    }
    function sfToast(msg,type){
        var c=document.getElementById('sfToastContainer');if(!c)return;
        var map={success:'success',danger:'danger',info:'info',warning:'warning'};
        var el=document.createElement('div');
        el.className='toast align-items-center text-bg-'+(map[type]||'info')+' border-0';
        el.setAttribute('role','alert');
        el.innerHTML='<div class="d-flex"><div class="toast-body">'+msg+'</div>'+
            '<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="إغلاق"></button></div>';
        c.appendChild(el);bootstrap.Toast.getOrCreateInstance(el,{delay:4000}).show();
        el.addEventListener('hidden.bs.toast',function(){el.remove()});
    }
    function sfConfirm(message){
        return new Promise(function(resolve){
            var m=document.getElementById('sfConfirmModal');
            if(!m||!window.bootstrap){resolve(window.confirm(message));return}
            var msgEl=m.querySelector('[data-sf-confirm-message]');
            var okBtn=m.querySelector('[data-sf-confirm-ok]');
            var cancelBtn=m.querySelector('[data-sf-confirm-cancel]');
            if(msgEl)msgEl.textContent=message;
            var modal=bootstrap.Modal.getOrCreateInstance(m);
            var done=false;
            function finish(v){if(done)return;done=true;modal.hide();resolve(v)}
            function onOk(){finish(true)}
            function onCancel(){finish(false)}
            if(okBtn)okBtn.addEventListener('click',onOk,{once:true});
            if(cancelBtn)cancelBtn.addEventListener('click',onCancel,{once:true});
            m.addEventListener('hidden.bs.modal',function(){
                if(okBtn)okBtn.removeEventListener('click',onOk);
                if(cancelBtn)cancelBtn.removeEventListener('click',onCancel);
                if(!done){done=true;resolve(false)}
            },{once:true});
            modal.show();
        });
    }
    function sfSetText(id,v){var el=document.getElementById(id);if(!el)return;el.textContent=(v===undefined||v===null||v==='')?'-':v;}
    function sfSetValue(id,v){var el=document.getElementById(id);if(!el)return;el.value=(v===undefined||v===null)?'':v;}
    function sfDebounce(fn,wait){var t;return function(){var c=this,a=arguments;clearTimeout(t);t=setTimeout(function(){fn.apply(c,a)},wait||150);};}
    function sfSetLoading(b,i,txt){if(!b)return;if(i){if(!b.dataset.sfOrig)b.dataset.sfOrig=b.innerHTML;b.disabled=true;b.innerHTML='<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>'+(txt?'<span class="ms-2">'+txt+'</span>':'');}else{b.disabled=false;if(b.dataset.sfOrig)b.innerHTML=b.dataset.sfOrig;}}
    function sfReveal(){
        if(!('IntersectionObserver' in window)){document.querySelectorAll('[data-sf-reveal]').forEach(function(el){el.classList.add('is-visible')});return;}
        var o=new IntersectionObserver(function(es,obs){es.forEach(function(e){if(e.isIntersecting){e.target.classList.add('is-visible');obs.unobserve(e.target)}})},{threshold:.08,rootMargin:'0px 0px -40px 0px'});
        document.querySelectorAll('[data-sf-reveal]').forEach(function(el){o.observe(el)});
    }
    function sfTheme(){
        var t=document.getElementById('sfThemeToggle'),ic=document.getElementById('sfThemeIcon');
        function apply(x){document.documentElement.setAttribute('data-bs-theme',x);try{localStorage.setItem('sf-theme',x)}catch(e){}if(ic)ic.className=x==='dark'?'bi bi-sun':'bi bi-moon-stars';}
        function cur(){return document.documentElement.getAttribute('data-bs-theme')==='dark'?'dark':'light';}
        if(ic)ic.className=cur()==='dark'?'bi bi-sun':'bi bi-moon-stars';
        if(t)t.addEventListener('click',function(){apply(cur()==='dark'?'light':'dark')});
    }
    function sfPreventDoubleSubmit(){
        document.querySelectorAll('form').forEach(function(f){
            f.addEventListener('submit',function(e){
                if(f.dataset.sfSubmitting==='1'){e.preventDefault();return;}
                f.dataset.sfSubmitting='1';
                var b=f.querySelector('button[type="submit"]');
                if(b&&!b.disabled){sfSetLoading(b,true);setTimeout(function(){f.dataset.sfSubmitting='0';sfSetLoading(b,false);},3000);}
                else{setTimeout(function(){f.dataset.sfSubmitting='0';},3000);}
            });
        });
    }
    function sfSidebar(){
        var t=document.getElementById('sfSidebarToggle'),s=document.getElementById('sfSidebar'),b=document.getElementById('sfSidebarBackdrop');
        function close(){if(!s)return;s.classList.remove('is-open');if(b)b.classList.remove('is-open');}
        function open(){if(!s)return;s.classList.add('is-open');if(b)b.classList.add('is-open');}
        if(t)t.addEventListener('click',function(){s&&s.classList.contains('is-open')?close():open();});
        if(b)b.addEventListener('click',close);
        window.addEventListener('keydown',function(e){if(e.key==='Escape')close();});
    }
    function sfSidebarSections(){
        document.querySelectorAll('[data-sf-section-toggle]').forEach(function(btn){
            btn.addEventListener('click',function(){
                var sec=btn.closest('.sf-nav-section');if(!sec)return;
                sec.classList.toggle('open');
                btn.setAttribute('aria-expanded',sec.classList.contains('open')?'true':'false');
            });
        });
    }
    function sfInterceptConfirmForms(){
        document.querySelectorAll('form[onsubmit]').forEach(function(f){
            var a=f.getAttribute('onsubmit')||'';
            if(a.indexOf('confirm(')===-1)return;
            var m=a.match(/confirm\((['"])([\s\S]*?)\1\)/);if(!m)return;
            var msg=m[2];f.removeAttribute('onsubmit');
            f.addEventListener('submit',function(e){
                if(f.dataset.sfConfirmed==='1')return;
                e.preventDefault();
                sfConfirm(msg).then(function(ok){if(ok){f.dataset.sfConfirmed='1';f.submit();}});
            });
        });
    }
    function sfAutohideAlerts(){
        document.querySelectorAll('[data-sf-autohide]').forEach(function(el){
            setTimeout(function(){el.style.transition='opacity .4s ease, transform .4s ease';el.style.opacity='0';el.style.transform='translateY(-6px)';setTimeout(function(){el.remove();},450);},4000);
        });
    }
    function sfAlertClose(){
        document.querySelectorAll('[data-sf-alert-close]').forEach(function(btn){
            btn.addEventListener('click',function(){
                var a=btn.closest('.sf-alert');if(!a)return;
                a.style.transition='opacity .3s ease';a.style.opacity='0';setTimeout(function(){a.remove();},320);
            });
        });
    }

    // ==================================================
    // Coach Offcanvas Logic
    // ==================================================
    var offcanvasEl = document.getElementById('sfCoachOffcanvas');
    var approveForm = document.getElementById('sfCoachApproveForm');
    var rejectForm = document.getElementById('sfCoachRejectForm');

    function showCoach(id){
        var dataEl = document.getElementById('sf-coach-data-'+id);
        if(!dataEl || !offcanvasEl) return;
        var data;
        try { data = JSON.parse(dataEl.textContent); } catch(e){ return; }

        sfSetText('sfCoachName', data.name);
        sfSetText('sfCoachName2', data.name);
        sfSetText('sfCoachSpec', data.specialization);
        sfSetText('sfCoachExp', (data.experience || '0') + ' سنوات');
        sfSetText('sfCoachBio', data.bio);

        if(approveForm) approveForm.action = data.approve_url;
        if(rejectForm) rejectForm.action = data.reject_url;

        var reasonEl = document.getElementById('sfCoachRejectReason');
        if(reasonEl) reasonEl.value = '';

        bootstrap.Offcanvas.getOrCreateInstance(offcanvasEl).show();
    }

    document.addEventListener('click', function(e){
        var btn = e.target.closest('.open-coach-btn');
        if(!btn) return;
        var id = btn.getAttribute('data-id');
        if(id) showCoach(id);
    });

    // ==================================================
    // Tabs
    // ==================================================
    function switchTab(name){
        document.querySelectorAll('.sf-tab-btn').forEach(function(b){
            var isActive = b.getAttribute('data-sf-tab') === name;
            b.classList.toggle('active', isActive);
            b.setAttribute('aria-selected', isActive ? 'true' : 'false');
        });
        var pending = document.getElementById('sfTabPending');
        var rejected = document.getElementById('sfTabRejected');
        if(pending) pending.style.display = (name === 'pending') ? '' : 'none';
        if(rejected) rejected.style.display = (name === 'rejected') ? '' : 'none';
    }

    document.querySelectorAll('.sf-tab-btn').forEach(function(btn){
        btn.addEventListener('click', function(){
            switchTab(btn.getAttribute('data-sf-tab'));
        });
    });

    // ==================================================
    // Init
    // ==================================================
    document.addEventListener('DOMContentLoaded', function(){
        sfReveal();
        sfTheme();
        sfSidebar();
        sfSidebarSections();
        sfPreventDoubleSubmit();
        sfInterceptConfirmForms();
        sfAutohideAlerts();
        sfAlertClose();
    });
})();
</script>

</body>
</html>
