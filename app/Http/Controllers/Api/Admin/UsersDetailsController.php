<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\Goal;
use App\Models\ActivityLevel;
use App\Models\TrainingLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\DietaryRestriction;

class UsersDetailsController extends Controller
{
    public function index()
    {
        $users = User::with([
            'profile.goal',
            'profile.activityLevel',
            'profile.trainingLocation',
            'profile.dietaryRestrictions'
        ])->paginate(10);

        $goals = Goal::all();
        $activityLevels = ActivityLevel::all();
        $locations = TrainingLocation::all();
        $dietaryRestrictions = DietaryRestriction::all();

        return view('admin.usersDetails', compact(
            'users',
            'goals',
            'activityLevels',
            'locations',
            'dietaryRestrictions'
        ));
    }

    /**
     * إنشاء مستخدم جديد وملف شخصي له في القاعدة
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'nullable|string|min:8',
            'role' => 'required|in:user,coach,User,Coach',
            'gender' => 'nullable|in:ذكر,أنثى,male,female',
            'height' => 'nullable|numeric|min:0',
            'weight' => 'nullable|numeric|min:0',
            'date_of_birth' => 'nullable|date|before:today',
            'goal_id' => 'nullable|exists:goals,id',
            'activity_level_id' => 'nullable|exists:activity_levels,id',
            // 'training_location_id' => 'nullable|exists:training_locations,id',
            'health_condition_note' => 'nullable|string|max:1000',
            'dietary_restriction_note' => 'nullable|string|max:1000',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'available_days' => 'nullable|string|max:255',
            'trainer_type' => 'nullable|in:ai,human,AI,بشري,ذكاء اصطناعي',
            'disclaimer_accepted' => 'nullable|boolean',
            'notes' => 'nullable|string|max:1000',
        ]);

        DB::transaction(function () use ($request, $validated) {

            // ==========================================
            // 1. إنشاء المستخدم الأساسي
            // ==========================================
            $user = User::create([
                'full_name' => $validated['full_name'],
                'email' => $validated['email'],
                'password' => Hash::make($request->password ?? 'password123'),
                'role' => strtolower($validated['role']),
            ]);

            // ==========================================
            // 2. تحويل الجنس إلى القيمة المقبولة في DB
            // ==========================================
            $genderMap = [
                'أنثى' => 'female',
                'ذكر' => 'male',
                'female' => 'female',
                'male' => 'male',
            ];

            $gender = $genderMap[$request->gender] ?? $request->gender;

            // ==========================================
            // 3. تحويل نوع المدرب
            // ==========================================
            $trainerTypeMap = [
                'بشري' => 'human',
                'ذكاء اصطناعي' => 'ai',
                'human' => 'human',
                'ai' => 'ai',
                'AI' => 'ai',
            ];

            $trainerType = $trainerTypeMap[$request->trainer_type]
                ?? $request->trainer_type;

            // ==========================================
            // 4. رفع الصورة
            // ==========================================
            if ($request->hasFile('profile_photo')) {
                $validated['profile_photo'] = $request
                    ->file('profile_photo')
                    ->store('profiles', 'public');
            }

            // ==========================================
            // 5. إنشاء Profile
            // ==========================================
            $user->profile()->create([
                'gender' => $gender,
                'height' => $validated['height'] ?? null,
                'weight' => $validated['weight'] ?? null,
                'date_of_birth' => $validated['date_of_birth'] ?? null,
                'goal_id' => $validated['goal_id'] ?? null,
                'activity_level_id' => $validated['activity_level_id'] ?? null,
                // 'training_location_id' => $validated['training_location_id'] ?? null,
                'health_condition_note' => $validated['health_condition_note'] ?? null,
                'dietary_restriction_note' => $validated['dietary_restriction_note'] ?? null,
                'profile_photo' => $validated['profile_photo'] ?? null,
                'available_days' => $validated['available_days'] ?? null,
                'trainer_type' => $trainerType,
                'disclaimer_accepted' => $request->has('disclaimer_accepted') ? 1 : 0,
                'notes' => $validated['notes'] ?? null,
            ]);
        });

        return redirect()
            ->back()
            ->with('success', 'تم إضافة المستخدم بنجاح');
    }

    /**
     * تحديث بيانات المستخدم والملف الشخصي
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8',
            'role' => 'required|in:user,coach,User,Coach',
            'gender' => 'nullable|in:ذكر,أنثى,male,female',
            'trainer_type' => 'nullable|in:ai,human,AI,بشري,ذكاء اصطناعي',
            'date_of_birth' => 'nullable|date',
            'height' => 'nullable|numeric|min:0',
            'weight' => 'nullable|numeric|min:0',
            'goal_id' => 'nullable|exists:goals,id',
            'activity_level_id' => 'nullable|exists:activity_levels,id',
            // 'training_location_id' => 'nullable|exists:training_locations,id',
            'health_condition_note' => 'nullable|string|max:1000',
            'dietary_restriction_note' => 'nullable|string|max:1000',
            'available_days' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $user = User::findOrFail($id);

        DB::transaction(function () use ($request, $validated, $user) {

            // ==========================================
            // 1. تحديث بيانات المستخدم الأساسية
            // ==========================================
            $userData = [
                'full_name' => $validated['full_name'],
                'email' => $validated['email'],
                'role' => strtolower($validated['role']),
            ];

            // تغيير كلمة المرور فقط إذا تم إدخال كلمة مرور جديدة
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $user->update($userData);

            // ==========================================
            // 2. تحويل الجنس
            // ==========================================
            $genderMap = [
                'أنثى' => 'female',
                'ذكر' => 'male',
                'female' => 'female',
                'male' => 'male',
            ];

            $gender = $genderMap[$request->gender] ?? $request->gender;

            // ==========================================
            // 3. تحويل نوع المدرب
            // ==========================================
            $trainerTypeMap = [
                'بشري' => 'human',
                'ذكاء اصطناعي' => 'ai',
                'human' => 'human',
                'ai' => 'ai',
                'AI' => 'ai',
            ];

            $trainerType = $trainerTypeMap[$request->trainer_type]
                ?? $request->trainer_type;

            // ==========================================
            // 4. الصورة
            // ==========================================
            $profilePhotoPath = $user->profile?->profile_photo;

            if ($request->hasFile('profile_photo')) {

                // حذف الصورة القديمة
                if ($profilePhotoPath) {
                    Storage::disk('public')->delete($profilePhotoPath);
                }

                $profilePhotoPath = $request
                    ->file('profile_photo')
                    ->store('profiles', 'public');
            }

            // ==========================================
            // 5. تحديث الـ Profile
            // ==========================================
            $user->profile()->updateOrCreate(
                [
                    'user_id' => $user->id
                ],
                [
                    'gender' => $gender,
                    'trainer_type' => $trainerType,
                    'date_of_birth' => $request->date_of_birth,
                    'height' => $request->height,
                    'weight' => $request->weight,
                    'goal_id' => $request->goal_id,
                    'activity_level_id' => $request->activity_level_id,
                    // 'training_location_id' => $request->training_location_id,
                    'health_condition_note' => $request->health_condition_note,
                    'dietary_restriction_note' => $request->dietary_restriction_note,
                    'available_days' => $request->available_days,
                    'profile_photo' => $profilePhotoPath,
                    'notes' => $request->notes,
                ]
            );
        });

        return redirect()
            ->back()
            ->with('success', 'تم تحديث بيانات المستخدم بنجاح');
    }

    /**
     * حذف مستخدم وملفه الشخصي
     */
    public function destroy(User $user)
    {
        if ($user->profile && $user->profile->profile_photo) {
            Storage::disk('public')->delete($user->profile->profile_photo);
        }

        $user->delete();

        return redirect()
            ->back()
            ->with('success', 'تم حذف المستخدم بنجاح');
    }
}
