<!DOCTYPE html>
<html lang="ar" dir="rtl" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>مراجعة الدفع — SuperFit</title>

    {{-- Google Fonts: Readex Pro --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Readex+Pro:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- Bootstrap 5.3 RTL --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        /* =========================================================
           SuperFit Theme Tokens
        ========================================================= */
        :root {
            --sf-green-900: #1f4d1f;
            --sf-green-700: #2e7d32;
            --sf-teal-700:  #0f766e;
            --sf-green-50:  #eef6ee;
            --sf-white:     #ffffff;
            --sf-page-bg:   #f6faf6;
            --sf-text:      #374151;
            --sf-muted:     #6b7280;
            --sf-border:    #d9e6d9;
            --sf-border-strong: #c7dcc7;
            --sf-warning:   #b45309;
            --sf-warning-bg:#fff7e6;
            --sf-warning-border: #f5deb3;
            --sf-success:   #166534;
            --sf-success-bg:#e7f6e9;
            --sf-success-border: #b8dfb9;
            --sf-danger:    #c0392b;
            --sf-danger-bg: #fdecea;
            --sf-danger-border: #f0c4bf;

            --sf-radius-card: 20px;
            --sf-radius-pill: 50rem;
            --sf-shadow-card: 0 6px 22px rgba(31, 77, 31, .05);
            --sf-shadow-hover: 0 12px 34px rgba(31, 77, 31, .10);
        }

        * { box-sizing: border-box; }

        html, body { min-height: 100%; margin: 0; padding: 0; }

        body {
            font-family: 'Readex Pro', 'Segoe UI', Tahoma, sans-serif;
            background: var(--sf-page-bg);
            color: var(--sf-text);
            font-size: 14.5px;
            line-height: 1.75;
            -webkit-font-smoothing: antialiased;
        }

        /* =========================================================
           Layout Frame
        ========================================================= */
        .sf-page {
            max-width: 1280px;
            margin: 28px auto 60px;
            padding: 0 20px;
        }

        .sf-frame {
            background: var(--sf-white);
            border: 1.5px solid var(--sf-green-900);
            border-radius: 26px;
            padding: 26px 26px 34px;
            box-shadow: var(--sf-shadow-card);
        }

        @media (max-width: 767.98px) {
            .sf-page { margin: 16px auto 40px; padding: 0 12px; }
            .sf-frame { padding: 18px 14px 26px; border-radius: 20px; }
        }

        /* =========================================================
           Breadcrumb + Page Header
        ========================================================= */
        .sf-breadcrumb {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
            font-size: 13px;
            color: var(--sf-muted);
            margin-bottom: 14px;
        }
        .sf-breadcrumb a {
            color: var(--sf-green-900);
            text-decoration: none;
            font-weight: 600;
            transition: color .2s ease;
        }
        .sf-breadcrumb a:hover { color: var(--sf-green-700); }
        .sf-breadcrumb .sf-sep { color: var(--sf-border-strong); }
        .sf-breadcrumb .sf-current { color: var(--sf-muted); font-weight: 500; }

        .sf-header {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            justify-content: space-between;
            align-items: flex-start;
            padding-bottom: 20px;
            border-bottom: 1px dashed var(--sf-border);
            margin-bottom: 26px;
        }
        .sf-header-title h1 {
            font-size: 26px;
            font-weight: 700;
            color: var(--sf-green-900);
            margin: 0 0 6px;
            letter-spacing: -.2px;
        }
        .sf-header-title p {
            color: var(--sf-muted);
            font-size: 13.5px;
            margin: 0;
        }

        /* =========================================================
           Badges (pill)
        ========================================================= */
        .sf-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 14px;
            border-radius: var(--sf-radius-pill);
            font-size: 12.5px;
            font-weight: 600;
            border: 1px solid transparent;
            white-space: nowrap;
        }
        .sf-badge i { font-size: 13px; }
        .sf-badge-warning {
            background: var(--sf-warning-bg);
            color: var(--sf-warning);
            border-color: var(--sf-warning-border);
        }
        .sf-badge-success {
            background: var(--sf-success-bg);
            color: var(--sf-success);
            border-color: var(--sf-success-border);
        }
        .sf-badge-danger {
            background: var(--sf-danger-bg);
            color: var(--sf-danger);
            border-color: var(--sf-danger-border);
        }
        .sf-badge-neutral {
            background: var(--sf-green-50);
            color: var(--sf-green-900);
            border-color: var(--sf-border);
        }

        /* =========================================================
           Grid
        ========================================================= */
        .sf-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.65fr) minmax(0, 1fr);
            gap: 22px;
            align-items: start;
        }
        .sf-col { display: flex; flex-direction: column; gap: 22px; min-width: 0; }

        @media (max-width: 991.98px) {
            .sf-grid { grid-template-columns: 1fr; }
        }

        /* =========================================================
           Card
        ========================================================= */
        .sf-card {
            background: var(--sf-white);
            border: 1px solid var(--sf-border);
            border-radius: var(--sf-radius-card);
            box-shadow: var(--sf-shadow-card);
            overflow: hidden;
            transition: box-shadow .25s ease, border-color .25s ease;
            opacity: 0;
            transform: translateY(10px);
            animation: sfCardIn .55s cubic-bezier(.22, .8, .28, 1) forwards;
            animation-delay: calc(var(--i, 0) * 55ms);
        }
        @media (hover: hover) {
            .sf-card:hover {
                box-shadow: var(--sf-shadow-hover);
                border-color: var(--sf-border-strong);
            }
        }
        @keyframes sfCardIn {
            to { opacity: 1; transform: translateY(0); }
        }

        .sf-card-head {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 16px 20px;
            border-bottom: 1px solid var(--sf-border);
            background: linear-gradient(180deg, rgba(238, 246, 238, .55), rgba(255, 255, 255, 0));
        }
        .sf-card-head i {
            color: var(--sf-green-900);
            font-size: 17px;
            line-height: 1;
        }
        .sf-card-head h2 {
            margin: 0;
            font-size: 15.5px;
            font-weight: 700;
            color: var(--sf-green-900);
            letter-spacing: -.1px;
        }
        .sf-card-head .sf-head-action {
            margin-inline-start: auto;
        }

        .sf-card-body { padding: 20px; }

        /* =========================================================
           Row (label + value)
        ========================================================= */
        .sf-row {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 14px;
            padding: 11px 0;
            border-bottom: 1px dashed var(--sf-border);
        }
        .sf-row:last-child { border-bottom: none; padding-bottom: 0; }
        .sf-row:first-child { padding-top: 0; }

        .sf-row-label {
            color: var(--sf-muted);
            font-size: 13px;
            font-weight: 500;
            flex-shrink: 0;
        }
        .sf-row-value {
            color: var(--sf-text);
            font-size: 13.5px;
            font-weight: 600;
            text-align: start;
            word-break: break-word;
            overflow-wrap: anywhere;
        }
        .sf-row-value.mono {
            font-family: 'Readex Pro', monospace;
            letter-spacing: .3px;
        }
        .sf-row-value.muted { color: var(--sf-muted); font-weight: 500; }

        /* =========================================================
           Amount (highlighted)
        ========================================================= */
        .sf-amount {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            padding: 14px 0 6px;
        }
        .sf-amount-label {
            font-size: 12px;
            color: var(--sf-muted);
            font-weight: 500;
            margin-bottom: 4px;
        }
        .sf-amount-value {
            font-size: 34px;
            font-weight: 700;
            color: var(--sf-green-900);
            line-height: 1.1;
            letter-spacing: -.5px;
        }
        .sf-amount-meta {
            margin-top: 4px;
            font-size: 12.5px;
            color: var(--sf-muted);
        }

        /* =========================================================
           User block
        ========================================================= */
        .sf-user {
            display: flex;
            align-items: center;
            gap: 14px;
            padding-bottom: 16px;
            border-bottom: 1px dashed var(--sf-border);
            margin-bottom: 16px;
        }
        .sf-avatar {
            width: 58px;
            height: 58px;
            border-radius: 50%;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--sf-green-50);
            color: var(--sf-green-900);
            font-size: 22px;
            font-weight: 700;
            border: 2px solid var(--sf-border);
            overflow: hidden;
        }
        .sf-avatar img { width: 100%; height: 100%; object-fit: cover; }
        .sf-user-info { min-width: 0; }
        .sf-user-name {
            font-size: 15px;
            font-weight: 700;
            color: var(--sf-green-900);
            margin: 0 0 2px;
        }
        .sf-user-sub {
            font-size: 12.5px;
            color: var(--sf-muted);
            margin: 0;
            word-break: break-word;
        }

        /* =========================================================
           Buttons (pill)
        ========================================================= */
        .sf-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: var(--sf-radius-pill);
            font-family: inherit;
            font-size: 13.5px;
            font-weight: 600;
            cursor: pointer;
            border: 1.5px solid transparent;
            text-decoration: none;
            line-height: 1.2;
            transition: background-color .2s ease, color .2s ease,
                        border-color .2s ease, box-shadow .2s ease, transform .18s ease;
            white-space: nowrap;
        }
        .sf-btn:focus-visible {
            outline: none;
            box-shadow: 0 0 0 4px rgba(31, 77, 31, .18);
        }
        .sf-btn:active { transform: scale(.97); }
        .sf-btn:disabled { opacity: .6; cursor: not-allowed; transform: none; }

        .sf-btn-primary {
            background: var(--sf-green-900);
            color: #fff;
            border-color: var(--sf-green-900);
        }
        .sf-btn-primary:hover:not(:disabled) {
            background: var(--sf-green-700);
            border-color: var(--sf-green-700);
            color: #fff;
        }

        .sf-btn-outline {
            background: transparent;
            color: var(--sf-green-900);
            border-color: var(--sf-border-strong);
        }
        .sf-btn-outline:hover:not(:disabled) {
            background: var(--sf-green-50);
            border-color: var(--sf-green-900);
            color: var(--sf-green-900);
        }

        .sf-btn-danger {
            background: transparent;
            color: var(--sf-danger);
            border-color: var(--sf-danger-border);
        }
        .sf-btn-danger:hover:not(:disabled) {
            background: var(--sf-danger);
            color: #fff;
            border-color: var(--sf-danger);
        }

        .sf-btn-sm {
            padding: 7px 14px;
            font-size: 12.5px;
        }

        .sf-btn-block { width: 100%; }

        /* =========================================================
           Receipt image
        ========================================================= */
        .sf-receipt-wrap {
            background: var(--sf-green-50);
            border: 1px solid var(--sf-border);
            border-radius: 16px;
            padding: 12px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 220px;
            overflow: hidden;
        }
        .sf-receipt-img {
            display: block;
            max-width: 100%;
            max-height: 520px;
            border-radius: 12px;
            border: 1px solid var(--sf-border-strong);
            background: #fff;
            cursor: zoom-in;
            transition: transform .3s ease, box-shadow .3s ease;
        }
        @media (hover: hover) {
            .sf-receipt-img:hover {
                transform: scale(1.01);
                box-shadow: 0 12px 34px rgba(31, 77, 31, .12);
            }
        }
        .sf-receipt-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 14px;
        }

        /* =========================================================
           Empty state
        ========================================================= */
        .sf-empty {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px 18px;
            text-align: center;
            color: var(--sf-muted);
            gap: 8px;
        }
        .sf-empty-ic {
            width: 62px;
            height: 62px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--sf-green-50);
            color: var(--sf-green-900);
            font-size: 26px;
            margin-bottom: 4px;
        }
        .sf-empty h3 {
            font-size: 14.5px;
            font-weight: 700;
            color: var(--sf-text);
            margin: 0;
        }
        .sf-empty p {
            font-size: 12.5px;
            margin: 0;
            color: var(--sf-muted);
        }

        /* =========================================================
           Checklist
        ========================================================= */
        .sf-check {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 12px;
            cursor: pointer;
            user-select: none;
            transition: background-color .18s ease;
        }
        .sf-check:hover { background: rgba(238, 246, 238, .6); }
        .sf-check input[type="checkbox"] {
            appearance: none;
            -webkit-appearance: none;
            width: 20px;
            height: 20px;
            margin: 2px 0 0;
            border-radius: 6px;
            border: 1.5px solid var(--sf-border-strong);
            background: #fff;
            cursor: pointer;
            flex-shrink: 0;
            position: relative;
            transition: background-color .18s ease, border-color .18s ease;
        }
        .sf-check input[type="checkbox"]:checked {
            background: var(--sf-green-900);
            border-color: var(--sf-green-900);
        }
        .sf-check input[type="checkbox"]:checked::after {
            content: '';
            position: absolute;
            inset: 0;
            background: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='white'><path d='M12.7 4.3a1 1 0 0 1 0 1.4l-6.4 6.4a1 1 0 0 1-1.4 0L1.3 8.5a1 1 0 0 1 1.4-1.4L5 9.4l5.7-5.7a1 1 0 0 1 1.4 0z'/></svg>") center / 14px 14px no-repeat;
        }
        .sf-check input[type="checkbox"]:focus-visible {
            outline: none;
            box-shadow: 0 0 0 3px rgba(31, 77, 31, .18);
        }
        .sf-check span { font-size: 13.5px; color: var(--sf-text); line-height: 1.7; }

        /* =========================================================
           Textarea / inputs
        ========================================================= */
        .sf-field { margin-bottom: 16px; }
        .sf-field:last-child { margin-bottom: 0; }
        .sf-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--sf-text);
            margin-bottom: 8px;
        }
        .sf-label .sf-req { color: var(--sf-danger); margin-inline-start: 3px; }
        .sf-input,
        .sf-textarea,
        .sf-select {
            width: 100%;
            padding: 11px 14px;
            border-radius: 12px;
            border: 1.5px solid var(--sf-border);
            background: #fff;
            color: var(--sf-text);
            font-family: inherit;
            font-size: 13.5px;
            line-height: 1.6;
            outline: none;
            transition: border-color .2s ease, box-shadow .2s ease;
        }
        .sf-input:focus,
        .sf-textarea:focus,
        .sf-select:focus {
            border-color: var(--sf-green-900);
            box-shadow: 0 0 0 4px rgba(31, 77, 31, .10);
        }
        .sf-textarea { min-height: 96px; resize: vertical; }
        .sf-select { cursor: pointer; background-image: none; }

        .sf-help {
            display: block;
            margin-top: 6px;
            font-size: 12px;
            color: var(--sf-muted);
        }
        .sf-error {
            display: none;
            margin-top: 6px;
            font-size: 12.5px;
            color: var(--sf-danger);
            font-weight: 600;
        }
        .sf-error.is-visible { display: block; }
        .sf-field.has-error .sf-select,
        .sf-field.has-error .sf-textarea {
            border-color: var(--sf-danger);
            box-shadow: 0 0 0 4px rgba(192, 57, 43, .10);
        }

        /* =========================================================
           Timeline
        ========================================================= */
        .sf-timeline {
            position: relative;
            padding-inline-start: 8px;
        }
        .sf-tl-item {
            display: flex;
            gap: 14px;
            padding-bottom: 22px;
            position: relative;
        }
        .sf-tl-item:last-child { padding-bottom: 0; }
        .sf-tl-item::before {
            content: '';
            position: absolute;
            inset-inline-start: 17px;
            top: 36px;
            bottom: 0;
            width: 2px;
            background: var(--sf-border);
        }
        .sf-tl-item:last-child::before { display: none; }
        .sf-tl-icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--sf-green-50);
            color: var(--sf-green-900);
            border: 1.5px solid var(--sf-border);
            font-size: 15px;
            position: relative;
            z-index: 1;
        }
        .sf-tl-icon.success {
            background: var(--sf-success-bg);
            color: var(--sf-success);
            border-color: var(--sf-success-border);
        }
        .sf-tl-icon.danger {
            background: var(--sf-danger-bg);
            color: var(--sf-danger);
            border-color: var(--sf-danger-border);
        }
        .sf-tl-icon.warning {
            background: var(--sf-warning-bg);
            color: var(--sf-warning);
            border-color: var(--sf-warning-border);
        }
        .sf-tl-content { flex: 1; min-width: 0; padding-top: 4px; }
        .sf-tl-title {
            font-size: 13.5px;
            font-weight: 600;
            color: var(--sf-text);
            margin: 0 0 2px;
        }
        .sf-tl-meta {
            font-size: 12px;
            color: var(--sf-muted);
            margin: 0;
        }
        .sf-tl-meta strong {
            color: var(--sf-green-900);
            font-weight: 600;
        }

        /* =========================================================
           Alerts
        ========================================================= */
        .sf-alert {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 14px 16px;
            border-radius: 14px;
            font-size: 13.5px;
            border: 1px solid transparent;
            margin-bottom: 20px;
        }
        .sf-alert i:first-child {
            font-size: 18px;
            line-height: 1;
            flex-shrink: 0;
            margin-top: 2px;
        }
        .sf-alert-success {
            background: var(--sf-success-bg);
            color: var(--sf-success);
            border-color: var(--sf-success-border);
        }
        .sf-alert-danger {
            background: var(--sf-danger-bg);
            color: var(--sf-danger);
            border-color: var(--sf-danger-border);
        }
        .sf-alert-warning {
            background: var(--sf-warning-bg);
            color: var(--sf-warning);
            border-color: var(--sf-warning-border);
        }
        .sf-alert strong { font-weight: 700; }
        .sf-alert p { margin: 0; }

        /* =========================================================
           Action bar
        ========================================================= */
        .sf-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            justify-content: flex-end;
            padding: 18px 20px;
            background: var(--sf-white);
            border: 1px solid var(--sf-border);
            border-radius: var(--sf-radius-card);
            box-shadow: var(--sf-shadow-card);
            margin-top: 22px;
            opacity: 0;
            transform: translateY(10px);
            animation: sfCardIn .55s cubic-bezier(.22, .8, .28, 1) forwards;
            animation-delay: 380ms;
        }
        @media (max-width: 575.98px) {
            .sf-actions { flex-direction: column-reverse; }
            .sf-actions .sf-btn { width: 100%; }
        }

        /* =========================================================
           Modal
        ========================================================= */
        .modal-content {
            border: 1px solid var(--sf-border);
            border-radius: 20px;
            box-shadow: 0 22px 60px rgba(31, 77, 31, .14);
        }
        .modal-header {
            border-bottom: 1px solid var(--sf-border);
            padding: 18px 22px;
            align-items: center;
        }
        .modal-header h5 {
            margin: 0;
            font-size: 16px;
            font-weight: 700;
            color: var(--sf-green-900);
        }
        .modal-body { padding: 20px 22px; }
        .modal-footer {
            border-top: 1px solid var(--sf-border);
            padding: 14px 22px;
            gap: 8px;
        }
        .modal-body p { margin: 0 0 10px; color: var(--sf-text); font-size: 13.5px; }
        .modal-body p:last-child { margin-bottom: 0; }
        .modal-body .sf-modal-note {
            padding: 12px 14px;
            border-radius: 12px;
            background: var(--sf-green-50);
            color: var(--sf-green-900);
            font-size: 13px;
            font-weight: 600;
            display: flex;
            gap: 8px;
            align-items: flex-start;
        }
        .modal-body .sf-modal-note i { margin-top: 2px; }

        /* =========================================================
           Image preview modal
        ========================================================= */
        #sfImageModal .modal-dialog {
            max-width: 96vw;
        }
        .sf-img-stage {
            position: relative;
            overflow: auto;
            max-height: 78vh;
            background: #0f1a0f;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 12px;
        }
        .sf-img-stage img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            transition: transform .25s ease;
            transform-origin: center center;
        }
        .sf-zoom-bar {
            display: flex;
            gap: 8px;
            justify-content: center;
            margin-top: 14px;
        }

        /* =========================================================
           Reduced motion
        ========================================================= */
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: .001ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: .001ms !important;
            }
            .sf-card, .sf-actions { opacity: 1; transform: none; }
            .sf-receipt-img:hover { transform: none; }
        }
    </style>
