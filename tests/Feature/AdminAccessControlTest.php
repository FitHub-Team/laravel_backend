<?php

namespace Tests\Feature;

use App\Http\Middleware\EnsureAdmin;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\AdminAccessTestCase;

class AdminAccessControlTest extends AdminAccessTestCase
{
    private function admin(): Admin
    {
        return Admin::create([
            'name' => 'Test Admin',
            'email' => 'admin@example.test',
            'password' => 'Test-password-123!',
        ]);
    }

    private function user(string $role = 'user'): User
    {
        return User::create([
            'full_name' => 'Test User',
            'email' => 'user-'.$role.'@example.test',
            'password' => 'Test-password-123!',
            'role' => $role,
        ]);
    }

    private function adminRoutes(): array
    {
        return array_values(array_filter(
            $this->app['router']->getRoutes()->getRoutes(),
            fn ($route) => preg_match('~^(api/)?admin(?:/|$)~', $route->uri()),
        ));
    }

    private function protectedRoutes(bool $api): array
    {
        return array_values(array_filter($this->adminRoutes(), fn ($route) =>
            str_starts_with($route->uri(), 'api/') === $api
            && !in_array($route->uri(), ['api/admin/login', 'admin/login'], true)
        ));
    }

    private function path($route): string
    {
        return '/'.preg_replace('/\{[^}]+\}/', '999999', $route->uri());
    }

    private function resetAuthentication(): void
    {
        Auth::forgetGuards();
        config(['auth.defaults.guard' => 'web']);
        $this->flushHeaders();
    }

    public function test_every_admin_route_has_the_expected_security_boundary(): void
    {
        $this->assertCount(52, $this->adminRoutes());
        $this->assertCount(11, $this->protectedRoutes(true));
        $this->assertCount(38, $this->protectedRoutes(false));

        foreach ($this->adminRoutes() as $route) {
            $middleware = $route->gatherMiddleware();
            if (in_array($route->uri(), ['api/admin/login', 'admin/login'], true)) {
                $this->assertNotContains('auth:admin', $middleware);
                $this->assertNotContains('auth:sanctum', $middleware);
                $this->assertNotContains('admin.access', $middleware);
                continue;
            }

            $this->assertContains('admin.access', $middleware, $route->uri());
            $this->assertContains(
                str_starts_with($route->uri(), 'api/') ? 'auth:sanctum' : 'auth:admin',
                $middleware,
                $route->uri(),
            );
            $resolved = $this->app['router']->gatherRouteMiddleware($route);
            $this->assertLessThan(
                array_search(SubstituteBindings::class, $resolved, true),
                array_search(EnsureAdmin::class, $resolved, true),
                $route->uri(),
            );
            if (!str_starts_with($route->uri(), 'api/')) {
                $this->assertContains(PreventRequestForgery::class, $resolved);
            }
        }
    }

    public function test_all_protected_admin_apis_reject_anonymous_requests_as_json(): void
    {
        foreach ($this->protectedRoutes(true) as $route) {
            foreach ([[], ['Accept' => 'application/json']] as $headers) {
                $this->resetAuthentication();
                $this->call($route->methods()[0], $this->path($route), [], [], [],
                    $this->transformHeadersToServerVars($headers))
                    ->assertUnauthorized()
                    ->assertJsonPath('message', 'Unauthenticated.');
            }
        }
    }

    public static function nonAdminRoles(): array
    {
        return [['user'], ['coach'], ['admin']];
    }

    #[DataProvider('nonAdminRoles')]
    public function test_user_tokens_cannot_access_any_admin_api_even_with_admin_role(string $role): void
    {
        $user = $this->user($role);
        $token = $user->createToken('admin-token', ['*']);

        foreach ($this->protectedRoutes(true) as $route) {
            $this->resetAuthentication();
            $this->withToken($token->plainTextToken)
                ->json($route->methods()[0], $this->path($route))
                ->assertForbidden();
        }

        $this->assertDatabaseHas('personal_access_tokens', ['id' => $token->accessToken->id]);
        $this->assertDatabaseHas('users', ['id' => $user->id, 'role' => $role]);
    }

    public function test_admin_api_login_and_current_token_logout_keep_the_existing_contract(): void
    {
        $admin = $this->admin();
        $other = $admin->createToken('other-admin-client');
        $response = $this->postJson('/api/admin/login', [
            'email' => $admin->email,
            'password' => 'Test-password-123!',
        ])->assertOk()->assertJsonPath('data.admin.id', $admin->id)
            ->assertJsonStructure(['status', 'message', 'data' => ['admin', 'token']]);

        $this->withToken($response->json('data.token'))->getJson('/api/admin/users')->assertOk();
        $this->resetAuthentication();
        $this->withToken($response->json('data.token'))->postJson('/api/admin/logout')->assertOk();
        $this->resetAuthentication();
        $this->withToken($response->json('data.token'))->getJson('/api/admin/users')->assertUnauthorized();
        $this->assertDatabaseHas('personal_access_tokens', ['id' => $other->accessToken->id]);
        $this->resetAuthentication();
        $this->withToken($other->plainTextToken)->getJson('/api/admin/users')->assertOk();
    }

