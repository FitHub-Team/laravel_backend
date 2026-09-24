<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>إدارة النشاط — SuperFit</title>

    <link rel="stylesheet" href="{{ asset('front/css/activity-levelStyle.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@500;600;700;800&display=swap" rel="stylesheet">
</head>

<body>

<div class="wrap">

    {{-- Header --}}
    <div class="top">
        <div class="brand">
            <span class="kicker">SUPERFIT ADMIN</span>
            <h1>إدارة مستويات النشاط</h1>
            <p>عرض وإدارة مستويات النشاط البدني المتاحة في النظام.</p>
        </div>

        <div class="actions-wrapper">
            {{-- زر إضافة مستوى نشاط --}}
            <button type="button" class="btn-add" onclick="openModal('addModal')">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                إضافة مستوى نشاط
            </button>

            {{-- العودة للداشبورد --}}
            <a href="{{ route('admin.dashboard') }}" class="btn-dashboard">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Dashboard
            </a>
        </div>
    </div>

    {{-- الخط الزخرفي --}}
    <div class="strip"></div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert-success">
            <span class="alert-icon">✓</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- Errors --}}
    @if($errors->any())
        <div class="alert-success" style="background: var(--danger-bg); border-color: #f0cccc; color: var(--danger-dark);">
            <span class="alert-icon" style="background: #ffe0e0; color: var(--danger-dark);">!</span>
            <div>
                @foreach($errors->all() as $error)
                    <div style="margin-bottom: 3px;">{{ $error }}</div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Table Card --}}
    <div class="table-card">

        <div class="table-header">
            <div>
                <h2>مستويات النشاط</h2>
                <p>قائمة بمستويات النشاط البدني المسجلة</p>
            </div>

            <div class="levels-count">
                {{ isset($activityLevels) ?$activityLevels->total() : 0 }} مستوى
            </div>
        </div>

        {{-- Table --}}
        <div class="table-scroll">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th class="name-col">المستوى</th>
                        <th class="activity-cell">الحالة</th>
                        <th class="actions-cell">الإجراءات</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($activityLevels as $level)
                        <tr>
                            {{-- ID --}}
                            <td>
                                <span class="id-badge">#{{ $level->id }}</span>
                            </td>

                            {{-- Title --}}
                            <td class="name-col">
                                <div class="level-name-wrapper">
                                    <div class="level-icon">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-6z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <span class="name-text">{{ $level->title }}</span>
                                    </div>
                                </div>
                            </td>

                            {{-- Is Active (Toggle Switch) --}}
                            <td class="activity-cell">
                                <form action="{{ route('admin.activity-level.toggle', $level->id) }}" method="POST" id="toggle-form-{{ $level->id }}">
                                    @csrf
                                    @method('PATCH')
                                    <div class="activity-label">
                                        <label class="switch">
                                            <input type="checkbox" {{ $level->is_active ? 'checked' : '' }} onchange="document.getElementById('toggle-form-{{ $level->id }}').submit()">
                                            <span class="track"></span>
                                            <span class="thumb"></span>
                                        </label>
                                        <span class="activity-text">
                                            {{ $level->is_active ? 'مفعل' : 'غير مفعل' }}
                                        </span>
                                    </div>
                                </form>
                            </td>

                            {{-- Actions --}}
                            <td class="actions-cell">
                                {{-- زر التعديل --}}
                                <button type="button" class="btn-delete" style="background:var(--teal-50); border-color:rgba(18,143,137,.20); color:var(--teal-800);" onclick="openEditModal({{ $level }})">
                                    تعديل
                                </button>

                                {{-- زر الحذف --}}
                                <form action="{{ route('admin.activity-level.destroy', $level->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('هل أنت تأكد من رغبتك في حذف هذا المستوى؟');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete">
                                        حذف
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">
                                <div class="empty-state">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <strong>لا توجد مستويات نشاط حالياً</strong>
                                    <span>قم بإضافة مستوى نشاط جديد للبدء.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if(isset($activityLevels) && method_exists($activityLevels, 'hasPages') &&$activityLevels->hasPages())
            <div style="padding:18px 22px; background:#f8fcfb; border-top:1px solid var(--line);">
                {{ $activityLevels->links() }}
            </div>
        @endif

    </div>
</div>

{{-- Modal: إضافة --}}
<div class="modal" id="addModal">
    <div class="modal-content">
        <div class="modal-header">
            <div>
                <span class="modal-kicker">جديد</span>
                <h2>إضافة مستوى نشاط</h2>
                <p>أدخل بيانات مستوى النشاط الجديد</p>
            </div>
            <button type="button" class="modal-close" onclick="closeModal('addModal')">&times;</button>
        </div>

        <form action="{{ route('admin.activity-level.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="add_title">عنوان المستوى</label>
                <input type="text" name="title" id="add_title" required placeholder="مثال: نشاط متوسط">
            </div>

            <div class="active-option">
                <label>
                    <input type="checkbox" name="is_active" value="1" checked>
                    تفعيل المستوى مباشرة
                </label>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="closeModal('addModal')">إلغاء</button>
                <button type="submit" class="btn-add">حفظ</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal: تعديل --}}
<div class="modal" id="editModal">
    <div class="modal-content">
        <div class="modal-header">
            <div>
                <span class="modal-kicker">تعديل</span>
                <h2>تعديل مستوى النشاط</h2>
                <p>تحديث البيانات الحالية لمستوى النشاط</p>
            </div>
            <button type="button" class="modal-close" onclick="closeModal('editModal')">&times;</button>
        </div>

        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="edit_title">عنوان المستوى</label>
                <input type="text" name="title" id="edit_title" required>
            </div>

            <div class="active-option">
                <label>
                    <input type="checkbox" name="is_active" id="edit_is_active" value="1">
                    تفعيل المستوى
                </label>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="closeModal('editModal')">إلغاء</button>
                <button type="submit" class="btn-add">حفظ التغييرات</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal(id) {
        document.getElementById(id).style.display = 'flex';
        document.body.classList.add('modal-open');
    }

    function closeModal(id) {
        document.getElementById(id).style.display = 'none';
        document.body.classList.remove('modal-open');
    }

    function openEditModal(level) {
        let form = document.getElementById('editForm');
        form.action = `/admin/activity-level/${level.id}`;

        document.getElementById('edit_title').value = level.title;
        document.getElementById('edit_is_active').checked = level.is_active == 1;

        openModal('editModal');
    }
</script>

</body>
</html>
