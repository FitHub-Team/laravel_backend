<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminLoginRequest;
use App\Services\Admin\AdminAuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminAuthController extends Controller
{
    protected AdminAuthService $adminAuthService;

    public function __construct(AdminAuthService $adminAuthService)
    {
        $this->adminAuthService = $adminAuthService;
    }

    public function login(AdminLoginRequest $request): JsonResponse
    {
        $result = $this->adminAuthService->login($request->validated());
        return response()->json([
            'status' => 'true',
            'message' => 'تم تسجيل دخول الأدمن بنجاح',
            'data' => $result,
        ], 200);
    }

    public function logout(Request $request): JsonResponse
    {
        $this->adminAuthService->logout($request->user());

        return response()->json([
            'status' => true,
            'message' => 'تم تسجيل الخروج بنجاح',
        ], 200);
    }

    public function showLoginForm()
    {
        return view('admin.login');
    }

    public function loginWeb(Request $request)
    {
        $credentials = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (auth()->guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'email' => 'بيانات الدخول غير صحيحة.',
        ])->onlyInput('email', 'name');
    }

    public function logoutWeb(Request $request)
    {
        auth()->guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
    public function dashboard()
    {
        $totalUsers = \App\Models\User::count();

        $totalCoaches = \App\Models\CoachProfile::count();

        $activeSubscriptions = $totalUsers + $totalCoaches;

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalCoaches',
            'activeSubscriptions'
        ));
    }



    public function userManage()
    {
        return view('admin.index');
    }



}