</head>
<body>

{{-- @php
    /* =========================================================
       Defensive extraction (no business logic, just safe access)
    ========================================================= */
    $payment = $payment ?? null;

    $paymentId        = $payment->id ?? null;
    $paymentCode      = $payment->code ?? ($paymentId ? 'PAY-' . $paymentId : 'PAY-—');
    $paymentStatus    = strtolower($payment->status ?? 'pending'); // pending|confirmed|rejected
    $paymentAmount    = $payment->amount ?? null;
    $paymentCurrency  = $payment->currency ?? 'USD';
    $paymentCreated   = $payment->created_at ?? null;
    $paymentReceipt   = $payment->receipt_image ?? null;
    $paymentNotes     = $payment->admin_notes ?? '';
    $paymentRefNumber = $payment->reference_number ?? null;
    $paymentTransferDate = $payment->transfer_date ?? null;
    $paymentRejectionReason = $payment->rejection_reason ?? null;

    $user         = $payment->user ?? null;
    $paymentMethod = $payment->paymentMethod ?? null;
    $subscription = $payment->subscription ?? null;

    $userName  = $user->name ?? 'غير متوفر';
    $userEmail = $user->email ?? null;
    $userPhone = $user->phone ?? ($user->profile->phone ?? null);
    $userPhoto = $user->profile_photo ?? ($user->profile->profile_photo ?? null);

    $methodName     = $paymentMethod->name ?? ($payment->method_name ?? 'غير متوفر');
    $beneficiary    = $paymentMethod->beneficiary_name ?? 'SuperFit';
    $beneficiaryNum = $paymentMethod->beneficiary_number ?? null;

    $subPlan    = $subscription->plan_name ?? ($subscription->plan->name ?? null);
    $subDuration = $subscription->duration ?? null;
    $subPrice   = $subscription->price ?? $paymentAmount;
    $subCode    = $subscription->code ?? ($subscription->id ? 'SUB-' . $subscription->id : null);
    $subStatus  = $subscription->status ?? null;

    // Confirmation / rejection metadata
    $confirmedByName = $payment->confirmedBy->name ?? $payment->confirmed_by_name ?? null;
    $confirmedAt     = $payment->confirmed_at ?? null;
    $rejectedByName  = $payment->rejectedBy->name ?? $payment->rejected_by_name ?? null;
    $rejectedAt      = $payment->rejected_at ?? null;

    // Formatting helpers
    $fmtAmount = function ($amount, $currency = 'USD') {
        if ($amount === null || $amount === '') return '—';
        $symbol = $currency === 'USD' ? '$' : ($currency === 'ILS' ? '₪' : $currency . ' ');
        return $symbol . number_format((float) $amount, 2);
    };
    $fmtDate = function ($value, $withTime = true) {
        if (!$value) return 'غير متوفر';
        try {
            $dt = $value instanceof \Carbon\Carbon ? $value : \Carbon\Carbon::parse($value);
            return $withTime
                ? $dt->translatedFormat('d F Y، h:i a')
                : $dt->translatedFormat('d F Y');
        } catch (\Throwable $e) {
            return 'غير متوفر';
        }
    };

    $statusMeta = [
        'pending'   => ['label' => 'قيد المراجعة',   'class' => 'sf-badge-warning', 'icon' => 'bi-hourglass-split'],
        'confirmed' => ['label' => 'تم تأكيد الدفع', 'class' => 'sf-badge-success', 'icon' => 'bi-check-circle-fill'],
        'rejected'  => ['label' => 'مرفوض',          'class' => 'sf-badge-danger',  'icon' => 'bi-x-circle-fill'],
    ][$paymentStatus] ?? ['label' => 'قيد المراجعة', 'class' => 'sf-badge-warning', 'icon' => 'bi-hourglass-split'];
@endphp --}}

