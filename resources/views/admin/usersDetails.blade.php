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

        <header class="page-header">
            <div class="header-title">
                <h2>إدارة المستخدمين</h2>
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
                <tbody>
                    <tr>
                        <td><img src="https://via.placeholder.com/40" alt="صورة المستخدم" class="user-avatar"></td>
                        <td>أحمد محمد</td>
                        <td>ahmed@example.com</td>
                        <td><span class="badge role-user">User</span></td>
                        <td>ذكر</td>
                        <td>خسارة الوزن</td>
                        <td>نشيط جداً</td>
                        <td><span class="badge status-verified">موثق</span></td>
                        <td>2026-06-15</td>
                        <td>
                            <button class="btn-action btn-details" onclick="openDetailsModal()">التفاصيل</button>
                            <button class="btn-action btn-delete" onclick="this.closest('tr').remove()">حذف</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>


    <div id="userDetailsModal" class="modal-overlay" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>تفاصيل المستخدم</h3>
                <button class="close-modal" onclick="closeDetailsModal()">&times;</button>
            </div>

            <div class="modal-body">
                <div class="cards-grid">
                    <div class="info-card">
                        <h4>المعلومات الأساسية</h4>
                        <p><strong>الاسم:</strong> أحمد محمد</p>
                        <p><strong>البريد:</strong> ahmed@example.com</p>
                        <p><strong>توثيق البريد:</strong> موثق (2026-06-15)</p>
                    </div>

                    <div class="info-card">
                        <h4>المعلومات الشخصية</h4>
                        <p><strong>الجنس:</strong> ذكر</p>
                        <p><strong>تاريخ الميلاد:</strong> 1998-05-12</p>
                    </div>

                    <div class="info-card">
                        <h4>البيانات الجسمانية</h4>
                        <p><strong>الطول:</strong> 175 سم</p>
                        <p><strong>الوزن:</strong> 78 كغ</p>
                    </div>

                    <div class="info-card">
                        <h4>بيانات التدريب والهدف</h4>
                        <p><strong>الهدف:</strong> خسارة الوزن</p>
                        <p><strong>مستوى النشاط:</strong> نشيط جداً</p>
                        <p><strong>مكان التدريب:</strong> الجيم</p>
                        <p><strong>نوع المدرب:</strong> AI</p>
                        <p><strong>أيام التدريب:</strong> السبت، الإثنين، الأربعاء</p>
                    </div>

                    <div class="info-card">
                        <h4>البيانات الصحية والغذائية</h4>
                        <p><strong>الحالات الصحية:</strong> لا يوجد (ملاحظة: سليمة)</p>
                        <p><strong>القيود الغذائية:</strong> خالي من الجلوتين</p>
                        <p><strong>إخلاء المسؤولية:</strong> نعم</p>
                    </div>

                    <div class="info-card">
                        <h4>معلومات الحساب والتواريخ</h4>
                        <p><strong>تاريخ الإنشاء:</strong> 2026-06-15 10:00 AM</p>
                        <p><strong>آخر تحديث:</strong> 2026-09-01 02:30 PM</p>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn-action btn-delete" onclick="closeDetailsModal()">إغلاق</button>
            </div>
        </div>
    </div>
    <div id="addUserModal" class="modal-overlay" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>إضافة مستخدم جديد</h3>
                <button class="close-modal" onclick="closeAddUserModal()">&times;</button>
            </div>

            <div class="modal-body">
                <form id="addUserForm">
                    <div class="cards-grid">
                        <div class="info-card">
                            <h4>المعلومات الأساسية</h4>
                            <p><label>الاسم الكامل:</label><br><input type="text" id="newUserName" class="form-input" placeholder="أدخل الاسم"></p>
                            <p><label>البريد الإلكتروني:</label><br><input type="email" id="newUserEmail" class="form-input" placeholder="example@domain.com"></p>
                            <p><label>نوع الحساب:</label><br>
                                <select id="newUserRole" class="form-input">
                                    <option value="User">User</option>
                                    <option value="Coach">Coach</option>
                                </select>
                            </p>
                        </div>

                        <div class="info-card">
                            <h4>المعلومات الشخصية</h4>
                            <p><label>الجنس:</label><br>
                                <select id="newUserGender" class="form-input">
                                    <option value="ذكر">ذكر</option>
                                    <option value="أنثى">أنثى</option>
                                </select>
                            </p>
                            <p><label>تاريخ الميلاد:</label><br><input type="date" class="form-input"></p>
                        </div>

                        <div class="info-card">
                            <h4>البيانات الجسمانية</h4>
                            <p><label>الطول (سم):</label><br><input type="number" class="form-input" placeholder="175"></p>
                            <p><label>الوزن (كغ):</label><br><input type="number" class="form-input" placeholder="78"></p>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button class="btn-action btn-details" onclick="saveNewUser()">حفظ المستخدم</button>
                <button class="btn-action btn-delete" onclick="closeAddUserModal()">إلغاء</button>
            </div>
        </div>
    </div>
    <script>

        function openDetailsModal() {
            document.getElementById('userDetailsModal').style.display = 'flex';
        }
        function closeDetailsModal() {
            document.getElementById('userDetailsModal').style.display = 'none';
        }

        // دوال التحكم بمودال إضافة مستخدم جديد
        function openAddUserModal() {
            document.getElementById('addUserModal').style.display = 'flex';
        }
        function closeAddUserModal() {
            document.getElementById('addUserModal').style.display = 'none';
        }


        function saveNewUser() {
            const name = document.getElementById('newUserName').value.trim();
            const email = document.getElementById('newUserEmail').value.trim();
            const role = document.getElementById('newUserRole').value;
            const gender = document.getElementById('newUserGender').value;


            if (!name || !email) {
                alert('الرجاء إدخال الاسم والبريد الإلكتروني على الأقل!');
                return;
            }

            const roleClass = role === 'Coach' ? 'role-coach' : 'role-user';
            const today = new Date().toISOString().split('T')[0];
            const tableBody = document.querySelector('.users-table tbody');

            const newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td><img src="https://via.placeholder.com/40" alt="صورة المستخدم" class="user-avatar"></td>
                <td>${name}</td>
                <td>${email}</td>
                <td><span class="badge ${roleClass}">${role}</span></td>
                <td>${gender}</td>
                <td>خسارة الوزن</td>
                <td>نشيط</td>
                <td><span class="badge status-verified">موثق</span></td>
                <td>${today}</td>
                <td>
                    <button class="btn-action btn-details" onclick="openDetailsModal()">التفاصيل</button>
                    <button class="btn-action btn-delete" onclick="this.closest('tr').remove()">حذف</button>
                </td>
            `;

            tableBody.prepend(newRow);
            document.getElementById('addUserForm').reset();
            closeAddUserModal();

            alert('تم إضافة المستخدم بنجاح إلى الجدول!');
        }
    </script>
</body>
</html>
