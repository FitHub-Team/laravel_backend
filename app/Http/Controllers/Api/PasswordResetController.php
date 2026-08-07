<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class PasswordResetController extends Controller
{
    function forgotPassword(Request $request)
    {
      
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json([
                'status' => true,
                'message' => 'Password reset link sent successfully'
            ]);
        }
        return response()->json([
            'status' => false,
            'message' => 'Unable to send reset link'
        ], 500);
    }


    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);


        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }


        $status = Password::reset(
            $request->only(
                'email',
                'password',
                'password_confirmation',
                'token'
            ),

            function (User $user, string $password) {

                $user->password = Hash::make($password);
                $user->save();
            }
        );


        if ($status === Password::PASSWORD_RESET) {

            return response()->json([
                'status' => true,
                'message' => 'Password reset successfully'
            ]);
        }


        return response()->json([
            'status' => false,
            'message' => 'Invalid token or email'
        ], 400);
    }
}
