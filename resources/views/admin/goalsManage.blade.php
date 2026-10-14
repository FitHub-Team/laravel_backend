<!DOCTYPE html>
<html lang="ar" dir="rtl" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>إدارة الأهداف — SuperFit</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <script>
        (function () {
            try {
                var t = localStorage.getItem('sf-theme');
                if (t === 'dark') document.documentElement.setAttribute('data-bs-theme', 'dark');
            } catch (e) {}
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
            background-image:
                radial-gradient(circle at 100% 0%,rgba(20,184,166,.08),transparent 42%),
                radial-gradient(circle at 0% 100%,rgba(22,163,74,.08),transparent 42%);
            background-attachment:fixed;
        }
        .sf-layout{display:flex;min-height:100vh;align-items:stretch}

        /* Sidebar */
        .sf-sidebar{
            width:280px;flex-shrink:0;background:linear-gradient(180deg,var(--sf-teal-900),#062624 140%);
            color:#D9F2EC;padding:22px 16px;display:flex;flex-direction:column;
            border-inline-end:1px solid rgba(255,255,255,.05);position:sticky;top:0;height:100vh;
            overflow-y:auto;scrollbar-width:thin;scrollbar-color:rgba(20,184,166,.35) transparent;
        }
        .sf-sidebar::-webkit-scrollbar{width:6px}
        .sf-sidebar::-webkit-scrollbar-thumb{background:rgba(20,184,166,.35);border-radius:10px}
        [data-bs-theme="dark"] .sf-sidebar{background:linear-gradient(180deg,#062624,#030d0c 140%)}
        .sf-brand{display:flex;align-items:center;gap:12px;padding:10px 12px 22px;text-decoration:none;color:inherit;border-bottom:1px solid rgba(255,255,255,.06);margin-bottom:18px}
        .sf-brand-mark{width:42px;height:42px;border-radius:12px;display:flex;align-items:center;justify-content:center;
            background:linear-gradient(135deg,var(--sf-teal-500),var(--sf-green-600));color:#fff;font-size:20px;
            box-shadow:0 8px 20px rgba(20,184,166,.35)}
        .sf-brand-text{display:flex;flex-direction:column;line-height:1.1}
        .sf-brand-name{font-size:17px;font-weight:800;color:#fff}
        .sf-brand-sub{font-size:11px;font-weight:600;color:rgba(217,242,236,.55);margin-top:3px;letter-spacing:.6px}
        .sf-nav{display:flex;flex-direction:column;gap:6px;flex:1}
        .sf-nav-section-toggle{width:100%;display:flex;align-items:center;justify-content:space-between;gap:10px;
            padding:10px 12px;background:transparent;border:none;color:rgba(217,242,236,.55);font-family:inherit;
            font-size:11.5px;font-weight:800;letter-spacing:1px;text-transform:uppercase;cursor:pointer;border-radius:10px;
            transition:color .2s ease,background-color .2s ease}
        .sf-nav-section-toggle:hover{color:#fff;background:rgba(255,255,255,.04)}
        .sf-nav-chevron{font-size:14px;transition:transform .25s ease}
        .sf-nav-section.open .sf-nav-chevron{transform:rotate(180deg)}
        .sf-nav-section-items{display:grid;grid-template-rows:0fr;transition:grid-template-rows .3s ease}
        .sf-nav-section.open .sf-nav-section-items{grid-template-rows:1fr}
        .sf-nav-section-items-inner{overflow:hidden;min-height:0;display:flex;flex-direction:column;gap:4px;padding:4px 0 6px}
        .sf-nav-btn{display:flex;align-items:center;gap:11px;padding:10px 12px;border-radius:11px;color:#D9F2EC;
            text-decoration:none;font-family:inherit;font-size:13.5px;font-weight:600;
            transition:background-color .2s ease,color .2s ease;border:none;background:transparent;
            width:100%;text-align:start;cursor:pointer}
        .sf-nav-btn i{font-size:16px;width:20px;text-align:center;color:rgba(217,242,236,.75);transition:color .2s ease}
        .sf-nav-btn:hover{background:rgba(255,255,255,.06);color:#fff}
        .sf-nav-btn.active{background:linear-gradient(135deg,var(--sf-teal-500),var(--sf-green-600));color:#062624;font-weight:800;box-shadow:0 6px 18px rgba(20,184,166,.30)}
        .sf-nav-btn.active i{color:#062624}
        .sf-nav-btn-danger{color:#FFD5D5}
        .sf-nav-btn-danger i{color:#FFAFAF}
        .sf-nav-btn-danger:hover{background:rgba(225,29,72,.18);color:#fff}

        /* Main */
        .sf-main-wrap{flex:1;min-width:0;display:flex;flex-direction:column}
        .sf-topbar{display:flex;align-items:center;gap:16px;padding:20px 28px;border-bottom:1px solid var(--sf-border);
            background:rgba(243,250,248,.85);backdrop-filter:saturate(140%) blur(8px);position:sticky;top:0;z-index:5}
        [data-bs-theme="dark"] .sf-topbar{background:rgba(7,24,22,.85)}
        .sf-topbar-title{flex:1;min-width:0}
        .sf-topbar-title h1{font-size:20px;font-weight:800;margin:0 0 2px;color:var(--sf-text)}
        .sf-topbar-title p{font-size:13px;color:var(--sf-muted);margin:0}
        .sf-topbar-actions{display:flex;align-items:center;gap:10px}
        .sf-icon-btn{width:40px;height:40px;border-radius:12px;display:inline-flex;align-items:center;justify-content:center;
            background:var(--sf-surface);border:1px solid var(--sf-border);color:var(--sf-text);cursor:pointer;
            font-size:17px;transition:background-color .2s ease,color .2s ease}
        .sf-icon-btn:hover{background:var(--sf-teal-100);color:var(--sf-teal-700)}
        .sf-main{padding:28px;flex:1}

        /* Page header */
        .sf-page-head{display:flex;flex-wrap:wrap;gap:16px;justify-content:space-between;align-items:flex-start;margin-bottom:24px}
        .sf-page-kicker{color:var(--sf-teal-700);font-size:11px;font-weight:800;letter-spacing:1.4px;display:inline-block;margin-bottom:4px}
        .sf-page-title{font-size:26px;font-weight:800;margin:0;color:var(--sf-text)}
        .sf-page-desc{color:var(--sf-muted);font-size:13.5px;margin:6px 0 0}
        .sf-page-actions{display:flex;flex-wrap:wrap;gap:10px}

        /* Buttons */
        .sf-btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;padding:10px 18px;border-radius:12px;
            font-family:inherit;font-size:13.5px;font-weight:700;cursor:pointer;border:1px solid transparent;
            text-decoration:none;transition:transform .18s ease,box-shadow .18s ease,background-position .5s ease}
        .sf-btn-primary{color:#fff;background:linear-gradient(135deg,var(--sf-teal-700),var(--sf-green-600));
            background-size:200% 200%;box-shadow:0 8px 20px rgba(15,118,110,.22)}
        .sf-btn-primary:hover{background-position:100% 0;box-shadow:0 12px 28px rgba(15,118,110,.30);transform:translateY(-1px)}
        .sf-btn-primary:active{transform:scale(.97)}
        .sf-btn-light{background:var(--sf-surface);border-color:var(--sf-border);color:var(--sf-text)}
        .sf-btn-light:hover{background:var(--sf-teal-100);border-color:rgba(20,184,166,.35);color:var(--sf-teal-700)}
        .sf-btn-danger{background:var(--sf-danger);color:#fff}
        .sf-btn-danger:hover{background:#be123c;transform:translateY(-1px)}
        .sf-btn-soft-danger{background:rgba(225,29,72,.08);border-color:rgba(225,29,72,.20);color:var(--sf-danger)}
        .sf-btn-soft-danger:hover{background:rgba(225,29,72,.14)}
        .sf-btn:active{transform:scale(.97)}

        /* Toolbar */
        .sf-toolbar{display:flex;flex-wrap:wrap;gap:12px;justify-content:space-between;align-items:center;margin-bottom:20px}
        .sf-search-wrap{position:relative;flex:1;min-width:220px;max-width:420px}
        .sf-search-wrap i{position:absolute;inset-inline-start:14px;top:50%;transform:translateY(-50%);color:var(--sf-muted);font-size:16px;pointer-events:none}
        .sf-search{width:100%;height:44px;padding:0 44px;border-radius:12px;border:1px solid var(--sf-border);
            background:var(--sf-surface);color:var(--sf-text);font-family:inherit;font-size:13.5px;outline:none;
            transition:border-color .2s ease,box-shadow .2s ease}
        .sf-search:focus{border-color:var(--sf-teal-700);box-shadow:0 0 0 4px rgba(15,118,110,.10)}
        .sf-toolbar-right{display:flex;align-items:center;gap:10px}

        .sf-count-pill{display:inline-flex;align-items:center;gap:8px;padding:8px 14px;border-radius:999px;
            background:var(--sf-teal-100);color:var(--sf-teal-700);font-size:12.5px;font-weight:800}
        [data-bs-theme="dark"] .sf-count-pill{background:rgba(20,184,166,.15)}

        .sf-view-toggle{display:inline-flex;background:var(--sf-surface);border:1px solid var(--sf-border);border-radius:12px;padding:4px;gap:2px}
        .sf-view-btn{border:none;background:transparent;padding:7px 12px;border-radius:9px;cursor:pointer;
            color:var(--sf-muted);font-size:15px;transition:background-color .2s ease,color .2s ease}
        .sf-view-btn:hover{color:var(--sf-teal-700)}
        .sf-view-btn.active{background:var(--sf-teal-100);color:var(--sf-teal-700)}

        /* Card */
        .sf-card{background:var(--sf-surface);border:1px solid var(--sf-border);border-radius:18px;
            box-shadow:0 10px 30px rgba(15,118,110,.06);overflow:hidden}

        /* Card Grid */
        .sf-goals-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:18px}
        .sf-goal-card{background:var(--sf-surface);border:1px solid var(--sf-border);border-radius:18px;overflow:hidden;
            display:flex;flex-direction:column;transition:transform .22s ease,box-shadow .22s ease,border-color .22s ease;
            animation:sfFadeUp .5s both;animation-delay:calc(var(--i,0) * 60ms)}
        @media (hover:hover){
            .sf-goal-card:hover{transform:translateY(-3px);box-shadow:0 16px 40px rgba(15,118,110,.12);border-color:rgba(20,184,166,.35)}
            .sf-goal-card:hover .sf-goal-img img,.sf-goal-card:hover .sf-goal-img svg{transform:scale(1.06)}
        }
        .sf-goal-img{height:140px;background:linear-gradient(135deg,var(--sf-teal-100),#E9FBF5);display:flex;align-items:center;justify-content:center;overflow:hidden;position:relative}
        [data-bs-theme="dark"] .sf-goal-img{background:linear-gradient(135deg,rgba(20,184,166,.14),rgba(22,163,74,.14))}
        .sf-goal-img img{width:100%;height:100%;object-fit:cover;transition:transform .35s ease}
        .sf-goal-img svg{width:64px;height:64px;color:var(--sf-teal-700);transition:transform .35s ease}
        .sf-goal-body{padding:16px;display:flex;flex-direction:column;gap:10px;flex:1}
        .sf-goal-title{font-size:15px;font-weight:800;margin:0;color:var(--sf-text)}
        .sf-goal-desc{font-size:12.5px;color:var(--sf-muted);margin:0;line-height:1.7;flex:1}
        .sf-goal-foot{display:flex;justify-content:space-between;align-items:center;gap:10px;padding-top:10px;border-top:1px dashed var(--sf-border)}
        .sf-badge{display:inline-flex;align-items:center;gap:6px;padding:5px 10px;border-radius:8px;font-size:11.5px;font-weight:800}
        .sf-badge-success{background:rgba(22,163,74,.12);color:var(--sf-green-600)}
        .sf-badge-muted{background:rgba(91,123,122,.12);color:var(--sf-muted)}

        /* Table */
        .sf-table-wrap{width:100%;overflow-x:auto}
        table.sf-table{width:100%;min-width:720px;border-collapse:collapse;text-align:right}
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

        .sf-tbl-img{width:52px;height:52px;border-radius:12px;overflow:hidden;background:var(--sf-teal-100);display:flex;
            align-items:center;justify-content:center;color:var(--sf-teal-700)}
        [data-bs-theme="dark"] .sf-tbl-img{background:rgba(20,184,166,.15)}
        .sf-tbl-img img{width:100%;height:100%;object-fit:cover}
        .sf-tbl-img svg{width:24px;height:24px}

        /* Toggle switch */
        .sf-switch{display:inline-flex;align-items:center;gap:10px;cursor:pointer}
        .sf-switch input{display:none}
        .sf-switch-track{position:relative;width:44px;height:24px;border-radius:999px;background:#CBD5D4;
            transition:background-color .25s ease;flex-shrink:0}
        .sf-switch-track::after{content:'';position:absolute;top:2px;inset-inline-start:2px;width:20px;height:20px;border-radius:50%;
            background:#fff;box-shadow:0 2px 6px rgba(0,0,0,.18);transition:transform .25s ease}
        .sf-switch input:checked + .sf-switch-track{background:linear-gradient(135deg,var(--sf-teal-700),var(--sf-green-600))}
        [dir="rtl"] .sf-switch input:checked + .sf-switch-track::after{transform:translateX(-20px)}
        [dir="ltr"] .sf-switch input:checked + .sf-switch-track::after{transform:translateX(20px)}
        .sf-switch-text{font-size:12.5px;font-weight:800;color:var(--sf-text)}

        /* Empty */
        .sf-empty{padding:60px 20px;text-align:center}
        .sf-empty-ic{width:64px;height:64px;border-radius:50%;display:flex;align-items:center;justify-content:center;
            margin:0 auto 14px;background:var(--sf-teal-100);color:var(--sf-teal-700);font-size:26px}
        [data-bs-theme="dark"] .sf-empty-ic{background:rgba(20,184,166,.15)}
        .sf-empty h3{font-size:15px;font-weight:800;color:var(--sf-text);margin:0 0 4px}
        .sf-empty p{color:var(--sf-muted);font-size:12.5px;margin:0}

        /* Alerts */
        .sf-alert{display:flex;align-items:center;gap:10px;padding:13px 16px;border-radius:12px;margin-bottom:22px;font-size:13.5px;font-weight:600}
        .sf-alert-success{background:rgba(22,163,74,.10);color:var(--sf-green-600);border:1px solid rgba(22,163,74,.20)}
        .sf-alert-danger{background:rgba(225,29,72,.08);color:var(--sf-danger);border:1px solid rgba(225,29,72,.20)}
        .sf-alert i{font-size:18px}

        /* Modal */
        .modal-content{border-radius:18px;border:1px solid var(--sf-border);background:var(--sf-surface)}
        .modal-header{border-bottom:1px solid var(--sf-border);padding:18px 22px}
        .modal-header h5{margin:0;font-size:16px;font-weight:800;color:var(--sf-text)}
        .modal-header .sf-modal-kicker{color:var(--sf-teal-700);font-size:11px;font-weight:800;letter-spacing:1.2px;display:block;margin-bottom:3px}
        .modal-body{padding:22px}
        .modal-footer{border-top:1px solid var(--sf-border);padding:14px 22px}
        .sf-form-group{margin-bottom:16px}
        .sf-form-label{display:block;font-size:13px;font-weight:700;color:var(--sf-text);margin-bottom:8px}
        .sf-form-control{width:100%;padding:11px 14px;border-radius:12px;border:1px solid var(--sf-border);
            background:var(--sf-bg);color:var(--sf-text);font-family:inherit;font-size:13.5px;outline:none;
            transition:border-color .2s ease,box-shadow .2s ease,background-color .2s ease}
        .sf-form-control:focus{border-color:var(--sf-teal-700);background:var(--sf-surface);box-shadow:0 0 0 4px rgba(15,118,110,.10)}
        textarea.sf-form-control{min-height:100px;resize:vertical}
        .sf-file-input{width:100%;padding:10px;border-radius:12px;border:1.5px dashed var(--sf-border);
            background:var(--sf-bg);color:var(--sf-muted);font-family:inherit;font-size:12.5px;cursor:pointer}
        .sf-file-input:hover{border-color:var(--sf-teal-500)}
        .sf-preview{margin-top:10px;max-width:100%;border-radius:12px;overflow:hidden;display:none;border:1px solid var(--sf-border)}
        .sf-preview img{display:block;width:100%;max-height:180px;object-fit:cover}
        .sf-check{display:inline-flex;align-items:center;gap:9px;font-size:13px;color:var(--sf-text);font-weight:600;cursor:pointer}
        .sf-check input{width:17px;height:17px;accent-color:var(--sf-teal-700);cursor:pointer}

        /* Reveal & animations */
        html.js [data-sf-reveal]{opacity:0;transform:translateY(14px);transition:opacity .6s ease,transform .6s ease}
        html.js [data-sf-reveal].is-visible{opacity:1;transform:translateY(0)}
        @keyframes sfFadeUp{from{opacity:0;transform:translateY(14px)}to{opacity:1;transform:translateY(0)}}

        /* Responsive */
        @media (max-width:991.98px){
            .sf-layout{display:block}
            .sf-sidebar{position:fixed;top:0;bottom:0;inset-inline-start:0;height:100vh;z-index:1050;
                transform:translateX(100%);transition:transform .3s ease;box-shadow:-20px 0 50px rgba(0,0,0,.20)}
            [dir="rtl"] .sf-sidebar{transform:translateX(100%)}
            .sf-sidebar.is-open{transform:translateX(0)!important}
            .sf-sidebar-backdrop{position:fixed;inset:0;background:rgba(10,30,28,.45);backdrop-filter:blur(2px);
                z-index:1040;opacity:0;visibility:hidden;transition:opacity .25s ease,visibility .25s ease}
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
            .sf-goals-grid{grid-template-columns:1fr}
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
            <span class="sf-brand-mark" aria-hidden="true"><i class="bi bi-lightning-charge-fill"></i></span>
            <span class="sf-brand-text">
                <span class="sf-brand-name">SuperFit</span>
                <span class="sf-brand-sub">ADMIN PANEL</span>
            </span>
        </a>

        <nav class="sf-nav">
            <div class="sf-nav-section open">
                <button type="button" class="sf-nav-section-toggle" data-sf-section-toggle aria-expanded="true">
                    <span>نظرة عامة</span><i class="bi bi-chevron-down sf-nav-chevron" aria-hidden="true"></i>
                </button>
                <div class="sf-nav-section-items"><div class="sf-nav-section-items-inner">
                    <a href="{{ route('admin.dashboard') }}" class="sf-nav-btn {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-bar-chart-line" aria-hidden="true"></i><span>إحصائيات لوحة التحكم</span>
                    </a>
                </div></div>
            </div>

            <div class="sf-nav-section open">
                <button type="button" class="sf-nav-section-toggle" data-sf-section-toggle aria-expanded="true">
                    <span>الإدارة</span><i class="bi bi-chevron-down sf-nav-chevron" aria-hidden="true"></i>
                </button>
                <div class="sf-nav-section-items"><div class="sf-nav-section-items-inner">
                    <a href="{{ route('admin.users-details') }}" class="sf-nav-btn {{ request()->routeIs('admin.users-details') ? 'active' : '' }}">
                        <i class="bi bi-people" aria-hidden="true"></i><span>إدارة تفاصيل المستخدمين</span>
                    </a>
                    <a href="{{ route('admin.trainer.manage') }}" class="sf-nav-btn {{ request()->routeIs('admin.trainer.manage') ? 'active' : '' }}">
                        <i class="bi bi-person-badge" aria-hidden="true"></i><span>إدارة المدربين</span>
                    </a>
                    <a href="{{ route('admin.coaches.requests') }}" class="sf-nav-btn {{ request()->routeIs('admin.coaches.requests') ? 'active' : '' }}">
                        <i class="bi bi-clipboard-check" aria-hidden="true"></i><span>طلبات الانضمام</span>
                    </a>
                </div></div>
            </div>

            <div class="sf-nav-section open">
                <button type="button" class="sf-nav-section-toggle" data-sf-section-toggle aria-expanded="true">
                    <span>الملف الرياضي</span><i class="bi bi-chevron-down sf-nav-chevron" aria-hidden="true"></i>
                </button>
                <div class="sf-nav-section-items"><div class="sf-nav-section-items-inner">
                    <a href="{{ route('admin.goals.manage') }}" class="sf-nav-btn {{ request()->routeIs('admin.goals.manage') ? 'active' : '' }}">
                        <i class="bi bi-bullseye" aria-hidden="true"></i><span>الأهداف</span>
                    </a>
                    <a href="{{ route('admin.activity-level.manage') }}" class="sf-nav-btn {{ request()->routeIs('admin.activity-level.manage') ? 'active' : '' }}">
                        <i class="bi bi-activity" aria-hidden="true"></i><span>مستوى النشاط</span>
                    </a>
                    <a href="{{ route('admin.health-restrictions.manage') }}" class="sf-nav-btn {{ request()->routeIs('admin.health-restrictions.manage') ? 'active' : '' }}">
                        <i class="bi bi-heart-pulse" aria-hidden="true"></i><span>القيود الصحية</span>
                    </a>
                    <a href="{{ route('admin.skills.index') }}" class="sf-nav-btn {{ request()->routeIs('admin.skills.index') ? 'active' : '' }}">
                        <i class="bi bi-award" aria-hidden="true"></i><span>المهارات</span>
                    </a>
                </div></div>
            </div>

            <div class="sf-nav-section open">
                <button type="button" class="sf-nav-section-toggle" data-sf-section-toggle aria-expanded="true">
                    <span>الحساب</span><i class="bi bi-chevron-down sf-nav-chevron" aria-hidden="true"></i>
                </button>
                <div class="sf-nav-section-items"><div class="sf-nav-section-items-inner">
                    <a href="{{ route('admin.login') }}" class="sf-nav-btn">
                        <i class="bi bi-box-arrow-in-right" aria-hidden="true"></i><span>تسجيل الدخول</span>
                    </a>
                    <form action="{{ route('admin.logout') }}" method="POST" style="display:contents">
                        @csrf
                        <button type="submit" class="sf-nav-btn sf-nav-btn-danger">
                            <i class="bi bi-box-arrow-left" aria-hidden="true"></i><span>تسجيل الخروج</span>
                        </button>
                    </form>
                </div></div>
            </div>
        </nav>
    </aside>

    <div class="sf-sidebar-backdrop" id="sfSidebarBackdrop"></div>

    {{-- Main --}}
    <div class="sf-main-wrap">

        <header class="sf-topbar">
            <button type="button" class="sf-icon-btn d-lg-none" id="sfSidebarToggle" aria-label="فتح القائمة">
                <i class="bi bi-list" aria-hidden="true"></i>
            </button>
            <div class="sf-topbar-title">
                <h1>الأهداف الرياضية</h1>
                <p>إدارة الأهداف الظاهرة داخل التطبيق</p>
            </div>
            <div class="sf-topbar-actions">
                <button type="button" class="sf-icon-btn" id="sfThemeToggle" aria-label="تبديل المظهر">
                    <i class="bi bi-moon-stars" id="sfThemeIcon" aria-hidden="true"></i>
                </button>
            </div>
        </header>

        <main class="sf-main">

            {{-- Session --}}
            @if(session('success'))
                <div class="sf-alert sf-alert-success" role="alert" data-sf-autohide>
                    <i class="bi bi-check-circle-fill" aria-hidden="true"></i>
                    <span>{{ session('success') }}</span>
                    <button type="button" class="btn-close ms-auto" aria-label="إغلاق" data-sf-alert-close></button>
                </div>
            @endif

            @if($errors->any())
                <div class="sf-alert sf-alert-danger" role="alert">
                    <i class="bi bi-exclamation-triangle-fill" aria-hidden="true"></i>
                    <div>
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                    <button type="button" class="btn-close ms-auto" aria-label="إغلاق" data-sf-alert-close></button>
                </div>
            @endif

            {{-- Page header --}}
            <div class="sf-page-head" data-sf-reveal>
                <div>
                    <span class="sf-page-kicker">SUPERFIT · لوحة المحتوى</span>
                    <h2 class="sf-page-title">جدول الأهداف</h2>
                    <p class="sf-page-desc">أضف أهداف اللياقة البدنية التي تظهر للمستخدمين في التطبيق.</p>
                </div>
                <div class="sf-page-actions">
                    <a href="{{ route('admin.dashboard') }}" class="sf-btn sf-btn-light">
                        <i class="bi bi-arrow-right" aria-hidden="true"></i>
                        لوحة التحكم
                    </a>
                    <button type="button" class="sf-btn sf-btn-primary" onclick="openAddModal()">
                        <i class="bi bi-plus-lg" aria-hidden="true"></i>
                        هدف جديد
                    </button>
                </div>
            </div>

            {{-- Toolbar --}}
            <div class="sf-toolbar" data-sf-reveal>
                <div class="sf-search-wrap">
                    <i class="bi bi-search" aria-hidden="true"></i>
                    <input type="text" id="sfGoalsSearch" class="sf-search" placeholder="ابحث باسم الهدف أو الوصف..." autocomplete="off">
                </div>
                <div class="sf-toolbar-right">
                    <span class="sf-count-pill" id="sfGoalsCount">
                        <i class="bi bi-bullseye" aria-hidden="true"></i>
                        <span>{{ $goals->count() }} هدف</span>
                    </span>
                    <div class="sf-view-toggle" role="tablist" aria-label="عرض">
                        <button type="button" class="sf-view-btn active" id="sfViewCards" aria-label="عرض البطاقات" aria-pressed="true">
                            <i class="bi bi-grid-3x3-gap-fill" aria-hidden="true"></i>
                        </button>
                        <button type="button" class="sf-view-btn" id="sfViewTable" aria-label="عرض الجدول" aria-pressed="false">
                            <i class="bi bi-table" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Empty --}}
            @if($goals->isEmpty())
                <div class="sf-card sf-empty" data-sf-reveal>
                    <div class="sf-empty-ic"><i class="bi bi-bullseye" aria-hidden="true"></i></div>
                    <h3>لا توجد أهداف بعد</h3>
                    <p>اضغط على "هدف جديد" لإضافة أول هدف لياقة.</p>
                </div>
            @else

                {{-- Cards View --}}
                <div id="sfGoalsCards" class="sf-goals-grid">
                    @foreach($goals as $i => $goal)
                        <article class="sf-goal-card" data-sf-searchable
                                 data-title="{{ $goal->title }}"
                                 data-desc="{{ $goal->description }}"
                                 style="--i:{{ $i }}">
                            <div class="sf-goal-img">
                                @if($goal->image)
                                    <img src="{{ asset('storage/' . $goal->image) }}" alt="{{ $goal->title }}">
                                @else
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                         stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <circle cx="12" cy="12" r="9"></circle>
                                        <circle cx="12" cy="12" r="5"></circle>
                                        <circle cx="12" cy="12" r="1"></circle>
                                    </svg>
                                @endif
                            </div>
                            <div class="sf-goal-body">
                                <h3 class="sf-goal-title">{{ $goal->title }}</h3>
                                <p class="sf-goal-desc">{{ $goal->description ?? 'لا يوجد وصف.' }}</p>
                                <div class="sf-goal-foot">
                                    <form action="{{ route('admin.goals.toggle', $goal->id) }}" method="POST" style="display:contents">
                                        @csrf
                                        @method('PATCH')
                                        <label class="sf-switch">
                                            <input type="checkbox" onchange="this.form.submit()" {{ $goal->is_active ? 'checked' : '' }}>
                                            <span class="sf-switch-track"></span>
                                            <span class="sf-switch-text">{{ $goal->is_active ? 'مفعل' : 'غير مفعل' }}</span>
                                        </label>
                                    </form>

                                    <form action="{{ route('admin.goals.destroy', $goal->id) }}" method="POST" style="display:contents"
                                          onsubmit="return confirm('هل أنت متأكد من عملية الحذف؟');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="sf-btn sf-btn-soft-danger" style="padding:6px 12px;font-size:12px">
                                            <i class="bi bi-trash" aria-hidden="true"></i>
                                            حذف
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                {{-- Table View --}}
                <div id="sfGoalsTable" class="sf-card" style="display:none">
                    <div class="sf-table-wrap">
                        <table class="sf-table">
                            <thead>
                                <tr>
                                    <th style="width:70px">#</th>
                                    <th>الصورة</th>
                                    <th>اسم الهدف</th>
                                    <th>الوصف</th>
                                    <th>الحالة</th>
                                    <th style="text-align:center">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($goals as $i => $goal)
                                    <tr data-sf-searchable
                                        data-title="{{ $goal->title }}"
                                        data-desc="{{ $goal->description }}"
                                        style="--i:{{ $i }}">
                                        <td><span class="sf-id-badge">#{{ $goal->id }}</span></td>
                                        <td>
                                            <div class="sf-tbl-img">
                                                @if($goal->image)
                                                    <img src="{{ asset('storage/' . $goal->image) }}" alt="{{ $goal->title }}">
                                                @else
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                                         stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                        <circle cx="12" cy="12" r="9"></circle>
                                                        <circle cx="12" cy="12" r="5"></circle>
                                                        <circle cx="12" cy="12" r="1"></circle>
                                                    </svg>
                                                @endif
                                            </div>
                                        </td>
                                        <td><strong>{{ $goal->title }}</strong></td>
                                        <td style="color:var(--sf-muted)">{{ \Illuminate\Support\Str::limit($goal->description, 60) ?? '-' }}</td>
                                        <td>
                                            <form action="{{ route('admin.goals.toggle', $goal->id) }}" method="POST" style="display:contents">
                                                @csrf
                                                @method('PATCH')
                                                <label class="sf-switch">
                                                    <input type="checkbox" onchange="this.form.submit()" {{ $goal->is_active ? 'checked' : '' }}>
                                                    <span class="sf-switch-track"></span>
                                                    <span class="sf-switch-text">{{ $goal->is_active ? 'مفعل' : 'غير مفعل' }}</span>
                                                </label>
                                            </form>
                                        </td>
                                        <td style="text-align:center">
                                            <form action="{{ route('admin.goals.destroy', $goal->id) }}" method="POST" style="display:inline"
                                                  onsubmit="return confirm('هل أنت متأكد من عملية الحذف؟');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="sf-btn sf-btn-soft-danger" style="padding:6px 12px;font-size:12px">
                                                    <i class="bi bi-trash" aria-hidden="true"></i>
                                                    حذف
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div id="sfGoalsNoResults" class="sf-card sf-empty" style="display:none;margin-top:16px">
                    <div class="sf-empty-ic"><i class="bi bi-search" aria-hidden="true"></i></div>
                    <h3>لا توجد نتائج مطابقة</h3>
                    <p>حاول تعديل كلمات البحث.</p>
                </div>

            @endif

        </main>
    </div>
</div>

{{-- Add Modal --}}
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addGoalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <span class="sf-modal-kicker">SUPERFIT</span>
                    <h5 id="addGoalLabel">إضافة هدف جديد</h5>
                    <p style="margin:3px 0 0;font-size:12.5px;color:var(--sf-muted)">أضف هدف لياقة جديد ليظهر للمستخدمين.</p>
                </div>
                <button type="button" class="btn-close" onclick="closeAddModal()" aria-label="إغلاق"></button>
            </div>

            <form action="{{ route('admin.goals.store') }}" method="POST" enctype="multipart/form-data" id="sfAddGoalForm">
                @csrf
                <div class="modal-body">

                    <div class="sf-form-group">
                        <label class="sf-form-label" for="title">عنوان الهدف</label>
                        <input type="text" name="title" id="title" class="sf-form-control" required placeholder="مثال: خسارة الوزن">
                    </div>

                    <div class="sf-form-group">
                        <label class="sf-form-label" for="description">الوصف</label>
                        <textarea name="description" id="description" class="sf-form-control" rows="4" placeholder="أدخل وصف الهدف..."></textarea>
                    </div>

                    <div class="sf-form-group">
                        <label class="sf-form-label" for="image">صورة / أيقونة الهدف</label>
                        <input type="file" name="image" id="image" class="sf-file-input" accept="image/*">
                        <div class="sf-preview" id="sfImagePreview">
                            <img src="" alt="معاينة الصورة">
                        </div>
                    </div>

                    <label class="sf-check">
                        <input type="checkbox" name="is_active" value="1" checked>
                        <span>تفعيل الهدف تلقائياً</span>
                    </label>

                </div>

                <div class="modal-footer">
                    <button type="button" class="sf-btn sf-btn-light" onclick="closeAddModal()">إلغاء</button>
                    <button type="submit" class="sf-btn sf-btn-primary">
                        <i class="bi bi-check-lg" aria-hidden="true"></i>
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

    // ============================================
    // SF Utilities
    // ============================================
    function sfCsrf(){var m=document.querySelector('meta[name="csrf-token"]');return m?m.getAttribute('content'):''}

    function sfFetchJson(url,options){
        options=options||{};
        var headers=Object.assign({'Accept':'application/json','X-Requested-With':'XMLHttpRequest','X-CSRF-TOKEN':sfCsrf()},options.headers||{});
        return fetch(url,Object.assign({},options,{headers:headers})).then(function(r){
            return r.text().then(function(t){
                var d;try{d=t?JSON.parse(t):{}}catch(e){d={raw:t}}
                if(!r.ok){var err=new Error((d&&d.message)?d.message:'HTTP '+r.status);err.status=r.status;err.data=d;throw err}
                return d;
            });
        });
    }

    function sfToast(msg,type){
        var c=document.getElementById('sfToastContainer');if(!c)return;
        type=type||'info';
        var map={success:'success',danger:'danger',info:'info',warning:'warning'};
        var el=document.createElement('div');
        el.className='toast align-items-center text-bg-'+(map[type]||'info')+' border-0';
        el.setAttribute('role','alert');
        el.innerHTML='<div class="d-flex"><div class="toast-body">'+msg+'</div>'+
            '<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="إغلاق"></button></div>';
        c.appendChild(el);
        var t=bootstrap.Toast.getOrCreateInstance(el,{delay:4000});
        t.show();
        el.addEventListener('hidden.bs.toast',function(){el.remove()});
    }

    function sfConfirm(message){
        return new Promise(function(resolve){
            var modalEl=document.getElementById('sfConfirmModal');
            if(!modalEl||!window.bootstrap){resolve(window.confirm(message));return}
            var msgEl=modalEl.querySelector('[data-sf-confirm-message]');
            var okBtn=modalEl.querySelector('[data-sf-confirm-ok]');
            var cancelBtn=modalEl.querySelector('[data-sf-confirm-cancel]');
            if(msgEl)msgEl.textContent=message;
            var modal=bootstrap.Modal.getOrCreateInstance(modalEl);
            var done=false;
            function finish(v){if(done)return;done=true;modal.hide();resolve(v)}
            function onOk(){finish(true)}
            function onCancel(){finish(false)}
            if(okBtn)okBtn.addEventListener('click',onOk,{once:true});
            if(cancelBtn)cancelBtn.addEventListener('click',onCancel,{once:true});
            modalEl.addEventListener('hidden.bs.modal',function(){
                if(okBtn)okBtn.removeEventListener('click',onOk);
                if(cancelBtn)cancelBtn.removeEventListener('click',onCancel);
                if(!done){done=true;resolve(false)}
            },{once:true});
            modal.show();
        });
    }

    function sfSetText(id,value){var el=document.getElementById(id);if(!el)return;el.textContent=(value===undefined||value===null||value==='')?'-':value}
    function sfSetValue(id,value){var el=document.getElementById(id);if(!el)return;el.value=(value===undefined||value===null)?'':value}
    function sfDebounce(fn,wait){var t;return function(){var c=this,a=arguments;clearTimeout(t);t=setTimeout(function(){fn.apply(c,a)},wait||150)}}
    function sfSetLoading(b,i,txt){if(!b)return;if(i){if(!b.dataset.sfOrig)b.dataset.sfOrig=b.innerHTML;b.disabled=true;b.innerHTML='<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>'+(txt?'<span class="ms-2">'+txt+'</span>':'');}else{b.disabled=false;if(b.dataset.sfOrig)b.innerHTML=b.dataset.sfOrig;}}

    function sfReveal(){
        if(!('IntersectionObserver' in window)){document.querySelectorAll('[data-sf-reveal]').forEach(function(el){el.classList.add('is-visible')});return}
        var o=new IntersectionObserver(function(entries,obs){
            entries.forEach(function(e){if(e.isIntersecting){e.target.classList.add('is-visible');obs.unobserve(e.target)}});
        },{threshold:.08,rootMargin:'0px 0px -40px 0px'});
        document.querySelectorAll('[data-sf-reveal]').forEach(function(el){o.observe(el)});
    }

    function sfTheme(){
        var t=document.getElementById('sfThemeToggle'),ic=document.getElementById('sfThemeIcon');
        function apply(x){document.documentElement.setAttribute('data-bs-theme',x);try{localStorage.setItem('sf-theme',x)}catch(e){}if(ic)ic.className=x==='dark'?'bi bi-sun':'bi bi-moon-stars'}
        function cur(){return document.documentElement.getAttribute('data-bs-theme')==='dark'?'dark':'light'}
        if(ic)ic.className=cur()==='dark'?'bi bi-sun':'bi bi-moon-stars';
        if(t)t.addEventListener('click',function(){apply(cur()==='dark'?'light':'dark')});
    }

    function sfPreventDoubleSubmit(){
        document.querySelectorAll('form').forEach(function(f){
            f.addEventListener('submit',function(e){
                if(f.dataset.sfSubmitting==='1'){e.preventDefault();return}
                f.dataset.sfSubmitting='1';
                var b=f.querySelector('button[type="submit"]');
                if(b&&!b.disabled){sfSetLoading(b,true);setTimeout(function(){f.dataset.sfSubmitting='0';sfSetLoading(b,false)},3000);}
                else{setTimeout(function(){f.dataset.sfSubmitting='0'},3000);}
            });
        });
    }

    function sfSidebar(){
        var t=document.getElementById('sfSidebarToggle'),s=document.getElementById('sfSidebar'),b=document.getElementById('sfSidebarBackdrop');
        function close(){if(!s)return;s.classList.remove('is-open');if(b)b.classList.remove('is-open')}
        function open(){if(!s)return;s.classList.add('is-open');if(b)b.classList.add('is-open')}
        if(t)t.addEventListener('click',function(){s&&s.classList.contains('is-open')?close():open()});
        if(b)b.addEventListener('click',close);
        window.addEventListener('keydown',function(e){if(e.key==='Escape')close()});
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
            var m=a.match(/confirm\((['"])([\s\S]*?)\1\)/);
            if(!m)return;
            var msg=m[2];
            f.removeAttribute('onsubmit');
            f.addEventListener('submit',function(e){
                if(f.dataset.sfConfirmed==='1')return;
                e.preventDefault();
                sfConfirm(msg).then(function(ok){
                    if(ok){f.dataset.sfConfirmed='1';f.submit()}
                });
            });
        });
    }

    function sfAutohideAlerts(){
        document.querySelectorAll('[data-sf-autohide]').forEach(function(el){
            setTimeout(function(){
                el.style.transition='opacity .4s ease, transform .4s ease';
                el.style.opacity='0';
                el.style.transform='translateY(-6px)';
                setTimeout(function(){el.remove()},450);
            },4000);
        });
    }

    function sfAlertClose(){
        document.querySelectorAll('[data-sf-alert-close]').forEach(function(btn){
            btn.addEventListener('click',function(){
                var a=btn.closest('.sf-alert');if(!a)return;
                a.style.transition='opacity .3s ease';
                a.style.opacity='0';
                setTimeout(function(){a.remove()},320);
            });
        });
    }

    // ============================================
    // Global functions called from Blade
    // ============================================
    window.openAddModal = function(){
        var el=document.getElementById('addModal');
        if(!el)return;
        var m=bootstrap.Modal.getOrCreateInstance(el);
        m.show();
    };
    window.closeAddModal = function(){
        var el=document.getElementById('addModal');
        if(!el)return;
        var m=bootstrap.Modal.getInstance(el)||bootstrap.Modal.getOrCreateInstance(el);
        m.hide();
    };

    // ============================================
    // Init
    // ============================================
    document.addEventListener('DOMContentLoaded', function(){

        sfReveal();
        sfTheme();
        sfSidebar();
        sfSidebarSections();
        sfPreventDoubleSubmit();
        sfInterceptConfirmForms();
        sfAutohideAlerts();
        sfAlertClose();

        // ============================================
        // Image preview
        // ============================================
        var fileInput=document.getElementById('image');
        var preview=document.getElementById('sfImagePreview');
        if(fileInput&&preview){
            var previewImg=preview.querySelector('img');
            fileInput.addEventListener('change',function(){
                var f=fileInput.files&&fileInput.files[0];
                if(f&&f.type.indexOf('image/')===0){
                    var reader=new FileReader();
                    reader.onload=function(e){
                        previewImg.src=e.target.result;
                        preview.style.display='block';
                    };
                    reader.readAsDataURL(f);
                }else{
                    preview.style.display='none';
                    previewImg.src='';
                }
            });
        }

        // ============================================
        // Search
        // ============================================
        var searchInput=document.getElementById('sfGoalsSearch');
        var noResults=document.getElementById('sfGoalsNoResults');
        var goalsCountEl=document.querySelector('#sfGoalsCount span');

        if(searchInput){
            var run=function(){
                var q=searchInput.value.trim().toLowerCase();
                var items=document.querySelectorAll('[data-sf-searchable]');
                var visible=0;
                items.forEach(function(el){
                    var title=(el.getAttribute('data-title')||'').toLowerCase();
                    var desc=(el.getAttribute('data-desc')||'').toLowerCase();
                    var match=!q||title.indexOf(q)!==-1||desc.indexOf(q)!==-1;
                    el.style.display=match?'':'none';
                    if(match)visible++;
                });
                if(noResults)noResults.style.display=(visible===0&&q)?'block':'none';
                if(goalsCountEl&&q)goalsCountEl.textContent=visible+' هدف';
            };
            searchInput.addEventListener('input',sfDebounce(run,150));
        }

        // ============================================
        // View toggle
        // ============================================
        var viewCardsBtn=document.getElementById('sfViewCards');
        var viewTableBtn=document.getElementById('sfViewTable');
        var cardsEl=document.getElementById('sfGoalsCards');
        var tableEl=document.getElementById('sfGoalsTable');

        function applyView(view){
            if(!cardsEl||!tableEl)return;
            if(view==='table'){
                cardsEl.style.display='none';
                tableEl.style.display='';
                if(viewTableBtn)viewTableBtn.classList.add('active');
                if(viewCardsBtn)viewCardsBtn.classList.remove('active');
                if(viewTableBtn)viewTableBtn.setAttribute('aria-pressed','true');
                if(viewCardsBtn)viewCardsBtn.setAttribute('aria-pressed','false');
            }else{
                cardsEl.style.display='';
                tableEl.style.display='none';
                if(viewCardsBtn)viewCardsBtn.classList.add('active');
                if(viewTableBtn)viewTableBtn.classList.remove('active');
                if(viewCardsBtn)viewCardsBtn.setAttribute('aria-pressed','true');
                if(viewTableBtn)viewTableBtn.setAttribute('aria-pressed','false');
            }
            try{localStorage.setItem('sf-goals-view',view)}catch(e){}
        }

        var savedView='cards';
        try{savedView=localStorage.getItem('sf-goals-view')||'cards'}catch(e){}
        applyView(savedView);

        if(viewCardsBtn)viewCardsBtn.addEventListener('click',function(){applyView('cards')});
        if(viewTableBtn)viewTableBtn.addEventListener('click',function(){applyView('table')});

    });
})();
</script>

</body>
</html>
