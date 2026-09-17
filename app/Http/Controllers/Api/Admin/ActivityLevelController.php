<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLevel;
use Illuminate\Http\Request;

class ActivityLevelController extends Controller
{
    // عرض مستويات النشاط
    public function index()
    {
        $activityLevels = ActivityLevel::latest()->get();
        return view('admin.activity-levelManage', compact('activityLevels'));
    }

    // حفظ مستوى نشاط جديد
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'is_active' => 'nullable|boolean',
        ]);

        ActivityLevel::create([
            'title' => $request->title,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()->back()->with('success', 'تم إضافة مستوى النشاط بنجاح');
    }

    // تغيير حالة التفعيل
    public function toggleActive(ActivityLevel $activityLevel)
    {
        $activityLevel->update(['is_active' => !$activityLevel->is_active]);
        return redirect()->back()->with('success', 'تم تحديث الحالة بنجاح');
    }

    // حذف مستوى النشاط
    public function destroy(ActivityLevel $activityLevel)
    {
        $activityLevel->delete();
        return redirect()->back()->with('success', 'تم الحذف بنجاح');
    }
}
