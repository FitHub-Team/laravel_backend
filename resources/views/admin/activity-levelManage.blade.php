<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مستويات النشاط</title>
    <link rel="stylesheet" href="{{ asset('front/css/activity-levelStyle.css') }}">

</head>
<body>
<div class="wrap">
    <div class="top">
        <div class="brand">
            <span class="kicker">SuperFit · لوحة المحتوى</span>
            <h1>مستويات النشاط</h1>
            <p>إدارة مستويات النشاط المتاحة للمستخدمين وتحديد حالتها.</p>
        </div>

        <div class="actions-wrapper">
            <a href="{{ route('admin.dashboard') }}" class="btn-dashboard">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m15 18-6-6 6-6"/>
                </svg>
                لوحة التحكم
            </a>

            <button class="btn-add" onclick="openAddModal()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>
                مستوى جديد
            </button>
        </div>
    </div>

    <div class="strip"></div>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-card">
        <div class="table-scroll">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>اسم المستوى</th>
                        <th>الحالة / التفعيل</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activityLevels as $level)
                        <tr>
                            <td>{{ $level->id }}</td>
                            <td class="name-col">
                                <span class="goal-text">{{ $level->title }}</span>
                            </td>
                            <td class="activity-cell">
                                <form action="{{ route('admin.activity-level.toggle', $level->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <label class="activity-label" style="cursor: pointer;">
                                        <span class="switch">
                                            <input type="checkbox" onchange="this.form.submit()" {{ $level->is_active ? 'checked' : '' }}>
                                            <span class="track"></span>
                                            <span class="thumb"></span>
                                        </span>
                                        <span class="activity-text">{{ $level->is_active ? 'مفعل' : 'غير مفعل' }}</span>
                                    </label>
                                </form>
                            </td>
                            <td class="actions-cell">
                                <form action="{{ route('admin.activity-level.destroy', $level->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('هل أنت تأكد من عملية الحذف؟');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete">حذف</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">
                                <div class="empty-state">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v4M12 18v4M4.9 4.9l2.8 2.8M16.3 16.3l2.8 2.8M2 12h4M18 12h4M4.9 19.1l2.8-2.8M16.3 7.7l2.8-2.8"/></svg>
                                    <strong>لا توجد مستويات نشاط بعد</strong>
                                    اضغط على "مستوى جديد" لإضافة أول مستوى نشاط.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal إضافة مستوى جديد -->
<div id="addModal" class="modal">
    <div class="modal-content">
        <h2>إضافة مستوى نشاط جديد</h2>
        <form action="{{ route('admin.activity-level.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="title">اسم مستوى النشاط</label>
                <input type="text" name="title" id="title" required placeholder="مثال: نشاط متوسط">
            </div>
            <div class="form-group">
                <label>
                    <input type="checkbox" name="is_active" value="1" checked>
                    تفعيل المستوى تلقائياً
                </label>
            </div>
            <div class="modal-actions">
                <button type="button" onclick="closeAddModal()" class="btn-dashboard">إلغاء</button>
                <button type="submit" class="btn-add">حفظ البيانات</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openAddModal() {
        document.getElementById('addModal').style.display = 'flex';
    }
    function closeAddModal() {
        document.getElementById('addModal').style.display = 'none';
    }
</script>
</body>
</html>
