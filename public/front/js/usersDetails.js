document.addEventListener('DOMContentLoaded', function () {

    const modal = document.getElementById('addUserModal');
    const form = document.getElementById('addUserForm');

    const modalTitle = document.getElementById('modalTitle');
    const formMethod = document.getElementById('formMethod');
    const saveBtn = document.getElementById('saveBtn');

    const nameInput = document.getElementById('newUserName');
    const emailInput = document.getElementById('newUserEmail');
    const passwordInput = document.getElementById('newUserPassword');
    const roleInput = document.getElementById('newUserRole');
    const photoInput = document.getElementById('newUserPhoto');

    const genderInput = document.getElementById('newUserGender');
    const birthdateInput = document.getElementById('newUserBirthdate');

    const heightInput = document.getElementById('newUserHeight');
    const weightInput = document.getElementById('newUserWeight');

    const goalInput = document.getElementById('newUserGoal');
    const activityInput = document.getElementById('newUserActivity');
    // const locationInput = document.getElementById('newUserLocation');
    const coachTypeInput = document.getElementById('newUserCoachType');
    const daysInput = document.getElementById('newUserDays');

    const healthInput = document.getElementById('newUserHealth');
    const dietInput = document.getElementById('newUserDiet');
    const notesInput = document.getElementById('newUserNotes');


    // ==========================================
    // إضافة مستخدم جديد
    // ==========================================

    window.openAddUserModal = function () {

        form.reset();

        form.action = '/admin/users';

        formMethod.value = 'POST';

        modalTitle.textContent = 'إضافة مستخدم جديد';

        saveBtn.textContent = 'حفظ البيانات';

        passwordInput.required = false;

        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    };


    // ==========================================
    // تعديل مستخدم
    // ==========================================

    window.openEditModal = function (button) {

        const row = button.closest('tr');

        if (!row) {
            console.error('لم يتم العثور على صف المستخدم');
            return;
        }

        const id = row.dataset.id;

        // تغيير عنوان المودال
        modalTitle.textContent = 'تعديل بيانات المستخدم';

        // تغيير طريقة الإرسال إلى PUT
        formMethod.value = 'PUT';

        // رابط التحديث
        form.action = '/admin/users/' + id;

        // البيانات الأساسية
        nameInput.value = row.dataset.name || '';
        emailInput.value = row.dataset.email || '';
        roleInput.value = (row.dataset.role || 'user').toLowerCase();

        // لا نضع كلمة المرور القديمة
        passwordInput.value = '';
        passwordInput.required = false;

        // البيانات الشخصية
        genderInput.value = normalizeGender(row.dataset.gender);

        birthdateInput.value = row.dataset.birthdate || '';

        // البيانات الجسمانية
        heightInput.value = row.dataset.height || '';
        weightInput.value = row.dataset.weight || '';

        // بيانات التدريب
        goalInput.value = row.dataset.goalId || '';
        activityInput.value = row.dataset.activityId || '';
        // locationInput.value = row.dataset.placeId || '';

        coachTypeInput.value = normalizeCoachType(row.dataset.coachtype);

        daysInput.value = row.dataset.days || '';

        // البيانات الصحية
        healthInput.value = row.dataset.health || '';
        dietInput.value = row.dataset.diet || '';

        // الملاحظات
        notesInput.value = row.dataset.notes || '';

        // تغيير نص الزر
        saveBtn.textContent = 'حفظ التعديلات';

        // فتح المودال
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    };


    // ==========================================
    // تحويل الجنس للقيمة الموجودة في قاعدة البيانات
    // ==========================================

    function normalizeGender(value) {

        if (!value) {
            return '';
        }

        if (value === 'ذكر' || value === 'male') {
            return 'ذكر';
        }

        if (value === 'أنثى' || value === 'female') {
            return 'أنثى';
        }

        return value;
    }


    // ==========================================
    // تحويل نوع المدرب
    // ==========================================

    function normalizeCoachType(value) {

        if (!value) {
            return 'ai';
        }

        value = value.toLowerCase();

        if (value === 'ai' || value === 'ذكاء اصطناعي') {
            return 'ai';
        }

        if (value === 'human' || value === 'بشري') {
            return 'human';
        }

        return value;
    }


    // ==========================================
    // إغلاق مودال الإضافة / التعديل
    // ==========================================

    window.closeAddUserModal = function () {

        modal.style.display = 'none';

        document.body.style.overflow = '';

        form.reset();

        form.action = '/admin/users';

        formMethod.value = 'POST';

        modalTitle.textContent = 'إضافة مستخدم جديد';

        saveBtn.textContent = 'حفظ البيانات';
    };


    // ==========================================
    // إغلاق المودال عند الضغط خارج المحتوى
    // ==========================================

    modal.addEventListener('click', function (event) {

        if (event.target === modal) {
            closeAddUserModal();
        }

    });


    // ==========================================
    // مودال التفاصيل
    // ==========================================

    window.openDetailsModal = function (button) {

        const row = button.closest('tr');

        if (!row) {
            return;
        }

        setText('viewName', row.dataset.name);
        setText('viewEmail', row.dataset.email);
        setText('viewRole', row.dataset.role);
        setText('viewVerified', row.dataset.verified);
        setText('viewVerifiedDate', row.dataset.verifiedDate);

        setText('viewGender', row.dataset.gender || '-');
        setText('viewBirthdate', row.dataset.birthdate || '-');

        setText('viewHeight', row.dataset.height || '-');
        setText('viewWeight', row.dataset.weight || '-');

        setText('viewGoal', row.dataset.goal || '-');
        setText('viewActivity', row.dataset.activity || '-');
        setText('viewPlace', row.dataset.place || '-');
        setText('viewCoachType', row.dataset.coachtype || '-');
        setText('viewDays', row.dataset.days || '-');

        setText('viewHealth', row.dataset.health || '-');
        setText('viewDiet', row.dataset.diet || '-');
        setText('viewDisclaimer', row.dataset.disclaimer || '-');

        setText(
            'viewNotes',
            row.dataset.notes || 'لا توجد ملاحظات إضافية'
        );

        setText('viewCreated', row.dataset.created || '-');
        setText('viewUpdated', row.dataset.updated || '-');

        document.getElementById('userDetailsModal').style.display = 'flex';

        document.body.style.overflow = 'hidden';
    };


    window.closeDetailsModal = function () {

        document.getElementById('userDetailsModal').style.display = 'none';

        document.body.style.overflow = '';
    };


    // ==========================================
    // دالة مساعدة لعرض النصوص
    // ==========================================

    function setText(id, value) {

        const element = document.getElementById(id);

        if (element) {
            element.textContent = value ?? '-';
        }
    }


    // ==========================================
    // منع إرسال النموذج أكثر من مرة
    // ==========================================

    form.addEventListener('submit', function () {

        saveBtn.disabled = true;

        setTimeout(function () {
            saveBtn.disabled = false;
        }, 3000);

    });

});
