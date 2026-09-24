<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>جدول الأهداف — SuperFit</title>

    <link rel="stylesheet"
        href="{{ asset('front/css/goalsStyle.css') }}">

    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@500;600;700;800&display=swap"
        rel="stylesheet">
</head>

<body>

<div class="wrap">

    <!-- Header -->
    <div class="top">

        <div class="brand">
            <span class="kicker">SuperFit · لوحة المحتوى</span>

            <h1>جدول الأهداف</h1>

            <p>
                أضف أهداف اللياقة البدنية التي تظهر للمستخدمين في التطبيق.
            </p>
        </div>

        <div class="actions-wrapper">

            <a href="{{ route('admin.dashboard') }}" class="btn-dashboard">

                <svg viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="2.2"
                     stroke-linecap="round"
                     stroke-linejoin="round">

                    <path d="m15 18-6-6 6-6"/>

                </svg>

                لوحة التحكم
            </a>

            <button type="button"
                    class="btn-add"
                    onclick="openAddModal()">

                <svg viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="2.4"
                     stroke-linecap="round">

                    <path d="M12 5v14M5 12h14"/>

                </svg>

                هدف جديد
            </button>

        </div>

    </div>

    <!-- Gradient line -->
    <div class="strip"></div>


    <!-- Success Message -->
    @if(session('success'))

        <div class="alert-success">

            <span class="alert-icon">✓</span>

            {{ session('success') }}

        </div>

    @endif


    <!-- Table Card -->
    <div class="table-card">

        <div class="table-header">

            <div>
                <h2>الأهداف الرياضية</h2>
                <p>إدارة الأهداف الظاهرة داخل التطبيق</p>
            </div>

            <div class="goals-count">
                {{ $goals->count() }} هدف
            </div>

        </div>


        <div class="table-scroll">

            <table>

                <thead>

                    <tr>

                        <th>الصورة</th>

                        <th>اسم الهدف</th>

                        <th>الوصف</th>

                        <th>الحالة</th>

                        <th>الإجراءات</th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($goals as $goal)

                        <tr>

                            <!-- Image -->
                            <td class="avatar-cell">

                                <div class="avatar">

                                    @if($goal->image)

                                        <img
                                            src="{{ asset('storage/' . $goal->image) }}"
                                            alt="{{ $goal->title }}"
                                        >

                                    @else

                                        <svg viewBox="0 0 24 24"
                                             fill="none"
                                             stroke="currentColor"
                                             stroke-width="1.8"
                                             stroke-linecap="round"
                                             stroke-linejoin="round">

                                            <path d="M6.5 6.5 17.5 17.5
                                                     M4 9V6a2 2 0 0 1 2-2h3
                                                     M20 15v3a2 2 0 0 1-2 2h-3
                                                     M4 15v3a2 2 0 0 0 2 2h3
                                                     M20 9V6a2 2 0 0 0-2-2h-3"/>

                                        </svg>

                                    @endif

                                </div>

                            </td>


                            <!-- Name -->
                            <td class="name-col">

                                <span class="name-text">
                                    {{ $goal->title }}
                                </span>

                            </td>


                            <!-- Description -->
                            <td class="desc-col">

                                <span class="desc-text">
                                    {{ $goal->description ?? '-' }}
                                </span>

                            </td>


                            <!-- Status -->
                            <td class="activity-cell">

                                <form
                                    action="{{ route('admin.goals.toggle', $goal->id) }}"
                                    method="POST"
                                >

                                    @csrf
                                    @method('PATCH')

                                    <label class="activity-label">

                                        <span class="switch">

                                            <input
                                                type="checkbox"
                                                onchange="this.form.submit()"
                                                {{ $goal->is_active ? 'checked' : '' }}
                                            >

                                            <span class="track"></span>

                                            <span class="thumb"></span>

                                        </span>

                                        <span class="activity-text">
                                            {{ $goal->is_active ? 'مفعل' : 'غير مفعل' }}
                                        </span>

                                    </label>

                                </form>

                            </td>


                            <!-- Actions -->
                            <td class="actions-cell">

                                <form
                                    action="{{ route('admin.goals.destroy', $goal->id) }}"
                                    method="POST"
                                    onsubmit="return confirm('هل أنت متأكد من عملية الحذف؟');"
                                >

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn-delete"
                                    >
                                        حذف
                                    </button>

                                </form>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="5">

                                <div class="empty-state">

                                    <svg viewBox="0 0 24 24"
                                         fill="none"
                                         stroke="currentColor"
                                         stroke-width="1.8"
                                         stroke-linecap="round"
                                         stroke-linejoin="round">

                                        <path d="M12 2v4
                                                 M12 18v4
                                                 M4.9 4.9l2.8 2.8
                                                 M16.3 16.3l2.8 2.8
                                                 M2 12h4
                                                 M18 12h4
                                                 M4.9 19.1l2.8-2.8
                                                 M16.3 7.7l2.8-2.8"/>

                                    </svg>

                                    <strong>
                                        لا توجد أهداف بعد
                                    </strong>

                                    <span>
                                        اضغط على "هدف جديد" لإضافة أول هدف لياقة.
                                    </span>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>