@php
    /* =========================================================
       Dummy Data for Frontend Preview (بيانات وهمية للمعاينة)
    ========================================================= */
    $payment = $payment ?? (object)[
        'id' => 101,
        'code' => 'PAY-101',
        'status' => 'pending', // يمكنك تغييرها إلى 'confirmed' أو 'rejected' لاختبار التصميم
        'amount' => 150.00,
        'currency' => 'USD',
        'created_at' => now(),
        'receipt_image' => null,
        'admin_notes' => 'ملاحظات تجريبية للمعاينة',
        'reference_number' => 'REF-987654321',
        'transfer_date' => now()->subDays(1),
        'rejection_reason' => null,
        'user' => (object)[
            'id' => 1,
            'name' => 'أحمد محمود',
            'email' => 'ahmed@example.com',
            'phone' => '0599000000',
            'profile_photo' => null,
        ],
        'paymentMethod' => (object)[
            'name' => 'تحويل بنكي (PalPay)',
            'beneficiary_name' => 'SuperFit Co.',
            'beneficiary_number' => 'PS123456789',
        ],
        'subscription' => (object)[
            'id' => 50,
            'plan_name' => 'الاشتراك الذهبي - 3 أشهر',
            'duration' => '3 أشهر',
            'price' => 150.00,
            'code' => 'SUB-50',
            'status' => 'pending',
        ],
        'confirmedBy' => (object)['name' => 'المدير العام'],
        'confirmed_at' => null,
        'rejectedBy' => (object)['name' => 'المدير العام'],
        'rejected_at' => null,
    ];

    /* Extraction Variables */
    $paymentId        = $payment->id ?? null;
    $paymentCode      = $payment->code ?? ($paymentId ? 'PAY-' . $paymentId : 'PAY-—');
    $paymentStatus    = strtolower($payment->status ?? 'pending');
    $paymentAmount    = $payment->amount ?? null;
    $paymentCurrency  = $payment->currency ?? 'USD';
    $paymentCreated   = $payment->created_at ?? null;
    $paymentReceipt   = $payment->receipt_image ?? null;
    $paymentNotes     = $payment->admin_notes ?? '';
    $paymentRefNumber = $payment->reference_number ?? null;
    $paymentTransferDate = $payment->transfer_date ?? null;
    $paymentRejectionReason = $payment->rejection_reason ?? null;

    $user         = $payment->user ?? null;
    $paymentMethod = $payment->paymentMethod ?? null;
    $subscription = $payment->subscription ?? null;

    $userName  = $user->name ?? 'غير متوفر';
    $userEmail = $user->email ?? null;
    $userPhone = $user->phone ?? ($user->profile->phone ?? null);
    $userPhoto = $user->profile_photo ?? ($user->profile->profile_photo ?? null);

    $methodName     = $paymentMethod->name ?? ($payment->method_name ?? 'غير متوفر');
    $beneficiary    = $paymentMethod->beneficiary_name ?? 'SuperFit';
    $beneficiaryNum = $paymentMethod->beneficiary_number ?? null;

    $subPlan    = $subscription->plan_name ?? ($subscription->plan->name ?? null);
    $subDuration = $subscription->duration ?? null;
    $subPrice   = $subscription->price ?? $paymentAmount;
    $subCode    = $subscription->code ?? ($subscription->id ? 'SUB-' . $subscription->id : null);
    $subStatus  = $subscription->status ?? null;

    $confirmedByName = $payment->confirmedBy->name ?? $payment->confirmed_by_name ?? null;
    $confirmedAt     = $payment->confirmed_at ?? null;
    $rejectedByName  = $payment->rejectedBy->name ?? $payment->rejected_by_name ?? null;
    $rejectedAt      = $payment->rejected_at ?? null;

    $fmtAmount = function ($amount, $currency = 'USD') {
        if ($amount === null || $amount === '') return '—';
        $symbol = $currency === 'USD' ? '$' : ($currency === 'ILS' ? '₪' : $currency . ' ');
        return $symbol . number_format((float) $amount, 2);
    };

    $fmtDate = function ($value, $withTime = true) {
        if (!$value) return 'غير متوفر';
        try {
            $dt = $value instanceof \Carbon\Carbon ? $value : \Carbon\Carbon::parse($value);
            return $withTime
                ? $dt->translatedFormat('d F Y، h:i a')
                : $dt->translatedFormat('d F Y');
        } catch (\Throwable $e) {
            return 'غير متوفر';
        }
    };

    $statusMeta = [
        'pending'   => ['label' => 'قيد المراجعة',   'class' => 'sf-badge-warning', 'icon' => 'bi-hourglass-split'],
        'confirmed' => ['label' => 'تم تأكيد الدفع', 'class' => 'sf-badge-success', 'icon' => 'bi-check-circle-fill'],
        'rejected'  => ['label' => 'مرفوض',          'class' => 'sf-badge-danger',  'icon' => 'bi-x-circle-fill'],
    ][$paymentStatus] ?? ['label' => 'قيد المراجعة', 'class' => 'sf-badge-warning', 'icon' => 'bi-hourglass-split'];
