<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>إدارة مهارات الكوتش وأخصائي التغذية — SuperFit</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link
        href="https://fonts.googleapis.com/css2?family=Cairo:wght@500;600;700;800&display=swap"
        rel="stylesheet"
    >

    <style>
        body {
            font-family: 'Cairo', sans-serif;
        }

        .modal {
            display: none;
        }

        .modal.active {
            display: flex;
        }

        body.modal-open {
            overflow: hidden;
        }
    </style>
</head>

<body class="bg-[#f4fbf8] text-gray-800">

<div class="container mx-auto px-4 py-8 md:px-8" dir="rtl">

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">

        <div>
            <div class="flex items-center gap-3 mb-1">

                <div class="w-11 h-11 rounded-xl bg-[#0f766e] text-white flex items-center justify-center shadow-sm">
                    <svg class="w-6 h-6"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19z"
                        />

                    </svg>
                </div>

                <h1 class="text-2xl font-bold text-[#174e49]">
                    إدارة مهارات الكوتش وأخصائي التغذية
                </h1>

            </div>

            <p class="text-sm text-gray-500 mt-1">
                عرض وإدارة قائمة المهارات المتاحة للمدربين وأخصائيي التغذية وإمكانية إضافة أو تعديل أو حذف المهارات.
            </p>
        </div>


        <!-- Header Buttons -->
        <div class="flex items-center gap-3">

            <!-- Dashboard -->
            <a href="{{ route('admin.dashboard') }}"
               class="inline-flex items-center justify-center px-4 py-2 bg-white hover:bg-[#e7f5f1] text-[#176b63] border border-[#d6ebe6] font-semibold text-sm rounded-xl shadow-sm transition-colors">

                <svg class="w-5 h-5 ml-2"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"
                    />

                </svg>

                Dashboard
            </a>


            <!-- Add Skill -->
            <button
                type="button"
                onclick="openAddModal()"
                class="inline-flex items-center justify-center px-4 py-2 bg-[#0f766e] hover:bg-[#0b625c] text-white font-semibold text-sm rounded-xl shadow-sm transition-colors">

                <svg class="w-5 h-5 ml-2"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 4v16m8-8H4"
                    />

                </svg>

                إضافة مهارة جديدة
            </button>

        </div>

    </div>


    <!-- Success Message -->
    @if(session('success'))

        <div class="mb-6 p-4 bg-[#e8f7f2] border-r-4 border-[#2aa889] rounded-xl flex items-center shadow-sm">

            <svg class="h-5 w-5 text-[#2aa889] ml-3"
                 fill="currentColor"
                 viewBox="0 0 20 20">

                <path
                    fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                    clip-rule="evenodd"
                />

            </svg>

            <span class="text-sm font-semibold text-[#176b63]">
                {{ session('success') }}
            </span>

        </div>

    @endif


    <!-- Validation Errors -->
    @if($errors->any())

        <div class="mb-6 p-4 bg-[#fff4f4] border-r-4 border-[#c4515d] rounded-xl shadow-sm">

            <div class="flex items-center mb-2">

                <svg class="h-5 w-5 text-[#c4515d] ml-2"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 8v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"
                    />

                </svg>

                <span class="font-semibold text-[#a33f49]">
                    يوجد خطأ في البيانات
                </span>

            </div>

            <ul class="text-sm text-[#a33f49] list-disc mr-7">

                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif


    <!-- Skills Table -->
    <div class="bg-white rounded-2xl shadow-[0_8px_30px_rgba(15,118,110,0.08)] border border-[#dceee9] overflow-hidden">

        <div class="overflow-x-auto">

            <table class="min-w-full divide-y divide-gray-200 text-right">

                <thead class="bg-[#edf8f5]">

                    <tr>

                        <th class="px-6 py-4 text-xs font-semibold text-[#4b706b] uppercase tracking-wider">
                            # ID
                        </th>

                        <th class="px-6 py-4 text-xs font-semibold text-[#4b706b] uppercase tracking-wider">
                            اسم المهارة
                        </th>

                        <th class="px-6 py-4 text-xs font-semibold text-[#4b706b] uppercase tracking-wider">
                            الحالة
                        </th>

                        <th class="px-6 py-4 text-xs font-semibold text-[#4b706b] uppercase tracking-wider text-center">
                            الإجراءات (Actions)
                        </th>

                    </tr>

                </thead>


                <tbody class="bg-white divide-y divide-[#e8f1ef]">

                    @forelse($skills as $skill)

                        <tr class="hover:bg-[#f7fcfa] transition-colors duration-150">

                            <!-- ID -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-500">
                                #{{ $skill->id }}
                            </td>


                            <!-- Skill Name -->
                            <td class="px-6 py-4 whitespace-nowrap">

                                <div class="flex items-center">

                                    <div class="h-9 w-9 bg-[#dff3ed] text-[#0f766e] rounded-full flex items-center justify-center font-bold ml-3 text-sm">

                                        {{ mb_substr($skill->name, 0, 1) }}

                                    </div>

                                    <span class="text-sm font-medium text-gray-900">
                                        {{ $skill->name }}
                                    </span>

                                </div>

                            </td>


                            <!-- Status (Toggle Switch like image) -->
                            <td class="px-6 py-4 whitespace-nowrap">

                                <form
                                    action="{{ route('admin.skills.toggle', $skill->id) }}"
                                    method="POST"
                                    class="inline-flex items-center gap-3">

                                    @csrf
                                    @method('PATCH')

                                    <span class="text-sm font-bold text-[#174e49]">
                                        {{ $skill->is_active ? 'مفعل' : 'غير مفعل' }}
                                    </span>

                                    <button
                                        type="submit"
                                        class="relative inline-flex h-7 w-12 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ $skill->is_active ? 'bg-[#0f766e]' : 'bg-gray-300' }}"
                                        role="switch"
                                        aria-checked="{{ $skill->is_active ? 'true' : 'false' }}">

                                        <span
                                            aria-hidden="true"
                                            class="pointer-events-none inline-block h-6 w-6 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $skill->is_active ? '-translate-x-5' : 'translate-x-0' }}">
                                        </span>
                                    </button>

                                </form>

                            </td>


                            <!-- Actions -->
                            <td class="px-6 py-4 whitespace-nowrap text-center">

                                <div class="flex items-center justify-center gap-2">

                                    <!-- Edit Button -->
                                    <button
                                        type="button"
                                        onclick="openEditModal(
                                            {{ $skill->id }},
                                            @js($skill->name)
                                        )"
                                        class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-[#e5f4f0] text-[#0f766e] hover:bg-[#d5eee8] border border-[#cde9e2] transition-colors"
                                        title="تعديل">

                                        <svg class="w-4 h-4"
                                             fill="none"
                                             stroke="currentColor"
                                             viewBox="0 0 24 24">

                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"
                                            />

                                        </svg>

                                    </button>


                                    <!-- Delete Button -->
                                    <button
                                        type="button"
                                        onclick="openDeleteModal(
                                            {{ $skill->id }},
                                            @js($skill->name)
                                        )"
                                        class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-[#fff0f1] text-[#c4515d] hover:bg-[#ffe1e4] border border-[#f5d1d5] transition-colors"
                                        title="حذف">

                                        <svg class="w-4 h-4"
                                             fill="none"
                                             stroke="currentColor"
                                             viewBox="0 0 24 24">

                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                            />

                                        </svg>

                                    </button>

                                </div>

                            </td>

                        </tr>


                    @empty

                        <tr>

                            <td colspan="4" class="px-6 py-12 text-center text-[#5f7772]">

                                <svg
                                    class="mx-auto h-12 w-12 text-[#83aaa2] mb-3"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24">

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                    />

                                </svg>

                                <p class="text-base font-semibold text-[#385a55]">
                                    لا توجد مهارات مسجلة حالياً.
                                </p>

                                <p class="text-sm text-gray-400 mt-1">
                                    يمكنك إضافة مهارة جديدة من الزر أعلاه.
                                </p>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        <!-- Pagination -->
        @if(method_exists($skills, 'links'))

            <div class="px-6 py-4 bg-[#f7fbfa] border-t border-[#e1eeeb]">
                {{ $skills->links() }}
            </div>

        @endif

    </div>

</div>


<!-- ========================================================= -->
<!-- ADD SKILL MODAL -->
<!-- ========================================================= -->

<div
    id="addModal"
    class="modal fixed inset-0 z-50 items-center justify-center bg-black/40 backdrop-blur-sm px-4">

    <div
        class="bg-white w-full max-w-md rounded-2xl shadow-2xl overflow-hidden"
        onclick="event.stopPropagation()">

        <!-- Modal Header -->
        <div class="px-6 py-5 bg-[#edf8f5] border-b border-[#dceee9] flex items-center justify-between">

            <div class="flex items-center gap-3">

                <div class="w-10 h-10 rounded-xl bg-[#0f766e] text-white flex items-center justify-center">

                    <svg class="w-5 h-5"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 4v16m8-8H4"
                        />

                    </svg>

                </div>

                <div>

                    <h2 class="text-lg font-bold text-[#174e49]">
                        إضافة مهارة جديدة
                    </h2>

                    <p class="text-xs text-gray-500 mt-1">
                        أضف مهارة جديدة وربطها بالكوتش/الأخصائي
                    </p>

                </div>

            </div>


            <button
                type="button"
                onclick="closeAddModal()"
                class="w-8 h-8 rounded-lg text-gray-400 hover:bg-white hover:text-gray-700 transition">

                <svg class="w-5 h-5 mx-auto"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M6 18L18 6M6 6l12 12"
                    />

                </svg>

            </button>

        </div>


        <!-- Add Form -->
        <form
            action="{{ route('admin.skills.store') }}"
            method="POST">

            @csrf

            <div class="p-6 space-y-4">

                <!-- Select Coach -->
                <div>
                    <label class="block text-sm font-semibold text-[#385a55] mb-2">
                        اختر الكوتش / أخصائي التغذية
                    </label>

                    <select
                        name="coach_id"
                        required
                        class="w-full px-4 py-3 border border-[#d6ebe6] rounded-xl text-sm outline-none focus:ring-2 focus:ring-[#0f766e]/20 focus:border-[#0f766e] bg-white transition">
                        <option value="" disabled selected>اختر الكوتش من القائمة...</option>
                        @foreach($coaches as $coach)
                            <option value="{{ $coach->id }}">{{ $coach->full_name }} (ID: #{{ $coach->id }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Skill Name -->
                <div>
                    <label class="block text-sm font-semibold text-[#385a55] mb-2">
                        اسم المهارة
                    </label>

                    <input
                        type="text"
                        name="name"
                        required
                        placeholder="مثال: تمارين القوة"
                        class="w-full px-4 py-3 border border-[#d6ebe6] rounded-xl text-sm outline-none focus:ring-2 focus:ring-[#0f766e]/20 focus:border-[#0f766e] transition"
                    >
                </div>

                <!-- Status Checkbox/Toggle -->
                <div class="flex items-center justify-between p-3 bg-[#f7fcfa] border border-[#d6ebe6] rounded-xl">
                    <span class="text-sm font-semibold text-[#385a55]">تفعيل المهارة تلقائياً؟</span>

                    <input type="hidden" name="is_active" value="0">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" class="sr-only peer" checked>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:right-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#0f766e]"></div>
                    </label>
                </div>

            </div>


            <!-- Footer -->
            <div class="px-6 py-4 bg-[#f8fcfb] border-t border-[#e1eeeb] flex justify-start gap-3">

                <button
                    type="submit"
                    class="px-5 py-2.5 bg-[#0f766e] hover:bg-[#0b625c] text-white text-sm font-semibold rounded-xl transition">

                    إضافة المهارة

                </button>

                <button
                    type="button"
                    onclick="closeAddModal()"
                    class="px-5 py-2.5 bg-white hover:bg-gray-50 text-gray-600 border border-gray-200 text-sm font-semibold rounded-xl transition">

                    إلغاء

                </button>

            </div>

        </form>

    </div>

</div>


<!-- ========================================================= -->
<!-- EDIT SKILL MODAL -->
<!-- ========================================================= -->

<div
    id="editModal"
    class="modal fixed inset-0 z-50 items-center justify-center bg-black/40 backdrop-blur-sm px-4">

    <div
        class="bg-white w-full max-w-md rounded-2xl shadow-2xl overflow-hidden"
        onclick="event.stopPropagation()">

        <!-- Header -->
        <div class="px-6 py-5 bg-[#edf8f5] border-b border-[#dceee9] flex items-center justify-between">

            <div class="flex items-center gap-3">

                <div class="w-10 h-10 rounded-xl bg-[#0f766e] text-white flex items-center justify-center">

                    <svg class="w-5 h-5"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"
                        />

                    </svg>

                </div>

                <div>

                    <h2 class="text-lg font-bold text-[#174e49]">
                        تعديل المهارة
                    </h2>

                    <p class="text-xs text-gray-500 mt-1">
                        قم بتعديل اسم المهارة
                    </p>

                </div>

            </div>


            <button
                type="button"
                onclick="closeEditModal()"
                class="w-8 h-8 rounded-lg text-gray-400 hover:bg-white hover:text-gray-700 transition">

                <svg class="w-5 h-5 mx-auto"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M6 18L18 6M6 6l12 12"
                    />

                </svg>

            </button>

        </div>


        <!-- Edit Form -->
        <form
            id="editSkillForm"
            method="POST">

            @csrf
            @method('PUT')

            <div class="p-6">

                <label class="block text-sm font-semibold text-[#385a55] mb-2">
                    اسم المهارة
                </label>

                <input
                    id="editSkillName"
                    type="text"
                    name="name"
                    required
                    class="w-full px-4 py-3 border border-[#d6ebe6] rounded-xl text-sm outline-none focus:ring-2 focus:ring-[#0f766e]/20 focus:border-[#0f766e] transition"
                >

            </div>


            <!-- Footer -->
            <div class="px-6 py-4 bg-[#f8fcfb] border-t border-[#e1eeeb] flex justify-start gap-3">

                <button
                    type="submit"
                    class="px-5 py-2.5 bg-[#0f766e] hover:bg-[#0b625c] text-white text-sm font-semibold rounded-xl transition">

                    حفظ التعديل

                </button>

                <button
                    type="button"
                    onclick="closeEditModal()"
                    class="px-5 py-2.5 bg-white hover:bg-gray-50 text-gray-600 border border-gray-200 text-sm font-semibold rounded-xl transition">

                    إلغاء

                </button>

            </div>

        </form>

    </div>

</div>


<!-- ========================================================= -->
<!-- DELETE SKILL MODAL -->
<!-- ========================================================= -->

<div
    id="deleteModal"
    class="modal fixed inset-0 z-50 items-center justify-center bg-black/40 backdrop-blur-sm px-4">

    <div
        class="bg-white w-full max-w-md rounded-2xl shadow-2xl overflow-hidden"
        onclick="event.stopPropagation()">

        <!-- Header -->
        <div class="px-6 py-5 bg-[#fff5f5] border-b border-[#f5d1d5] flex items-center justify-between">

            <div class="flex items-center gap-3">

                <div class="w-10 h-10 rounded-xl bg-[#fff0f1] text-[#c4515d] flex items-center justify-center">

                    <svg class="w-5 h-5"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 9v2m0 4h.01M5.07 19h13.86a2 2 0 001.73-3L13.73 4a2 2 0 00-3.46 0L3.34 16a2 2 0 001.73 3z"
                        />

                    </svg>

                </div>

                <div>

                    <h2 class="text-lg font-bold text-[#8f3d46]">
                        حذف المهارة
                    </h2>

                    <p class="text-xs text-gray-500 mt-1">
                        تأكيد عملية الحذف
                    </p>

                </div>

            </div>


            <button
                type="button"
                onclick="closeDeleteModal()"
                class="w-8 h-8 rounded-lg text-gray-400 hover:bg-white hover:text-gray-700 transition">

                <svg class="w-5 h-5 mx-auto"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M6 18L18 6M6 6l12 12"
                    />

                </svg>

            </button>

        </div>


        <!-- Delete Form -->
        <form
            id="deleteSkillForm"
            method="POST">

            @csrf
            @method('DELETE')

            <div class="p-6 text-center">

                <div class="w-14 h-14 mx-auto mb-4 rounded-full bg-[#fff0f1] text-[#c4515d] flex items-center justify-center">

                    <svg class="w-7 h-7"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                        />

                    </svg>

                </div>


                <p class="text-sm text-gray-600">
                    هل أنت متأكد من حذف المهارة؟
                </p>

                <p
                    id="deleteSkillName"
                    class="mt-2 text-base font-bold text-[#174e49]">
                </p>

                <p class="text-xs text-gray-400 mt-2">
                    لا يمكن التراجع عن هذه العملية.
                </p>

            </div>


            <!-- Footer -->
            <div class="px-6 py-4 bg-[#fdfafa] border-t border-[#f0e2e3] flex justify-start gap-3">

                <button
                    type="submit"
                    class="px-5 py-2.5 bg-[#c4515d] hover:bg-[#aa414c] text-white text-sm font-semibold rounded-xl transition">

                    حذف المهارة

                </button>

                <button
                    type="button"
                    onclick="closeDeleteModal()"
                    class="px-5 py-2.5 bg-white hover:bg-gray-50 text-gray-600 border border-gray-200 text-sm font-semibold rounded-xl transition">

                    إلغاء

                </button>

            </div>

        </form>

    </div>

</div>


<!-- ========================================================= -->
<!-- JAVASCRIPT -->
<!-- ========================================================= -->

<script>

    /*
    |--------------------------------------------------------------------------
    | Helper
    |--------------------------------------------------------------------------
    */

    function openModal(modal) {
        modal.classList.add('active');
        document.body.classList.add('modal-open');
    }


    function closeModal(modal) {
        modal.classList.remove('active');
        document.body.classList.remove('modal-open');
    }


    /*
    |--------------------------------------------------------------------------
    | Add Modal
    |--------------------------------------------------------------------------
    */

    const addModal = document.getElementById('addModal');

    function openAddModal() {
        openModal(addModal);
    }

    function closeAddModal() {
        closeModal(addModal);
    }


    /*
    |--------------------------------------------------------------------------
    | Edit Modal
    |--------------------------------------------------------------------------
    */

    const editModal = document.getElementById('editModal');
    const editSkillForm = document.getElementById('editSkillForm');
    const editSkillName = document.getElementById('editSkillName');


    function openEditModal(id, name) {

        editSkillName.value = name;

        editSkillForm.action = "{{ url('/admin/skills') }}/" + id;

        openModal(editModal);
    }


    function closeEditModal() {
        closeModal(editModal);
    }


    /*
    |--------------------------------------------------------------------------
    | Delete Modal
    |--------------------------------------------------------------------------
    */

    const deleteModal = document.getElementById('deleteModal');
    const deleteSkillForm = document.getElementById('deleteSkillForm');
    const deleteSkillName = document.getElementById('deleteSkillName');


    function openDeleteModal(id, name) {

        deleteSkillName.textContent = name;

        deleteSkillForm.action = "{{ url('/admin/skills') }}/" + id;

        openModal(deleteModal);
    }


    function closeDeleteModal() {
        closeModal(deleteModal);
    }


    /*
    |--------------------------------------------------------------------------
    | Close Modal when clicking outside
    |--------------------------------------------------------------------------
    */

    addModal.addEventListener('click', function (event) {

        if (event.target === addModal) {
            closeAddModal();
        }

    });


    editModal.addEventListener('click', function (event) {

        if (event.target === editModal) {
            closeEditModal();
        }

    });


    deleteModal.addEventListener('click', function (event) {

        if (event.target === deleteModal) {
            closeDeleteModal();
        }

    });


    /*
    |--------------------------------------------------------------------------
    | ESC Key
    |--------------------------------------------------------------------------
    */

    document.addEventListener('keydown', function (event) {

        if (event.key === 'Escape') {

            closeAddModal();
            closeEditModal();
            closeDeleteModal();

        }

    });

</script>

</body>
</html>
