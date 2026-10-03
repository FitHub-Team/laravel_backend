<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    public function index()
    {
        $skills = Skill::latest()->paginate(10);
        $coaches = User::where('role', 'coach')->get();

        return view('admin.skillsManage', compact('skills', 'coaches'));
    }

    // عرض صفحة إضافة مهارة جديدة
    public function create()
    {
        return redirect()->route('admin.skills.index');
    }

    // حفظ مهارة جديدة
    public function store(Request $request)
    {
        $request->validate([
            'coach_id' => 'required|exists:users,id', // أو exists:coaches,id حسب اسم الجدول
            'name' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);

        Skill::create([
            'user_id' => $request->coach_id, // أو coach_id حسب تسمية العمود في جدول المهارات
            'name' => $request->name,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->back()->with('success', 'تم إضافة المهارة بنجاح');
    }
    // عرض صفحة التعديل
    public function edit($id)
    {
        return redirect()->route('admin.skills.index');
    }

    // تحديث البيانات
    public function update(Request $request, Skill $skill)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:skills,name,' . $skill->id,
            'is_active' => 'nullable|boolean',
        ]);

        $skill->update([
            'name' => $request->name,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.skills.index')->with('success', 'تم تعديل المهارة بنجاح.');
    }

    // تبديل الحالة (Active / Inactive) بسرعة
    public function toggleActive(Skill $skill)
    {
        $skill->update(['is_active' => !$skill->is_active]);
        return back()->with('success', 'تم تغيير حالة المهارة بنجاح.');
    }

    // حذف المهارة
    public function destroy(Skill $skill)
    {
        $skill->delete();
        return back()->with('success', 'تم حذف المهارة بنجاح.');
    }
}
