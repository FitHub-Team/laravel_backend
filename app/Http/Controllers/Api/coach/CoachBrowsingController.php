<?php

namespace App\Http\Controllers\Api\coach;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CoachBrowsingController extends Controller
{
    public function index() {
         $coach = auth()->user();
         return response()->json($coach);
    }
}
