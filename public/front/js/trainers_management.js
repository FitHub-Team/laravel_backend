document.addEventListener('DOMContentLoaded', function () {

    const searchInput = document.getElementById('search-input');
    const tableBody = document.getElementById('trainers-table-body');
    const drawerCheck = document.getElementById('drawer-check');
    let selectedTrainerId = null;

    // ==========================================
    // 1. البحث المحلي داخل صفوف الجدول المطبوعة من Blade
    // ==========================================
    if (searchInput && tableBody) {
        searchInput.addEventListener('input', function () {
            const query = this.value.toLowerCase().trim();
            const rows = tableBody.querySelectorAll('tr');

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(query)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }

    // ==========================================
    // 2. عرض تفاصيل المدرب في الـ Drawer عند الضغط على "عرض التفاصيل"
    // ==========================================
    document.addEventListener('click', function (e) {
        if (e.target && e.target.classList.contains('details-btn')) {
            const trainerId = e.target.getAttribute('data-id');
            if (trainerId) {
                fetchTrainerDetails(trainerId);
            }
        }
    });

    function fetchTrainerDetails(id) {
        selectedTrainerId = id;

        // إرسال طلب AJAX لجلب تفاصيل المدرب من السيرفر
        fetch(`/admin/trainers/${id}/details`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(data => {
                populateDrawer(data);
                if (drawerCheck) drawerCheck.checked = true;
            })
            .catch(error => {
                console.error('Error fetching trainer details:', error);
                alert('تعذر جلب تفاصيل المدرب');
            });
    }

    function populateDrawer(trainer) {
        setText('drawer-name', trainer.user ? trainer.user.name : trainer.name);
        setText('drawer-national-id', trainer.national_id);
        setText('drawer-birth-year', trainer.birth_year);
        setText('drawer-location', trainer.location);
        setText('drawer-specialization', trainer.specialization);
        setText('drawer-experience', trainer.experience ? `${trainer.experience} سنوات` : '-');

        // التعامل مع الشهادات سواء كانت مصفوفة أو نص
        let certs = trainer.certifications;
        if (Array.isArray(certs)) {
            certs = certs.join('، ');
        }
        setText('drawer-certifications', certs);
        setText('drawer-bio', trainer.bio);

        // صورة البروفايل
        const avatarImg = document.getElementById('drawer-avatar-img');
        const avatarFallback = document.getElementById('drawer-avatar-fallback');

        if (trainer.profile_image && avatarImg) {
            avatarImg.src = trainer.profile_image;
            avatarImg.style.display = 'block';
            if (avatarFallback) avatarFallback.style.display = 'none';
        } else {
            if (avatarImg) avatarImg.style.display = 'none';
            if (avatarFallback) avatarFallback.style.display = 'inline';
        }

        // زر التفعيل / إلغاء التفعيل
        const approvalBtn = document.getElementById('toggle-approval-btn');
        if (approvalBtn) {
            if (trainer.is_approved) {
                approvalBtn.textContent = 'إلغاء الاعتماد';
                approvalBtn.style.backgroundColor = '#fee2e2';
                approvalBtn.style.color = '#ef4444';
            } else {
                approvalBtn.textContent = 'موافقة وتفعيل الحساب';
                approvalBtn.style.backgroundColor = '#dcfce7';
                approvalBtn.style.color = '#15803d';
            }
        }
    }

    // Helper لتغيير النصوص مع فحص القيم الفارغة
    function setText(elementId, value) {
        const element = document.getElementById(elementId);
        if (element) {
            element.textContent = (value !== undefined && value !== null && value !== '') ? value : '-';
        }
    }

    // ==========================================
    // 3. إغلاق الـ Drawer عند الضغط على الزر أو الخلفية
    // ==========================================
    const closeDrawer = document.querySelector('.close-drawer');
    if (closeDrawer) {
        closeDrawer.addEventListener('click', function () {
            if (drawerCheck) drawerCheck.checked = false;
        });
    }

    const drawerOverlay = document.querySelector('.drawer-overlay');
    if (drawerOverlay) {
        drawerOverlay.addEventListener('click', function (e) {
            if (e.target === drawerOverlay && drawerCheck) {
                drawerCheck.checked = false;
            }
        });
    }

    // ==========================================
    // 4. إرسال طلب تفعيل/إلغاء اعتماد الحساب للـ Backend
    // ==========================================
    const approvalBtn = document.getElementById('toggle-approval-btn');
    if (approvalBtn) {
        approvalBtn.addEventListener('click', function () {
            if (!selectedTrainerId) return;

            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            fetch(`/admin/trainers/${selectedTrainerId}/toggle-approval`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(res => res.json())
                .then(res => {
                    if (res.success) {
                        location.reload(); // إعادة تحميل الصفحة لتحديث الحالة والجدول
                    }
                })
                .catch(err => console.error(err));
        });
    }

    // ==========================================
    // 5. زر الحذف من النظام
    // ==========================================
    // ==========================================
    // 5. زر الحذف من النظام
    // ==========================================
    const deleteBtn = document.getElementById('delete-trainer-btn');
    if (deleteBtn) {
        deleteBtn.addEventListener('click', function () {
            if (!selectedTrainerId) return;

            if (confirm('هل أنتِ متأكدة من حذف حساب هذا المدرب ونقله لجدول المرفوضات؟')) {
                const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

                fetch(`/admin/trainers/${selectedTrainerId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        rejection_reason: 'تم حذف الحساب بواسطة أدمن النظام'
                    })
                })
                    .then(res => res.json())
                    .then(res => {
                        if (res.success) {
                            // إعادة تحميل الصفحة لتحديث الجداول والعدادات
                            window.location.reload();
                        }
                    })
                    .catch(err => console.error(err));
            }
        });
    }
});
