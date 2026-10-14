<!DOCTYPE html>
<html lang="ar" dir="rtl" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>إدارة تفاصيل المستخدمين — SuperFit</title>

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
        .sf-page-actions{display:flex;flex-wrap:wrap;gap:10px}

        .sf-btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;padding:10px 18px;border-radius:12px;
            font-family:inherit;font-size:13.5px;font-weight:700;cursor:pointer;border:1px solid transparent;text-decoration:none;
            transition:transform .18s ease,box-shadow .18s ease}
        .sf-btn-light{background:var(--sf-surface);border-color:var(--sf-border);color:var(--sf-text)}
        .sf-btn-light:hover{background:var(--sf-teal-100);border-color:rgba(20,184,166,.35);color:var(--sf-teal-700)}
        .sf-btn-primary{color:#fff;background:linear-gradient(135deg,var(--sf-teal-700),var(--sf-green-600));box-shadow:0 8px 20px rgba(15,118,110,.22)}
        .sf-btn-primary:hover{box-shadow:0 12px 28px rgba(15,118,110,.30);transform:translateY(-1px)}
        .sf-btn-danger{background:var(--sf-danger);color:#fff}
        .sf-btn-danger:hover{background:#be123c;transform:translateY(-1px)}
        .sf-btn-soft-teal{background:var(--sf-teal-100);border-color:rgba(20,184,166,.20);color:var(--sf-teal-700)}
        .sf-btn-soft-teal:hover{background:rgba(20,184,166,.20)}
        .sf-btn-soft-danger{background:rgba(225,29,72,.08);border-color:rgba(225,29,72,.20);color:var(--sf-danger)}
        .sf-btn-soft-danger:hover{background:rgba(225,29,72,.14)}
        .sf-btn:active{transform:scale(.97)}

        .sf-alert{display:flex;align-items:center;gap:10px;padding:13px 16px;border-radius:12px;margin-bottom:22px;font-size:13.5px;font-weight:600}
        .sf-alert-success{background:rgba(22,163,74,.10);color:var(--sf-green-600);border:1px solid rgba(22,163,74,.20)}
        .sf-alert-danger{background:rgba(225,29,72,.08);color:var(--sf-danger);border:1px solid rgba(225,29,72,.20)}
        .sf-alert i{font-size:18px}

        .sf-card{background:var(--sf-surface);border:1px solid var(--sf-border);border-radius:18px;
            box-shadow:0 10px 30px rgba(15,118,110,.06);overflow:hidden}

        .sf-toolbar{display:flex;flex-wrap:wrap;gap:12px;margin-bottom:20px;padding:16px 20px;background:var(--sf-surface);
            border:1px solid var(--sf-border);border-radius:16px;align-items:center}
        .sf-toolbar .sf-search-wrap{position:relative;flex:1;min-width:220px;max-width:420px}
        .sf-toolbar .sf-search-wrap i{position:absolute;inset-inline-start:14px;top:50%;transform:translateY(-50%);color:var(--sf-muted);font-size:16px;pointer-events:none}
        .sf-search{width:100%;height:42px;padding:0 44px;border-radius:12px;border:1px solid var(--sf-border);background:var(--sf-bg);
            color:var(--sf-text);font-family:inherit;font-size:13.5px;outline:none;transition:border-color .2s ease,box-shadow .2s ease}
        .sf-search:focus{border-color:var(--sf-teal-700);box-shadow:0 0 0 4px rgba(15,118,110,.10);background:var(--sf-surface)}
        .sf-filter-select{height:42px;padding:0 36px 0 14px;border-radius:12px;border:1px solid var(--sf-border);background:var(--sf-bg);
            color:var(--sf-text);font-family:inherit;font-size:13px;cursor:pointer;outline:none;
            background-image:url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%235B7B7A'><path d='M4.5 6l3.5 4 3.5-4z'/></svg>");
            background-repeat:no-repeat;background-position:left 10px center;background-size:14px;appearance:none}
        .sf-filter-select:focus{border-color:var(--sf-teal-700);box-shadow:0 0 0 4px rgba(15,118,110,.10)}

        .sf-table-wrap{width:100%;overflow-x:auto}
        table.sf-table{width:100%;min-width:1100px;border-collapse:collapse;text-align:right}
        table.sf-table th{padding:13px 16px;background:var(--sf-teal-100);color:var(--sf-teal-700);border-bottom:1px solid var(--sf-border);
            font-size:12px;font-weight:800;white-space:nowrap}
        [data-bs-theme="dark"] table.sf-table th{background:rgba(20,184,166,.14)}
        table.sf-table tbody tr{border-bottom:1px solid var(--sf-border);transition:background-color .18s ease}
        table.sf-table tbody tr:last-child{border-bottom:none}
        table.sf-table tbody tr:hover{background:rgba(20,184,166,.04)}
        table.sf-table td{padding:12px 16px;font-size:13px;vertical-align:middle}
        .sf-id-badge{display:inline-flex;align-items:center;padding:3px 8px;border-radius:8px;background:var(--sf-teal-100);
            color:var(--sf-teal-700);font-size:11.5px;font-weight:700}
        [data-bs-theme="dark"] .sf-id-badge{background:rgba(20,184,166,.15)}

        .sf-avatar{width:40px;height:40px;border-radius:50%;display:flex;align-items:center;justify-content:center;
            background:linear-gradient(135deg,var(--sf-teal-500),var(--sf-green-600));color:#fff;font-size:15px;font-weight:800;overflow:hidden}
        .sf-avatar img{width:100%;height:100%;object-fit:cover}
        .sf-avatar i{font-size:20px}

        .sf-badge{display:inline-flex;align-items:center;gap:5px;padding:4px 10px;border-radius:999px;font-size:11.5px;font-weight:800}
        .sf-badge-success{background:rgba(22,163,74,.12);color:var(--sf-green-600)}
        .sf-badge-danger{background:rgba(225,29,72,.12);color:var(--sf-danger)}
        .sf-badge-info{background:rgba(14,165,233,.12);color:var(--sf-info)}
        .sf-badge-warning{background:rgba(245,158,11,.14);color:#B45309}
        .sf-badge-teal{background:var(--sf-teal-100);color:var(--sf-teal-700)}
        [data-bs-theme="dark"] .sf-badge-teal{background:rgba(20,184,166,.15)}

        .sf-tbl-actions{display:flex;justify-content:center;gap:6px;flex-wrap:nowrap}
        .sf-tbl-actions .sf-btn{padding:6px 11px;font-size:11.5px;border-radius:9px}

        .sf-empty{padding:60px 20px;text-align:center}
        .sf-empty h3{font-size:15px;font-weight:800;color:var(--sf-text);margin:0 0 4px}
        .sf-empty p{color:var(--sf-muted);font-size:12.5px;margin:0}

        .sf-pagination{padding:16px 22px;background:rgba(243,250,248,.6);border-top:1px solid var(--sf-border)}
        [data-bs-theme="dark"] .sf-pagination{background:rgba(7,24,22,.4)}
        .sf-pagination .pagination{margin:0;gap:4px;justify-content:center}
        .sf-pagination .page-link{border:1px solid var(--sf-border);color:var(--sf-text);border-radius:10px;padding:6px 12px;background:var(--sf-surface)}
        .sf-pagination .page-link:hover{background:var(--sf-teal-100);color:var(--sf-teal-700)}
        .sf-pagination .page-item.active .page-link{background:linear-gradient(135deg,var(--sf-teal-700),var(--sf-green-600));border-color:transparent;color:#fff}

        /* Modals */
        .modal-content{border-radius:18px;border:1px solid var(--sf-border);background:var(--sf-surface)}
        .modal-header{border-bottom:1px solid var(--sf-border);padding:18px 22px}
        .modal-header h3,.modal-header h5{margin:0;font-size:16px;font-weight:800;color:var(--sf-text)}
        .sf-modal-kicker{color:var(--sf-teal-700);font-size:10px;font-weight:800;letter-spacing:1.2px;display:block;margin-bottom:3px}
        .modal-body{padding:22px}
        .modal-footer{border-top:1px solid var(--sf-border);padding:14px 22px;display:flex;gap:8px}

        .sf-form-group{margin-bottom:16px}
        .sf-form-label{display:block;font-size:13px;font-weight:700;color:var(--sf-text);margin-bottom:7px}
        .sf-form-control,select.sf-form-control,textarea.sf-form-control{width:100%;padding:10px 13px;border-radius:11px;
            border:1px solid var(--sf-border);background:var(--sf-bg);color:var(--sf-text);font-family:inherit;font-size:13px;outline:none;
            transition:border-color .2s ease,box-shadow .2s ease}
        .sf-form-control:focus{border-color:var(--sf-teal-700);box-shadow:0 0 0 4px rgba(15,118,110,.10);background:var(--sf-surface)}
        textarea.sf-form-control{min-height:70px;resize:vertical}

        .sf-info-card{background:var(--sf-bg);border:1px solid var(--sf-border);border-radius:14px;padding:16px}
        .sf-info-card h4{font-size:13px;font-weight:800;color:var(--sf-teal-700);margin:0 0 10px;
            padding-bottom:8px;border-bottom:1px dashed var(--sf-border)}
        .sf-info-card p{margin:0 0 8px;font-size:13px;color:var(--sf-text);line-height:1.7}
        .sf-info-card p:last-child{margin-bottom:0}
        .sf-info-card strong{color:var(--sf-muted);font-weight:700;font-size:12px;margin-inline-end:6px}

        .sf-cards-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:14px}
        .sf-form-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:14px}

        /* Tabs */
        .sf-tabs{display:flex;gap:4px;border-bottom:1px solid var(--sf-border);margin-bottom:20px;flex-wrap:wrap}
        .sf-tab{padding:10px 16px;border:none;background:transparent;color:var(--sf-muted);font-family:inherit;font-size:13px;
            font-weight:700;cursor:pointer;border-bottom:2px solid transparent;transition:color .2s ease,border-color .2s ease}
        .sf-tab:hover{color:var(--sf-teal-700)}
        .sf-tab.active{color:var(--sf-teal-700);border-bottom-color:var(--sf-teal-700)}
        .sf-tab-pane{display:none}
        .sf-tab-pane.active{display:block}

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
            .sf-page-actions{width:100%}
            .sf-page-actions .sf-btn{flex:1;justify-content:center}
            .sf-toolbar{padding:12px}
            .sf-toolbar .sf-search-wrap{max-width:none}
        }
        @media (prefers-reduced-motion:reduce){
            *,*::before,*::after{animation-duration:.001ms!important;animation-iteration-count:1!important;transition-duration:.001ms!important}
            html.js [data-sf-reveal]{opacity:1!important;transform:none!important}
        }
    </style>
</head>
<body>

<div class="sf-layout">

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
                <h1>إدارة تفاصيل المستخدمين</h1>
                <p>عرض ومتابعة بيانات المشتركين والمدربين في نظام SuperFit</p>
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

            @if($errors->any())
                <div class="sf-alert sf-alert-danger" role="alert">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <div>@foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach</div>
                    <button type="button" class="btn-close ms-auto" data-sf-alert-close aria-label="إغلاق"></button>
                </div>
            @endif

            <div class="sf-page-head" data-sf-reveal>
                <div>
                    <span class="sf-page-kicker">SUPERFIT ADMIN</span>
                    <h2 class="sf-page-title">إدارة تفاصيل المستخدمين</h2>
                    <p class="sf-page-desc">عرض ومتابعة بيانات المشتركين والمدربين في نظام SuperFit</p>
                </div>
                <div class="sf-page-actions">
                    <a href="{{ route('admin.dashboard') }}" class="sf-btn sf-btn-light">
                        <i class="bi bi-arrow-right"></i>
                        العودة للوحة التحكم
                    </a>
                    <button type="button" class="sf-btn sf-btn-primary" onclick="openAddUserModal()">
                        <i class="bi bi-plus-lg"></i>
                        إضافة مستخدم جديد
                    </button>
                </div>
            </div>

            {{-- Toolbar --}}
            <div class="sf-toolbar" data-sf-reveal>
                <div class="sf-search-wrap">
                    <i class="bi bi-search"></i>
                    <input type="text" id="sfUsersSearch" class="sf-search" placeholder="ابحث بالاسم أو البريد الإلكتروني..." autocomplete="off">
                </div>
                <select id="sfFilterRole" class="sf-filter-select" aria-label="نوع الحساب">
                    <option value="">كل الأنواع</option>
                    <option value="user">مستخدم</option>
                    <option value="coach">مدرب</option>
                </select>
                <select id="sfFilterVerified" class="sf-filter-select" aria-label="حالة التوثيق">
                    <option value="">كل الحالات</option>
                    <option value="1">موثق</option>
                    <option value="0">غير موثق</option>
                </select>
            </div>

            <div class="sf-card" data-sf-reveal>
                <div class="sf-table-wrap">
                    <table class="sf-table" id="usersTable">
                        <thead>
                            <tr>
                                <th>الصورة</th>
                                <th>الاسم الكامل</th>
                                <th>البريد الإلكتروني</th>
                                <th>نوع الحساب</th>
                                <th>الجنس</th>
                                <th>الهدف</th>
                                <th>مستوى النشاط</th>
                                <th>حالة التوثيق</th>
                                <th>تاريخ التسجيل</th>
                                <th style="text-align:center">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody id="usersTableBody">
                            @forelse($users as $user)
                                <tr
                                    data-id="{{ $user->id }}"
                                    data-name="{{ $user->full_name }}"
                                    data-email="{{ $user->email }}"
                                    data-role="{{ strtolower($user->role) }}"
                                    data-verified="{{ $user->email_verified_at ? 'موثق' : 'غير موثق' }}"
                                    data-verified-date="{{ $user->email_verified_at ? $user->email_verified_at->format('Y-m-d') : '-' }}"
                                    data-gender="{{ $user->profile?->gender ?? '' }}"
                                    data-birthdate="{{ $user->profile?->date_of_birth ?? '' }}"
                                    data-height="{{ $user->profile?->height ?? '' }}"
                                    data-weight="{{ $user->profile?->weight ?? '' }}"
                                    data-goal-id="{{ $user->profile?->goal_id }}"
                                    data-goal="{{ $user->profile?->goal?->title ?? 'غير محدد' }}"
                                    data-activity-id="{{ $user->profile?->activity_level_id }}"
                                    data-activity="{{ $user->profile?->activityLevel?->title ?? 'غير محدد' }}"
                                    data-place="{{ $user->profile?->trainingLocation?->name ?? 'غير محدد' }}"
                                    data-coachtype="{{ $user->profile?->trainer_type ?? 'AI' }}"
                                    data-days="{{ is_array($user->profile?->available_days) ? implode(', ', $user->profile->available_days) : ($user->profile?->available_days ?? '') }}"
                                    data-health="{{ $user->profile?->health_condition_note ?? '' }}"
                                    data-diet="{{ $user->profile?->dietary_restriction_note ?? '' }}"
                                    data-disclaimer="{{ $user->profile?->disclaimer_accepted ? 'نعم' : 'لا' }}"
                                    data-created="{{ $user->created_at->format('Y-m-d H:i A') }}"
                                    data-updated="{{ $user->updated_at->format('Y-m-d H:i A') }}"
                                    data-notes="{{ $user->profile?->notes ?? '' }}">

                                    <td>
                                        <div class="sf-avatar">
                                            @if($user->profile?->profile_photo)
                                                <img src="{{ asset('storage/' . $user->profile->profile_photo) }}" alt="{{ $user->full_name }}">
                                            @else
                                                <i class="bi bi-person"></i>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="cell-name"><strong>{{ $user->full_name }}</strong></td>
                                    <td class="cell-email" style="color:var(--sf-muted)">{{ $user->email }}</td>
                                    <td class="cell-role">
                                        @if(strtolower($user->role) === 'coach')
                                            <span class="sf-badge sf-badge-info">Coach</span>
                                        @else
                                            <span class="sf-badge sf-badge-teal">User</span>
                                        @endif
                                    </td>
                                    <td class="cell-gender">{{ $user->profile?->gender ?? '-' }}</td>
                                    <td class="cell-goal">{{ $user->profile?->goal?->title ?? '-' }}</td>
                                    <td class="cell-activity">{{ $user->profile?->activityLevel?->title ?? '-' }}</td>
                                    <td class="cell-verified">
                                        @if($user->email_verified_at)
                                            <span class="sf-badge sf-badge-success"><i class="bi bi-check-circle-fill"></i>موثق</span>
                                        @else
                                            <span class="sf-badge sf-badge-danger"><i class="bi bi-x-circle-fill"></i>غير موثق</span>
                                        @endif
                                    </td>
                                    <td class="cell-created" style="color:var(--sf-muted)">{{ $user->created_at->format('Y-m-d') }}</td>
                                    <td>
                                        <div class="sf-tbl-actions">
                                            <button type="button" class="sf-btn sf-btn-soft-teal" onclick="openDetailsModal(this)">
                                                <i class="bi bi-eye"></i>
                                                التفاصيل
                                            </button>
                                            <button type="button" class="sf-btn sf-btn-soft-teal" onclick="openEditModal(this)">
                                                <i class="bi bi-pencil"></i>
                                                تعديل
                                            </button>
                                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display:inline"
                                                  onsubmit="return confirm('هل أنت متأكد من حذف هذا المستخدم؟')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="sf-btn sf-btn-soft-danger">
                                                    <i class="bi bi-trash"></i>
                                                    حذف
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10">
                                        <div class="sf-empty">
                                            <h3>لا يوجد مستخدمين مسجلين حالياً.</h3>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if(isset($users) && method_exists($users, 'links'))
                    <div class="sf-pagination">{{ $users->links() }}</div>
                @endif
            </div>

        </main>
    </div>
</div>

{{-- ============ Details Modal ============ --}}
<div class="modal fade" id="userDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <span class="sf-modal-kicker">USER PROFILE</span>
                    <h3>تفاصيل المستخدم</h3>
                </div>
                <button type="button" class="btn-close" onclick="closeDetailsModal()" aria-label="إغلاق"></button>
            </div>

            <div class="modal-body">

                <div class="sf-tabs" role="tablist">
                    <button type="button" class="sf-tab active" data-sf-tab="tab-personal" role="tab">البيانات الشخصية</button>
                    <button type="button" class="sf-tab" data-sf-tab="tab-activity" role="tab">النشاط والأهداف</button>
                    <button type="button" class="sf-tab" data-sf-tab="tab-health" role="tab">التفضيلات والصحة</button>
                </div>

                <div class="sf-tab-pane active" id="tab-personal">
                    <div class="sf-cards-grid">
                        <div class="sf-info-card">
                            <h4>المعلومات الأساسية</h4>
                            <p><strong>الاسم:</strong> <span id="viewName">-</span></p>
                            <p><strong>البريد:</strong> <span id="viewEmail">-</span></p>
                            <p><strong>نوع الحساب:</strong> <span id="viewRole">-</span></p>
                            <p><strong>توثيق البريد:</strong> <span id="viewVerified">-</span> (<span id="viewVerifiedDate">-</span>)</p>
                        </div>
                        <div class="sf-info-card">
                            <h4>المعلومات الشخصية</h4>
                            <p><strong>الجنس:</strong> <span id="viewGender">-</span></p>
                            <p><strong>تاريخ الميلاد:</strong> <span id="viewBirthdate">-</span></p>
                            <p><strong>الطول:</strong> <span id="viewHeight">-</span> سم</p>
                            <p><strong>الوزن:</strong> <span id="viewWeight">-</span> كغ</p>
                        </div>
                    </div>
                </div>

                <div class="sf-tab-pane" id="tab-activity">
                    <div class="sf-cards-grid">
                        <div class="sf-info-card">
                            <h4>الهدف والنشاط</h4>
                            <p><strong>الهدف:</strong> <span id="viewGoal">-</span></p>
                            <p><strong>مستوى النشاط:</strong> <span id="viewActivity">-</span></p>
                            <p><strong>مكان التدريب:</strong> <span id="viewPlace">-</span></p>
                        </div>
                        <div class="sf-info-card">
                            <h4>تفاصيل التدريب</h4>
                            <p><strong>نوع المدرب:</strong> <span id="viewCoachType">-</span></p>
                            <p><strong>أيام التدريب:</strong> <span id="viewDays">-</span></p>
                        </div>
                        <div class="sf-info-card">
                            <h4>معلومات الحساب والتواريخ</h4>
                            <p><strong>تاريخ الإنشاء:</strong> <span id="viewCreated">-</span></p>
                            <p><strong>آخر تحديث:</strong> <span id="viewUpdated">-</span></p>
                        </div>
                    </div>
                </div>

                <div class="sf-tab-pane" id="tab-health">
                    <div class="sf-cards-grid">
                        <div class="sf-info-card">
                            <h4>البيانات الصحية والغذائية</h4>
                            <p><strong>الحالات الصحية:</strong> <span id="viewHealth">-</span></p>
                            <p><strong>القيود الغذائية:</strong> <span id="viewDiet">-</span></p>
                            <p><strong>إخلاء المسؤولية:</strong> <span id="viewDisclaimer">-</span></p>
                        </div>
                        <div class="sf-info-card">
                            <h4>تفاصيل إضافية</h4>
                            <p><span id="viewNotes">لا توجد ملاحظات إضافية</span></p>
                        </div>
                    </div>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="sf-btn sf-btn-light" onclick="closeDetailsModal()">إغلاق</button>
            </div>
        </div>
    </div>
</div>

{{-- ============ Add/Edit Modal ============ --}}
<div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <span class="sf-modal-kicker">USER MANAGER</span>
                    <h3 id="modalTitle">إضافة مستخدم جديد</h3>
                </div>
                <button type="button" class="btn-close" onclick="closeAddUserModal()" aria-label="إغلاق"></button>
            </div>

            <form id="addUserForm" method="POST" action="{{ route('admin.users.store') }}" enctype="multipart/form-data" novalidate>
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">

                <div class="modal-body">
                    <div class="sf-form-grid">

                        <div class="sf-info-card">
                            <h4>المعلومات الأساسية</h4>
                            <div class="sf-form-group">
                                <label class="sf-form-label">الاسم الكامل <span style="color:var(--sf-danger)">*</span></label>
                                <input type="text" name="full_name" id="newUserName" class="sf-form-control" placeholder="أدخل الاسم" required>
                            </div>
                            <div class="sf-form-group">
                                <label class="sf-form-label">البريد الإلكتروني <span style="color:var(--sf-danger)">*</span></label>
                                <input type="email" name="email" id="newUserEmail" class="sf-form-control" placeholder="example@domain.com" required>
                            </div>
                            <div class="sf-form-group">
                                <label class="sf-form-label">كلمة المرور</label>
                                <input type="password" name="password" id="newUserPassword" class="sf-form-control" placeholder="اتركه فارغاً في حال عدم التغيير">
                            </div>
                            <div class="sf-form-group">
                                <label class="sf-form-label">نوع الحساب <span style="color:var(--sf-danger)">*</span></label>
                                <select name="role" id="newUserRole" class="sf-form-control" required>
                                    <option value="user">User</option>
                                    <option value="coach">Coach</option>
                                </select>
                            </div>
                            <div class="sf-form-group">
                                <label class="sf-form-label">صورة الملف الشخصي</label>
                                <input type="file" name="profile_photo" id="newUserPhoto" class="sf-form-control" accept="image/*">
                            </div>
                        </div>

                        <div class="sf-info-card">
                            <h4>المعلومات الشخصية</h4>
                            <div class="sf-form-group">
                                <label class="sf-form-label">الجنس</label>
                                <select name="gender" id="newUserGender" class="sf-form-control">
                                    <option value="">اختر الجنس</option>
                                    <option value="ذكر">ذكر</option>
                                    <option value="أنثى">أنثى</option>
                                </select>
                            </div>
                            <div class="sf-form-group">
                                <label class="sf-form-label">تاريخ الميلاد</label>
                                <input type="date" name="date_of_birth" id="newUserBirthdate" class="sf-form-control">
                            </div>
                        </div>

                        <div class="sf-info-card">
                            <h4>البيانات الجسمانية</h4>
                            <div class="sf-form-group">
                                <label class="sf-form-label">الطول (سم)</label>
                                <input type="number" step="0.1" name="height" id="newUserHeight" class="sf-form-control" placeholder="175">
                            </div>
                            <div class="sf-form-group">
                                <label class="sf-form-label">الوزن (كغ)</label>
                                <input type="number" step="0.1" name="weight" id="newUserWeight" class="sf-form-control" placeholder="78">
                            </div>
                        </div>

                        <div class="sf-info-card">
                            <h4>بيانات التدريب والهدف</h4>
                            <div class="sf-form-group">
                                <label class="sf-form-label">الهدف الرياضي</label>
                                <select name="goal_id" id="newUserGoal" class="sf-form-control">
                                    <option value="">اختر الهدف</option>
                                    @foreach($goals as $goal)
                                        <option value="{{ $goal->id }}">{{ $goal->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="sf-form-group">
                                <label class="sf-form-label">مستوى النشاط</label>
                                <select name="activity_level_id" id="newUserActivity" class="sf-form-control">
                                    <option value="">اختر مستوى النشاط</option>
                                    @foreach($activityLevels as $level)
                                        <option value="{{ $level->id }}">{{ $level->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="sf-form-group">
                                <label class="sf-form-label">نوع المدرب</label>
                                <select name="trainer_type" id="newUserCoachType" class="sf-form-control">
                                    <option value="ai">AI</option>
                                    <option value="human">بشري</option>
                                </select>
                            </div>
                            <div class="sf-form-group">
                                <label class="sf-form-label">أيام التدريب المتاحة</label>
                                <input type="text" name="available_days" id="newUserDays" class="sf-form-control" placeholder="مثال: السبت، الإثنين، الأربعاء">
                            </div>
                        </div>

                        <div class="sf-info-card">
                            <h4>البيانات الصحية والغذائية</h4>
                            <div class="sf-form-group">
                                <label class="sf-form-label">الحالات الصحية</label>
                                <textarea name="health_condition_note" id="newUserHealth" class="sf-form-control" rows="2" placeholder="أدخل الملاحظات الصحية إن وجدت"></textarea>
                            </div>
                            <div class="sf-form-group">
                                <label class="sf-form-label">القيود الغذائية</label>
                                <input type="text" name="dietary_restriction_note" id="newUserDiet" class="sf-form-control" placeholder="مثال: حساسية جلوتين">
                            </div>
                        </div>

                        <div class="sf-info-card">
                            <h4>ملاحظات وتفاصيل إضافية</h4>
                            <div class="sf-form-group">
                                <label class="sf-form-label">ملاحظات إضافية</label>
                                <textarea name="notes" id="newUserNotes" class="sf-form-control" rows="3" placeholder="أضف أي تفاصيل أخرى هنا"></textarea>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="sf-btn sf-btn-light" onclick="closeAddUserModal()">إلغاء</button>
                    <button type="submit" class="sf-btn sf-btn-primary" id="saveBtn">
                        <i class="bi bi-check-lg"></i>
                        حفظ البيانات
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Toast + Confirm --}}
<div class="toast-container position-fixed top-0 start-0 p-3" id="sfToastContainer" aria-live="polite" aria-atomic="true" style="z-index:1090"></div>

<div class="modal fade" id="sfConfirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5>تأكيد العملية</h5>
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
    // Modal element refs
    // ==================================================
    var modalEl        = document.getElementById('addUserModal');
    var detailsModalEl = document.getElementById('userDetailsModal');
    var form           = document.getElementById('addUserForm');
    var modalTitle     = document.getElementById('modalTitle');
    var formMethod     = document.getElementById('formMethod');
    var saveBtn        = document.getElementById('saveBtn');

    var nameInput      = document.getElementById('newUserName');
    var emailInput     = document.getElementById('newUserEmail');
    var passwordInput  = document.getElementById('newUserPassword');
    var roleInput      = document.getElementById('newUserRole');
    var genderInput    = document.getElementById('newUserGender');
    var birthdateInput = document.getElementById('newUserBirthdate');
    var heightInput    = document.getElementById('newUserHeight');
    var weightInput    = document.getElementById('newUserWeight');
    var goalInput      = document.getElementById('newUserGoal');
    var activityInput  = document.getElementById('newUserActivity');
    var coachTypeInput = document.getElementById('newUserCoachType');
    var daysInput      = document.getElementById('newUserDays');
    var healthInput    = document.getElementById('newUserHealth');
    var dietInput      = document.getElementById('newUserDiet');
    var notesInput     = document.getElementById('newUserNotes');

    function getModal(el){ return el ? bootstrap.Modal.getOrCreateInstance(el) : null; }

    function normalizeGender(value){
        if(!value) return '';
        if(value === 'ذكر' || value === 'male' || value === 'Male') return 'ذكر';
        if(value === 'أنثى' || value === 'female' || value === 'Female') return 'أنثى';
        return value;
    }
    function normalizeCoachType(value){
        if(!value) return 'ai';
        value = String(value).toLowerCase();
        if(value === 'ai' || value === 'ذكاء اصطناعي') return 'ai';
        if(value === 'human' || value === 'بشري') return 'human';
        return value;
    }

    // ==========================================
    // Global functions (called from Blade onclick)
    // ==========================================
    window.openAddUserModal = function(){
        if(!form || !modalEl) return;
        form.reset();
        form.action = '/admin/users';
        formMethod.value = 'POST';
        modalTitle.textContent = 'إضافة مستخدم جديد';
        saveBtn.innerHTML = '<i class="bi bi-check-lg"></i> حفظ البيانات';
        if(passwordInput) passwordInput.required = false;
        getModal(modalEl).show();
    };

    window.closeAddUserModal = function(){
        if(!modalEl) return;
        getModal(modalEl).hide();
    };

    window.openEditModal = function(button){
        var row = button.closest('tr');
        if(!row) return;

        var id = row.dataset.id;

        modalTitle.textContent = 'تعديل بيانات المستخدم';
        formMethod.value = 'PUT';
        form.action = '/admin/users/' + id;

        nameInput.value = row.dataset.name || '';
        emailInput.value = row.dataset.email || '';
        roleInput.value = (row.dataset.role || 'user').toLowerCase();

        passwordInput.value = '';
        passwordInput.required = false;

        genderInput.value = normalizeGender(row.dataset.gender);
        birthdateInput.value = row.dataset.birthdate || '';

        heightInput.value = row.dataset.height || '';
        weightInput.value = row.dataset.weight || '';

        goalInput.value = row.dataset.goalId || '';
        activityInput.value = row.dataset.activityId || '';
        coachTypeInput.value = normalizeCoachType(row.dataset.coachtype);
        daysInput.value = row.dataset.days || '';

        healthInput.value = row.dataset.health || '';
        dietInput.value = row.dataset.diet || '';
        notesInput.value = row.dataset.notes || '';

        saveBtn.innerHTML = '<i class="bi bi-check-lg"></i> حفظ التعديلات';
        getModal(modalEl).show();
    };

    window.openDetailsModal = function(button){
        var row = button.closest('tr');
        if(!row || !detailsModalEl) return;

        sfSetText('viewName', row.dataset.name);
        sfSetText('viewEmail', row.dataset.email);
        sfSetText('viewRole', row.dataset.role);
        sfSetText('viewVerified', row.dataset.verified);
        sfSetText('viewVerifiedDate', row.dataset.verifiedDate);

        sfSetText('viewGender', row.dataset.gender || '-');
        sfSetText('viewBirthdate', row.dataset.birthdate || '-');
        sfSetText('viewHeight', row.dataset.height || '-');
        sfSetText('viewWeight', row.dataset.weight || '-');

        sfSetText('viewGoal', row.dataset.goal || '-');
        sfSetText('viewActivity', row.dataset.activity || '-');
        sfSetText('viewPlace', row.dataset.place || '-');
        sfSetText('viewCoachType', row.dataset.coachtype || '-');
        sfSetText('viewDays', row.dataset.days || '-');

        sfSetText('viewHealth', row.dataset.health || '-');
        sfSetText('viewDiet', row.dataset.diet || '-');
        sfSetText('viewDisclaimer', row.dataset.disclaimer || '-');

        sfSetText('viewNotes', row.dataset.notes || 'لا توجد ملاحظات إضافية');
        sfSetText('viewCreated', row.dataset.created || '-');
        sfSetText('viewUpdated', row.dataset.updated || '-');

        // Reset tabs
        detailsModalEl.querySelectorAll('.sf-tab').forEach(function(t, i){
            t.classList.toggle('active', i === 0);
        });
        detailsModalEl.querySelectorAll('.sf-tab-pane').forEach(function(p, i){
            p.classList.toggle('active', i === 0);
        });

        getModal(detailsModalEl).show();
    };

    window.closeDetailsModal = function(){
        if(!detailsModalEl) return;
        getModal(detailsModalEl).hide();
    };

    // ==========================================
    // Tabs inside details modal
    // ==========================================
    document.querySelectorAll('.sf-tab').forEach(function(tab){
        tab.addEventListener('click', function(){
            var target = tab.getAttribute('data-sf-tab');
            var modal = tab.closest('.modal');
            if(!modal) return;
            modal.querySelectorAll('.sf-tab').forEach(function(t){t.classList.remove('active')});
            modal.querySelectorAll('.sf-tab-pane').forEach(function(p){p.classList.remove('active')});
            tab.classList.add('active');
            var pane = modal.querySelector('#' + target);
            if(pane) pane.classList.add('active');
        });
    });

    // ==========================================
    // Double-submit prevention on add/edit form
    // ==========================================
    if(form){
        form.addEventListener('submit', function(){
            saveBtn.disabled = true;
            setTimeout(function(){ saveBtn.disabled = false; }, 3000);
        });
    }

    // ==========================================
    // Search + filters
    // ==========================================
    document.addEventListener('DOMContentLoaded', function(){
        sfReveal();
        sfTheme();
        sfSidebar();
        sfSidebarSections();
        sfPreventDoubleSubmit();
        sfInterceptConfirmForms();
        sfAutohideAlerts();
        sfAlertClose();

        var searchInput = document.getElementById('sfUsersSearch');
        var filterRole = document.getElementById('sfFilterRole');
        var filterVerified = document.getElementById('sfFilterVerified');
        var tbody = document.getElementById('usersTableBody');

        function runFilters(){
            if(!tbody) return;
            var q = (searchInput ? searchInput.value : '').toLowerCase().trim();
            var role = filterRole ? filterRole.value : '';
            var ver = filterVerified ? filterVerified.value : '';

            tbody.querySelectorAll('tr').forEach(function(row){
                if(!row.dataset.id) return;
                var txt = row.textContent.toLowerCase();
                var matchesText = !q || txt.indexOf(q) !== -1;
                var rowRole = (row.dataset.role || '').toLowerCase();
                var matchesRole = !role || rowRole === role;
                var rowVer = row.dataset.verifiedDate && row.dataset.verifiedDate !== '-' ? '1' : '0';
                var matchesVer = !ver || rowVer === ver;

                var show = matchesText && matchesRole && matchesVer;
                row.style.display = show ? '' : 'none';
            });
        }

        if(searchInput) searchInput.addEventListener('input', sfDebounce(runFilters, 150));
        if(filterRole) filterRole.addEventListener('change', runFilters);
        if(filterVerified) filterVerified.addEventListener('change', runFilters);
    });
})();
</script>

</body>
</html>
