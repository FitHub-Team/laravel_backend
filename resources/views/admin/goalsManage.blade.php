<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>جدول الأهداف</title>
    <link rel="stylesheet" href="{{ asset('front/css/goalsStyle.css') }}">

</head>
<body>
<div class="wrap">
    <div class="top">
        <div class="brand">
            <span class="kicker">SuperFit · لوحة المحتوى</span>
            <h1>جدول الأهداف</h1>
            <p>أضف أهداف اللياقة البدنية التي تظهر للمستخدمين في التطبيق.</p>
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
                هدف جديد
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
                        <th>الصورة</th>
                        <th>اسم الهدف</th>
                        <th>الوصف</th>
                        <th>الحالة / التفعيل</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($goals as $goal)
                        <tr>
                            <td class="avatar-cell">
                                <div class="avatar">
                                    @if($goal->image)
                                        <img src="{{ asset('storage/' . $goal->image) }}" alt="{{ $goal->title }}" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                                    @else
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M6.5 6.5 17.5 17.5M4 9V6a2 2 0 0 1 2-2h3M20 15v3a2 2 0 0 1-2 2h-3M4 15v3a2 2 0 0 1 2 2h3M20 9V6a2 2 0 0 0-2-2h-3"/>
                                        </svg>
                                    @endif
                                </div>
                            </td>
                            <td class="name-col">
                                <span class="goal-text">{{ $goal->title }}</span>
                            </td>
                            <td class="desc-col">
                                <span class="goal-text">{{ $goal->description ?? '-' }}</span>
                            </td>
                            <td class="activity-cell">
                                <form action="{{ route('admin.goals.toggle', $goal->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <label class="activity-label" style="cursor: pointer;">
                                        <span class="switch">
                                            <input type="checkbox" onchange="this.form.submit()" {{ $goal->is_active ? 'checked' : '' }}>
                                            <span class="track"></span>
                                            <span class="thumb"></span>
                                        </span>
                                        <span class="activity-text">{{ $goal->is_active ? 'مفعل' : 'غير مفعل' }}</span>
                                    </label>
                                </form>
                            </td>
                            <td class="actions-cell">
                                <form action="{{ route('admin.goals.destroy', $goal->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('هل أنت تأكد من عملية الحذف؟');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete">حذف</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="empty-state">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v4M12 18v4M4.9 4.9l2.8 2.8M16.3 16.3l2.8 2.8M2 12h4M18 12h4M4.9 19.1l2.8-2.8M16.3 7.7l2.8-2.8"/></svg>
                                    <strong>لا توجد أهداف بعد</strong>
                                    اضغط على "هدف جديد" لإضافة أول هدف لياقة.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal إضافة هدف جديد -->
<div id="addModal" class="modal">
    <div class="modal-content">
        <h2>إضافة هدف جديد</h2>
        <form action="{{ route('admin.goals.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="title">عنوان الهدف</label>
                <input type="text" name="title" id="title" required placeholder="مثال: خسارة الوزن">
            </div>
            <div class="form-group">
                <label for="description">الوصف</label>
                <textarea name="description" id="description" rows="3" placeholder="أدخل وصف الهدف..."></textarea>
            </div>
            <div class="form-group">
                <label for="image">صورة / أيقونة الهدف</label>
                <input type="file" name="image" id="image" accept="image/*">
            </div>
            <div class="form-group">
                <label>
                    <input type="checkbox" name="is_active" value="1" checked>
                    تفعيل الهدف تلقائياً
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