<!-- ========================================= -->
<!-- Modal إضافة هدف جديد -->
<!-- ========================================= -->

<div id="addModal" class="modal">

    <div class="modal-content">

        <div class="modal-header">

            <div>

                <span class="modal-kicker">
                    SuperFit
                </span>

                <h2>
                    إضافة هدف جديد
                </h2>

                <p>
                    أضف هدف لياقة جديد ليظهر للمستخدمين.
                </p>

            </div>

            <button
                type="button"
                class="modal-close"
                onclick="closeAddModal()"
            >
                &times;
            </button>

        </div>


        <form
            action="{{ route('admin.goals.store') }}"
            method="POST"
            enctype="multipart/form-data"
        >

            @csrf


            <!-- Title -->
            <div class="form-group">

                <label for="title">
                    عنوان الهدف
                </label>

                <input
                    type="text"
                    name="title"
                    id="title"
                    required
                    placeholder="مثال: خسارة الوزن"
                >

            </div>


            <!-- Description -->
            <div class="form-group">

                <label for="description">
                    الوصف
                </label>

                <textarea
                    name="description"
                    id="description"
                    rows="4"
                    placeholder="أدخل وصف الهدف..."
                ></textarea>

            </div>


            <!-- Image -->
            <div class="form-group">

                <label for="image">
                    صورة / أيقونة الهدف
                </label>

                <div class="file-input-wrapper">

                    <input
                        type="file"
                        name="image"
                        id="image"
                        accept="image/*"
                    >

                </div>

            </div>


            <!-- Active -->
            <div class="active-option">

                <label>

                    <input
                        type="checkbox"
                        name="is_active"
                        value="1"
                        checked
                    >

                    <span>
                        تفعيل الهدف تلقائياً
                    </span>

                </label>

            </div>


            <!-- Modal Actions -->
            <div class="modal-actions">

                <button
                    type="button"
                    onclick="closeAddModal()"
                    class="btn-cancel"
                >
                    إلغاء
                </button>

                <button
                    type="submit"
                    class="btn-add"
                >
                    حفظ البيانات
                </button>

            </div>

        </form>

    </div>

</div>


<script>

    function openAddModal() {
        document.getElementById('addModal').style.display = 'flex';
        document.body.classList.add('modal-open');
    }

    function closeAddModal() {
        document.getElementById('addModal').style.display = 'none';
        document.body.classList.remove('modal-open');
    }

    // إغلاق المودال عند الضغط خارج الصندوق
    document.getElementById('addModal').addEventListener('click', function (event) {

        if (event.target === this) {
            closeAddModal();
        }

    });

    // إغلاق المودال بزر Escape
    document.addEventListener('keydown', function (event) {

        if (event.key === 'Escape') {
            closeAddModal();
        }

    });

</script>

</body>
</html>
