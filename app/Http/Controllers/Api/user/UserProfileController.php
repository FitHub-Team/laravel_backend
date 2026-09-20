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
            //dd($user);
            $profile = $user->userProfile;

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

                    'goal' => $profile?->goal?->title,

                    'activity_level' => $profile?->activityLevel?->title,

                    'health_conditions' => $profile?->healthConditions?->pluck('title'),

                    'dietary_restrictions' => $profile?->dietaryRestrictions?->pluck('title'),

                    'training_location' => $profile?->trainingLocation?->title,

                    'available_days' => $profile?->available_days,

                    'trainer_type' => $profile?->trainer_type,

                    'health_condition_note' => $profile?->health_condition_note,

                    'dietary_restriction_note' => $profile?->dietary_restriction_note,

                    'disclaimer_accepted' => $profile?->disclaimer_accepted,

                    'profile_photo' => $profile?->profile_photo,

                ],



            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error fetching user profile data',
            ], 500);
        }
    }
    // public function getCoaches(User $user)
    // {
    //     try {
    //         $coaches = $user->coaches()->get();
    //         return response()->json([
    //             'message' => 'User coaches data',
    //             'data' => $coaches,
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'message' => 'Error fetching user coaches data',
    //         ], 500);
    //     }
    // }
}
