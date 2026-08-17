<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\EmailVerificationService;
use Illuminate\Http\Request;


class EmailVerificationController extends Controller
{
    public function __construct(
        private EmailVerificationService $emailVerificationService
    ) {}


    public function verifyEmail(Request $request)
    {
        $user = User::find($request->route('id'));

        if (! $user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found',
            ], 404);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'status' => true,
                'message' => 'Email already verified',
            ]);
        }

        $verified = $this->emailVerificationService->verify(
            $user,
            (string) $request->route('hash')
        );

        if (! $verified) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid verification link.',
            ], 403);
        }

        return response()->json([
            'status' => true,
            'message' => 'Email verified successfully',
        ]);
    }


    public function resend(Request $request)
    {
        $user = $request->user();

        if (! $user) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'status' => false,
                'message' => 'Email already verified',
            ], 400);
        }

        $this->emailVerificationService->resend($user);

        return response()->json([
            'status' => true,
            'message' => 'Verification email sent successfully',
        ]);
    }
}
