document.addEventListener('DOMContentLoaded', function () {

    // ==========================================
    // Variables
    // ==========================================

    const searchInput =
        document.getElementById('search-input');

    const tableBody =
        document.getElementById('users-table-body');

    const drawerCheck =
        document.getElementById('drawer-check');

    const editForm =
        document.getElementById('edit-user-form');

    let selectedUserId = null;


    // ==========================================
    // 1. Local Search
    // ==========================================

    if (searchInput && tableBody) {

        searchInput.addEventListener('input', function () {

            const query =
                this.value.toLowerCase().trim();

            const rows =
                tableBody.querySelectorAll('tr');


            rows.forEach(row => {

                const text =
                    row.textContent.toLowerCase();

                row.style.display =
                    text.includes(query)
                        ? ''
                        : 'none';
            });
        });
    }


    // ==========================================
    // 2. Open User Details
    // ==========================================

    document.addEventListener('click', function (e) {

        const btn =
            e.target.closest(
                '.details-btn, .btn-view'
            );


        if (!btn) {
            return;
        }


        e.preventDefault();


        const userId =
            btn.getAttribute('data-id');


        if (!userId) {
            return;
        }


        selectedUserId = userId;


        displayUserDetails(userId);


        if (drawerCheck) {
            drawerCheck.checked = true;
        }
    });


    // ==========================================
    // 3. Get User Details From API
    // ==========================================

    function displayUserDetails(id) {

        fetch(`/api/admin/users/${id}`, {

            method: 'GET',

            headers: {

                'Accept': 'application/json',

                'X-Requested-With':
                    'XMLHttpRequest'
            }

        })

            .then(async response => {

                const text =
                    await response.text();


                console.log(
                    'Details API Status:',
                    response.status
                );


                console.log(
                    'Details API Response:',
                    text
                );


                if (!response.ok) {

                    throw new Error(
                        `HTTP ${response.status}: ${text}`
                    );
                }


                return JSON.parse(text);
            })


            .then(response => {

                console.log(
                    'Parsed User Details:',
                    response
                );


                /*
                 * UserDetailResource يرجع:
                 *
                 * {
                 *   data: {
                 *      id,
                 *      name,
                 *      email,
                 *      gender,
                 *      dob,
                 *      height,
                 *      weight,
                 *      health_goal
                 *   }
                 * }
                 */

                const user =
                    response.data ?? response;


                populateDrawer(user);
            })


            .catch(error => {

                console.error(
                    'Error loading user details:',
                    error
                );


                alert(
                    'تعذر جلب تفاصيل المستخدم'
                );
            });
    }


    // ==========================================
    // 4. Populate Drawer
    // ==========================================

    function populateDrawer(user) {

        console.log(
            'User used to populate drawer:',
            user
        );


        // Name
        setValue(
            'drawer-edit-name',
            user.full_name || ''
        );


        // Email
        setValue(
            'drawer-edit-email',
            user.email || ''
        );


        // Gender
        let genderVal =
            user.gender || 'male';


        if (
            ['أنثى', 'female', 'Female']
                .includes(genderVal)
        ) {

            genderVal = 'female';

        } else {

            genderVal = 'male';
        }


        setValue(
            'drawer-edit-gender',
            genderVal
        );


        // Date of birth
        setValue(
            'drawer-edit-dob',
            user.dob || ''
        );


        // Height
        setValue(
            'drawer-edit-height',
            user.height ?? ''
        );


        // Weight
        setValue(
            'drawer-edit-weight',
            user.weight ?? ''
        );


        // Health goal
        setValue(
            'drawer-edit-goal',
            user.health_goal || ''
        );


        // ==========================================
        // Avatar
        // ==========================================

        const avatarImg =
            document.getElementById(
                'drawer-avatar-img'
            );

        const avatarFallback =
            document.getElementById(
                'drawer-avatar-fallback'
            );


        if (
            user.profile_image &&
            avatarImg
        ) {

            avatarImg.src =
                user.profile_image;

            avatarImg.style.display =
                'block';


            if (avatarFallback) {

                avatarFallback.style.display =
                    'none';
            }

        } else {

            if (avatarImg) {

                avatarImg.style.display =
                    'none';
            }


            if (avatarFallback) {

                avatarFallback.style.display =
                    'inline';
            }
        }
    }


    // ==========================================
    // Helper: Set Input Value
    // ==========================================

    function setValue(elementId, value) {

        const element =
            document.getElementById(elementId);


        if (element) {

            element.value = value;
        }
    }


    // ==========================================
    // 5. Update User
    // ==========================================

    if (editForm) {

        editForm.addEventListener(
            'submit',
            function (e) {

                e.preventDefault();


                if (!selectedUserId) {
                    return;
                }


                const token =
                    document
                        .querySelector(
                            'meta[name="csrf-token"]'
                        )
                        ?.getAttribute(
                            'content'
                        );


                const submitBtn =
                    document.getElementById(
                        'save-user-btn'
                    );


                if (submitBtn) {

                    submitBtn.disabled = true;

                    submitBtn.textContent =
                        'جاري الحفظ...';
                }


                // ==========================================
                // Data
                // ==========================================

                const updatedData = {

                    full_name:
                        document.getElementById(
                            'drawer-edit-name'
                        ).value,

                    email:
                        document.getElementById(
                            'drawer-edit-email'
                        ).value,

                    gender:
                        document.getElementById(
                            'drawer-edit-gender'
                        ).value,

                    dob:
                        document.getElementById(
                            'drawer-edit-dob'
                        ).value,

                    height:
                        document.getElementById(
                            'drawer-edit-height'
                        ).value,

                    weight:
                        document.getElementById(
                            'drawer-edit-weight'
                        ).value,

                    health_goal:
                        document.getElementById(
                            'drawer-edit-goal'
                        ).value
                };


                // ==========================================
                // Send PUT Request
                // ==========================================

                fetch(
                    `/api/admin/users/${selectedUserId}`,
                    {

                        method: 'PUT',

                        headers: {

                            'Content-Type':
                                'application/json',

                            'Accept':
                                'application/json',

                            'X-CSRF-TOKEN':
                                token,

                            'X-Requested-With':
                                'XMLHttpRequest'
                        },

                        body:
                            JSON.stringify(
                                updatedData
                            )
                    }
                )


                    .then(async response => {

                        const text =
                            await response.text();


                        console.log(
                            'Update API Status:',
                            response.status
                        );


                        console.log(
                            'Update API Response:',
                            text
                        );


                        if (!response.ok) {

                            throw new Error(
                                `HTTP ${response.status}: ${text}`
                            );
                        }


                        return JSON.parse(text);
                    })


                    .then(data => {

                        if (!data.status) {

                            throw new Error(
                                data.message ||
                                'تعذر حفظ البيانات'
                            );
                        }


                        // ==========================================
                        // Update Table
                        // ==========================================

                        const row =
                            document.getElementById(
                                `user-row-${selectedUserId}`
                            );


                        if (row) {

                            const nameCell =
                                row.querySelector(
                                    '.user-name-cell strong'
                                );

                            const emailCell =
                                row.querySelector(
                                    '.user-email-cell'
                                );


                            if (nameCell) {

                                nameCell.textContent =
                                    updatedData.full_name;
                            }


                            if (emailCell) {

                                emailCell.textContent =
                                    updatedData.email;
                            }
                        }


                        alert(
                            'تم حفظ التعديلات بنجاح في قاعدة البيانات!'
                        );


                        if (drawerCheck) {

                            drawerCheck.checked =
                                false;
                        }
                    })


                    .catch(error => {

                        console.error(
                            'Update error:',
                            error
                        );


                        alert(
                            'حدث خطأ أثناء حفظ التعديلات.'
                        );
                    })


                    .finally(() => {

                        if (submitBtn) {

                            submitBtn.disabled =
                                false;

                            submitBtn.textContent =
                                'حفظ التعديلات';
                        }
                    });
            }
        );
    }


    // ==========================================
    // 6. Close Drawer
    // ==========================================

    const closeDrawer =
        document.querySelector(
            '.close-drawer'
        );


    if (closeDrawer) {

        closeDrawer.addEventListener(
            'click',
            function () {

                if (drawerCheck) {

                    drawerCheck.checked =
                        false;
                }
            }
        );
    }


    const drawerOverlay =
        document.querySelector(
            '.drawer-overlay'
        );


    if (drawerOverlay) {

        drawerOverlay.addEventListener(
            'click',
            function (e) {

                if (
                    e.target === drawerOverlay &&
                    drawerCheck
                ) {

                    drawerCheck.checked =
                        false;
                }
            }
        );
    }


    // ==========================================
    // 7. Delete User
    // ==========================================

    const deleteBtn =
        document.getElementById(
            'delete-user-btn'
        );


    if (deleteBtn) {

        deleteBtn.addEventListener(
            'click',
            function () {

                if (!selectedUserId) {
                    return;
                }


                if (
                    !confirm(
                        'هل أنتِ متأكدة من حذف حساب هذا المستخدم؟'
                    )
                ) {

                    return;
                }


                const token =
                    document
                        .querySelector(
                            'meta[name="csrf-token"]'
                        )
                        ?.getAttribute(
                            'content'
                        );


                fetch(
                    `/api/admin/users/${selectedUserId}`,
                    {

                        method: 'DELETE',

                        headers: {

                            'Content-Type':
                                'application/json',

                            'Accept':
                                'application/json',

                            'X-CSRF-TOKEN':
                                token,

                            'X-Requested-With':
                                'XMLHttpRequest'
                        }
                    }
                )


                    .then(async response => {

                        const text =
                            await response.text();


                        console.log(
                            'Delete API Status:',
                            response.status
                        );


                        console.log(
                            'Delete API Response:',
                            text
                        );


                        if (!response.ok) {

                            throw new Error(
                                `HTTP ${response.status}: ${text}`
                            );
                        }


                        return JSON.parse(text);
                    })


                    .then(data => {

                        if (data.status) {

                            const row =
                                document.getElementById(
                                    `user-row-${selectedUserId}`
                                );


                            if (row) {

                                row.remove();
                            }


                            if (drawerCheck) {

                                drawerCheck.checked =
                                    false;
                            }


                            alert(
                                'تم حذف المستخدم بنجاح'
                            );

                        } else {

                            alert(
                                'حدث خطأ أثناء الحذف: ' +
                                data.message
                            );
                        }
                    })


                    .catch(error => {

                        console.error(
                            'Delete error:',
                            error
                        );


                        alert(
                            'حدث خطأ أثناء حذف المستخدم.'
                        );
                    });
            }
        );
    }

});
