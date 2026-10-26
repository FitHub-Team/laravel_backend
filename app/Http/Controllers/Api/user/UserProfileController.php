<?php

namespace App\Http\Controllers\Api\user;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\workout_session;
use App\Services\User\ProfileService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserProfileController extends Controller
{
    public function __construct(private ProfileService $profileService) {}

    public function index(Request $request)
    {
        try {
            $user = $request->user();
            $user->load('userProfile');

            $photo = $user->userProfile?->profile_photo;
            $today = today();

            // جميع الجلسات المكتملة للمتدرب
            $completedSessions = workout_session::where('trainee_id', $user->id)
                ->where('status', 'completed')
                ->count();

            // الخطة الفعّالة حاليًا
            $plan = DB::table('workout_plans')
                ->where('trainee_id', $user->id)
                ->where('status', 'active')
                ->whereDate('start_date', '<=', $today->toDateString())
                ->whereDate('end_date', '>=', $today->toDateString())
                ->orderByDesc('start_date')
                ->orderByDesc('id')
                ->first();

            $currentWeek = null;

            if ($plan) {
                $startDate = Carbon::parse($plan->start_date)->startOfDay();

                $daysPassed = (int) $startDate->diffInDays($today);

                $currentWeek = intdiv($daysPassed, 7) + 1;
            }

            return response()->json([
                'status' => true,
                'data' => [
                    'full_name' => $user->full_name,
                    'email' => $user->email,

                    'profile_photo' => $photo
                        ? asset('storage/' . $photo)
                        : null,

                    'role' => in_array($user->role, ['user', 'trainee'])
                        ? 'متدرب'
                        : $user->role,

                    'completed_sessions' => $completedSessions,
                    'current_week' => $currentWeek,
                ],
            ]);
        } catch (\Exception $e) {
            report($e);

            return response()->json([
                'status' => false,
                'message' => 'حدث خطأ أثناء جلب بيانات الملف الشخصي.',
            ], 500);
        }
    }
}
