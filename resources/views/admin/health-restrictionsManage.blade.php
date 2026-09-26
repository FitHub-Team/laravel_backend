<!DOCTYPE html>

<html lang="ar" dir="rtl">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>جدول ادارة القيود الصحية — SuperFit</title>

    <link rel="stylesheet"
        href="{{ asset('front/css/health.css') }}">

    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@500;600;700;800&display=swap"
        rel="stylesheet">



</head>

<body>

<div class="health-page" x-data="{ openModal: false, selectedProfileId: '' }">

    <div class="health-wrap">

        {{-- =========================
             الترويسة
        ========================== --}}

        <div class="page-header">

            <div class="header-content">

                <div class="page-kicker">
                    SuperFit · لوحة المحتوى
                </div>

                <h1 class="page-title">
                    إدارة القيود الصحية والغذائية
                </h1>

                <p class="page-subtitle">
                    عرض وتخصيص الحساسيات والقيود الصحية لملفات المستخدمين.
                </p>

            </div>

            <div class="header-actions">

                <a href="{{ route('admin.dashboard') }}"
                   class="dashboard-btn">

                    <svg fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M3 12l9-9 9 9M5 10v10h14V10">
                        </path>

                    </svg>

                    لوحة التحكم

                </a>

                <button
                    @click="openModal = true; selectedProfileId = ''"
                    type="button"
                    class="add-btn">

                    <svg fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M12 4v16m8-8H4">
                        </path>

                    </svg>

                    ربط قيد صحي جديد

                </button>

            </div>

        </div>

        <div class="teal-strip"></div>


        {{-- =========================
             رسائل النجاح
        ========================== --}}

        @if(session('success'))

            <div class="success-alert">

                <svg fill="currentColor"
                     viewBox="0 0 20 20">

                    <path fill-rule="evenodd"
                          d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                          clip-rule="evenodd"/>

                </svg>

                <span>
                    {{ session('success') }}
                </span>

            </div>

        @endif


        {{-- =========================
             رسائل الأخطاء
        ========================== --}}

        @if($errors->any())

            <div class="error-alert">

                <ul>

                    @foreach($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

            </div>

        @endif


        {{-- =========================
             جدول المستخدمين
        ========================== --}}

        <div class="table-card">

            <div class="table-card-header">

                <div>

                    <h2 class="table-card-title">
                        المستخدمون والقيود الصحية
                    </h2>

                    <p class="table-card-subtitle">
                        إدارة القيود الغذائية والصحية المرتبطة بكل مستخدم.
                    </p>

                </div>

                <span class="count-badge">
                    {{ $users->total() }} مستخدم
                </span>

            </div>


            <div class="table-wrapper">

                <table class="health-table">

                    <thead>

                        <tr>

                            <th>#</th>

                            <th>
                                المستخدم
                            </th>

                            <th>
                                القيود الصحية والغذائية المسجلة
                            </th>

                            <th style="text-align:center;">
                                الإجراءات
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($users as $user)

                            <tr>

                                {{-- المعرف --}}

                                <td>

                                    <span class="record-id">
                                        #{{ $user->id }}
                                    </span>

                                </td>


                                {{-- المستخدم --}}

                                <td>

                                    <div class="user-info">

                                        <div class="user-avatar">

                                            {{ mb_substr($user->name, 0, 1) }}

                                        </div>

                                        <div>

                                            <div class="user-name">
                                                {{ $user->name }}
                                            </div>

                                            <div class="user-email">
                                                {{ $user->email }}
                                            </div>

                                        </div>

                                    </div>

                                </td>


                                {{-- القيود --}}

                                <td>

                                    @if($user->profile && $user->profile->dietaryRestrictions->isNotEmpty())

                                        <div class="restriction-list">

                                            @foreach($user->profile->dietaryRestrictions as $restriction)

                                                <span class="restriction-badge">

                                                    <span class="restriction-dot"></span>

                                                    {{ $restriction->name }}

                                                   <form action="{{ route('admin.health-restrictions.destroy', ['userProfile' => $user->profile->id, 'dietaryRestriction' => $restriction->id]) }}"
      method="POST"
      style="display:inline;"
      onsubmit="return confirm('هل تريد إزالة هذا القيد عن المستخدم؟');">
    @csrf
    @method('DELETE')

    <button type="submit" class="remove-restriction" title="إزالة القيد">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
    </button>
</form>

                                                </span>

                                            @endforeach

                                        </div>

                                    @else

                                        <span class="no-restrictions">
                                            لا توجد قيود مسجلة
                                        </span>

                                    @endif

                                </td>


                                {{-- الإجراءات --}}

                                <td class="actions-cell">

                                    @if($user->profile)

                                        <button
                                            @click="openModal = true; selectedProfileId = '{{ $user->profile->id }}'"
                                            type="button"
                                            class="add-restriction-btn">

                                            + إضافة قيد

                                        </button>

                                    @else

                                        <span class="no-profile">
                                            لا يوجد ملف شخصي
                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="4" class="empty-state">

                                    <div class="empty-icon">

                                        <svg fill="none"
                                             stroke="currentColor"
                                             viewBox="0 0 24 24">

                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  stroke-width="2"
                                                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>

                                        </svg>

                                    </div>

                                    <p class="empty-title">
                                        لا يوجد مستخدمون حالياً
                                    </p>

                                    <p class="empty-text">
                                        لم يتم العثور على أي مستخدمين لعرضهم.
                                    </p>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>


            {{-- Pagination --}}

            @if($users->hasPages())

                <div class="pagination-wrapper">

                    {{ $users->links() }}

                </div>

            @endif

        </div>


        {{-- =========================
             Modal
        ========================== --}}

        <div
            x-show="openModal"
            x-cloak
            class="modal-overlay"
            style="display: none;">

            <div
                @click="openModal = false"
                class="modal-backdrop">
            </div>


            <div class="restriction-modal">

                <div class="modal-header">

                    <div>

                        <div class="modal-kicker">
                            SuperFit · القيود الصحية
                        </div>

                        <h3 class="modal-title">
                            ربط قيد صحي جديد بالمستخدم
                        </h3>

                    </div>


                    <button
                        @click="openModal = false"
                        type="button"
                        class="modal-close">

                        <svg fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M6 18L18 6M6 6l12 12">
                            </path>

                        </svg>

                    </button>

                </div>


                <form
                    action="{{ route('admin.health-restrictions.store') }}"
                    method="POST"
                    class="modal-form">

                    @csrf


                    {{-- المستخدم --}}

                    <div class="form-group">

                        <label
                            for="user_profile_id"
                            class="form-label">

                            المستخدم / الملف الشخصي

                        </label>

                        <div class="select-wrapper">

                            <select
                                name="user_profile_id"
                                id="user_profile_id"
                                x-model="selectedProfileId"
                                class="form-select"
                                required>

                                <option value="">
                                    اختر المستخدم...
                                </option>

                                @foreach($users as $user)

                                    @if($user->profile)

                                        <option value="{{ $user->profile->id }}">
                                            {{ $user->name }} ({{ $user->email }})
                                        </option>

                                    @endif

                                @endforeach

                            </select>

                        </div>

                    </div>


                    {{-- القيد الصحي --}}

                    <div class="form-group">

                        <label
                            for="dietary_restriction_id"
                            class="form-label">

                            القيد الصحي / الغذائي

                        </label>

                        <div class="select-wrapper">

                            <select
                                name="dietary_restriction_id"
                                id="dietary_restriction_id"
                                class="form-select"
                                required>

                                <option value="">
                                    اختر القيد الصحي...
                                </option>

                                @foreach($dietaryRestrictions as $restriction)

                                    <option value="{{ $restriction->id }}">
                                        {{ $restriction->name }}
                                    </option>

                                @endforeach

                            </select>

                        </div>

                    </div>


                    {{-- الأزرار --}}

                    <div class="modal-actions">

                        <button
                            type="button"
                            @click="openModal = false"
                            class="cancel-btn">

                            إلغاء

                        </button>

                        <button
                            type="submit"
                            class="save-btn">

                            حفظ القيد

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>


{{-- Alpine.js إذا لم يكن موجوداً في health.css/layout --}}

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

</body>

</html>