@endphp



    <main class="sf-page" role="main">

    <div class="sf-frame">

        {{-- =========================================================
             Breadcrumb
        ========================================================= --}}
        <nav class="sf-breadcrumb" aria-label="مسار التنقل">
            <a href="{{ url('/') }}">الرئيسية</a>
            <span class="sf-sep">/</span>
            <a href="#">المدفوعات</a>
            <span class="sf-sep">/</span>
            <span class="sf-current">تفاصيل الدفع {{ $paymentCode ? '#' . ltrim($paymentCode, '#') : '' }}</span>
        </nav>

        {{-- =========================================================
             Page Header
        ========================================================= --}}
        <header class="sf-header">
            <div class="sf-header-title">
                <h1>مراجعة الدفع</h1>
                <p>راجع تفاصيل عملية الدفع وإشعار التحويل قبل تأكيد العملية.</p>
            </div>
            <span class="sf-badge {{ $statusMeta['class'] }}" role="status">
                <i class="bi {{ $statusMeta['icon'] }}" aria-hidden="true"></i>
                {{ $statusMeta['label'] }}
            </span>
        </header>

        {{-- =========================================================
             Missing payment (top-level empty state)
        ========================================================= --}}
        @if(!$payment)
            <div class="sf-alert sf-alert-danger" role="alert">
                <i class="bi bi-exclamation-octagon-fill" aria-hidden="true"></i>
                <div>
                    <strong>لم يتم العثور على عملية الدفع المطلوبة.</strong>
                    <p>تحقق من رقم العملية أو عد إلى قائمة المدفوعات.</p>
                </div>
            </div>
        @else

            {{-- Confirmed banner --}}
            @if($paymentStatus === 'confirmed')
                <div class="sf-alert sf-alert-success" role="status">
                    <i class="bi bi-patch-check-fill" aria-hidden="true"></i>
                    <div>
                        <strong>تم تأكيد الدفع بواسطة {{ $confirmedByName ?? 'الأدمن' }}</strong>
                        <p>تاريخ التأكيد: {{ $fmtDate($confirmedAt) }}</p>
                    </div>
                </div>
            @endif

            {{-- Rejected banner --}}
            @if($paymentStatus === 'rejected')
                <div class="sf-alert sf-alert-danger" role="alert">
                    <i class="bi bi-x-octagon-fill" aria-hidden="true"></i>
                    <div>
                        <strong>تم رفض الدفع بواسطة {{ $rejectedByName ?? 'الأدمن' }}</strong>
                        <p>تاريخ الرفض: {{ $fmtDate($rejectedAt) }}</p>
                        @if($paymentRejectionReason)
                            <p>سبب الرفض: {{ $paymentRejectionReason }}</p>
                        @endif
                    </div>
                </div>
            @endif

            {{-- =========================================================
                 Two-column grid
            ========================================================= --}}
            <div class="sf-grid">

                {{-- =================================================
                     Main column
                ================================================= --}}
                <section class="sf-col" aria-label="تفاصيل الدفع">

                    {{-- 1. ملخص عملية الدفع --}}
                    <article class="sf-card" style="--i:0">
                        <div class="sf-card-head">
                            <i class="bi bi-receipt-cutoff" aria-hidden="true"></i>
                            <h2>ملخص عملية الدفع</h2>
                        </div>
                        <div class="sf-card-body">

                            <div class="sf-amount">
                                <span class="sf-amount-label">المبلغ المطلوب</span>
                                <span class="sf-amount-value">{{ $fmtAmount($paymentAmount, $paymentCurrency) }}</span>
                                <span class="sf-amount-meta">
                                    بتاريخ الإرسال: {{ $fmtDate($paymentCreated) }}
                                </span>
                            </div>

                            <div class="sf-row">
                                <span class="sf-row-label">رقم العملية</span>
                                <span class="sf-row-value mono">#{{ ltrim($paymentCode, '#') }}</span>
                            </div>
                            <div class="sf-row">
                                <span class="sf-row-label">طريقة الدفع</span>
                                <span class="sf-row-value">{{ $methodName }}</span>
                            </div>
                            <div class="sf-row">
                                <span class="sf-row-label">حالة الدفع</span>
                                <span class="sf-row-value">
                                    <span class="sf-badge {{ $statusMeta['class'] }}">
                                        <i class="bi {{ $statusMeta['icon'] }}" aria-hidden="true"></i>
                                        {{ $statusMeta['label'] }}
                                    </span>
                                </span>
                            </div>
                            <div class="sf-row">
                                <span class="sf-row-label">تاريخ الإرسال</span>
                                <span class="sf-row-value">{{ $fmtDate($paymentCreated) }}</span>
                            </div>

                        </div>
                    </article>

                    {{-- 2. تفاصيل الاشتراك --}}
                    <article class="sf-card" style="--i:1">
                        <div class="sf-card-head">
                            <i class="bi bi-card-checklist" aria-hidden="true"></i>
                            <h2>تفاصيل الاشتراك</h2>
                        </div>
                        <div class="sf-card-body">
                            @if($subscription)
                                <div class="sf-row">
                                    <span class="sf-row-label">اسم الباقة</span>
                                    <span class="sf-row-value">{{ $subPlan ?? 'غير متوفر' }}</span>
                                </div>
                                <div class="sf-row">
                                    <span class="sf-row-label">مدة الاشتراك</span>
                                    <span class="sf-row-value">{{ $subDuration ?? 'غير متوفر' }}</span>
                                </div>
                                <div class="sf-row">
                                    <span class="sf-row-label">السعر المتوقع</span>
                                    <span class="sf-row-value">{{ $fmtAmount($subPrice, $paymentCurrency) }}</span>
                                </div>
                                <div class="sf-row">
                                    <span class="sf-row-label">حالة الاشتراك</span>
                                    <span class="sf-row-value">
                                        @if($paymentStatus === 'pending')
                                            <span class="sf-badge sf-badge-warning">
                                                <i class="bi bi-hourglass-split" aria-hidden="true"></i>
                                                بانتظار تأكيد الدفع
                                            </span>
                                        @elseif($paymentStatus === 'confirmed')
                                            <span class="sf-badge sf-badge-success">
                                                <i class="bi bi-check-circle-fill" aria-hidden="true"></i>
                                                مُفعّل
                                            </span>
                                        @else
                                            <span class="sf-badge sf-badge-danger">
                                                <i class="bi bi-x-circle-fill" aria-hidden="true"></i>
                                                غير مُفعّل
                                            </span>
                                        @endif
                                    </span>
                                </div>
                                <div class="sf-row">
                                    <span class="sf-row-label">رقم الاشتراك</span>
                                    <span class="sf-row-value mono">
                                        {{ $subCode ? '#' . ltrim($subCode, '#') : 'غير متوفر' }}
                                    </span>
                                </div>
                            @else
                                <div class="sf-empty">
                                    <div class="sf-empty-ic"><i class="bi bi-card-checklist" aria-hidden="true"></i></div>
                                    <h3>لا توجد بيانات اشتراك</h3>
                                    <p>لم يتم العثور على اشتراك مرتبط بعملية الدفع.</p>
                                </div>
                            @endif
                        </div>
                    </article>

                    {{-- 3. تفاصيل التحويل --}}
                    <article class="sf-card" style="--i:2">
                        <div class="sf-card-head">
                            <i class="bi bi-arrow-left-right" aria-hidden="true"></i>
                            <h2>تفاصيل التحويل</h2>
                        </div>
                        <div class="sf-card-body">
                            <div class="sf-row">
                                <span class="sf-row-label">مبلغ التحويل</span>
                                <span class="sf-row-value">{{ $fmtAmount($paymentAmount, $paymentCurrency) }}</span>
                            </div>
                            <div class="sf-row">
                                <span class="sf-row-label">رقم المرجع</span>
                                <span class="sf-row-value mono">
                                    {{ $paymentRefNumber ?: 'غير متوفر' }}
                                </span>
                            </div>
                            <div class="sf-row">
                                <span class="sf-row-label">تاريخ التحويل</span>
                                <span class="sf-row-value">
                                    {{ $paymentTransferDate ? $fmtDate($paymentTransferDate, false) : 'غير متوفر' }}
                                </span>
                            </div>
                            <div class="sf-row">
                                <span class="sf-row-label">طريقة الدفع</span>
                                <span class="sf-row-value">{{ $methodName }}</span>
                            </div>
                            <div class="sf-row">
                                <span class="sf-row-label">المستفيد</span>
                                <span class="sf-row-value mono">
                                    {{ $beneficiaryNum ?: $beneficiary }}
                                </span>
                            </div>
                        </div>
                    </article>

                    {{-- 4. إشعار الدفع --}}
                    <article class="sf-card" style="--i:3">
                        <div class="sf-card-head">
                            <i class="bi bi-image" aria-hidden="true"></i>
                            <h2>إشعار الدفع</h2>
                        </div>
                        <div class="sf-card-body">

                            @if($paymentReceipt)
                                <div class="sf-receipt-wrap">
                                    <img
                                        src="{{ asset('storage/' . $paymentReceipt) }}"
                                        alt="إشعار الدفع لعملية {{ $paymentCode }}"
                                        class="sf-receipt-img"
                                        id="sfReceiptThumb"
                                        loading="lazy"
                                    >
                                </div>
                                <div class="sf-receipt-actions">
                                    <button type="button" class="sf-btn sf-btn-outline sf-btn-sm" id="sfOpenFullImg">
                                        <i class="bi bi-arrows-fullscreen" aria-hidden="true"></i>
                                        عرض بالحجم الكامل
                                    </button>
                                </div>
                            @else
                                <div class="sf-empty">
                                    <div class="sf-empty-ic"><i class="bi bi-image" aria-hidden="true"></i></div>
                                    <h3>لم يتم رفع إشعار دفع لهذه العملية.</h3>
                                    <p>يمكنك مراجعة البيانات الأخرى أو التواصل مع المستخدم لطلب الإشعار.</p>
                                </div>
                            @endif

                        </div>
                    </article>

                    {{-- ملاحظات الأدمن --}}
                    <article class="sf-card" style="--i:4">
                        <div class="sf-card-head">
                            <i class="bi bi-journal-text" aria-hidden="true"></i>
                            <h2>ملاحظات الأدمن</h2>
                        </div>
                        <div class="sf-card-body">

                            @if($paymentStatus === 'rejected' && $paymentRejectionReason)
                                <div class="sf-field">
                                    <label class="sf-label">سبب الرفض</label>
                                    <div class="sf-alert sf-alert-danger" style="margin: 0;">
                                        <i class="bi bi-info-circle-fill" aria-hidden="true"></i>
                                        <p>{{ $paymentRejectionReason }}</p>
                                    </div>
                                </div>
                            @endif

                            <div class="sf-field">
                                <label class="sf-label" for="sfAdminNotes">ملاحظتك حول عملية الدفع</label>
                                <textarea
                                    id="sfAdminNotes"
                                    name="admin_notes"
                                    class="sf-textarea"
                                    placeholder="اكتب ملاحظتك حول عملية الدفع..."
                                    @if($paymentStatus !== 'pending') disabled @endif
                                >{{ old('admin_notes', $paymentNotes) }}</textarea>
                                @if($paymentStatus !== 'pending')
                                    <span class="sf-help">تم إغلاق العملية، لا يمكن إضافة ملاحظات جديدة.</span>
                                @endif
                            </div>

                        </div>
                    </article>

                    {{-- سجل العملية --}}
                    <article class="sf-card" style="--i:5">
                        <div class="sf-card-head">
                            <i class="bi bi-clock-history" aria-hidden="true"></i>
                            <h2>سجل العملية</h2>
                        </div>
                        <div class="sf-card-body">
                            <div class="sf-timeline">

                                @if($paymentCreated)
                                    <div class="sf-tl-item">
                                        <div class="sf-tl-icon warning" aria-hidden="true">
                                            <i class="bi bi-upload"></i>
                                        </div>
                                        <div class="sf-tl-content">
                                            <p class="sf-tl-title">تم إرسال إشعار الدفع</p>
                                            <p class="sf-tl-meta">
                                                {{ $fmtDate($paymentCreated) }} —
                                                بواسطة <strong>{{ $userName }}</strong>
                                            </p>
                                        </div>
                                    </div>
                                @endif

                                @if($paymentStatus === 'confirmed' && $confirmedAt)
                                    <div class="sf-tl-item">
                                        <div class="sf-tl-icon success" aria-hidden="true">
                                            <i class="bi bi-check-lg"></i>
                                        </div>
                                        <div class="sf-tl-content">
                                            <p class="sf-tl-title">تم تأكيد عملية الدفع</p>
                                            <p class="sf-tl-meta">
                                                {{ $fmtDate($confirmedAt) }} —
                                                بواسطة <strong>{{ $confirmedByName ?? 'الأدمن' }}</strong>
                                            </p>
                                        </div>
                                    </div>
                                @endif

                                @if($paymentStatus === 'rejected' && $rejectedAt)
                                    <div class="sf-tl-item">
                                        <div class="sf-tl-icon danger" aria-hidden="true">
                                            <i class="bi bi-x-lg"></i>
                                        </div>
                                        <div class="sf-tl-content">
                                            <p class="sf-tl-title">تم رفض عملية الدفع</p>
                                            <p class="sf-tl-meta">
                                                {{ $fmtDate($rejectedAt) }} —
                                                بواسطة <strong>{{ $rejectedByName ?? 'الأدمن' }}</strong>
                                            </p>
                                            @if($paymentRejectionReason)
                                                <p class="sf-tl-meta" style="margin-top:4px;">
                                                    السبب: {{ $paymentRejectionReason }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                @if($paymentStatus === 'pending')
                                    <div class="sf-tl-item">
                                        <div class="sf-tl-icon" aria-hidden="true">
                                            <i class="bi bi-hourglass-split"></i>
                                        </div>
                                        <div class="sf-tl-content">
                                            <p class="sf-tl-title">بانتظار المراجعة</p>
                                            <p class="sf-tl-meta">
                                                العملية بانتظار قرار الأدمن.
                                            </p>
                                        </div>
                                    </div>
                                @endif

                            </div>
                        </div>
                    </article>

                </section>

                {{-- =================================================
                     Sidebar column
                ================================================= --}}
                <aside class="sf-col" aria-label="معلومات إضافية">

                    {{-- معلومات المستخدم --}}
                    <article class="sf-card" style="--i:0">
                        <div class="sf-card-head">
                            <i class="bi bi-person-circle" aria-hidden="true"></i>
                            <h2>معلومات المستخدم</h2>
                        </div>
                        <div class="sf-card-body">

                            @if($user)
                                <div class="sf-user">
                                    <div class="sf-avatar" aria-hidden="true">
                                        @if($userPhoto)
                                            <img src="{{ asset('storage/' . $userPhoto) }}" alt="">
                                        @else
                                            {{ mb_substr($userName, 0, 1, 'UTF-8') }}
                                        @endif
                                    </div>
                                    <div class="sf-user-info">
                                        <p class="sf-user-name">{{ $userName }}</p>
                                        <p class="sf-user-sub">{{ $userEmail ?? 'بريد غير متوفر' }}</p>
                                    </div>
                                </div>

                                <div class="sf-row">
                                    <span class="sf-row-label">رقم الهاتف</span>
                                    <span class="sf-row-value mono">
                                        {{ $userPhone ?: 'غير متوفر' }}
                                    </span>
                                </div>
                                <div class="sf-row">
                                    <span class="sf-row-label">رقم المستخدم</span>
                                    <span class="sf-row-value mono">
                                        #{{ $user->id ?? '—' }}
                                    </span>
                                </div>

                                @if($user->id ?? null)
                                    <div style="margin-top:16px;">
                                        <a href="{{ url('/admin/users/' . $user->id) }}"
                                           class="sf-btn sf-btn-outline sf-btn-sm">
                                            <i class="bi bi-person-lines-fill" aria-hidden="true"></i>
                                            عرض الملف الشخصي
                                        </a>
                                    </div>
                                @endif
                            @else
                                <div class="sf-empty">
                                    <div class="sf-empty-ic"><i class="bi bi-person" aria-hidden="true"></i></div>
                                    <h3>لا توجد بيانات مستخدم</h3>
                                    <p>لم يتم العثور على مستخدم مرتبط بعملية الدفع.</p>
                                </div>
                            @endif

                        </div>
                    </article>

                    {{-- طريقة الدفع --}}
                    <article class="sf-card" style="--i:1">
                        <div class="sf-card-head">
                            <i class="bi bi-credit-card-2-front" aria-hidden="true"></i>
                            <h2>طريقة الدفع</h2>
                        </div>
                        <div class="sf-card-body">
                            <div class="sf-row">
                                <span class="sf-row-label">طريقة الدفع</span>
                                <span class="sf-row-value">{{ $methodName }}</span>
                            </div>
                            <div class="sf-row">
                                <span class="sf-row-label">اسم المستفيد</span>
                                <span class="sf-row-value">{{ $beneficiary ?: 'غير متوفر' }}</span>
                            </div>
                            <div class="sf-row">
                                <span class="sf-row-label">رقم الحساب / المحفظة</span>
                                <span class="sf-row-value mono">
                                    {{ $beneficiaryNum ?: 'غير متوفر' }}
                                </span>
                            </div>
                        </div>
                    </article>

                    {{-- قائمة التحقق --}}
                    <article class="sf-card" style="--i:2">
                        <div class="sf-card-head">
                            <i class="bi bi-list-check" aria-hidden="true"></i>
                            <h2>قائمة التحقق من الدفع</h2>
                        </div>
                        <div class="sf-card-body" style="padding: 10px 12px;">

                            @php
                                $checklist = [
                                    ['id' => 'chk_receipt_clear',  'label' => 'الإشعار واضح ويمكن قراءته'],
                                    ['id' => 'chk_amount_match',   'label' => 'المبلغ يطابق سعر الاشتراك'],
                                    ['id' => 'chk_beneficiary',    'label' => 'بيانات المستفيد صحيحة'],
                                    ['id' => 'chk_transfer_date',  'label' => 'تاريخ التحويل صحيح'],
                                    ['id' => 'chk_reference',      'label' => 'رقم المرجع صحيح'],
                                    ['id' => 'chk_user_match',     'label' => 'بيانات المستخدم متطابقة مع الإشعار'],
                                ];
                            @endphp

                            @foreach($checklist as $item)
                                <label class="sf-check" for="{{ $item['id'] }}">
                                    <input type="checkbox" id="{{ $item['id'] }}" name="{{ $item['id'] }}" value="1"
                                           @if($paymentStatus !== 'pending') disabled @endif>
                                    <span>{{ $item['label'] }}</span>
                                </label>
                            @endforeach

                            <p class="sf-help" style="padding: 10px 12px 4px;">
                                هذه القائمة للمساعدة البصرية فقط، ولا تُستخدم كوسيلة تحقق في الواجهة.
                            </p>

                        </div>
                    </article>

                    {{-- حالة الدفع --}}
                    <article class="sf-card" style="--i:3">
                        <div class="sf-card-head">
                            <i class="bi bi-shield-check" aria-hidden="true"></i>
                            <h2>حالة الدفع</h2>
                        </div>
                        <div class="sf-card-body">

                            @if($paymentStatus === 'pending')
                                <span class="sf-badge sf-badge-warning" style="margin-bottom:12px;">
                                    <i class="bi bi-hourglass-split" aria-hidden="true"></i>
                                    قيد المراجعة
                                </span>
                                <p style="font-size:13px;color:var(--sf-muted);margin:0;">
                                    راجع بيانات التحويل وإشعار الدفع، ثم اتخذ القرار من الأزرار في أسفل الصفحة.
                                </p>
                            @elseif($paymentStatus === 'confirmed')
                                <span class="sf-badge sf-badge-success" style="margin-bottom:12px;">
                                    <i class="bi bi-check-circle-fill" aria-hidden="true"></i>
                                    تم تأكيد الدفع
                                </span>
                                <div class="sf-row">
                                    <span class="sf-row-label">تم التأكيد بواسطة</span>
                                    <span class="sf-row-value">{{ $confirmedByName ?? 'الأدمن' }}</span>
                                </div>
                                <div class="sf-row">
                                    <span class="sf-row-label">تاريخ التأكيد</span>
                                    <span class="sf-row-value">{{ $fmtDate($confirmedAt) }}</span>
                                </div>
                            @elseif($paymentStatus === 'rejected')
                                <span class="sf-badge sf-badge-danger" style="margin-bottom:12px;">
                                    <i class="bi bi-x-circle-fill" aria-hidden="true"></i>
                                    مرفوض
                                </span>
                                <div class="sf-row">
                                    <span class="sf-row-label">تم الرفض بواسطة</span>
                                    <span class="sf-row-value">{{ $rejectedByName ?? 'الأدمن' }}</span>
                                </div>
                                <div class="sf-row">
                                    <span class="sf-row-label">تاريخ الرفض</span>
                                    <span class="sf-row-value">{{ $fmtDate($rejectedAt) }}</span>
                                </div>
                                <div class="sf-row">
                                    <span class="sf-row-label">سبب الرفض</span>
                                    <span class="sf-row-value muted">{{ $paymentRejectionReason ?: 'غير متوفر' }}</span>
                                </div>
                            @endif

                        </div>
                    </article>

                </aside>
            </div>

            {{-- =========================================================
                 Action bar (only for pending)
            ========================================================= --}}
            @if($paymentStatus === 'pending')
                <div class="sf-actions" role="group" aria-label="إجراءات الدفع">
                    <button type="button" class="sf-btn sf-btn-danger" id="sfRejectBtn">
                        <i class="bi bi-x-lg" aria-hidden="true"></i>
                        رفض الدفع
                    </button>
                    <button type="button" class="sf-btn sf-btn-primary" id="sfConfirmBtn">
                        <i class="bi bi-check-lg" aria-hidden="true"></i>
                        تأكيد الدفع
                    </button>
                </div>
            @endif

        @endif

    </div>
</main>

{{-- =========================================================
     Modal: تأكيد الدفع
========================================================= --}}
<div class="modal fade" id="sfConfirmModal" tabindex="-1" aria-labelledby="sfConfirmLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="sfConfirmLabel">تأكيد عملية الدفع</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
            </div>
            <div class="modal-body">
                <p>
                    هل أنت متأكد من تأكيد عملية الدفع بقيمة
                    <strong>{{ $fmtAmount($paymentAmount ?? 0, $paymentCurrency ?? 'USD') }}</strong>؟
                </p>
                <div class="sf-modal-note">
                    <i class="bi bi-info-circle-fill" aria-hidden="true"></i>
                    <span>سيؤدي تأكيد العملية إلى تفعيل اشتراك المستخدم.</span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="sf-btn sf-btn-outline sf-btn-sm" data-bs-dismiss="modal">إلغاء</button>
                <button type="button" class="sf-btn sf-btn-primary sf-btn-sm" id="sfConfirmSubmit">
                    <i class="bi bi-check-lg" aria-hidden="true"></i>
                    تأكيد الدفع
                </button>
            </div>
        </div>
    </div>
</div>

{{-- =========================================================
     Modal: رفض الدفع
========================================================= --}}
<div class="modal fade" id="sfRejectModal" tabindex="-1" aria-labelledby="sfRejectLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="sfRejectLabel">رفض عملية الدفع</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
            </div>
            <div class="modal-body">

                <p>يرجى تحديد سبب رفض عملية الدفع.</p>

                <form id="sfRejectForm" novalidate>

                    <div class="sf-field" id="sfReasonField">
                        <label class="sf-label" for="sfRejectReason">
                            سبب الرفض <span class="sf-req">*</span>
                        </label>
                        <select id="sfRejectReason" name="rejection_reason" class="sf-select" required>
                            <option value="">اختر سبب الرفض...</option>
                            <option value="الإشعار غير واضح">الإشعار غير واضح</option>
                            <option value="المبلغ غير صحيح">المبلغ غير صحيح</option>
                            <option value="بيانات المستفيد غير صحيحة">بيانات المستفيد غير صحيحة</option>
                            <option value="التحويل غير صالح">التحويل غير صالح</option>
                            <option value="عملية دفع مكررة">عملية دفع مكررة</option>
                            <option value="سبب آخر">سبب آخر</option>
                        </select>
                        <span class="sf-error" id="sfReasonError">يرجى تحديد سبب الرفض.</span>
                    </div>

                    <div class="sf-field">
                        <label class="sf-label" for="sfRejectNotes">ملاحظات إضافية</label>
                        <textarea id="sfRejectNotes" name="rejection_notes" class="sf-textarea"
                                  placeholder="أضف أي تفاصيل إضافية حول سبب الرفض (اختياري)"></textarea>
                    </div>

                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="sf-btn sf-btn-outline sf-btn-sm" data-bs-dismiss="modal">إلغاء</button>
                <button type="button" class="sf-btn sf-btn-danger sf-btn-sm" id="sfRejectSubmit">
                    <i class="bi bi-x-lg" aria-hidden="true"></i>
                    رفض الدفع
                </button>
            </div>
        </div>
    </div>
</div>

{{-- =========================================================
     Modal: عرض صورة الإشعار بالحجم الكامل
========================================================= --}}
<div class="modal fade" id="sfImageModal" tabindex="-1" aria-labelledby="sfImageLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="sfImageLabel">إشعار الدفع</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
            </div>
            <div class="modal-body">
                <div class="sf-img-stage">
                    <img id="sfFullImg" src="" alt="إشعار الدفع بالحجم الكامل">
                </div>
                <div class="sf-zoom-bar">
                    <button type="button" class="sf-btn sf-btn-outline sf-btn-sm" id="sfZoomOut" aria-label="تصغير">
                        <i class="bi bi-zoom-out" aria-hidden="true"></i>
                    </button>
                    <button type="button" class="sf-btn sf-btn-outline sf-btn-sm" id="sfZoomReset" aria-label="استعادة الحجم">
                        <i class="bi bi-arrow-counterclockwise" aria-hidden="true"></i>
                        100%
                    </button>
                    <button type="button" class="sf-btn sf-btn-outline sf-btn-sm" id="sfZoomIn" aria-label="تكبير">
                        <i class="bi bi-zoom-in" aria-hidden="true"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Toast container --}}
