<?php

namespace App\Http\Controllers\Api\coach;

use App\Http\Controllers\Controller;
use App\Models\CoachAvailability;
use Illuminate\Http\Request;

class AvailabilityController extends Controller
{
    // عرض أوقات العمل المتاحة للكوتش
    public function index(Request $request)
    {
        $availabilities = $request->user()->availabilities;

        return response()->json([
            'status' => true,
            'data' => $availabilities
        ], 200);
    }

    // تحديد أو تحديث أوقات العمل المتاحة
    public function store(Request $request)
    {
        $request->validate([
            'availabilities' => 'required|array|min:1',
            'availabilities.*.day_of_week' => 'required|in:Saturday,Sunday,Monday,Tuesday,Wednesday,Thursday,Friday',
            'availabilities.*.start_time' => 'required|date_format:H:i',
            'availabilities.*.end_time' => 'required|date_format:H:i|after:availabilities.*.start_time',
        ]);

        $coach = $request->user();

        // حذف المواعيد القديمة واستبدالها بالجديدة
        $coach->availabilities()->delete();

        foreach ($request->availabilities as $slot) {
            $coach->availabilities()->create($slot);
        }

        return response()->json([
            'status' => true,
            'message' => 'Working hours updated successfully',
            'data' => $coach->availabilities
        ], 200);
    }
}