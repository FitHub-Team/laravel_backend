<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLevel;
use App\Models\dietary_restrictions;
use App\Models\User;
use Illuminate\Http\Request;

class ActivityLevelController extends Controller
{
    public function index()
    {
        $activityLevels = ActivityLevel::latest()->paginate(10);

        return view('admin.activity-levelManage', compact('activityLevels'));
    }

    // حفظ مستوى نشاط جديد
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255|unique:activity_levels,title',
            'is_active' => 'nullable|boolean',
        ]);

        ActivityLevel::create([
            'title' => $validated['title'],
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()->back()->with('success', 'تم إضافة مستوى النشاط بنجاح.');
    }

    // تحديث مستوى النشاط
    public function update(Request $request, ActivityLevel $activityLevel)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255|unique:activity_levels,title,' . $activityLevel->id,
            'is_active' => 'nullable|boolean',
        ]);

        $activityLevel->update([
            'title' => $validated['title'],
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()->back()->with('success', 'تم تعديل مستوى النشاط بنجاح.');
    }

    // تغيير حالة التفعيل
    public function toggleActive(ActivityLevel $activityLevel)
    {
        $activityLevel->update([
            'is_active' => !$activityLevel->is_active,
        ]);

        return redirect()->back()->with('success', 'تم تحديث حالة التفعيل بنجاح.');
    }

    // حذف مستوى النشاط
    public function destroy(ActivityLevel $activityLevel)
    {
        $activityLevel->delete();
        return redirect()->back()->with('success', 'تم حذف مستوى النشاط بنجاح.');
    }
}
