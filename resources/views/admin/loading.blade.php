<!DOCTYPE html>
<html lang="ar" dir="rtl" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>جاري التحميل — SuperFit</title>

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
        }

        [data-bs-theme="dark"] {
            --sf-bg: #071816;
            --sf-surface: #0E2320;
            --sf-text: #E8F5F2;
            --sf-muted: #9CB5B1;
            --sf-border: #1C3B37;
            --sf-teal-100: #133F3A;
        }

        * { box-sizing: border-box; }
        html, body { height: 100%; margin: 0; padding: 0; }

        body {
            font-family: 'Cairo', 'Segoe UI', Tahoma, sans-serif;
            background: var(--sf-bg);
            color: var(--sf-text);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background-image:
                radial-gradient(circle at 15% 20%, rgba(20, 184, 166, 0.10), transparent 45%),
                radial-gradient(circle at 85% 80%, rgba(22, 163, 74, 0.10), transparent 45%);
        }

        .sf-splash {
            width: 100%;
            padding: 24px;
            text-align: center;
        }

        .sf-splash-inner {
            display: inline-block;
            animation: sfSplashIn 700ms cubic-bezier(.2,.7,.3,1) both;
        }

        .sf-splash-mark {
            width: 104px;
            height: 104px;
            border-radius: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            background: linear-gradient(135deg, var(--sf-teal-700), var(--sf-green-600));
            color: #ffffff;
            box-shadow: 0 22px 55px rgba(15, 118, 110, 0.30);
            position: relative;
            isolation: isolate;
        }

        .sf-splash-mark::before,
        .sf-splash-mark::after {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: inherit;
            border: 2px solid var(--sf-teal-500);
            animation: sfPulse 2.4s ease-out infinite;
            opacity: 0;
            z-index: -1;
        }

        .sf-splash-mark::after { animation-delay: 1.2s; }

        .sf-splash-mark svg { width: 56px; height: 56px; position: relative; z-index: 1; }

        .sf-splash-title {
            font-size: 44px;
            font-weight: 800;
            letter-spacing: -0.5px;
            margin: 0 0 22px;
            background: linear-gradient(135deg, var(--sf-teal-700), var(--sf-green-600));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .sf-splash-dots {
            display: inline-flex;
            gap: 9px;
            margin-bottom: 18px;
        }

        .sf-splash-dots span {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: var(--sf-teal-700);
            animation: sfDot 1.2s ease-in-out infinite;
        }

        .sf-splash-dots span:nth-child(1) { animation-delay: 0s;    background: var(--sf-teal-700); }
        .sf-splash-dots span:nth-child(2) { animation-delay: 0.15s; background: var(--sf-teal-500); }
        .sf-splash-dots span:nth-child(3) { animation-delay: 0.30s; background: var(--sf-green-400); }
        .sf-splash-dots span:nth-child(4) { animation-delay: 0.45s; background: var(--sf-green-600); }

        .sf-splash-text {
            color: var(--sf-muted);
            font-size: 15px;
            font-weight: 600;
            margin: 0;
        }

        @keyframes sfSplashIn {
            from { opacity: 0; transform: translateY(20px) scale(0.96); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }

        @keyframes sfPulse {
            0%   { transform: scale(1);    opacity: 0.55; }
            100% { transform: scale(1.55); opacity: 0; }
        }

        @keyframes sfDot {
            0%, 80%, 100% { transform: translateY(0);   opacity: 0.65; }
            40%           { transform: translateY(-9px); opacity: 1; }
        }

        @media (max-width: 480px) {
            .sf-splash-mark { width: 86px; height: 86px; border-radius: 26px; }
            .sf-splash-mark svg { width: 44px; height: 44px; }
            .sf-splash-title { font-size: 34px; }
        }

        @media (prefers-reduced-motion: reduce) {
            .sf-splash-inner,
            .sf-splash-mark::before,
            .sf-splash-mark::after,
            .sf-splash-dots span {
                animation: none !important;
            }
            .sf-splash-inner { opacity: 1; transform: none; }
            .sf-splash-dots span { opacity: 1; }
            .sf-splash-mark::before,
            .sf-splash-mark::after { display: none; }
        }
    </style>
</head>
<body>
    <main class="sf-splash" role="status" aria-live="polite">
        <div class="sf-splash-inner">
            <div class="sf-splash-mark" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M11 20A7 7 0 0 1 4 13c0-6 6-10 15-11 0 9-3 15-8 18z"></path>
                    <path d="M4 13c3 0 6-1 8-3"></path>
                </svg>
            </div>

            <h1 class="sf-splash-title">SuperFit</h1>

            <div class="sf-splash-dots" aria-hidden="true">
                <span></span>
                <span></span>
                <span></span>
                <span></span>
            </div>

            <p class="sf-splash-text">جاري تجهيز لوحة التحكم...</p>
        </div>
    </main>
</body>
</html>
