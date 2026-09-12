<?php

namespace App\Http\Controllers\Api\coach;

use App\Http\Controllers\Controller;
use App\Services\Coach\AvailabilityService;
use Illuminate\Http\Request;

class AvailabilityController extends Controller
{
    protected AvailabilityService $availabilityService;

    public function __construct(AvailabilityService $availabilityService)
    {
        $this->availabilityService = $availabilityService;
    }

    // عرض أوقات العمل المتاحة للكوتش
    public function index(Request $request)
    {
        $availabilities = $this->availabilityService->getAvailabilities($request->user());

        return response()->json([
            'status' => true,
            'data' => $availabilities
        ], 200);
    }

    // تحديد أو تحديث أوقات العمل المتاحة
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'availabilities' => 'required|array|min:1',
            'availabilities.*.day_of_week' => 'required|in:Saturday,Sunday,Monday,Tuesday,Wednesday,Thursday,Friday',
            'availabilities.*.start_time' => 'required|date_format:H:i',
            'availabilities.*.end_time' => 'required|date_format:H:i|after:availabilities.*.start_time',
        ]);

        $availabilities = $this->availabilityService->updateAvailabilities(
            $request->user(), 
            $validatedData['availabilities']
        );

        return response()->json([
            'status' => true,
            'message' => 'Working hours updated successfully',
            'data' => $availabilities
        ], 200);
    }
}