    public function test_invalid_api_credentials_and_tokens_are_rejected(): void
    {
        $admin = $this->admin();
        $this->postJson('/api/admin/login', ['email' => $admin->email, 'password' => 'wrong'])
            ->assertUnprocessable()->assertJsonValidationErrors('email');
        foreach (['user', 'coach', 'admin'] as $role) {
            $user = $this->user($role);
            $this->postJson('/api/admin/login', ['email' => $user->email, 'password' => 'Test-password-123!'])
                ->assertUnprocessable()->assertJsonValidationErrors('email');
        }
        $this->withToken('invalid-test-token')->getJson('/api/admin/users')->assertUnauthorized();
    }

    public function test_all_protected_web_routes_require_an_admin_session(): void
    {
        foreach ($this->protectedRoutes(false) as $route) {
            $this->resetAuthentication();
            $this->json($route->methods()[0], $this->path($route))->assertUnauthorized();
            $this->resetAuthentication();
            $this->call($route->methods()[0], $this->path($route))->assertRedirect('/admin/login');
        }
    }

    #[DataProvider('nonAdminRoles')]
    public function test_web_user_sessions_do_not_authenticate_as_admins(string $role): void
    {
        $this->actingAs($this->user($role), 'web');
        foreach ($this->protectedRoutes(false) as $route) {
            $this->json($route->methods()[0], $this->path($route))->assertUnauthorized();
        }
    }

    public function test_browser_login_and_logout_preserve_the_admin_guard_and_session(): void
    {
        $admin = $this->admin();
        $this->get('/admin/login')->assertOk();
        $oldSessionId = $this->app['session']->getId();
        $this->post('/admin/login', [
            'name' => $admin->name,
            'email' => $admin->email,
            'password' => 'Test-password-123!',
        ])->assertRedirect('/admin');
        $this->assertAuthenticatedAs($admin, 'admin');
        $this->assertNotSame($oldSessionId, $this->app['session']->getId());
        $this->getJson('/admin/data/users/999999')->assertNotFound();
        $this->post('/admin/logout')->assertRedirect('/admin/login');
        $this->assertGuest('admin');
        $this->getJson('/admin/data/users/999999')->assertUnauthorized();
    }

    public function test_invalid_browser_login_does_not_authenticate(): void
    {
        $admin = $this->admin();
        $this->from('/admin/login')->post('/admin/login', [
            'name' => $admin->name,
            'email' => $admin->email,
            'password' => 'wrong',
        ])->assertRedirect('/admin/login')->assertSessionHasErrors('email');
        $this->assertGuest('admin');
    }

    private function enforceRequestForgeryProtection(): void
    {
        // Laravel normally skips this check during tests. Exercise the real middleware logic.
        $this->app->bind(PreventRequestForgery::class, fn ($app) => new class($app, $app['encrypter']) extends PreventRequestForgery
        {
            protected function runningUnitTests() { return false; }
        });
    }

    public function test_browser_user_management_preserves_json_and_csrf_without_a_bearer_token(): void
    {
        $admin = $this->admin();
        $user = $this->user();
        Auth::guard('admin')->login($admin);
        $this->resetAuthentication();
        $this->enforceRequestForgeryProtection();
        $this->withSession(['_token' => 'synthetic-csrf-proof']);

        $this->getJson('/admin/data/users/'.$user->id)
            ->assertOk()->assertJsonPath('data.id', $user->id);
        $this->putJson('/admin/data/users/'.$user->id, ['full_name' => 'Blocked'])
            ->assertStatus(419);
        $this->assertDatabaseHas('users', ['id' => $user->id, 'full_name' => 'Test User']);
        $this->putJson('/admin/data/users/'.$user->id, ['full_name' => 'Updated'], [
            'X-CSRF-TOKEN' => 'synthetic-csrf-proof',
        ])->assertOk()->assertJsonPath('data.full_name', 'Updated');
        $this->deleteJson('/admin/data/users/'.$user->id)->assertStatus(419);
        $this->assertDatabaseHas('users', ['id' => $user->id]);
        $this->deleteJson('/admin/data/users/'.$user->id, [], [
            'X-CSRF-TOKEN' => 'synthetic-csrf-proof',
        ])->assertOk();
        $this->assertDatabaseMissing('users', ['id' => $user->id]);

        $javascript = file_get_contents(base_path('public/front/js/users-management.js'));
        $this->assertStringNotContainsString('/api/admin/users/', $javascript);
        $this->assertSame(3, substr_count($javascript, '/admin/data/users/'));
    }

    public function test_admin_session_and_api_token_remain_separate(): void
    {
        $admin = $this->admin();
        $this->actingAs($admin, 'admin');
        $this->getJson('/api/admin/users')->assertUnauthorized();
        Auth::guard('admin')->logout();
        $this->resetAuthentication();
        $this->withToken($admin->createToken('admin-token')->plainTextToken)
            ->getJson('/admin/data/users/999999')->assertUnauthorized();
    }

    public function test_a_user_cannot_pass_the_explicit_admin_check_even_in_the_wrong_guard(): void
    {
        $this->actingAs($this->user('admin'), 'admin');
        $this->getJson('/admin/data/users/999999')->assertForbidden();
    }
}
