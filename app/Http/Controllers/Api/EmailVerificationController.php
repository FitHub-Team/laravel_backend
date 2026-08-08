<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;


class EmailVerificationController extends Controller
{


public function verifyEmail(Request $request)
{
    $user = User::find($request->route('id'));

    if (! $user) {
        return response()->json([
            'status' => false,
            'message' => 'User not found'
        ], 404);
    }

    if (! hash_equals(
        sha1($user->getEmailForVerification()),
        (string) $request->route('hash')
    )) {
        return response()->json([
            'status' => false,
            'message' => 'Invalid verification link.'
        ], 403);
    }

    if ($user->hasVerifiedEmail()) {
        return response()->json([
            'status' => true,
            'message' => 'Email already verified'
        ]);
    }

    $user->markEmailAsVerified();

    event(new Verified($user));

    return response()->json([
        'status' => true,
        'message' => 'Email verified successfully'
    ]);
}
    
    public function resend(Request $request)
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'Email already verified'
            ]);
        }

        $user->sendEmailVerificationNotification();

        return response()->json([
            'message' => 'Verification email sent successfully'
        ]);
    }
}
