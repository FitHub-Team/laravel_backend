<?php

namespace Tests\Feature;

use Tests\AdminAccessTestCase;

class ToastNotificationsTest extends AdminAccessTestCase
{
    public function test_blade_login_loads_shared_toasts_and_flashes_session_feedback(): void
    {
        $this->withSession(['success' => 'Saved successfully'])
            ->get('/admin/login')
            ->assertOk()
            ->assertSee('front/css/toast.css', false)
            ->assertSee('front/js/toast.js', false)
            ->assertSee('window.SuperFitToast.show("Saved successfully", "success")', false);
    }

    public function test_flash_content_is_escaped_and_validation_errors_remain_visible(): void
    {
        $this->withSession(['error' => '</script><script>alert(1)</script>'])
            ->get('/admin/login')
            ->assertOk()
            ->assertDontSee('</script><script>alert(1)</script>', false);

        $this->from('/admin/login')->post('/admin/login', [])
            ->assertRedirect('/admin/login')->assertSessionHasErrors(['name', 'email', 'password']);
        $this->get('/admin/login')->assertOk()->assertSee('window.SuperFitToast.error(', false);
    }
}