<div class="toast-container position-fixed top-0 start-0 p-3" id="sfToastContainer"
     aria-live="polite" aria-atomic="true" style="z-index: 1090;"></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
(function () {
    'use strict';

    // ==================================================
    // Small utilities (page-local, no external deps)
    // ==================================================
    function getMeta(name) {
        var el = document.querySelector('meta[name="' + name + '"]');
        return el ? el.getAttribute('content') : '';
    }

    function toast(message, type) {
        var container = document.getElementById('sfToastContainer');
        if (!container) return;
        var map = { success: 'success', danger: 'danger', info: 'info', warning: 'warning' };
        var el = document.createElement('div');
        el.className = 'toast align-items-center text-bg-' + (map[type] || 'info') + ' border-0';
        el.setAttribute('role', 'alert');
        el.setAttribute('aria-live', 'assertive');
        el.innerHTML =
            '<div class="d-flex">' +
                '<div class="toast-body">' + message + '</div>' +
                '<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="إغلاق"></button>' +
            '</div>';
        container.appendChild(el);
        var instance = bootstrap.Toast.getOrCreateInstance(el, { delay: 4000 });
        instance.show();
        el.addEventListener('hidden.bs.toast', function () { el.remove(); });
    }

    function setLoading(button, loading, loadingText) {
        if (!button) return;
        if (loading) {
            if (!button.dataset.sfOrig) button.dataset.sfOrig = button.innerHTML;
            button.disabled = true;
            button.innerHTML =
                '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>' +
                (loadingText ? '<span class="ms-2">' + loadingText + '</span>' : '');
        } else {
            button.disabled = false;
            if (button.dataset.sfOrig) button.innerHTML = button.dataset.sfOrig;
        }
    }

    document.addEventListener('DOMContentLoaded', function () {

        // ==================================================
        // Image preview modal
        // ==================================================
        var receiptThumb  = document.getElementById('sfReceiptThumb');
        var openFullBtn   = document.getElementById('sfOpenFullImg');
        var imageModalEl  = document.getElementById('sfImageModal');
        var fullImg       = document.getElementById('sfFullImg');
        var zoomLevel     = 1;

        function openImageModal() {
            if (!receiptThumb || !imageModalEl || !fullImg) return;
            fullImg.src = receiptThumb.getAttribute('src');
            zoomLevel = 1;
            fullImg.style.transform = 'scale(1)';
            bootstrap.Modal.getOrCreateInstance(imageModalEl).show();
        }

        if (receiptThumb) receiptThumb.addEventListener('click', openImageModal);
        if (openFullBtn) openFullBtn.addEventListener('click', openImageModal);

        function applyZoom() {
            if (!fullImg) return;
            zoomLevel = Math.max(0.5, Math.min(4, zoomLevel));
            fullImg.style.transform = 'scale(' + zoomLevel + ')';
        }
        var zoomInBtn  = document.getElementById('sfZoomIn');
        var zoomOutBtn = document.getElementById('sfZoomOut');
        var zoomReset  = document.getElementById('sfZoomReset');
        if (zoomInBtn)  zoomInBtn.addEventListener('click', function () { zoomLevel += 0.25; applyZoom(); });
        if (zoomOutBtn) zoomOutBtn.addEventListener('click', function () { zoomLevel -= 0.25; applyZoom(); });
        if (zoomReset)  zoomReset.addEventListener('click', function () { zoomLevel = 1; applyZoom(); });

        if (imageModalEl) {
            imageModalEl.addEventListener('hidden.bs.modal', function () {
                if (fullImg) fullImg.src = '';
            });
        }

        // ==================================================
        // Confirm payment
        // ==================================================
        var confirmBtn    = document.getElementById('sfConfirmBtn');
        var confirmModal  = document.getElementById('sfConfirmModal');
        var confirmSubmit = document.getElementById('sfConfirmSubmit');

        if (confirmBtn && confirmModal) {
            confirmBtn.addEventListener('click', function () {
                bootstrap.Modal.getOrCreateInstance(confirmModal).show();
            });
        }

        if (confirmSubmit) {
            confirmSubmit.addEventListener('click', function () {
                setLoading(confirmSubmit, true, 'جاري التأكيد...');
                // TODO: استبدل هذا بنداء fetch/axios للـ Backend الفعلي
                setTimeout(function () {
                    setLoading(confirmSubmit, false);
                    if (confirmModal) bootstrap.Modal.getOrCreateInstance(confirmModal).hide();
                    toast('تم تأكيد عملية الدفع بنجاح.', 'success');
                }, 700);
            });
        }

        // ==================================================
        // Reject payment (with validation)
        // ==================================================
        var rejectBtn    = document.getElementById('sfRejectBtn');
        var rejectModal  = document.getElementById('sfRejectModal');
        var rejectSubmit = document.getElementById('sfRejectSubmit');
        var reasonField  = document.getElementById('sfReasonField');
        var reasonSelect = document.getElementById('sfRejectReason');
        var reasonError  = document.getElementById('sfReasonError');
        var rejectNotes  = document.getElementById('sfRejectNotes');

        if (rejectBtn && rejectModal) {
            rejectBtn.addEventListener('click', function () {
                if (reasonField) reasonField.classList.remove('has-error');
                if (reasonError) reasonError.classList.remove('is-visible');
                if (reasonSelect) reasonSelect.value = '';
                if (rejectNotes) rejectNotes.value = '';
                bootstrap.Modal.getOrCreateInstance(rejectModal).show();
            });
        }

        if (rejectSubmit) {
            rejectSubmit.addEventListener('click', function () {

                var reason = reasonSelect ? reasonSelect.value.trim() : '';

                if (!reason) {
                    if (reasonField) reasonField.classList.add('has-error');
                    if (reasonError) reasonError.classList.add('is-visible');
                    if (reasonSelect) reasonSelect.focus();
                    return;
                }

                if (reasonField) reasonField.classList.remove('has-error');
                if (reasonError) reasonError.classList.remove('is-visible');

                setLoading(rejectSubmit, true, 'جاري الرفض...');
                // TODO: استبدل هذا بنداء fetch/axios للـ Backend الفعلي
                setTimeout(function () {
                    setLoading(rejectSubmit, false);
                    if (rejectModal) bootstrap.Modal.getOrCreateInstance(rejectModal).hide();
                    toast('تم رفض عملية الدفع.', 'danger');
                }, 700);
            });
        }

        if (reasonSelect) {
            reasonSelect.addEventListener('change', function () {
                if (reasonSelect.value.trim()) {
                    if (reasonField) reasonField.classList.remove('has-error');
                    if (reasonError) reasonError.classList.remove('is-visible');
                }
            });
        }

        // ==================================================
        // Stagger cards animation
        // ==================================================
        document.querySelectorAll('.sf-card').forEach(function (card, i) {
            if (!card.style.getPropertyValue('--i')) {
                card.style.setProperty('--i', String(Math.min(i, 12)));
            }
        });
    });
})();
</script>

</body>
</html>
