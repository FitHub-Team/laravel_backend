<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CoachProfile;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalCoaches = CoachProfile::count();

        $activeSubscriptions = 0;

        return view('admin.dashboard', compact('totalUsers', 'totalCoaches', 'activeSubscriptions'));
    }
}
