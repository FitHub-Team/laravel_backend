<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\DietaryRestriction;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;

class HealthRestrictionController extends Controller
{

    public function index()
    {

        $users = User::with('profile.dietaryRestrictions')->paginate(10);

        $dietaryRestrictions = DietaryRestriction::all();

        return view('admin.health-restrictionsManage', compact('users', 'dietaryRestrictions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_profile_id' => 'required|exists:user_profiles,id',
            'dietary_restriction_id' => 'required|exists:dietary_restrictions,id',
        ], [
            'user_profile_id.required' => 'يرجى اختيار المستخدم.',
            'user_profile_id.exists' => 'الملف الشخصي للمستخدم غير موجود.',
            'dietary_restriction_id.required' => 'يرجى اختيار القيد الصحي/الغذائي.',
            'dietary_restriction_id.exists' => 'القيد المحدد غير موجود في قاعدة البيانات.',
        ]);

        $userProfile = UserProfile::findOrFail($validated['user_profile_id']);

        $userProfile->dietaryRestrictions()->syncWithoutDetaching([
            $validated['dietary_restriction_id']
        ]);

        return redirect()->back()->with('success', 'تم إضافة القيد الصحي بنجاح!');
    }

    public function destroy(UserProfile $userProfile, DietaryRestriction $dietaryRestriction)
    {
        // فك ارتباط القيد عن الملف الشخصي للمستخدم
        $userProfile->dietaryRestrictions()->detach($dietaryRestriction->id);

        return redirect()->back()->with('success', 'تم إزالة القيد بنجاح!');
    }
}
