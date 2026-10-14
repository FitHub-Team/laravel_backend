<!DOCTYPE html>
<html lang="ar" dir="rtl" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>تسجيل دخول الأدمن — SuperFit</title>

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

        html, body { min-height: 100%; margin: 0; padding: 0; }

        body {
            font-family: 'Cairo', 'Segoe UI', Tahoma, sans-serif;
            background: var(--sf-bg);
            color: var(--sf-text);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
            background-image:
                radial-gradient(circle at 100% 0%, rgba(20, 184, 166, 0.12), transparent 45%),
                radial-gradient(circle at 0% 100%, rgba(22, 163, 74, 0.12), transparent 45%);
        }

        .sf-login-card {
            width: 100%;
            max-width: 1080px;
            min-height: 620px;
            background: var(--sf-surface);
            border-radius: 28px;
            overflow: hidden;
            display: flex;
            box-shadow: 0 22px 60px rgba(15, 61, 46, 0.10);
            border: 1px solid var(--sf-border);
            animation: sfLoginIn 600ms cubic-bezier(.2,.7,.3,1) both;
        }

        /* ================= Side (right in RTL) ================= */

        .sf-login-side {
            flex: 1 1 48%;
            padding: 48px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            color: #ffffff;
            position: relative;
            overflow: hidden;
            background:
                linear-gradient(135deg, var(--sf-teal-900) 0%, #145A42 55%, var(--sf-green-600) 130%);
        }

        .sf-login-side::before,
        .sf-login-side::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            border: 1px solid rgba(255, 255, 255, 0.10);
            pointer-events: none;
        }

        .sf-login-side::before {
            width: 420px; height: 420px;
            top: -160px; inset-inline-end: -160px;
            animation: sfFloat 14s ease-in-out infinite;
        }

        .sf-login-side::after {
            width: 520px; height: 520px;
            bottom: -200px; inset-inline-start: -200px;
            animation: sfFloat 18s ease-in-out infinite reverse;
        }

        .sf-login-side-inner {
            position: relative;
            z-index: 1;
            display: flex;
            flex-direction: column;
            height: 100%;
            gap: 28px;
        }

        .sf-login-logo {
            display: flex;
            justify-content: center;
            animation: sfFadeUp 700ms 120ms cubic-bezier(.2,.7,.3,1) both;
        }

        .sf-login-logo img {
            max-width: 220px;
            max-height: 220px;
            object-fit: contain;
            filter: drop-shadow(0 12px 30px rgba(0, 0, 0, 0.25));
        }

        .sf-login-side-text {
            margin-top: auto;
            animation: sfFadeUp 700ms 220ms cubic-bezier(.2,.7,.3,1) both;
        }

        .sf-login-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 12px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.10);
            border: 1px solid rgba(255, 255, 255, 0.16);
            color: #E4FBF3;
            font-size: 12px;
            font-weight: 700;
            margin-bottom: 22px;
        }

        .sf-login-badge-dot {
            width: 8px; height: 8px; border-radius: 50%;
            background: var(--sf-green-400);
            box-shadow: 0 0 0 0 rgba(74, 222, 128, 0.6);
            animation: sfDotPulse 2s ease-out infinite;
        }

        .sf-login-side-text h2 {
            font-size: 34px;
            font-weight: 800;
            line-height: 1.5;
            margin: 0 0 16px;
        }

        .sf-login-side-text h2 span {
            color: var(--sf-green-400);
        }

        .sf-login-side-text p {
            color: rgba(232, 255, 248, 0.75);
            font-size: 14px;
            line-height: 1.9;
            margin: 0;
            max-width: 400px;
        }

        .sf-login-side-footer {
            display: flex;
            align-items: center;
            gap: 12px;
            color: rgba(255, 255, 255, 0.55);
            font-size: 12px;
            animation: sfFadeUp 700ms 320ms cubic-bezier(.2,.7,.3,1) both;
        }

        .sf-login-side-footer::before {
            content: '';
            width: 40px;
            height: 1px;
            background: rgba(255, 255, 255, 0.25);
        }

        /* ================= Form Side ================= */

        .sf-login-form-side {
            flex: 1 1 52%;
            padding: 48px 56px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .sf-login-form-wrap {
            width: 100%;
            max-width: 400px;
            animation: sfFadeUp 700ms 180ms cubic-bezier(.2,.7,.3,1) both;
        }

        .sf-login-mobile-logo {
            display: none;
            justify-content: center;
            margin-bottom: 24px;
        }

        .sf-login-mobile-logo img {
            max-width: 150px;
            max-height: 70px;
            object-fit: contain;
        }

        .sf-login-kicker {
            color: var(--sf-teal-700);
            font-size: 13px;
            font-weight: 700;
            margin: 0 0 12px;
        }

        .sf-login-title {
            color: var(--sf-text);
            font-size: 30px;
            font-weight: 800;
            margin: 0 0 10px;
        }

        .sf-login-desc {
            color: var(--sf-muted);
            font-size: 13.5px;
            line-height: 1.8;
            margin: 0 0 32px;
        }

        .sf-login-alert {
            border-radius: 14px;
            padding: 12px 16px;
            margin-bottom: 20px;
            font-size: 13px;
            background: rgba(225, 29, 72, 0.08);
            border: 1px solid rgba(225, 29, 72, 0.20);
            color: var(--sf-danger);
        }

        .sf-login-alert p { margin: 0 0 4px; }
        .sf-login-alert p:last-child { margin-bottom: 0; }

        .sf-form-group {
            margin-bottom: 18px;
        }

        .sf-form-label {
            display: block;
            font-size: 13px;
            font-weight: 700;
            color: var(--sf-text);
            margin-bottom: 8px;
        }

        .sf-input-wrap {
            position: relative;
        }

        .sf-input-icon {
            position: absolute;
            inset-inline-start: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--sf-muted);
            font-size: 16px;
            pointer-events: none;
            transition: color .2s ease;
        }

        .sf-input {
            width: 100%;
            height: 52px;
            border-radius: 14px;
            border: 1px solid var(--sf-border);
            background: var(--sf-bg);
            padding: 0 46px 0 46px;
            font-family: inherit;
            font-size: 14px;
            color: var(--sf-text);
            outline: none;
            transition: border-color .2s ease, box-shadow .2s ease, background-color .2s ease;
        }

        .sf-input::placeholder { color: #A7BCBA; }

        .sf-input:focus {
            border-color: var(--sf-teal-700);
            background: var(--sf-surface);
            box-shadow: 0 0 0 4px rgba(15, 118, 110, 0.12);
        }

        .sf-input-wrap:focus-within .sf-input-icon {
            color: var(--sf-teal-700);
        }

        .sf-input-toggle {
            position: absolute;
            inset-inline-end: 8px;
            top: 50%;
            transform: translateY(-50%);
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            background: transparent;
            color: var(--sf-muted);
            cursor: pointer;
            border-radius: 10px;
            transition: background-color .2s ease, color .2s ease;
        }

        .sf-input-toggle:hover {
            background: var(--sf-teal-100);
            color: var(--sf-teal-700);
        }

        .sf-login-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 6px 0 20px;
            font-size: 13px;
        }

        .sf-checkbox {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--sf-muted);
            font-weight: 600;
            cursor: pointer;
            user-select: none;
        }

        .sf-checkbox input {
            width: 16px;
            height: 16px;
            accent-color: var(--sf-teal-700);
            cursor: pointer;
        }

        .sf-forgot-link {
            color: var(--sf-teal-700);
            font-weight: 700;
            text-decoration: none;
            transition: color .2s ease;
        }

        .sf-forgot-link:hover { color: var(--sf-green-600); }

        .sf-btn-primary {
            width: 100%;
            height: 52px;
            border: none;
            border-radius: 14px;
            color: #ffffff;
            font-family: inherit;
            font-size: 14px;
            font-weight: 800;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            position: relative;
            overflow: hidden;
            background:
                linear-gradient(135deg, var(--sf-teal-700), var(--sf-green-600));
            background-size: 200% 200%;
            box-shadow: 0 10px 24px rgba(15, 118, 110, 0.25);
            transition: transform .18s ease, box-shadow .18s ease, background-position .6s ease;
        }

        .sf-btn-primary:hover {
            background-position: 100% 0;
            box-shadow: 0 14px 32px rgba(15, 118, 110, 0.32);
            transform: translateY(-1px);
        }

        .sf-btn-primary:active { transform: scale(0.98); }

        .sf-btn-primary:disabled {
            opacity: 0.75;
            cursor: not-allowed;
            transform: none;
        }

        .sf-btn-primary .spinner-border {
            width: 16px;
            height: 16px;
            border-width: 2px;
        }

        .sf-login-footer {
            margin-top: 28px;
            padding-top: 20px;
            border-top: 1px solid var(--sf-border);
            text-align: center;
            color: var(--sf-muted);
            font-size: 12.5px;
        }

        /* ================= Animations ================= */

        @keyframes sfLoginIn {
            from { opacity: 0; transform: translateY(18px) scale(0.98); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }

        @keyframes sfFadeUp {
            from { opacity: 0; transform: translateY(14px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        @keyframes sfFloat {
            0%, 100% { transform: translate3d(0, 0, 0) rotate(0deg); }
            50%      { transform: translate3d(-20px, 20px, 0) rotate(6deg); }
        }

        @keyframes sfDotPulse {
            0%   { box-shadow: 0 0 0 0 rgba(74, 222, 128, 0.55); }
            70%  { box-shadow: 0 0 0 8px rgba(74, 222, 128, 0); }
            100% { box-shadow: 0 0 0 0 rgba(74, 222, 128, 0); }
        }

        /* ================= Responsive ================= */

        @media (max-width: 991.98px) {
            .sf-login-card {
                min-height: auto;
                flex-direction: column;
                border-radius: 22px;
            }
            .sf-login-side { display: none; }
            .sf-login-form-side {
                padding: 40px 26px;
            }
            .sf-login-mobile-logo { display: flex; }
        }

        @media (max-width: 480px) {
            body { padding: 12px; }
            .sf-login-form-side { padding: 32px 20px; }
            .sf-login-title { font-size: 26px; }
            .sf-input { height: 48px; }
            .sf-btn-primary { height: 48px; }
        }

        @media (prefers-reduced-motion: reduce) {
            .sf-login-card,
            .sf-login-side::before,
            .sf-login-side::after,
            .sf-login-logo,
            .sf-login-side-text,
            .sf-login-side-footer,
            .sf-login-form-wrap,
            .sf-login-badge-dot {
                animation: none !important;
            }
            * { transition: none !important; }
        }
    </style>
</head>
<body>

<div class="sf-login-card">

    {{-- ============ Side Panel ============ --}}
    <aside class="sf-login-side">
        <div class="sf-login-side-inner">

            <div class="sf-login-logo">
                <img src="{{ asset('front/imag/super5.png') }}" alt="SuperFit">
            </div>

            <div class="sf-login-side-text">
                <span class="sf-login-badge">
                    <span class="sf-login-badge-dot"></span>
                    منصة SuperFit للأدمن
                </span>

                <h2>
                    طوّر أدائك،<br>
                    <span>وأدر منصتك</span>
                </h2>

                <p>
                    أدِر المستخدمين والمدربين والاشتراكات،
                    وتابع جميع عمليات منصة SuperFit من مكان واحد.
                </p>
            </div>

            <div class="sf-login-side-footer">
                <span>Smart Fitness Management</span>
            </div>

        </div>
    </aside>

    {{-- ============ Form Side ============ --}}
    <section class="sf-login-form-side">

        <div class="sf-login-form-wrap">

            <div class="sf-login-mobile-logo">
                <img src="{{ asset('front/imag/super5.png') }}" alt="SuperFit">
            </div>

            <p class="sf-login-kicker">مرحباً بعودتك</p>
            <h1 class="sf-login-title">تسجيل الدخول</h1>
            <p class="sf-login-desc">
                سجّل دخولك إلى حساب الأدمن للوصول إلى لوحة التحكم
            </p>

            @if ($errors->any())
                <div class="sf-login-alert" role="alert">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('admin.login.submit') }}" method="POST" id="sfLoginForm" novalidate>
                @csrf

                {{-- Name --}}
                <div class="sf-form-group">
                    <label for="name" class="sf-form-label">الاسم</label>
                    <div class="sf-input-wrap">
                        <i class="bi bi-person sf-input-icon" aria-hidden="true"></i>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            class="sf-input"
                            value="{{ old('name') }}"
                            placeholder="أدخل اسمك"
                            required
                            autocomplete="username"
                        >
                    </div>
                </div>

                {{-- Email --}}
                <div class="sf-form-group">
                    <label for="email" class="sf-form-label">البريد الإلكتروني</label>
                    <div class="sf-input-wrap">
                        <i class="bi bi-envelope sf-input-icon" aria-hidden="true"></i>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="sf-input"
                            value="{{ old('email') }}"
                            placeholder="أدخل بريدك الإلكتروني"
                            required
                            autocomplete="email"
                        >
                    </div>
                </div>

                {{-- Password --}}
                <div class="sf-form-group">
                    <label for="password" class="sf-form-label">كلمة المرور</label>
                    <div class="sf-input-wrap">
                        <i class="bi bi-lock sf-input-icon" aria-hidden="true"></i>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="sf-input"
                            placeholder="كلمة المرور"
                            required
                            autocomplete="current-password"
                        >
                        <button
                            type="button"
                            id="togglePassword"
                            class="sf-input-toggle"
                            aria-label="إظهار كلمة المرور"
                            aria-pressed="false"
                        >
                            <i id="passwordIcon" class="bi bi-eye-slash" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>

                {{-- Options --}}
                <div class="sf-login-options">
                    <label class="sf-checkbox">
                        <input type="checkbox" name="remember" value="1">
                        <span>تذكرني</span>
                    </label>

                    <a href="#" class="sf-forgot-link">نسيت كلمة المرور؟</a>
                </div>

                {{-- Submit --}}
                <button type="submit" class="sf-btn-primary" id="sfLoginBtn">
                    <span class="sf-btn-text">تسجيل الدخول</span>
                    <i class="bi bi-arrow-left sf-btn-arrow" aria-hidden="true"></i>
                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                </button>

            </form>

            <div class="sf-login-footer">
                SuperFit Admin Panel
            </div>

        </div>

    </section>

</div>

<script>
(function () {
    'use strict';

    // ============================================
    // SF Utilities
    // ============================================

    function sfCsrf() {
        var meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute('content') : '';
    }

    function sfSetLoading(button, isLoading, loadingText) {
        if (!button) return;
        var textEl = button.querySelector('.sf-btn-text');
        var spinner = button.querySelector('.spinner-border');
        var arrow = button.querySelector('.sf-btn-arrow');

        if (isLoading) {
            button.disabled = true;
            if (!button.dataset.sfOriginalText && textEl) {
                button.dataset.sfOriginalText = textEl.textContent;
            }
            if (textEl && loadingText) textEl.textContent = loadingText;
            if (spinner) spinner.classList.remove('d-none');
            if (arrow) arrow.classList.add('d-none');
        } else {
            button.disabled = false;
            if (textEl && button.dataset.sfOriginalText) {
                textEl.textContent = button.dataset.sfOriginalText;
            }
            if (spinner) spinner.classList.add('d-none');
            if (arrow) arrow.classList.remove('d-none');
        }
    }

    function sfTheme() {
        var toggle = document.getElementById('sfThemeToggle');
        var icon = document.getElementById('sfThemeIcon');

        function apply(theme) {
            document.documentElement.setAttribute('data-bs-theme', theme);
            try { localStorage.setItem('sf-theme', theme); } catch (e) {}
            if (icon) {
                icon.className = theme === 'dark' ? 'bi bi-sun' : 'bi bi-moon-stars';
            }
        }

        function current() {
            return document.documentElement.getAttribute('data-bs-theme') === 'dark' ? 'dark' : 'light';
        }

        if (icon) {
            icon.className = current() === 'dark' ? 'bi bi-sun' : 'bi bi-moon-stars';
        }

        if (toggle) {
            toggle.addEventListener('click', function () {
                apply(current() === 'dark' ? 'light' : 'dark');
            });
        }
    }

    // ============================================
    // Init
    // ============================================

    document.addEventListener('DOMContentLoaded', function () {

        sfTheme();

        // ============================================
        // Password Toggle
        // ============================================

        var togglePassword = document.getElementById('togglePassword');
        var passwordInput = document.getElementById('password');
        var passwordIcon = document.getElementById('passwordIcon');

        if (togglePassword && passwordInput && passwordIcon) {
            togglePassword.addEventListener('click', function () {
                var isPassword = passwordInput.type === 'password';
                passwordInput.type = isPassword ? 'text' : 'password';
                passwordIcon.classList.toggle('bi-eye', isPassword);
                passwordIcon.classList.toggle('bi-eye-slash', !isPassword);
                togglePassword.setAttribute('aria-pressed', String(isPassword));
                togglePassword.setAttribute('aria-label', isPassword ? 'إخفاء كلمة المرور' : 'إظهار كلمة المرور');
            });
        }

        // ============================================
        // Login Form Submit (Spinner + prevent double)
        // ============================================

        var form = document.getElementById('sfLoginForm');
        var btn = document.getElementById('sfLoginBtn');

        if (form && btn) {
            form.addEventListener('submit', function () {
                if (form.dataset.sfSubmitting === '1') return;
                form.dataset.sfSubmitting = '1';
                sfSetLoading(btn, true, 'جاري الدخول...');

                setTimeout(function () {
                    form.dataset.sfSubmitting = '0';
                    sfSetLoading(btn, false);
                }, 5000);
            });
        }
    });
})();
</script>

</body>
</html>
