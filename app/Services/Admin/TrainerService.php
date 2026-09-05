<?php

namespace App\Services\Admin;

use App\Models\CoachProfile;
use App\Models\User;
use DB;
use Illuminate\Support\Facades\Request;

class TrainerService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    // جلب المدربين مع علاقة الـ User والبحث
    public function getPaginatedTrainers(Request $request)
    {
        $query = CoachProfile::with('user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('specialization', 'LIKE', "%{$search}%")
                    ->orWhere('location', 'LIKE', "%{$search}%")
                    ->orWhereHas('user', function ($u) use ($search) {
                        $u->where('name', 'LIKE', "%{$search}%")
                            ->orWhere('email', 'LIKE', "%{$search}%");
                    });
            });
        }

        return $query->latest()->paginate(10);
    }

    // إضافة مدرب جديد (إنشاء User + Trainer معاً داخل Transaction)
    public function createTrainer(array $userData, array $trainerData, $imageFile = null)
    {
        return DB::transaction(function () use ($userData, $trainerData, $imageFile) {
            if ($imageFile) {
                $path = $imageFile->store('users', 'public');
                $userData['profile_image'] = 'storage/' . $path;
            }

            // تحديد دور المستخدم كـ Trainer
            $userData['role'] = 'trainer';
            $user = User::create($userData);

            $trainerData['user_id'] = $user->id;
            return CoachProfile::create($trainerData);
        });
    }

    // تعديل بيانات مدرب
    public function updateTrainer(CoachProfile $trainer, array $userData, array $trainerData, $imageFile = null)
    {
        return DB::transaction(function () use ($trainer, $userData, $trainerData, $imageFile) {
            if ($imageFile) {
                $path = $imageFile->store('users', 'public');
                $userData['profile_image'] = 'storage/' . $path;
            }

            if (!empty($userData)) {
                $trainer->user->update($userData);
            }

            $trainer->update($trainerData);
            return $trainer->load('user');
        });
    }

    // تغيير حالة موافقة الأدمن (Approval toggle)
    public function toggleApproval(CoachProfile $trainer)
    {
        $trainer->update(['is_approved' => !$trainer->is_approved]);
        return $trainer;
    }

    // حذف مدرب (وحذف الـ User المرتبط به تلقائياً أو يدوياً)
    public function deleteTrainer(CoachProfile $trainer)
    {
        return DB::transaction(function () use ($trainer) {
            $user = $trainer->user;
            $trainer->delete();
            if ($user) {
                $user->delete();
            }
        });
    }
}
