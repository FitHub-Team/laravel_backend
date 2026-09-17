<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Goal;
use Illuminate\Http\Request;
use Storage;

class GoalController extends Controller
{
    // عرض قائمة الأهداف
    public function index()
    {
        $goals = Goal::latest()->get();
        return view('admin.goalsManage', compact('goals'));
    }

    // حفظ هدف جديد في قاعدة البيانات
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
            'is_active' => 'nullable|boolean',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('goals', 'public');
        }

        Goal::create([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $imagePath,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()->back()->with('success', 'تم إضافة الهدف بنجاح');
    }

    // التبديل بين تفعيل / تعطيل الهدف
    public function toggleActive(Goal $goal)
    {
        $goal->update(['is_active' => !$goal->is_active]);
        return redirect()->back()->with('success', 'تم تحديث حالة الهدف');
    }

    // حذف الهدف
    public function destroy(Goal $goal)
    {
        if ($goal->image) {
            Storage::disk('public')->delete($goal->image);
        }
        $goal->delete();
        return redirect()->back()->with('success', 'تم حذف الهدف بنجاح');
    }
}
