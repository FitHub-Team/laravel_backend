<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\TrainerDetailResource;
use App\Http\Resources\TrainerIndexResource;
use App\Models\CoachProfile;
use App\Models\User;
use App\Services\Admin\TrainerService;
use Illuminate\Http\Request;

class TrainerController extends Controller
{
    protected $trainerService;

    public function __construct(TrainerService $trainerService)
    {
        $this->trainerService = $trainerService;
    }

    public function index(Request $request)
    {
        $coaches = CoachProfile::with('user')->where('is_approved', true)->get();
        return view('admin.trainers_index', compact('coaches'));
    }

    public function show($id)
    {
        $trainer = CoachProfile::with('user')->findOrFail($id);
        return new TrainerDetailResource($trainer);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'specialization' => 'nullable|string',
            'experience' => 'nullable|integer',
            'birth_year' => 'nullable|integer',
            'location' => 'nullable|string',
            'national_id' => 'nullable|string',
            'bio' => 'nullable|string',
            'certifications' => 'nullable|array',
            'is_approved' => 'nullable|boolean',
        ]);

        $userData = $request->only(['name', 'email', 'password']);
        $userData['password'] = bcrypt($userData['password']);

        $trainerData = $request->only([
            'specialization',
            'experience',
            'birth_year',
            'location',
            'national_id',
            'bio',
            'certifications',
            'is_approved'
        ]);

        $trainer = $this->trainerService->createTrainer(
            $userData,
            $trainerData,
            $request->file('profile_image')
        );

        return response()->json([
            'status' => true,
            'message' => 'تم إضافة ملف المدرب بنجاح',
            'data' => new TrainerDetailResource($trainer)
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $trainer = CoachProfile::with('user')->findOrFail($id);

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:users,email,' . $trainer->user_id,
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'specialization' => 'nullable|string',
            'experience' => 'nullable|integer',
            'birth_year' => 'nullable|integer',
            'location' => 'nullable|string',
            'national_id' => 'nullable|string',
            'bio' => 'nullable|string',
            'certifications' => 'nullable|array',
            'is_approved' => 'nullable|boolean',
        ]);

        $userData = $request->only(['name', 'email']);
        $trainerData = $request->only([
            'specialization',
            'experience',
            'birth_year',
            'location',
            'national_id',
            'bio',
            'certifications',
            'is_approved'
        ]);

        $updatedTrainer = $this->trainerService->updateTrainer(
            $trainer,
            array_filter($userData),
            array_filter($trainerData, fn($val) => !is_null($val)),
            $request->file('profile_image')
        );

        return response()->json([
            'status' => true,
            'message' => 'تم تحديث بيانات المدرب بنجاح',
            'data' => new TrainerDetailResource($updatedTrainer)
        ]);
    }
    public function getDetails($id)
    {
        $trainer = CoachProfile::with('user')->findOrFail($id);
        return response()->json($trainer);
    }
    public function toggleApproval($id)
    {
        $trainer = CoachProfile::findOrFail($id);
        $trainer->is_approved = !$trainer->is_approved;
        $trainer->save();

        return response()->json(['success' => true]);
    }

    public function destroy(Request $request, $id)
    {
        $coach = CoachProfile::findOrFail($id);

        // إلغاء الاعتماد وتحديد سبب الرفض/الحذف
        $coach->is_approved = false;
        $coach->rejection_reason = $request->input('rejection_reason', 'تم حذف الحساب بواسطة المسؤول');
        $coach->save();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'تم نقل حساب المدرب إلى قائمة المحذوفات/المرفوضة'
            ]);
        }

        return redirect()->back()->with('success', 'تم نقل الحساب لجدول المحذوفات.');
    }

    public function trainerManage()
    {
        $trainers = CoachProfile::with('user')
            ->where('is_approved', 1)
            ->get();

        return view('admin.trainerManage', compact('trainers'));
    }

    public function coachRequests()
    {
        // الطلبات المعلقة: لم يتم اعتمادها وبدون سبب رفض
        $pendingCoaches = CoachProfile::with('user')
            ->where(function ($q) {
                $q->where('is_approved', 0)
                    ->orWhereNull('is_approved');
            })
            ->where(function ($q) {
                $q->whereNull('rejection_reason')
                    ->orWhere('rejection_reason', '');
            })
            ->get();

        // الطلبات المرفوضة: توجد بها قيمة في سبب الرفض
        $rejectedCoaches = CoachProfile::with('user')
            ->whereNotNull('rejection_reason')
            ->where('rejection_reason', '!=', '')
            ->get();

        return view('admin.coaches_requests', compact('pendingCoaches', 'rejectedCoaches'));
    }
    public function approve($id)
    {
        $coach = CoachProfile::findOrFail($id);

        $coach->is_approved = true;
        $coach->rejection_reason = null; // مسح أي سبب رفض سابق
        $coach->save();

        if ($coach->user_id) {
            User::where('id', $coach->user_id)->update([
                'role' => 'coach',
            ]);
        }

        // التوجيه لصفحة إدارة المدربين المعتمدين
        return redirect()->route('admin.trainer.manage')
            ->with('success', 'تم اعتماد الكوتش بنجاح ونقله إلى جدول المدربين المعتمدين.');
    }


    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $coach = CoachProfile::findOrFail($id);

        // استخدام الحفظ المباشر لضمان التعديل بصرف النظر عن الـ Mass Assignment
        $coach->is_approved = 0;
        $coach->rejection_reason = $request->rejection_reason;
        $coach->save();

        return redirect()->route('admin.coaches.requests')
            ->with('success', 'تم رفض الطلب وإضافته لجدول الطلبات المرفوضة.');
    }


}
