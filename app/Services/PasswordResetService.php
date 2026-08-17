<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class PasswordResetService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }
    public function forgotPassword(string $email): string
    {
        return Password::sendResetLink([
            'email' => $email,
        ]);
    }
    public function resetPassword(array $data): string
    {
        return Password::reset(
            $data,
            function (User $user, string $password) {
                $user->password = Hash::make($password);
                $user->save();
            }
        );
    }
}
