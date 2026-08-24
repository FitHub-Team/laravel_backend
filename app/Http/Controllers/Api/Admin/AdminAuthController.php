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













}
