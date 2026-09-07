<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طلبات اعتمادات المدربين — Super Fit</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
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
            --danger-bg: #fee2e2;
            --danger-fg: #ef4444;
            --success-bg: #dcfce7;
            --success-fg: #15803d;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Cairo', 'Inter', system-ui, sans-serif;
            background: var(--teal-50);
            color: var(--ink);
            padding: 32px 24px 60px;
            background-image:
                radial-gradient(circle at 100% 0%, #d8f2ec 0%, transparent 45%),
                radial-gradient(circle at 0% 100%, #d8f2ec 0%, transparent 45%);
            background-repeat: no-repeat;
        }

        .container { max-width: 1160px; margin: 0 auto; }

        .navbar {
            display: flex;
            gap: 10px;
            background: linear-gradient(135deg, var(--teal-800), var(--teal-950));
            padding: 10px;
            border-radius: 16px;
            margin-bottom: 24px;
            box-shadow: 0 8px 24px rgba(15, 110, 110, 0.18);
        }

        .nav-link {
            flex: 1;
            text-align: center;
            padding: 13px 16px;
            border-radius: 11px;
            color: #cdece6;
            text-decoration: none;
            font-weight: 600;
            font-size: 15px;
            transition: background .18s ease, color .18s ease;
        }

        .nav-link:hover { background: rgba(255, 255, 255, 0.08); color: #fff; }

        .nav-link.active {
            background: var(--teal-400);
            color: var(--teal-950);
            font-weight: 700;
        }

        .page-title {
            font-size: 25px;
            color: var(--teal-950);
            margin-bottom: 24px;
            font-weight: 800;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-panel {
            background: var(--paper);
            border-radius: 18px;
            border: 1px solid var(--line);
            padding: 26px;
            margin-bottom: 28px;
            box-shadow: 0 6px 22px rgba(15, 110, 110, 0.07);
        }

        .card-title {
            font-size: 17px;
            font-weight: 700;
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        table { width: 100%; border-collapse: collapse; text-align: right; margin-top: 4px; }

        th {
            background: var(--teal-50);
            padding: 13px 16px;
            font-size: 13px;
            font-weight: 700;
            color: var(--teal-800);
            border-bottom: 1px solid var(--line);
            white-space: nowrap;
        }
        th:first-child { border-top-right-radius: 10px; }
        th:last-child  { border-top-left-radius: 10px; }

        tbody tr { transition: background .15s ease; }
        tbody tr:hover { background: var(--teal-50); }

        td { padding: 14px 16px; font-size: 14px; border-bottom: 1px solid var(--line); vertical-align: middle; }
        tbody tr:last-child td { border-bottom: none; }

        .btn-audit {
            background: var(--teal-800);
            color: #fff;
            padding: 8px 16px;
            border-radius: 20px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            display: inline-block;
            border: none;
            transition: background .15s ease, transform .15s ease;
        }
        .btn-audit:hover { background: var(--teal-600); transform: translateY(-1px); }

        /* ---------- Offcanvas / Drawer CSS ---------- */
        .drawer-toggle { display: none; }
        .drawer-overlay {
            position: fixed; inset: 0;
            background: rgba(10, 47, 44, 0.4);
            backdrop-filter: blur(2px);
            opacity: 0; visibility: hidden;
            transition: 0.3s ease;
            z-index: 99;
        }
        .drawer-content {
            position: fixed; top: 0; left: 0;
            width: 420px; max-width: 100%; height: 100vh;
            background: #fff;
            z-index: 100;
            transform: translateX(-100%);
            transition: 0.3s ease;
            padding: 28px;
            box-shadow: 10px 0 34px rgba(10, 47, 44, 0.16);
            overflow-y: auto;
        }

        /* تفعيل الـ Drawer عند الضغط على الـ Checkbox */
        @foreach($pendingCoaches as $coach)
            #drawer-toggle-{{ $coach->id }}:checked ~ .drawer-overlay { opacity: 1; visibility: visible; }
            #drawer-toggle-{{ $coach->id }}:checked ~ .drawer-content-{{ $coach->id }} { transform: translateX(0); }
        @endforeach

        .drawer-header {
            display: flex; justify-content: space-between; align-items: center;
            border-bottom: 1px solid var(--line);
            padding-bottom: 16px; margin-bottom: 20px;
        }
        .drawer-header h3 { font-size: 17px; font-weight: 700; color: var(--teal-950); }

        .close-btn {
            font-size: 22px; line-height: 1; cursor: pointer; color: var(--ink-soft);
            width: 32px; height: 32px; border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
            transition: background .15s ease;
        }
        .close-btn:hover { background: var(--teal-100); color: var(--teal-800); }

        .info-group { margin-bottom: 16px; }
        .info-label { font-size: 12px; color: var(--ink-soft); font-weight: 700; text-transform: uppercase; letter-spacing: .3px; }
        .info-value { font-size: 15px; color: var(--ink); margin-top: 4px; font-weight: 600; }

        .actions-box { margin-top: 26px; border-top: 1px solid var(--line); padding-top: 20px; }

        .btn-approve {
            width: 100%;
            background: var(--success-fg);
            color: white;
            border: none;
            padding: 13px;
            border-radius: 11px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            margin-bottom: 16px;
            transition: filter .15s ease, transform .15s ease;
        }
        .btn-approve:hover { filter: brightness(1.08); transform: translateY(-1px); }

        .reject-box {
            background: var(--danger-bg);
            border: 1px solid #fca5a5;
            padding: 14px;
            border-radius: 12px;
        }

        .btn-reject {
            width: 100%;
            background: var(--danger-fg);
            color: white;
            border: none;
            padding: 10px;
            border-radius: 9px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            margin-top: 8px;
            transition: filter .15s ease, transform .15s ease;
        }
        .btn-reject:hover { filter: brightness(1.05); transform: translateY(-1px); }
    </style>
</head>

<body>

    <div class="container">
        <nav class="navbar">
            <a href="{{ route('admin.dashboard') }}" class="nav-link">لوحة الإحصائيات (Dashboard)</a>
            <a href="{{ route('admin.trainer.manage') }}" class="nav-link active">إدارة المدربين (Coach Management)</a>
        </nav>

        <h1 class="page-title">📑 إدارة طلبات الكوتش</h1>

        @if(session('success'))
            <div style="background: #e6f4ea; color: #137333; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-size: 14px;">
                {{ session('success') }}
            </div>
        @endif

        <!-- ================= 1. جدول الطلبات بانتظار الفحص ================= -->
        <div class="card-panel">
            <h2 class="card-title" style="color: var(--teal-800);">⏳ طلبات بانتظار التدقيق والقبول ({{ $pendingCoaches->count() }})</h2>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>الاسم</th>
                        <th>التخصص</th>
                        <th>الخبرة</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendingCoaches as $coach)
                        <tr>
                            <td>#{{ $coach->id }}</td>
                            <td><strong>{{ $coach->name ?? 'مدرب جديد' }}</strong></td>
                            <td>{{ $coach->specialization ?? 'عام' }}</td>
                            <td>{{ $coach->experience ?? '0' }} سنوات</td>
                            <td>
                                <!-- زر فتح الـ Drawer بالاعتماد على ID المدرب -->
                                <label for="drawer-toggle-{{ $coach->id }}" class="btn-audit">🔍 تدقيق الطلب</label>

                                <!-- elements للتحكم بالـ Drawer بدون JS -->
                                <input type="checkbox" id="drawer-toggle-{{ $coach->id }}" class="drawer-toggle">
                                <label for="drawer-toggle-{{ $coach->id }}" class="drawer-overlay"></label>

                                <!-- الـ Drawer الخاص بهذه التفاصيل -->
                                <div class="drawer-content drawer-content-{{ $coach->id }}">
                                    <div class="drawer-header">
                                        <h3>تفاصيل طلب المدرب</h3>
                                        <label for="drawer-toggle-{{ $coach->id }}" class="close-btn">&times;</label>
                                    </div>

                                    <div class="info-group">
                                        <div class="info-label">اسم المدرب</div>
                                        <div class="info-value">{{ $coach->name ?? 'غير محدد' }}</div>
                                    </div>

                                    <div class="info-group">
                                        <div class="info-label">التخصص</div>
                                        <div class="info-value">{{ $coach->specialization ?? '-' }}</div>
                                    </div>

                                    <div class="info-group">
                                        <div class="info-label">سنوات الخبرة</div>
                                        <div class="info-value">{{ $coach->experience ?? '0' }} سنوات</div>
                                    </div>

                                    <div class="info-group">
                                        <div class="info-label">نبذة عنه / Bio</div>
                                        <div class="info-value" style="font-size: 13px; color: #555;">{{ $coach->bio ?? 'لا يوجد نبذة مكتوبة.' }}</div>
                                    </div>

                                    <div class="actions-box">
                                        <!-- 1. نموذج القبول -->
                                        <form action="{{ route('admin.coaches.approve', $coach->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn-approve">✓ قبول الطلب واعتماده</button>
                                        </form>

                                        <!-- 2. نموذج الرفض -->
                                        <div class="reject-box">
                                            <form action="{{ route('admin.coaches.reject', $coach->id) }}" method="POST">
                                                @csrf
                                                <label style="display:block; font-size:12px; font-weight:600; color:#991b1b; margin-bottom:6px;">رفض الطلب مع ذكر السبب:</label>
                                                <textarea name="rejection_reason" rows="3" required placeholder="اكتبي سبب عدم القبول هنا..." style="width:100%; border:1px solid #fca5a5; border-radius:6px; padding:8px; font-size:13px; outline:none;"></textarea>
                                                <button type="submit" class="btn-reject">✕ تأكيد الرفض</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align:center; color:#888; padding: 24px;">لا توجد طلبات معلقة بانتظار المراجعة حالياً.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- ================= 2. جدول الطلبات المرفوضة ================= -->
        <div class="card-panel">
            <h2 class="card-title" style="color: #991b1b;">❌ الطلبات المرفوضة ({{ $rejectedCoaches->count() }})</h2>
            <table>
                <thead>
                    <tr style="background: #fef2f2;">
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
                            <td>#{{ $coach->id }}</td>
                            <td><strong>{{ $coach->user->name ?? $coach->name ?? 'كوتش' }}</strong></td>
                            <td>{{ $coach->specialization ?? '-' }}</td>
                            <td style="color: #b91c1c; font-weight: 500;">{{ $coach->rejection_reason }}</td>
                            <td style="color: #777; font-size: 13px;">{{ $coach->updated_at ? $coach->updated_at->format('Y-m-d') : '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align:center; color:#888; padding: 24px;">لا توجد طلبات مرفوضة.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

</body>
</html>
