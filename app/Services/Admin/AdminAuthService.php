<?php

namespace App\Services\Admin;

use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AdminAuthService
{

    public function login(array $credentials): array
    {
        $admin = Admin::where('email', $credentials['email'])->first();

        if (!$admin || !Hash::check($credentials['password'], $admin->password)) {
            throw ValidationException::withMessages([
                'email' => ["بيانات الدخول غير صحيحة"],
            ]);
        }

        $token = $admin->createToken('admin-token')->plainTextToken;
        return [
            'admin' => $admin,
            'token' => $token,
        ];
    }

    public function logout(Admin $admin): void
    {
        $admin->currentAccessToken()->delete();
    }

}
