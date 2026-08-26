<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Auth\EmailVerificationService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class EmailVerificationController extends Controller
{
    public function __construct(
        private EmailVerificationService $emailVerificationService
    ) {}


    public function verifyEmail(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'code' => 'required|string|size:6',
        ]);
        $user = User::where('email', $validated['email'])->first();


        if (! $user) {
            throw ValidationException::withMessages([
                'email' => ['User not found'],
            ]);
        }


        if (!$this->emailVerificationService->verify($user, $validated['code'])) {
            throw ValidationException::withMessages([
                'code' => ['Invalid or expired verification code'],
            ]);
        }


        return response()->json([
            'status' => true,
            'message' => 'Email verified successfully',
        ]);
    }


    public function resend(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
        ]);
        $user = User::where('email', $validated['email'])->first();

        if (! $user) {
           throw ValidationException::withMessages([
                'email' => ['User not found'],
            ]);
        }

        $this->emailVerificationService->resend($user);

        return response()->json([
            'status' => true,
            'message' => 'Verification email sent successfully',
        ]);
    }
}
