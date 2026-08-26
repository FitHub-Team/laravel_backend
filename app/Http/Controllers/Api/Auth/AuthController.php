<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\LoginWithGoogleRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Http\Request;


class AuthController extends Controller
{
    public function __construct(
        private AuthService $authService
    ) {}
    public function register(RegisterRequest $request)
    {
        $result = $this->authService->register($request->validated());

        return response()->json([
            'status' => true,
            'message' => 'User registered successfully',
            'token' => $result['token'],
            'user' => $result['user'],
        ], 201);
    }
    public function login(LoginRequest $request)
    {
        $result = $this->authService->login($request->validated());
        return response()->json([
            'status' => true,
            'message' => 'Login successful',
            'token' => $result['token'],
            'user' => $result['user'],
        ], 200);
    }
    public function loginWithGoogle(LoginWithGoogleRequest $request)
    {
        $result = $this->authService->loginWithGoogle($request->id_token);
        return response()->json([
            'status' => true,
            'message' => 'Login with Google successful',
            'data' => $result,
        ]);
    }

    public function logout(Request $request)
    {
        $this->authService->logout($request->user());
        return response()->json([
            'status'  => true,
            'message' => 'Logged out successfully'
        ], 200);
    }
}
