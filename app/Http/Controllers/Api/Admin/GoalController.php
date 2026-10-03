<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Goal;
use Illuminate\Http\Request;
use Storage;

class GoalController extends Controller
{
    public function index(Request $request)
    {
        // فحص مباشر وصريح لمُحتوى الـ Header
        if ($request->header('Accept') === 'application/json' || $request->wantsJson()) {
            $goals = Goal::where('is_active', true)->latest()->get();

            return response()->json([
                'status' => true,
                'message' => 'Goals retrieved successfully',
                'data' => $goals->map(function ($goal) {
                    return [
                        'id' => $goal->id,
                        'title' => $goal->title,
                        'description' => $goal->description,
                        'image' => $goal->image ? asset('storage/' . $goal->image) : null,
                        'is_active' => (bool) $goal->is_active,
                    ];
                })
            ], 200);
        }

        // إذا كان الطلب عادي من المتصفح لرؤية لوحة التحكم
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
