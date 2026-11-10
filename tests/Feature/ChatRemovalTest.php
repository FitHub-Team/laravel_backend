<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\User;
use Tests\AdminAccessTestCase;

class ChatRemovalTest extends AdminAccessTestCase
{
    public function test_chat_endpoints_are_unavailable_to_guests_and_authenticated_accounts(): void
    {
        $this->getJson('/api/chat/1')->assertNotFound();
        $this->postJson('/api/chat/send', ['receiver_id' => 1, 'message' => 'Test'])
            ->assertNotFound();

        foreach (['user', 'coach'] as $role) {
            $account = User::create([
                'full_name' => 'Test account',
                'email' => $role.'@example.test',
                'password' => 'Test-password-123!',
                'role' => $role,
            ]);
            $this->actingAs($account, 'sanctum');
            $this->getJson('/api/chat/1')->assertNotFound();
            $this->postJson('/api/chat/send', ['receiver_id' => 1, 'message' => 'Test'])
                ->assertNotFound();
        }

        $admin = Admin::create([
            'name' => 'Test admin',
            'email' => 'admin@example.test',
            'password' => 'Test-password-123!',
        ]);
        $this->actingAs($admin, 'sanctum');
        $this->getJson('/api/chat/1')->assertNotFound();
        $this->postJson('/api/chat/send')->assertNotFound();
    }
}
