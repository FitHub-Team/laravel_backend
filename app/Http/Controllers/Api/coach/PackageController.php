<?php

namespace App\Http\Controllers\Api\coach;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    // عرض جميع باقات الكوتش الحالي
    public function index(Request $request)
    {
        $packages = $request->user()->packages()->latest()->get();

        return response()->json([
            'status' => true,
            'data' => $packages
        ], 200);
    }

    // إضافة باقة جديدة
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration_in_days' => 'required|integer|min:1',
            'is_active' => 'boolean'
        ]);

        $package = $request->user()->packages()->create($validated);

        return response()->json([
            'status' => true,
            'message' => 'Package created successfully',
            'data' => $package
        ], 201);
    }

    // عرض تفاصيل باقة محددة
    public function show(Request $request, $id)
    {
        $package = $request->user()->packages()->find($id);

        if (!$package) {
            return response()->json(['message' => 'Package not found'], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $package
        ], 200);
    }

    // تعديل باقة
    public function update(Request $request, $id)
    {
        $package = $request->user()->packages()->find($id);

        if (!$package) {
            return response()->json(['message' => 'Package not found'], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|required|numeric|min:0',
            'duration_in_days' => 'sometimes|required|integer|min:1',
            'is_active' => 'boolean'
        ]);

        $package->update($validated);

        return response()->json([
            'status' => true,
            'message' => 'Package updated successfully',
            'data' => $package
        ], 200);
    }

    // حذف باقة
    public function destroy(Request $request, $id)
    {
        $package = $request->user()->packages()->find($id);

        if (!$package) {
            return response()->json(['message' => 'Package not found'], 404);
        }

        $package->delete();

        return response()->json([
            'status' => true,
            'message' => 'Package deleted successfully'
        ], 200);
    }
}