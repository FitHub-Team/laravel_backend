<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Services\PasswordResetService;
use Illuminate\Support\Facades\Password;

class PasswordResetController extends Controller
{
    public function __construct(
        private PasswordResetService $passwordResetService
    ) {}
    function forgotPassword(ForgotPasswordRequest $request)
    {

        $status = $this->passwordResetService->forgotPassword(
            $request->email
        );

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json([
                'status' => true,
                'message' => 'Password reset link sent successfully',
            ], 200);
        }

        return response()->json([
            'status' => false,
            'message' => 'Unable to send reset link',
        ], 500);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $status = $this->passwordResetService->resetPassword(
            $request->validated()
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json([
                'status' => true,
                'message' => 'Password reset successfully',
            ], 200);
        }

        return response()->json([
            'status' => false,
            'message' => 'Invalid token or email',
        ], 400);
    }
}
