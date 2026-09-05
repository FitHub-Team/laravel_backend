<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\UserDetailResource;
use App\Http\Resources\Admin\UserIndexResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminUserController extends Controller
{
    /**
     * عرض جميع المستخدمين
     */
    public function index(Request $request)
    {
        $query = User::with('userProfile');

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        $users = $query->paginate(10);

        return UserIndexResource::collection($users);
    }


    /**
     * عرض تفاصيل مستخدم
     */
    public function show($id)
    {
        $user = User::with('userProfile')->findOrFail($id);

        return new UserDetailResource($user);
    }


    /**
     * تعديل مستخدم
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'المستخدم غير موجود'
            ], 404);
        }

        $validated = $request->validate([
            'full_name' => 'nullable|string|max:255',

            'email' => [
                'nullable',
                'email',
                'max:255',
                'unique:users,email,' . $id
            ],

            'gender' => 'nullable|string',
            'dob' => 'nullable|date',
            'height' => 'nullable|numeric',
            'weight' => 'nullable|numeric',
            'health_goal' => 'nullable|string|max:255',
        ]);


        DB::beginTransaction();

        try {

            // ==========================================
            // 1. تحديث بيانات users
            // ==========================================

            $userData = [];

            if (array_key_exists('full_name', $validated)) {
                $userData['full_name'] = $validated['full_name'];
            }

            if (array_key_exists('email', $validated)) {
                $userData['email'] = $validated['email'];
            }

            if (!empty($userData)) {
                $user->update($userData);
            }


            // ==========================================
            // 2. تحديث بيانات user_profiles
            // ==========================================

            $profileData = [];


            // Gender
            if (array_key_exists('gender', $validated)) {

                $genderInput = trim($validated['gender']);

                if (
                    in_array($genderInput, [
                        'ذكر',
                        'male',
                        'Male'
                    ])
                ) {

                    $profileData['gender'] = 'male';

                } elseif (
                    in_array($genderInput, [
                        'أنثى',
                        'female',
                        'Female'
                    ])
                ) {

                    $profileData['gender'] = 'female';

                } else {

                    $profileData['gender'] =
                        strtolower($genderInput);
                }
            }


            // Date of birth
            if (array_key_exists('dob', $validated)) {
                $profileData['date_of_birth'] =
                    $validated['dob'];
            }


            // Height
            if (array_key_exists('height', $validated)) {
                $profileData['height'] =
                    $validated['height'];
            }


            // Weight
            if (array_key_exists('weight', $validated)) {
                $profileData['weight'] =
                    $validated['weight'];
            }


            // Health goal
            if (array_key_exists('health_goal', $validated)) {
                $profileData['health_goal'] =
                    $validated['health_goal'];
            }


            // إنشاء أو تحديث profile
            if (!empty($profileData)) {

                $user->userProfile()->updateOrCreate(
                    [
                        'user_id' => $user->id
                    ],
                    $profileData
                );
            }


            // ==========================================
            // Commit
            // ==========================================

            DB::commit();


            // جلب البيانات الجديدة من قاعدة البيانات
            $freshUser = $user->fresh('userProfile');


            return response()->json([
                'status' => true,
                'message' => 'تم تعديل البيانات بنجاح',
                'data' => new UserDetailResource($freshUser)
            ], 200);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' =>
                    'حدث خطأ أثناء حفظ البيانات: ' .
                    $e->getMessage()
            ], 500);
        }
    }


    /**
     * حذف مستخدم
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        $user->delete();

        return response()->json([
            'status' => true,
            'message' => 'تم حذف حساب المستخدم بنجاح'
        ]);
    }


    /**
     * صفحة إدارة المستخدمين
     */
    public function userManage()
    {
        $users = User::with('userProfile')->get();

        return view(
            'admin.userManage',
            compact('users')
        );
    }
}
