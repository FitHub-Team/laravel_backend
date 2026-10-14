<?php

namespace App\Http\Controllers\Api\user;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\User\ProfileService;
use Illuminate\Http\Request;

class UserProfileController extends Controller
{
    public function __construct( private ProfileService $profileService ) {}
    
    public function index(Request $request)
    {
        try {
            $user = $this->profileService->show( $request->user() );
            $profile = $user->userProfile;

            // تحديد الدور باللغة العربية بناءً على قيمة الداتا بيز
            $roleName = ($user->role === 'user' || $user->role === 'trainee') ? 'متدرب' : $user->role;

            return response()->json([
                'message' => 'User profile data',
                'data' => [
                    'id' => $user->id,
                    'full_name' => $user->full_name,
                    'email' => $user->email,
                    'role' => $roleName,
                    'profile_photo' => $profile?->profile_photo,
                    'completed_sessions' => 20,          
                    'current_week_sessions' => 8,       
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error fetching user profile data',
            ], 500);
        }
    }
}