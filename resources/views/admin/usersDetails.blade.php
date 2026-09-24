<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة تفاصيل المستخدمين - SuperFit</title>
    <link rel="stylesheet" href="{{ asset('front/css/usersDetails.css') }}">
</head>
<body>

    <div class="admin-container">

        {{-- رسائل الإشعارات والنجاح --}}
        @if(session('success'))
            <div style="background-color: #d1e7dd; color: #0f5132; padding: 12px 20px; border-radius: 6px; margin-bottom: 20px; border: 1px solid #badbcc;">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div style="background-color: #f8d7da; color: #842029; padding: 12px 20px; border-radius: 6px; margin-bottom: 20px; border: 1px solid #f5c2c7;">
                <ul style="margin: 0; padding-right: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <header class="page-header">
            <div class="header-title">
                <a href="{{ route('admin.dashboard') }}" class="btn-back">
                    <span class="back-icon">→</span> العودة للوحة التحكم
                </a>
                <h2>إدارة تفاصيل المستخدمين</h2>
                <p>عرض ومتابعة بيانات المشتركين والمدربين في نظام SuperFit</p>
            </div>
            <button class="btn-add-user" onclick="openAddUserModal()">
                <span class="plus-icon">+</span> إضافة مستخدم جديد
            </button>
        </header>

        <div class="table-responsive">
            <table class="users-table">
                <thead>
                    <tr>
                        <th>الصورة</th>
                        <th>الاسم الكامل</th>
                        <th>البريد الإلكتروني</th>
                        <th>نوع الحساب</th>
                        <th>الجنس</th>
                        <th>الهدف</th>
                        <th>مستوى النشاط</th>
                        <th>حالة التوثيق</th>
                        <th>تاريخ التسجيل</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody id="usersTableBody">
                    @forelse($users as $user)
                    <tr
                        data-id="{{ $user->id }}"
                        data-name="{{ $user->full_name }}"
                        data-email="{{ $user->email }}"
                        data-role="{{ strtolower($user->role) }}"
                        data-verified="{{ $user->email_verified_at ? 'موثق' : 'غير موثق' }}"
                        data-verified-date="{{ $user->email_verified_at ? $user->email_verified_at->format('Y-m-d') : '-' }}"
                        data-gender="{{ $user->profile?->gender ?? '' }}"
                        data-birthdate="{{ $user->profile?->date_of_birth ?? '' }}"
                        data-height="{{ $user->profile?->height ?? '' }}"
                        data-weight="{{ $user->profile?->weight ?? '' }}"
                        data-goal-id="{{ $user->profile?->goal_id }}"
                        data-goal="{{ $user->profile?->goal?->title ?? 'غير محدد' }}"
                        data-activity-id="{{ $user->profile?->activity_level_id }}"
                        data-activity="{{ $user->profile?->activityLevel?->title ?? 'غير محدد' }}"
                        {{-- data-place-id="{{ $user->profile?->training_location_id }}" --}}
                        data-place="{{ $user->profile?->trainingLocation?->name ?? 'غير محدد' }}"
                        data-coachtype="{{ $user->profile?->trainer_type ?? 'AI' }}"
                        data-days="{{ is_array($user->profile?->available_days) ? implode(', ', $user->profile->available_days) : ($user->profile?->available_days ?? '') }}"
                        data-health="{{ $user->profile?->health_condition_note ?? '' }}"
                        data-diet="{{ $user->profile?->dietary_restriction_note ?? '' }}"
                        data-disclaimer="{{ $user->profile?->disclaimer_accepted ? 'نعم' : 'لا' }}"
                        data-created="{{ $user->created_at->format('Y-m-d H:i A') }}"
                        data-updated="{{ $user->updated_at->format('Y-m-d H:i A') }}"
                        data-notes="{{ $user->profile?->notes ?? '' }}">

                        <td>
                            <img src="{{ $user->profile?->profile_photo ? asset('storage/' . $user->profile->profile_photo) : 'https://via.placeholder.com/40' }}"
                                 alt="صورة المستخدم" class="user-avatar">
                        </td>
                        <td class="cell-name">{{ $user->full_name }}</td>
                        <td class="cell-email">{{ $user->email }}</td>
                        <td class="cell-role">
                            <span class="badge {{ strtolower($user->role) === 'coach' ? 'role-coach' : 'role-user' }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td class="cell-gender">{{ $user->profile?->gender ?? '-' }}</td>
                        <td class="cell-goal">{{ $user->profile?->goal?->title ?? '-' }}</td>
                        <td class="cell-activity">{{ $user->profile?->activityLevel?->title ?? '-' }}</td>
                        <td class="cell-verified">
                            @if($user->email_verified_at)
                                <span class="badge status-verified">موثق</span>
                            @else
                                <span class="badge role-coach">غير موثق</span>
                            @endif
                        </td>
                        <td class="cell-created">{{ $user->created_at->format('Y-m-d') }}</td>
                        <td>
                            <button class="btn-action btn-details" onclick="openDetailsModal(this)">التفاصيل</button>
                            <button class="btn-action btn-edit" onclick="openEditModal(this)">تعديل</button>

                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('هل أنت متأكد من حذف هذا المستخدم؟')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action btn-delete">حذف</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" style="text-align: center; padding: 20px;">لا يوجد مستخدمين مسجلين حالياً.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-wrapper" style="margin-top: 15px;">
            {{ $users->links() }}
        </div>
    </div>

    {{-- مودال عرض التفاصيل --}}
    <div id="userDetailsModal" class="modal-overlay" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>تفاصيل المستخدم</h3>
                <button type="button" class="close-modal" onclick="closeDetailsModal()">&times;</button>
            </div>

            <div class="modal-body">
                <div class="cards-grid">
                    <div class="info-card">
                        <h4>المعلومات الأساسية</h4>
                        <p><strong>الاسم:</strong> <span id="viewName"></span></p>
                        <p><strong>البريد:</strong> <span id="viewEmail"></span></p>
                        <p><strong>نوع الحساب:</strong> <span id="viewRole"></span></p>
                        <p><strong>توثيق البريد:</strong> <span id="viewVerified"></span> (<span id="viewVerifiedDate"></span>)</p>
                    </div>

                    <div class="info-card">
                        <h4>المعلومات الشخصية</h4>
                        <p><strong>الجنس:</strong> <span id="viewGender"></span></p>
                        <p><strong>تاريخ الميلاد:</strong> <span id="viewBirthdate"></span></p>
                    </div>

                    <div class="info-card">
                        <h4>البيانات الجسمانية</h4>
                        <p><strong>الطول:</strong> <span id="viewHeight"></span> سم</p>
                        <p><strong>الوزن:</strong> <span id="viewWeight"></span> كغ</p>
                    </div>

                    <div class="info-card">
                        <h4>بيانات التدريب والهدف</h4>
                        <p><strong>الهدف:</strong> <span id="viewGoal"></span></p>
                        <p><strong>مستوى النشاط:</strong> <span id="viewActivity"></span></p>
                        <p><strong>مكان التدريب:</strong> <span id="viewPlace"></span></p>
                        <p><strong>نوع المدرب:</strong> <span id="viewCoachType"></span></p>
                        <p><strong>أيام التدريب:</strong> <span id="viewDays"></span></p>
                    </div>

                    <div class="info-card">
                        <h4>البيانات الصحية والغذائية</h4>
                        <p><strong>الحالات الصحية:</strong> <span id="viewHealth"></span></p>
                        <p><strong>القيود الغذائية:</strong> <span id="viewDiet"></span></p>
                        <p><strong>إخلاء المسؤولية:</strong> <span id="viewDisclaimer"></span></p>
                    </div>

                    <div class="info-card">
                        <h4>تفاصيل إضافية</h4>
                        <p><span id="viewNotes">لا توجد ملاحظات إضافية</span></p>
                    </div>

                    <div class="info-card">
                        <h4>معلومات الحساب والتواريخ</h4>
                        <p><strong>تاريخ الإنشاء:</strong> <span id="viewCreated"></span></p>
                        <p><strong>آخر تحديث:</strong> <span id="viewUpdated"></span></p>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-action btn-delete" onclick="closeDetailsModal()">إغلاق</button>
            </div>
        </div>
    </div>

    {{-- مودال إضافة وتعديل مستخدم --}}
    <div id="addUserModal" class="modal-overlay" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalTitle">إضافة مستخدم جديد</h3>
                <button type="button" class="close-modal" onclick="closeAddUserModal()">&times;</button>
            </div>

            <form id="addUserForm" method="POST" action="{{ route('admin.users.store') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">

                <div class="modal-body">
                    <div class="cards-grid">

                        <div class="info-card">
                            <h4>المعلومات الأساسية</h4>
                            <p>
                                <label>الاسم الكامل <span style="color:red;">*</span>:</label><br>
                                <input type="text" name="full_name" id="newUserName" class="form-input" placeholder="أدخل الاسم" required>
                            </p>
                            <p>
                                <label>البريد الإلكتروني <span style="color:red;">*</span>:</label><br>
                                <input type="email" name="email" id="newUserEmail" class="form-input" placeholder="example@domain.com" required>
                            </p>
                            <p>
                                <label>كلمة المرور:</label><br>
                                <input type="password" name="password" id="newUserPassword" class="form-input" placeholder="اتركه فارغاً في حال عدم التغيير">
                            </p>
                            <p>
                                <label>نوع الحساب <span style="color:red;">*</span>:</label><br>
                                <select name="role" id="newUserRole" class="form-input" required>
                                    <option value="user">User</option>
                                    <option value="coach">Coach</option>
                                </select>
                            </p>
                            <p>
                                <label>صورة الملف الشخصي:</label><br>
                                <input type="file" name="profile_photo" id="newUserPhoto" class="form-input" accept="image/*">
                            </p>
                        </div>

                        <div class="info-card">
                            <h4>المعلومات الشخصية</h4>
                            <p>
                                <label>الجنس:</label><br>
                                <select name="gender" id="newUserGender" class="form-input">
                                    <option value="">اختر الجنس</option>
                                    <option value="ذكر">ذكر</option>
                                    <option value="أنثى">أنثى</option>
                                </select>
                            </p>
                            <p>
                                <label>تاريخ الميلاد:</label><br>
                                <input type="date" name="date_of_birth" id="newUserBirthdate" class="form-input">
                            </p>
                        </div>

                        <div class="info-card">
                            <h4>البيانات الجسمانية</h4>
                            <p>
                                <label>الطول (سم):</label><br>
                                <input type="number" step="0.1" name="height" id="newUserHeight" class="form-input" placeholder="175">
                            </p>
                            <p>
                                <label>الوزن (كغ):</label><br>
                                <input type="number" step="0.1" name="weight" id="newUserWeight" class="form-input" placeholder="78">
                            </p>
                        </div>

                        <div class="info-card">
                            <h4>بيانات التدريب والهدف</h4>
                            <p>
                                <label>الهدف الرياضي:</label><br>
                                <select name="goal_id" id="newUserGoal" class="form-input">
                                    <option value="">اختر الهدف</option>
                                    @foreach($goals as $goal)
                                        <option value="{{ $goal->id }}">{{ $goal->title }}</option>
                                    @endforeach
                                </select>
                            </p>
                            <p>
                                <label>مستوى النشاط:</label><br>
                                <select name="activity_level_id" id="newUserActivity" class="form-input">
                                    <option value="">اختر مستوى النشاط</option>
                                    @foreach($activityLevels as $level)
                                        <option value="{{ $level->id }}">{{ $level->title }}</option>
                                    @endforeach
                                </select>
                            </p>
                            <p>
                                <label>نوع المدرب:</label><br>
                                <select name="trainer_type" id="newUserCoachType" class="form-input">
                                    <option value="ai">AI</option>
                                    <option value="human">بشري</option>
                                </select>
                            </p>
                            <p>
                                <label>أيام التدريب المتاحة:</label><br>
                                <input type="text" name="available_days" id="newUserDays" class="form-input" placeholder="مثال: السبت، الإثنين، الأربعاء">
                            </p>
                        </div>

                        <div class="info-card">
                            <h4>البيانات الصحية والغذائية</h4>
                            <p>
                                <label>الحالات الصحية:</label><br>
                                <textarea name="health_condition_note" id="newUserHealth" class="form-input" rows="2" placeholder="أدخل الملاحظات الصحية إن وجدت"></textarea>
                            </p>
                            <p>
                                <label>القيود الغذائية:</label><br>
                                <input type="text" name="dietary_restriction_note" id="newUserDiet" class="form-input" placeholder="مثال: حساسية جلوتين">
                            </p>
                        </div>

                        <div class="info-card">
                            <h4>ملاحظات وتفاصيل إضافية</h4>
                            <p>
                                <label>ملاحظات إضافية:</label><br>
                                <textarea name="notes" id="newUserNotes" class="form-input" rows="3" placeholder="أضف أي تفاصيل أخرى هنا"></textarea>
                            </p>
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn-action btn-details" id="saveBtn" style="background-color: #28a745; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer;">
                        حفظ البيانات
                    </button>
                    <button type="button" class="btn-action btn-delete" onclick="closeAddUserModal()" style="padding: 10px 20px; border-radius: 5px; cursor: pointer;">
                        إلغاء
                    </button>
                </div>
            </form>
        </div>
    </div>

<script src="{{ asset('front/js/usersDetails.js') }}"></script>
</body>
</html>
