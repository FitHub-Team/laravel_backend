document.addEventListener('DOMContentLoaded', function () {

    const searchInput = document.getElementById('search-input');
    const tableBody = document.getElementById('trainers-table-body');
    const drawerCheck = document.getElementById('drawer-check');

    let selectedTrainerId = null;


    // =========================================================
    // 1. البحث داخل جدول المدربين
    // =========================================================

    if (searchInput && tableBody) {

        searchInput.addEventListener('input', function () {

            const query = this.value.toLowerCase().trim();
            const rows = tableBody.querySelectorAll('tr');

            rows.forEach(function (row) {

                const text = row.textContent.toLowerCase();

                row.style.display = text.includes(query)
                    ? ''
                    : 'none';

            });

        });

    }


    // =========================================================
    // 2. فتح تفاصيل المدرب
    // =========================================================

    document.addEventListener('click', function (e) {

        const detailsButton = e.target.closest('.details-btn');

        if (!detailsButton) {
            return;
        }

        const trainerId = detailsButton.getAttribute('data-id');

        if (trainerId) {
            fetchTrainerDetails(trainerId);
        }

    });


    function fetchTrainerDetails(id) {

        selectedTrainerId = id;

        fetch(`/admin/trainers/${id}/details`, {

            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }

        })

            .then(function (response) {

                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }

                return response.json();

            })

            .then(function (data) {

                populateDrawer(data);

                if (drawerCheck) {
                    drawerCheck.checked = true;
                }

            })

            .catch(function (error) {

                console.error(
                    'Error fetching trainer details:',
                    error
                );

                alert('تعذر جلب تفاصيل المدرب');

            });

    }


    // =========================================================
    // 3. تعبئة بيانات الـ Drawer
    // =========================================================

    function populateDrawer(trainer) {

        setText(
            'drawer-name',
            trainer.user
                ? trainer.user.name
                : trainer.name
        );

        setText(
            'drawer-national-id',
            trainer.national_id
        );

        setText(
            'drawer-birth-year',
            trainer.birth_year
        );

        setText(
            'drawer-location',
            trainer.location
        );

        setText(
            'drawer-specialization',
            trainer.specialization
        );

        setText(
            'drawer-experience',
            trainer.experience
                ? `${trainer.experience} سنوات`
                : '-'
        );


        // Certifications

        let certifications = trainer.certifications;

        if (Array.isArray(certifications)) {

            certifications = certifications.join('، ');

        }

        setText(
            'drawer-certifications',
            certifications
        );


        // Bio

        setText(
            'drawer-bio',
            trainer.bio
        );


        // =====================================================
        // Profile Image
        // =====================================================

        const avatarImg =
            document.getElementById('drawer-avatar-img');

        const avatarFallback =
            document.getElementById('drawer-avatar-fallback');


        if (trainer.profile_image && avatarImg) {

            avatarImg.src = trainer.profile_image;
            avatarImg.style.display = 'block';

            if (avatarFallback) {
                avatarFallback.style.display = 'none';
            }

        } else {

            if (avatarImg) {
                avatarImg.style.display = 'none';
            }

            if (avatarFallback) {
                avatarFallback.style.display = 'flex';
            }

        }


        // =====================================================
        // Approval Button
        // =====================================================

        const approvalBtn =
            document.getElementById('toggle-approval-btn');

        if (approvalBtn) {

            if (trainer.is_approved) {

                approvalBtn.textContent = 'إلغاء الاعتماد';

                approvalBtn.classList.remove('btn-approve');
                approvalBtn.classList.add('btn-unapprove');

            } else {

                approvalBtn.textContent =
                    'موافقة وتفعيل الحساب';

                approvalBtn.classList.remove('btn-unapprove');
                approvalBtn.classList.add('btn-approve');

            }

        }

    }


    // =========================================================
    // Helper
    // =========================================================

    function setText(elementId, value) {

        const element =
            document.getElementById(elementId);

        if (!element) {
            return;
        }

        element.textContent =
            value !== undefined &&
                value !== null &&
                value !== ''
                ? value
                : '-';

    }


    // =========================================================
    // 4. إغلاق الـ Drawer
    // =========================================================

    const closeDrawer =
        document.querySelector('.close-drawer');

    if (closeDrawer) {

        closeDrawer.addEventListener(
            'click',
            function () {

                if (drawerCheck) {
                    drawerCheck.checked = false;
                }

            }
        );

    }


    const drawerOverlay =
        document.querySelector('.drawer-overlay');

    if (drawerOverlay) {

        drawerOverlay.addEventListener(
            'click',
            function (e) {

                if (
                    e.target === drawerOverlay &&
                    drawerCheck
                ) {

                    drawerCheck.checked = false;

                }

            }
        );

    }


    // =========================================================
    // 5. اعتماد / إلغاء اعتماد المدرب
    // =========================================================

    const approvalBtn =
        document.getElementById('toggle-approval-btn');

    if (approvalBtn) {

        approvalBtn.addEventListener(
            'click',
            function () {

                if (!selectedTrainerId) {
                    return;
                }

                const token =
                    document
                        .querySelector(
                            'meta[name="csrf-token"]'
                        )
                        ?.getAttribute('content');


                fetch(
                    `/admin/trainers/${selectedTrainerId}/toggle-approval`,
                    {
                        method: 'POST',

                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token,
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    }
                )

                    .then(function (response) {

                        if (!response.ok) {
                            throw new Error(
                                'Failed to update approval'
                            );
                        }

                        return response.json();

                    })

                    .then(function (result) {

                        if (result.success) {

                            location.reload();

                        }

                    })

                    .catch(function (error) {

                        console.error(error);

                        alert(
                            'حدث خطأ أثناء تحديث حالة الاعتماد'
                        );

                    });

            }
        );

    }


    // =========================================================
    // 6. حذف المدرب
    // =========================================================

    const deleteBtn =
        document.getElementById('delete-trainer-btn');

    if (deleteBtn) {

        deleteBtn.addEventListener(
            'click',
            function () {

                if (!selectedTrainerId) {
                    return;
                }


                const confirmed = confirm(
                    'هل أنتِ متأكدة من حذف حساب هذا المدرب ونقله لجدول المرفوضات؟'
                );


                if (!confirmed) {
                    return;
                }


                const token =
                    document
                        .querySelector(
                            'meta[name="csrf-token"]'
                        )
                        ?.getAttribute('content');


                fetch(
                    `/admin/trainers/${selectedTrainerId}`,
                    {
                        method: 'DELETE',

                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token,
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },

                        body: JSON.stringify({
                            rejection_reason:
                                'تم حذف الحساب بواسطة أدمن النظام'
                        })
                    }
                )

                    .then(function (response) {

                        if (!response.ok) {
                            throw new Error(
                                'Failed to delete trainer'
                            );
                        }

                        return response.json();

                    })

                    .then(function (result) {

                        if (result.success) {

                            window.location.reload();

                        }

                    })

                    .catch(function (error) {

                        console.error(error);

                        alert(
                            'حدث خطأ أثناء حذف حساب المدرب'
                        );

                    });

            }
        );

    }

});
