<?php

namespace App\Http\Controllers\Api\user;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserProfileController extends Controller
{
    public function index(User $user)
    {
        try {
            $profile = $user->userProfile;
            if (!$user) {
                return response()->json([
                    'message' => 'User not found',
                ], 404);
            }
            $age = $profile?->date_of_birth
                ? \Carbon\Carbon::parse($profile->date_of_birth)->age
                : null;

            return response()->json([
                'message' => 'User profile data',
                'data' => [
                    'fullname' => $user->full_name,
                    'email' => $user->email,
                    'age' => $age,
                    'gender' => $profile?->gender,          
            'height' => $profile?->height,
            'weight' => $profile?->weight,
                    'health_goal' => $profile?->health_goal,
                    'profile_photo' => $profile?->profile_photo,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error fetching user profile data',
            ], 500);
        }
    }
    public function getCoaches(User $user)
    {
        try {
            $coaches = $user->coaches()->get();
            return response()->json([
                'message' => 'User coaches data',
                'data' => $coaches,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error fetching user coaches data',
            ], 500);
        }
    }
}